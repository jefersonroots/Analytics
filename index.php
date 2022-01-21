<script type="text/javascript">
	window.history.go(1);
</script>

<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
<?
@$login_cookie = $_COOKIE['login'];
if (isset($login_cookie)) {
	echo "Bem-Vindo, $login_cookie <br>";
?>


	<title>Analitico</title>
	<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
	<? include "visual.html"; ?>

	<div class="col-md-2" ALIGN="center"></div>
	<div class="container-fluid col-md-8" ALIGN="center">
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
						<input id="DATAINICIO" required name="DATAINICIO" class="form-control form-control-sm" onblur="funcao1();" type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
					</div>
					<div class="col-md-4">
						<label>
							<h5><strong>DATA FIM:</strong></h5>
						</label>
						<input id="DATAFIM" name="DATAFIM" required class="form-control form-control-sm" onblur="funcao1();" type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<? include('../restricao/listaLojaIP.php'); ?>

							<label for="COD_FILIAL">
								<h5><strong>ESTABELECIMENTO:</strong></h5>
							</label><br>
							<select required name="COD_FILIAL" class="form-control" id="COD_FILIAL">
								<option>Selecione...</option>
								<?php
								while ($row_estab = sqlsrv_fetch_array($querySelect)) {
								?>
									<option required type="text" name="f.COD_FILIAL" value="<? echo $row_estab['cod_filial'] ?>">
										<? echo $row_estab['filial'] ?>
									</option>
								<?
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-12" align="center">
						<INPUT class='btn btn-success' title=" " name="SendPesqUser" id="SendPesqUser" onblur="funcao1();" align="right" value="PESQUISAR" type='submit'></input>
					</div>

		</div>
	</div>
	</div>
	</form>
 
	<!-- adicionando codigo javascript para Onblur-->
	<script>
		function funcao1() {

			var DATAINICIO = document.getElementById("DATAINICIO");
			var DATAFIM = document.getElementById("DATAFIM");
			var COD_FILIAL = document.getElementById("COD_FILIAL");
			var SendPesqUser = document.getElementById("SendPesqUser");

		}
	</script>
	<script>
				$(function() {
					$('[data-toggle="tooltip"]').tooltip()
				})
			</script>
	<?
	@$DATAINICIO = date('Ymd', strtotime($_POST['DATAINICIO']));
	@$DATAFIM = date('Ymd', strtotime($_POST['DATAFIM']));
	@$COD_FILIAL = $_POST['COD_FILIAL'];
	
	$dataHoje = date("Ymd");
	//$dataLimiteMin = date("Ymd", strtotime($dataHoje) - (38 * 24 * 60 * 60));
	$dataLimiteMin = date("Ymd",  strtotime('first day of last month'));
	//$dataLimiteMin = date("Ymd",  strtotime('-40 day'));

	$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
	if ($SendPesqUser) {
		$COD_FILIAL = filter_input(INPUT_POST, 'COD_FILIAL', FILTER_SANITIZE_STRING);
		if (@$DATAINICIO >= $dataLimiteMin) {
	?>	

			<div class="panel-body"></div>
			<div class="panel-footer">
				<!-- LINHA DE INFORMAÇÕES DOS CAMPOS PESQUISADOS-->
				<div class="panel alert-dark" align='center'>
					<label fontco align='center'> <?
													ECHO" LOJA: ";echo @$COD_FILIAL   ;
													ECHO"   -    PERÍODO: ";echo date('d/m/Y', strtotime(@$DATAINICIO));
													ECHO" até ";echo date('d/m/Y', strtotime(@$DATAFIM));
													?>
					</label>
				</div>
			<!--  FIM DA LINHA DE INFORMAÇÕES DE PESQUISA -->
				<div class="container-fluid" float="">
					<div class='panel panel-primary'>
						<div class="panel-heading" ALIGN="center">
							<h4></h4>
						</div>
						<nav class="navbar navbar-light bg-light" ALIGN="center">
							<?
							$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
							if ($SendPesqUser) {
								$COD_FILIAL = filter_input(INPUT_POST, 'COD_FILIAL', FILTER_SANITIZE_STRING);
								$query = "
					declare @dataini date, @datafim date, @filial varchar(06), @Mdataini date, @Mdatafim date, @meta numeric(11,2)
					set @dataini = '" . $DATAINICIO . "'		
					set @datafim = '" . $DATAFIM . "'		
					set @filial = '" . $COD_FILIAL . "'
					set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
					set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESC
					set @meta = dbo.FX_CALCULA_META_PROJETADA(@filial,@dataini,@datafim,@Mdataini,@Mdatafim,2)
					
					SELECT
					format(sum(A.VALOR_PAGO), 'C', 'pt-br') AS 'VALOR_LIQUIDO',
					(select format(SUM(PV.PREVISAO_VALOR), 'C', 'pt-br') from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @dataini and @datafim) AS 'META_ORIGINAL',
					format(@meta, 'C', 'pt-br') AS 'META_PROJETADA',
					CASE WHEN (sum(A.VALOR_PAGO)) = 0 or @meta = 0 THEN
						CAST(0 AS NUMERIC(11,2))
					ELSE
						CAST(ROUND(((sum(A.VALOR_PAGO) / @meta) * 100),2) AS NUMERIC(11,2))
					end AS '%_META',
					COUNT(*) AS 'ATENDIMENTOS',
					(SELECT COUNT(*) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0) AS 'TROCAS',
					CAST(ROUND((((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0 AND A2.VALOR_PAGO > 5.00) /
						(SELECT case when COUNT(*) = 0 then 1 else count(*) end as 'TOTAL'  FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VALOR_TROCA > 0)) * 100),2) AS NUMERIC(11,2)) AS '%_TROCA_PROD',
					CAST(ROUND((sum(A.VALOR_PAGO) / COUNT(*)),2) AS NUMERIC(11,2)) AS 'TICKET_MEDIO',
					(SELECT SUM(B2.QTDE) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim) as 'PECAS',
					CAST(ROUND(((SELECT CAST(SUM(B2.QTDE) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim) /
						COUNT(*)),2) AS NUMERIC(11,2)) AS 'PECAS_A',
					format(sum(A.DESCONTO), 'C', 'pt-br') as 'DESCONTO',
					CASE WHEN (sum(A.VALOR_PAGO)) = 0 THEN
						CAST(0 AS NUMERIC(11,2))
					ELSE
						CAST(ROUND((sum(A.DESCONTO) / sum(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2))
					end AS '%_DESCONTO',
					CAST(ROUND(((SELECT SUM(B2.VALOR) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) /
						 sum(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2)) AS '%_VALOR_POLYELLE',
					CAST(ROUND(((SELECT CAST(COUNT(DISTINCT A2.TICKET) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						COUNT(*)) * 100,2) AS NUMERIC(11,2)) AS '%_A_POLYELLE',
					CAST(ROUND(((SELECT CAST(SUM(B2.PARCELAS_CARTAO) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						(SELECT COUNT(DISTINCT A2.TICKET) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00)),2) AS NUMERIC(11,2)) AS 'PARCELAS_POLYELLE',
					CAST(ROUND(((SELECT SUM(B2.VALOR) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00) / 
						(SELECT COUNT(DISTINCT A2.TICKET) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PARCELAS B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.LANCAMENTO_CAIXA = A2.LANCAMENTO_CAIXA AND B2.TERMINAL = A2.TERMINAL WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.CODIGO_ADMINISTRADORA = '12' AND B2.VALOR > 0.00)),2) AS NUMERIC(11,2)) AS 'TICKET_POLYELLE',
					CAST(ROUND(((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.CODIGO_CLIENTE IS NOT NULL) / 
						COUNT(*)) * 100,2) AS NUMERIC(11,2)) AS '%_CADASTRO',
						(select	ISNULL(sum(T.valor_pago),0) from	(select	lp.CODIGO_FILIAL_ORIGEM as 'cod_filial', concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) as 'ticket',	lpp.valor as 'valor_pago'
												from 
													loja_pedido lp
													left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
												where
													lp.tipo_pedido = 3 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
													and lp.DATA between @dataini and @datafim and lp.CODIGO_FILIAL_ORIGEM = @filial
													and not exists(	select	*
																	from
																		LOJA_VENDA A2
																	where
																		A2.DATA_HORA_CANCELAMENTO IS NULL
																		and A2.DATA_VENDA between @dataini and @datafim
																		and A2.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and A2.TICKET = concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-',''))
																		and A2.VALOR_PAGO = lpp.valor)
												) T) as 'PEDIDOS_FECHADOS' 
				
				FROM
					LOJA_VENDA A
					INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = A.CODIGO_FILIAL
				WHERE
					A.DATA_HORA_CANCELAMENTO IS NULL
					AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @dataini and @datafim
				group by C.FILIAL
				
									
";
				$query2 = "
					declare @dataini date, @datafim date, @filial varchar(06), @Mdataini date, @Mdatafim date, @meta numeric(11,2)
					set @dataini = '" . $DATAINICIO . "'		
					set @datafim = '" . $DATAFIM . "'		
					set @filial = '" . $COD_FILIAL . "'
					set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
					set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESC
					set @meta = dbo.FX_CALCULA_META_PROJETADA(@filial,@dataini,@datafim,@Mdataini,@Mdatafim,2)
					
					select 
						format(0, 'C', 'pt-br') AS 'VALOR_LIQUIDO',
						(select format(SUM(PV.PREVISAO_VALOR), 'C', 'pt-br') from LOJAS_PREVISAO_VENDAS PV INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = @filial WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @dataini and @datafim) AS 'META_ORIGINAL',
						format(@meta, 'C', 'pt-br') as 'META_PROJETADA',
						format(0, 'C', 'pt-br') AS '%_META',
						format(0, 'C', 'pt-br') AS 'ATENDIMENTOS',
						format(0, 'C', 'pt-br') AS 'TROCAS',
						format(0, 'C', 'pt-br') AS '%_TROCA_PROD',
						format(0, 'C', 'pt-br') AS 'TICKET_MEDIO',
						format(0, 'C', 'pt-br') AS 'PECAS',
						format(0, 'C', 'pt-br') AS 'PECAS_A'

				";

								echo "
						
							<TABLE aling='center' class='table table-bordered'>
							<thead style='background-color:#C0C0C0;' class='thead-'>
							<TR>
							<th align=''><div align='center' ><h5>VALOR LIQUIDO</h5></div></th>
							<th align=''><div align='center' ><h5>META ORIGINAL</h5></div></th>
							<th align=''><div align='center' ><h5>META PROJETADA</h5></div></th>
							<th align=''><div align='center' ><h5>% META</h5></div></th>
							<th align=''><div align='center' ><h5>ATENDIMENTOS</h5></div></th>
							<th align=''><div align='center' ><h5>TROCAS</h5></div></th>
							<th align=''><div align='center'  data-toggle='tooltip' title='Percentual de trocas com valor pago acima de R$ 5,00.' ><h5>% TROCAS PRODUTIVAS</h5></div></th>
							<th align=''><div align='center'  data-toggle='tooltip' title='Ticket médio considerando todos os atendimentos.'><h5>TICKET MÉDIO</h5></div></th>
							<th align=''><div align='center' ><h5>PEÇAS</h5></div></th>
							<th align=''><div align='center'  data-toggle='tooltip' title='Quantas peças por atendimento.' ><h5>PEÇAS/A</h5></div></th>
					</thead>
						</TR>";

								$stmtAC = sqlsrv_query($conn, $query);

								if ($stmtAC) 
								{
									$rows = sqlsrv_has_rows( $stmtAC );
									if ($rows === true)
									{
										/*echo "Existe linha no resultado da query. <br />";*/
										
										while (@$item = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)) 
										{
								
											echo 
											"	<TR>
											<TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD> 
											<TD align='center' class='col-md-1'><font size=2>" . $item['META_ORIGINAL'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['META_PROJETADA'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['%_META'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['ATENDIMENTOS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['TROCAS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_TROCA_PROD']). "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['TICKET_MEDIO'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . floatval($item['PECAS_A']) . "</font></TD>
							
											</TR>
											";
										}
									}
									else { 
										/*echo "Não existe linha no resultado da query. <br />";*/

										$stmtAC5 = sqlsrv_query($conn, $query2);

										while (@$item5 = sqlsrv_fetch_array($stmtAC5, SQLSRV_FETCH_ASSOC))
										{
											echo 
											"	<TR>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['VALOR_LIQUIDO'] . "</font></TD> 
											<TD align='center' class='col-md-1'><font size=2>" . $item5['META_ORIGINAL'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['META_PROJETADA'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['%_META'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['ATENDIMENTOS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['TROCAS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['%_TROCA_PROD']. "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['TICKET_MEDIO'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['PECAS'] . "</font></TD>
											<TD align='center' class='col-md-1'><font size=2>" . $item5['PECAS_A'] . "</font></TD>
								
											</TR>
											";
										}
									}
								}
								echo "</table>";
								echo "
					<TABLE aling='center' class='table table-bordered'>
					<thead style='background-color:#C0C0C0;' class='thead-'>
				<TR>
				<th align=''><div align='center' ><h5>DESCONTO</h5></div></th>
				<th align=''><div align='center' ><h5>% DESCONTO</h5></div></th>
				<th align=''><div   data-toggle='tooltip' title='Percentual do valor liquido somente para cartão Polyelle.' align='center' ><h5>% VENDAS CARTÃO POLYELLE</h5></div></th>
				<th align=''><div   data-toggle='tooltip' title='Percentual dos atendimentos com cartão Polyelle.' align='center' ><h5>% ATENDIMENTO CATÃO POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>MEDIA PARCELAS CARTÃO POLYELLE</h5></div></th>
				<th align=''><div align='center' ><h5>TICKET MÉDIO CARTÃO POLYELLE</h5></div></th>
				<th align=''><div   data-toggle='tooltip' title='Percentual dos atendimentos com CPF.' align='center' ><h5>% VENDAS C/CADASTRO</h5></div></th>
				<th align=''><div align='center' ><h5>PEDIDO FECHADO</h5></div></th>
			</thead>
				</TR>";
								@$stmtAC2 = sqlsrv_query($conn, $query);


								while (@$item2 = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {
									echo "	<TR>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['DESCONTO'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . floatval($item2['%_DESCONTO']) . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_VALOR_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_A_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['PARCELAS_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['TICKET_POLYELLE'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . $item2['%_CADASTRO'] . "</font></TD>
							<TD align='center' class='col-md-1'><font size=2>" . floatval($item2['PEDIDOS_FECHADOS']) . "</font></TD>
							</TR>";
								}
							}
							echo "</table>";

							?>
						</nav>

					</div>
					<!------ PARTE 2 --------->
					<div align='center' float="">
						<div class='panel panel-primary'>
							<div class="panel-heading" ALIGN="center">
								<h4>Resultado do Mês </h4>
							</div>
							<nav class="navbar navbar-light bg-light" ALIGN="center">
								<?
								$query2 = "
						declare  @filial varchar(06), @Mdataini date, @Mdatafim date
						set @filial = '" . $COD_FILIAL . "'	-- VARIÁVEL PARA ESCOLHA DA LOJA
						set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
						set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL
						
						SELECT
							format(sum(A.VALOR_PAGO), 'C', 'pt-br') AS 'VALOR_LIQUIDO',
							(select format(SUM(PV.PREVISAO_VALOR), 'C', 'pt-br') from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @Mdataini and @Mdatafim) AS 'META',
							case when (select SUM(PV.PREVISAO_VALOR) from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @Mdataini and @Mdatafim) = 0 then
								0.00
							else
								CAST(ROUND(((sum(A.VALOR_PAGO) / (select SUM(PV.PREVISAO_VALOR) from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.FILIAL AND PV.DATA_VENDA between @Mdataini and @Mdatafim)) * 100),2) AS NUMERIC(11,2))
							end AS '%_META',
							format(((select SUM(PV.PREVISAO_VALOR) from LOJAS_PREVISAO_VENDAS PV WHERE PV.FILIAL = C.filial AND PV.DATA_VENDA between @Mdataini and @Mdatafim) -
								sum(A.VALOR_PAGO)), 'C', 'pt-br')  AS 'DIF_META',
							(SELECT
								COUNT(*)
							FROM
								(SELECT A2.DATA_VENDA,SUM(A2.VALOR_PAGO) AS 'VALOR',PV.PREVISAO_VALOR 
								FROM
									LOJA_VENDA A2 
									INNER JOIN LOJAS_PREVISAO_VENDAS PV ON PV.FILIAL = C.FILIAL AND PV.DATA_VENDA = A2.DATA_VENDA 
								WHERE 
									A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial AND A2.DATA_VENDA between @Mdataini and @Mdatafim GROUP BY A2.DATA_VENDA,PV.PREVISAO_VALOR
								) T
							WHERE
								T.VALOR >= T.PREVISAO_VALOR) AS 'DIAS_POSITIVOS',
							(SELECT
								COUNT(*)
							FROM
								(SELECT A2.DATA_VENDA,SUM(A2.VALOR_PAGO) AS 'VALOR',PV.PREVISAO_VALOR 
								FROM 
									LOJA_VENDA A2 
									INNER JOIN LOJAS_PREVISAO_VENDAS PV ON PV.FILIAL = C.FILIAL AND PV.DATA_VENDA = A2.DATA_VENDA 
								WHERE 
									A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial AND A2.DATA_VENDA between @Mdataini and @Mdatafim GROUP BY A2.DATA_VENDA,PV.PREVISAO_VALOR
								) T
							WHERE
								T.VALOR < T.PREVISAO_VALOR) AS 'DIAS_NEGATIVOS',
							(select COUNT(*) from CLIENTES_VAREJO cv where cv.FILIAL = C.FILIAL and cv.CADASTRAMENTO between @Mdataini and @Mdatafim and cv.STATUS = 1) as 'CLIENTE_CADASTRADO',
							CAST(ROUND((select CAST(COUNT(*) AS numeric(11,3)) from CLIENTES_VAREJO cv where cv.FILIAL = C.FILIAL and cv.CADASTRAMENTO between @Mdataini and @Mdatafim and cv.STATUS = 1 and cv.CLIENTE_VAREJO IS NOT NULL AND cv.CPF_CGC IS NOT NULL AND cv.SEXO <> '' AND cv.EMAIL LIKE '%@%' AND (cv.TELEFONE <> '' AND cv.DDD <> '' OR cv.DDD_CELULAR <> ''  AND cv.CELULAR <> '') AND cv.ANIVERSARIO IS NOT NULL) /
								(select COUNT(*) from CLIENTES_VAREJO where FILIAL = C.FILIAL and CADASTRAMENTO between @Mdataini and @Mdatafim and STATUS = 1) * 100,2) AS NUMERIC(11,2)) AS '%_QUAL_CLIENTE'
	
						FROM
							LOJA_VENDA A
							INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = A.CODIGO_FILIAL
						WHERE
							A.DATA_HORA_CANCELAMENTO IS NULL
							AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @Mdataini and @Mdatafim
						group by C.FILIAL


";
								echo "
						
						<TABLE aling='center' class='table table-bordered' >
						<thead style='background-color:#C0C0C0;' class='thead-'>
							<TR>
							<th align='center'><div align='center' class='col-sm' ><h5>VALOR LIQUIDO</h5></div></th>
							<th align='center'><div align='center' class='col-sm' ><h5>META</h5></th></div>
							<th align='center'><div align='center' class='col-sm'  ><h5>% META</h5></th></div>
							<th align='center'><div align='center' class='col-sm' ><h5>DIF META</h5></th></div>
							<th align='center'><div data-toggle='tooltip' title='Dias do mês que ficaram acima da meta.' align='center' class='col-sm' ><h5>DIAS_POSITIVOS</h5></th></div>
							<th align='center'><div data-toggle='tooltip' title='Dias do mês que ficaram abaixo da meta. ' align='center' class='col-sm' ><h5>DIAS_NEGATIVOS</h5></th></div>
							<th align='center'><div data-toggle='tooltip' title='Quantidade de clientes cadastrados no mês.' align='center' class='col-sm'  ><h5>CLIENTES CADASTRADOS</h5></th></div>
							<th align='center'><div data-toggle='tooltip' title='Percentual de clientes cadastrados no mês com os campos: E-MAIL, DDD, TELEFONE, SEXO e ANIVERSÁRIO.
							' align='center' class='col-sm'  ><h5>%_QUALIDADE_CLIENTES CADASTRADOS</h5></th></div>
							</TR></thead>
						";

								@$stmtAC3 = Sqlsrv_query($conn, $query2);

								while (@$item = sqlsrv_fetch_array($stmtAC3, SQLSRV_FETCH_ASSOC)) {

									echo "
								<tr>
									<TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . $item['META'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['%_META'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['DIF_META'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['DIAS_POSITIVOS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['DIAS_NEGATIVOS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['CLIENTE_CADASTRADO'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['%_QUAL_CLIENTE'] . "</font></TD>
								</tr>";
								}

								echo "</table>"; ?>
							</nav>
						</div>
					</div>
					<!-- PARTE 3 RANKING VENDEDORES -->
					<div class="fluid" float="">
						<div class='panel panel-primary'>
							<div class="panel-heading" ALIGN="center">
								<h4>Ranking Vendedores </h4>
							</div>
							<nav class="navbar navbar-light bg-light" ALIGN="center">
								<?
								$query3 = "	
						declare @dataini date, @datafim date, @filial varchar(06), @Mdataini date, @Mdatafim date, @meta numeric(11,2)
set @dataini = '" . $DATAINICIO . "'		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
set @datafim = '" . $DATAFIM . "'		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL
set @filial = '" . $COD_FILIAL . "'		-- VARIÁVEL PARA ESCOLHA DA LOJA
set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESC
set @meta = dbo.FX_CALCULA_META_PROJETADA(@filial,@dataini,@datafim,@Mdataini,@Mdatafim,2)
					
SELECT
ROW_NUMBER() OVER(ORDER BY SUM(A.VALOR_PAGO) DESC) AS 'RNK',
a.vendedor,
B.NOME_VENDEDOR,
SUM(A.VALOR_PAGO) AS 'VENDA_LIQUIDA',
CASE WHEN (SUM(A.VALOR_PAGO)) = 0 THEN
	0
ELSE
	CAST(ROUND((SUM(A.VALOR_PAGO) /
		(select max(valor) from (SELECT a2.vendedor, sum(a2.valor_pago) as 'valor' FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim group by A2.VENDEDOR) t)) * 100,2) AS NUMERIC(11,2))
END	as '%_RNK',
CASE WHEN B.DESC_CARGO LIKE 'VENDED%' THEN
	CAST(ROUND(@meta /
		(select COUNT(*) from (SELECT A2.VENDEDOR FROM LOJA_VENDA A2 inner join LOJA_VENDEDORES B2 ON B2.VENDEDOR = A2.VENDEDOR WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.DESC_CARGO LIKE 'VENDED%' GROUP BY A2.VENDEDOR) t),2) AS NUMERIC(11,2))
ELSE
	0.00
END  AS 'META',
(select count(*) from (SELECT A2.DATA_VENDA FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR GROUP BY A2.VENDEDOR,A2.DATA_VENDA) t) AS 'DIAS',
CASE WHEN (SUM(A.VALOR_PAGO)) = 0 THEN
	0
ELSE
	CAST(ROUND((SUM(A.VALOR_PAGO) / 
		(select count(*) from (SELECT A2.DATA_VENDA FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR GROUP BY A2.VENDEDOR,A2.DATA_VENDA) t)),2) AS NUMERIC(11,2)) 
END	as 'VENDA P/DIA',
sum(A.DESCONTO) as 'DESCONTO',
COUNT(A.VALOR_PAGO) AS 'ATENDIMENTOS',
(SELECT COUNT(*) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR AND A2.VALOR_TROCA > 0) AS 'TROCAS',
CAST(ROUND(((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR AND A2.VALOR_TROCA > 0 AND A2.VALOR_PAGO > 5.00) / 
	(SELECT CASE WHEN COUNT(*) = 0 THEN 1 ELSE COUNT(*) END FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR AND A2.VALOR_TROCA > 0)) * 100,2) AS NUMERIC(11,2)) AS '%_TROCA_PROD',
CAST(ROUND((SUM(A.VALOR_PAGO) / COUNT(A.VALOR_PAGO)),2) AS NUMERIC(11,2)) AS 'TICKET_MEDIO',
(SELECT SUM(B2.QTDE) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR) as 'PECAS',
CAST(ROUND(((SELECT CAST(SUM(B2.QTDE) AS numeric(11,3)) FROM LOJA_VENDA A2 INNER JOIN LOJA_VENDA_PRODUTO B2 ON B2.CODIGO_FILIAL = A2.CODIGO_FILIAL AND B2.TICKET = A2.TICKET WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR) /
	COUNT(A.VALOR_PAGO)),2) AS NUMERIC(11,2)) AS 'PECAS_A',
CAST(ROUND(((SELECT CAST(COUNT(*) AS numeric(11,3)) FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR AND A2.CODIGO_CLIENTE IS NOT NULL) / 
	COUNT(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2)) AS '%_CADASTRO',
	(select	ISNULL(sum(T.valor_pago),0) from	(select	lp.CODIGO_FILIAL_ORIGEM as 'cod_filial',
														concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) as 'ticket',
														lpp.valor as 'valor_pago'
												from 
													loja_pedido lp
													left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
												where
													lp.tipo_pedido = 3 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
													and lp.DATA between @dataini and @datafim and lp.CODIGO_FILIAL_ORIGEM = @filial and lp.VENDEDOR = A.VENDEDOR
													and not exists(	select	*
																	from
																		LOJA_VENDA A2
																	where
																		A2.DATA_HORA_CANCELAMENTO IS NULL
																		AND A2.DATA_VENDA between @dataini and @datafim
																		and a2.VENDEDOR = A.VENDEDOR
																		and a2.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and a2.TICKET = concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-',''))
																		and A2.VALOR_PAGO = lpp.valor)
												) T) as 'PED_FECHADOS'

FROM
LOJA_VENDA A
INNER JOIN LOJA_VENDEDORES B ON B.VENDEDOR = A.VENDEDOR
INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = A.CODIGO_FILIAL
WHERE
A.DATA_HORA_CANCELAMENTO IS NULL
AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @dataini and @datafim
group by C.FILIAL, A.VENDEDOR, B.NOME_VENDEDOR, B.DESC_CARGO
ORDER BY VENDA_LIQUIDA DESC
";
								echo "
						
							<TABLE ALIGN='' class='table table-bordered responsive' style='display:block; overflow-x: auto;'>
							<thead style='background-color:	#C0C0C0;'  class='thead-'>
							<TR>
							<th align='center'><div align='center' ><h6>RNK</h6></div></th>
							<th align='center'><div align='center' ><h6>NOME VENDEDOR</h6></div></th>
							<th align='center'><div align='center' ><h6> COD. VENDEDOR</h6></div></th>
							<th align='center'><div align='center' ><h6>VENDA LIQUIDA</h6></div></th>
							<th align='center'><div data-toggle='tooltip' title='Percentual em relação a venda líquida dos vendedores.' align='center' ><h6>%_RNK</h6></div></th>
							<th align='center'><div align='center' ><h6>META</h6></div></th>
							<th align='center'><div data-toggle='tooltip' title='Quantidade de dias que o vendedor teve venda.' align='center' ><h6>DIAS</h6></div></th>
							<th align='center'><div align='center' ><h6>VENDA P/DIA</h6></div></th>
							<th align='center'><div align='center' ><h6>DESCONTO</h6></div></th>
							<th align='center'><div align='center' ><h6>ATENDIMENTOS</h6></th>
							<th align='center'><div align='center' ><h6>TROCAS</h6></div></th>
							<th align='center'><div align='center' ><h6>%_TROCA PRODUTIVA</h6></div></th>
							<th align='center'><div align='center' ><h6>TICKET MEDIO</h6></div></th>
							<th align='center'><div align='center' ><h6>PEÇAS</h6></div></th>
							<th align='center'><div align='center' ><h6>PEÇAS/A</h6></div></th>
							<th align='center'><div data-toggle='tooltip' title='Percentual dos atendimentos com CPF.' align='center' ><h6>% CADASTRO</h6></div></th>
							<th align='center'><div align='center' ><h6>PEDIDOS FECHADOS</h6></div></th>
							
						</TR></thead>";

								@$stmtAC4 = Sqlsrv_query($conn, $query3);

								while (@$item = sqlsrv_fetch_array($stmtAC4, SQLSRV_FETCH_ASSOC)) {

									echo "
									<TR>
									<TD scope='col' align='center' class='col-md-1'><font size=2>" . $item['RNK'] . "</font></TD> ";
									?>
								<TD align="center" class="col-md-1 view_data2"
								id1="<?php echo $COD_FILIAL ?>"
								id2="<?php echo $DATAINICIO ?>"
								id3="<?php echo $DATAFIM ?>"
								id4="<?php echo$item['vendedor'];?>"
								id5="<?php echo$item['NOME_VENDEDOR'];?>"
								
								>
								
								<font size=2><?php echo $item['NOME_VENDEDOR']?></font></TD>
									<?php
								echo"	


									<TD align='center' class='col-md-1'><font size=2>" . $item['vendedor'] . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['VENDA_LIQUIDA']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['%_RNK'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['META'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['DIAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['VENDA P/DIA'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['DESCONTO']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['ATENDIMENTOS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['TROCAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_TROCA_PROD']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['TICKET_MEDIO'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS_A'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_CADASTRO']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['PED_FECHADOS']) . "</font></TD>
									";?>		
									
									</TR>
<?								}

								echo "</table>"; ?>
						</div>
					</div>

					<div class="card-footer bg-transparent border-success" align='center'>
						<img align="center" src="img/logo.png"> <br>
						@TI.
					</div>
			<?

		} else {
			echo "<script language='javascript' type='text/javascript'>
    alert('Data fora do período permitido! ');
    </script>";
		}
	} else {
	}
			?>
			
<script >
    	id1="<?php echo $COD_FILIAL ?>"
								id2="<?php echo $DATAINICIO ?>"
								id3="<?php echo $DATAFIM ?>"
								id4="<?php echo$item['vendedor'];?>"
								id5="<?php echo$item['NOME_VENDEDOR'];?>"
    $(document).ready(function() {
                $(document).on('click', '.view_data2', function() {
                    var loja = $(this).attr("id1");
                    var datainicio = $(this).attr("id2");
                    var datafim = $(this).attr("id3");
                    var matricula = $(this).attr("id4");
                    var nomevendedor = $(this).attr("id5");
                    if (loja !== '') {
                        var dados = {
                            loja:loja,
							datainicio:datainicio,
							datafim:datafim,
							matricula:matricula,
							nomevendedor:nomevendedor
                            
                        };
                        $.post('detalhar-vendedor.php', dados, function(retorna) {
                            $("#detalhar").html(retorna);
                            $('#detalhar-vendedor').modal('show');
                        });
                    }
                });
            });

</script>

			<style>
				thead {
					border: 1px black;
					border-radius: 5px;
					-moz-border-radius: 5px;
				}

				table {
					flex: 1;
					border: 1px solid black;
					border-radius: 5px;
					-moz-border-radius: 5px;

				}
			</style>

		<? } else {
		echo "<script language='javascript' type='text/javascript'>
	        alert('Login e/ou senha incorretos');window.location
	        .href='login.html';</script>";
		die();
	} ?>
	<div class="modal fade  bd-example-modal-xl" id="detalhar-vendedor" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- <h3 class="modal-title" id="exampleModalLongTitle">GERAR DOCUMENTO DE DEVOLUÇÃO/TROCA</h3> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <span id="detalhar"> </span>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>