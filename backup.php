<br />
<title>Analitico</title>
<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
<? include "visual.html"; ?>

<div class="col-md-3" ALIGN="center"></div>
<div class="container-fluid col-md-6" ALIGN="center">
    <div class='panel panel-primary'>
        <div class="panel-heading" ALIGN="center">
            <h4>CAMPOS DE PESQUISA</h4>
        </div>
        <nav class="navbar navbar-light bg-light" ALIGN="center">
            <form action="" name="form1" method="post">
                <div name="DIV-COD_BARRA" class="col-md-4">
                    <LABEL class="label-input100">
                        <h5><strong>DATA INICIO:</strong> </h5>
                    </label>
                    <input id="DATAINICIO" name="DATAINICIO" class="form-control form-control-sm" onblur="funcao1();"
                        type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
                </div>
                <div class="col-md-4">
                    <label>
                        <h5><strong>DATA FIM:</strong></h5>
                    </label>
                    <input id="DATAFIM" name="DATAFIM" class="form-control form-control-sm" onblur="funcao1();"
                        type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <? include "listaLojaIP.php"; ?>
                        <label for="COD_FILIAL">
                            <h5><strong>ESTABELECIMENTO:</strong></h5>
                        </label><br>
                        <select required name="COD_FILIAL" class="form-control" id="COD_FILIAL">
                            <option>Selecione...</option>
                            <?php
							while ($row_estab = sqlsrv_fetch_array($querySelect)) {
							?>
                            <option required type="text" name="COD_FILIAL" value="<? echo $row_estab['COD_FILIAL'] ?>">
                                <? echo $row_estab['filial'] ?>
                            </option>
                            <?
							}
							?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" align="center">
                    <INPUT class='btn btn-success' title="PESQUISA POR PDV/CUPOM " name="SendPesqUser" id="SendPesqUser"
                        onblur="funcao1();" align="right" value="PESQUISAR" type='submit'></input></div>
            </form>
    </div>
</div>
</div>


<!-- adicionando codigo javascript para Onblur-->
<script languege=javascript>
function funcao1() {

    var DATAINICIO = document.getElementById("DATAINICIO");
    var DATAFIM = document.getElementById("DATAFIM");
    var COD_FILIAL = document.getElementById("COD_FILIAL");
    var SendPesqUser = document.getElementById("SendPesqUser");
}
</script>
<?
@$DATAINICIO = date('Ymd', strtotime($_GET['DATAINICIO']));		
				@$DATAFIM = date('Ymd', strtotime($_GET['DATAFIM']));
				@$COD_FILIAL = $_GET['COD_FILIAL'];
				$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
?>
<div class="panel-body"></div>
<div class="panel-footer">
    <div class="container-fluid" float="">
        <div class='panel panel-primary'>
            <div class="panel-heading" ALIGN="center">
                <h4></h4>
            </div>
            <nav class="navbar navbar-light bg-light" ALIGN="center">
                <?
				
				if ($SendPesqUser) {
					$COD_FILIAL = filter_input(INPUT_POST, 'COD_FILIAL', FILTER_SANITIZE_STRING);
					$query = "
					declare @dataini date, @datafim date, @filial varchar(06), @Mdataini date, @Mdatafim date
					set @dataini = '".$DATAINICIO."'		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
					set @datafim = ".$DATAFIM."'		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL
					set @filial = ".$COD_FILIAL."''	-- VARIÁVEL PARA ESCOLHA DA LOJA
					set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL

					SELECT
					sum(A.VALOR_PAGO) AS 'VALOR_LIQUIDO',
					(select SUM(PV.PREVISAO_VALOR) from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @dataini and @datafim) AS 'META',
					CAST(ROUND(((sum(A.VALOR_PAGO) / (select SUM(PV.PREVISAO_VALOR) from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.FILIAL AND PV.DATA_VENDA between @dataini and @datafim)) * 100),2) AS NUMERIC(11,2)) AS '%_META',
					COUNT(*) AS 'ATENDIMENTOS',
					(SELECT COUNT(*) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0) AS 'TROCAS',
					CAST(ROUND((((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0 AND A2.VALOR_PAGO > 5.00) /
						(SELECT COUNT(*) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0)) * 100),2) AS NUMERIC(11,2)) AS '%_TROCA_PROD',
					CAST(ROUND((sum(A.VALOR_PAGO) / COUNT(*)),2) AS NUMERIC(11,2)) AS 'TICKET_MEDIO',
					(SELECT SUM(B2.QTDE) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim) as 'PECAS',
					CAST(ROUND(((SELECT CAST(SUM(B2.QTDE) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim) /
						COUNT(*)),2) AS NUMERIC(11,2)) AS 'PECAS_A',
					sum(A.DESCONTO) as 'DESCONTO',
					CAST(ROUND((sum(A.DESCONTO) / sum(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2)) AS '%_DESCONTO',
					CAST(ROUND(((SELECT SUM(B2.VALOR) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) /
						 sum(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2)) AS '%_VALOR_POLYELLE',
					CAST(ROUND(((SELECT CAST(COUNT(DISTINCT A2.TICKET) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						COUNT(*)) * 100,2) AS NUMERIC(11,2)) AS '%_A_POLYELLE',
					CAST(ROUND(((SELECT CAST(SUM(B2.PARCELAS_CARTAO) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						(SELECT COUNT(DISTINCT A2.TICKET) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00)),2) AS NUMERIC(11,2)) AS 'PARCELAS_POLYELLE',
					CAST(ROUND(((SELECT SUM(B2.VALOR) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						(SELECT COUNT(DISTINCT A2.TICKET) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00)),2) AS NUMERIC(11,2)) AS 'TICKET_POLYELLE',
					CAST(ROUND(((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.CODIGO_CLIENTE IS NOT NULL) / 
						COUNT(*)) * 100,2) AS NUMERIC(11,2)) AS '%_CADASTRO'
				
				FROM
					LOJA_VENDA A
					INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = A.CODIGO_FILIAL
				WHERE
					A.DATA_HORA_CANCELAMENTO IS NULL
					AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @dataini and @datafim
				group by C.FILIAL
				
									
";

					echo "
						
							<TABLE aling='center' class='table table-bordered'>
							<TR>
							<th align=''><div align='center' ><h5>VALOR LIQUIDO</h5></div></th>
							<th align=''><div align='center' ><h5>META</h5></div></th>
							<th align=''><div align='center' ><h5>% META</h5></div></th>
							<th align=''><div align='center' ><h5>ATENDIMENTOS</h5></div></th>
							<th align=''><div align='center' ><h5>TROCAS</h5></div></th>
							<th align=''><div align='center' ><h5>% TROCAS PRODUTIVAS</h5></div></th>
							<th align=''><div align='center' ><h5>TICKET MÉDIO</h5></div></th>
							<th align=''><div align='center' ><h5>PEÇAS</h5></div></th>
							<th align=''><div align='center' ><h5>PEÇAS/A</h5></div></th>
					
						</TR>";

					@$stmtAC = sqlsrv_query($conn1, $query);

					if( $stmtAC =='' ) {
						die( print_r( sqlsrv_errors(), true));
					}else{}
					while (@$item = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)) {
					
						echo "	<TR>
								<TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD> 
								<TD align='center' class='col-md-1'><font size=2>" . $item['META'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['%_META'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['ATENDIMENTOS'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['TROCAS'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['%_TROCA_PROD'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['TICKET_MEDIO'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS'] . "</font></TD>
								<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS_A'] . "</font></TD>
								</TR>";
					}
					echo "
					<TABLE aling='center' class='table table-bordered'>
				<TR>
				<th align=''><div align='center' ><h5>DESCONTO</h5></div></th>
				<th align=''><div align='center' ><h5>% DESCONTO</h5></div></th>
				<th align=''><div align='center' ><h5>% VENDAS POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>% ATENDIMENTO CATÃO POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>MEDIA PARCELAS CARTÃO POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>TICKET MÉDIO CARTÃO POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>% VENDAS C/CADASTRO</h5></div></th>
			</TR>";
					@$stmtAC2 = sqlsrv_query($conn1, $query);


					while (@$item2 = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
						echo "	<TR>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['DESCONTO'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_DESCONTO'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_VALOR_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_A_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['PARCELAS_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['TICKET_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_CADASTRO'] . "</font></TD>
							</TR>";
					}
				} else {
					echo "nada foi encontrado";
				}
				echo "</table>";

				?>
            </nav>

        </div>
        <!-- dois começa aq-->
    </div>