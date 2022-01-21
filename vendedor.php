<br />
<title>Analitico Vendedores</title>
<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
<?
include "visual.html";

?>
<div class="col-sm-2" ALIGN="center"></div>
<div class="container-fluid col-md-8" ALIGN="center">
	<div class='panel panel-success border-primary'>
		<div class="panel-heading" ALIGN="center">
			<h4>CAMPOS DE PESQUISA</h4>
		</div>
		<nav class="navbar navbar-light bg-light" ALIGN="center">
			<form action="" name="form1" method="post">
				<div name="DIV-COD_BARRA" class="col-md-3">
					<LABEL class="label-input100">
				
						<h5><strong>DATA INICIO:</strong> </h5>
					</label>
					<input id="DATAINICIO" required name="DATAINICIO" class="form-control form-control-sm" onblur="funcao1();" type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
				</div>
				
				<div class="col-md-3">
					<label>
						<h5><strong>DATA FIM:</strong></h5>
					</label>
					<input id="DATAFIM" required name="DATAFIM" class="form-control form-control-sm" onblur="funcao1();" type="date" placeholder="dd/mm/aaaa" aria-label="Search" />

				</div>
				<div class="col-md-3">
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
				<div class="col-md-2">
					<label>
						<h5><strong>VENDEDOR:</strong></h5>
					</label>
					<input id="VENDEDOR" required name="VENDEDOR" class="form-control form-control-sm" onblur="funcao1();" type="number" placeholder="Código V..." aria-label="Search" />
				</div>
				</br>
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
		var VENDEDOR = document.getElementById("VENDEDOR");
		var SendPesqUser = document.getElementById("SendPesqUser");

	}
</script>

<?

@$DATAINICIO = date('Ymd', strtotime($_POST['DATAINICIO']));
@$DATAFIM = date('Ymd', strtotime($_POST['DATAFIM']));
@$COD_FILIAL = $_POST['COD_FILIAL'];
@$VENDEDOR = $_POST['VENDEDOR'];
?>

<?
$dataHoje = date("Ymd");
//$dataLimiteMin = date("Ymd", strtotime($dataHoje) - (38 * 24 * 60 * 60));
$dataLimiteMin = date("Ymd",  strtotime('first day of last month'));

$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
if ($SendPesqUser) {
	$COD_FILIAL = filter_input(INPUT_POST, 'COD_FILIAL', FILTER_SANITIZE_STRING);
if (@$DATAINICIO >= $dataLimiteMin) {
// se data selecionada for menor ou igual a data minima 

?>

<div class="panel-body"></div>
<div class="panel-footer">
	<div class="fluid" float="">
		<div class='panel panel-primary'>
			<div class="panel-heading" ALIGN="center">
				<h4>Ranking Vendedores </h4>
			</div>
			<nav class="navbar navbar-light bg-light" ALIGN="center">
				<?

				$query = "	declare @dataini date, @datafim date, @filial varchar(06), @vendedor varchar(04), @Mdataini date, @Mdatafim date, @meta numeric(11,2)
						set @dataini = '" . $DATAINICIO . "'		
						set @datafim = '" . $DATAFIM . "'		
						set @filial = '" . $COD_FILIAL . "'
						set @vendedor = '" . $VENDEDOR . "'
						set @Mdataini = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),'01')		-- VARIÁVEL PARA ESCOLHA DA DATA INICIAL
						set @Mdatafim = CONCAT(RTRIM(YEAR(GETDATE())),CONCAT(REPLICATE('0',2 - LEN(MONTH(GETDATE()))),MONTH(GETDATE())),DAY(EOMONTH(GETDATE())))		-- VARIÁVEL PARA ESCOLHA DA DATA FINAL

						--CALCULO DA META PROJETADA
						set @meta = dbo.FX_CALCULA_META_PROJETADA(@filial,@dataini,@datafim,@Mdataini,@Mdatafim,2)

						

select
	T.RNK,
	T.NOME_VENDEDOR,
	T.VENDA_LIQUIDA,
	T.[%_RNK],
	T.META,
	T.DIAS,
	T.DESCONTO,
	T.ATENDIMENTOS,
	T.TROCAS,
	T.[%_TROCA_PROD],
	T.TICKET_MEDIO,
	T.PECAS,
	T.PECAS_A,
	T.[%_CADASTRO],
	(select ISNULL(SUM(valor_pago),0.00) from LOJA_VENDA a2 where A2.DATA_HORA_CANCELAMENTO IS NULL AND a2.CODIGO_FILIAL <> @filial and A2.DATA_VENDA between @dataini and @datafim and a2.VENDEDOR = @vendedor) as 'OUTRAS_VENDAS',
	(T.VENDA_LIQUIDA + (select ISNULL(SUM(valor_pago),0.00) from LOJA_VENDA a2 where A2.DATA_HORA_CANCELAMENTO IS NULL AND a2.CODIGO_FILIAL <> @filial and A2.DATA_VENDA between @dataini and @datafim and a2.VENDEDOR = @vendedor)) AS 'TOTAL_VENDAS'
from
	(
	SELECT
		ROW_NUMBER() OVER(ORDER BY SUM(A.VALOR_PAGO) DESC) AS 'RNK',
		a.VENDEDOR,
		B.NOME_VENDEDOR,
		SUM(A.VALOR_PAGO) AS 'VENDA_LIQUIDA',
		CASE WHEN (SUM(A.VALOR_PAGO)) = 0 THEN
			0
		ELSE
			CAST(ROUND((SUM(A.VALOR_PAGO) /
				(select max(valor) from (SELECT a2.vendedor, sum(a2.valor_pago) as 'valor' FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim group by A2.VENDEDOR) t)) * 100,2) AS NUMERIC(11,2))
		END as '%_RNK',
		CASE WHEN B.DESC_CARGO LIKE 'VENDED%' THEN
			CAST(ROUND(
				@meta /
				(select COUNT(*) from (SELECT A2.VENDEDOR FROM LOJA_VENDA A2 inner join LOJA_VENDEDORES B2 ON B2.VENDEDOR = A2.VENDEDOR WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND B2.DESC_CARGO LIKE 'VENDED%' GROUP BY A2.VENDEDOR) t)
				,2) AS NUMERIC(11,2))
		ELSE
			0.00
		END  AS 'META',
		(select count(*) from (SELECT A2.DATA_VENDA FROM LOJA_VENDA A2 WHERE A2.DATA_HORA_CANCELAMENTO IS NULL AND A2.CODIGO_FILIAL = @filial and A2.DATA_VENDA between @dataini and @datafim AND A2.VENDEDOR = A.VENDEDOR GROUP BY A2.VENDEDOR,A2.DATA_VENDA) t) AS 'DIAS',
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
			COUNT(A.VALOR_PAGO)) * 100,2) AS NUMERIC(11,2)) AS '%_CADASTRO'

	FROM
		LOJA_VENDA A
		INNER JOIN LOJA_VENDEDORES B ON B.VENDEDOR = A.VENDEDOR
		INNER JOIN LOJAS_VAREJO C ON C.CODIGO_FILIAL = A.CODIGO_FILIAL
	WHERE
		A.DATA_HORA_CANCELAMENTO IS NULL
		AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @dataini and @datafim
	group by C.FILIAL, A.VENDEDOR, B.NOME_VENDEDOR, B.DESC_CARGO
	) T
where T.VENDEDOR = @vendedor
ORDER BY t.VENDA_LIQUIDA DESC

";
				echo "
						
							<TABLE ALIGN='' class='table table-bordered responsive' style='display:block; overflow-x: auto;'>
							<thead style='background-color:	#C0C0C0;'  class='thead-'>
							<TR>
							<th align='center'><div align='center' ><h6>RNK</h6></div></th>
							<th align='center'><div align='center' ><h6>NOME VENDEDOR</h6></div></th>
							<th align='center'><div align='center' ><h6>VENDA LIQUIDA</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Percentual em relação a venda líquida do primeiro vendedor da loja.'><h6>%_RNK</h6></div></th>
							<th align='center'><div align='center' ><h6>META</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Quantidade de dias que o vendedor teve venda na loja.'><h6>DIAS</h6></div></th>
							<th align='center'><div align='center' ><h6>DESCONTO</h6></div></th>
							<th align='center'><div align='center' ><h6>ATENDIMENTOS</h6></th>
							<th align='center'><div align='center' ><h6>TROCAS</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Percentual de trocas com valor pago acima de R$ 5,00 na loja.'><h6>%_TROCA PRODUTIVA</h6></div></th>
							<th align='center'><div align='center' ><h6>TICKET MEDIO</h6></div></th>
							<th align='center'><div align='center' ><h6>PEÇAS</h6></div></th>
							<th align='center'><div align='center' ><h6>PEÇAS/A</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Percentual dos atendimentos com CPF na loja.'><h6>% CADASTRO</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Venda liquida feita em outra loja.'><h6>OUTRAS VENDAS</h6></div></th>
							<th align='center'><div align='center' data-toggle='tooltip' title='Somatório da venda liquida na loja + outras vendas.' ><h6>TOTAL VENDAS</h6></div></th>
							
						</TR></thead>";

				@$ResultadoSQL = Sqlsrv_query($conn, $query);

				while (@$item = sqlsrv_fetch_array($ResultadoSQL, SQLSRV_FETCH_ASSOC)) {

					echo "
									<TR>
									<TD scope='col' align='center' class='col-md-1'><font size=2>" . $item['RNK'] . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . $item['NOME_VENDEDOR'] . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['VENDA_LIQUIDA']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_RNK']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['META']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['DIAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['DESCONTO']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['ATENDIMENTOS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['TROCAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_TROCA_PROD']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['TICKET_MEDIO']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . $item['PECAS'] . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['PECAS_A']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['%_CADASTRO']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['OUTRAS_VENDAS']) . "</font></TD>
									<TD align='center' class='col-md-1'><font size=2>" . floatval($item['TOTAL_VENDAS']) . "</font></TD>
								
									</TR>";
				}

				echo "</table>"; ?>
		</div>

		<!-- FIM DO PRIMEIRO BLOCO -->
		<div class="panel-body" align="center">
			<div class="col-md-3" align="center" float=""></div>
			<div class="col-md-6" align="center" float="">
				<div class='panel panel-primary'>
					<div class="panel-heading" ALIGN="center">
						<h4>Pedidos Fechados</h4>
					</div>
					<nav class="navbar navbar-light bg-light" ALIGN="center">
						<?

						$query = "	declare @dataini date, @datafim date, @filial varchar(06), @vendedor varchar(04)
						set @dataini = '" . $DATAINICIO . "'		
						set @datafim = '" . $DATAFIM . "'		
						set @filial = '" . $COD_FILIAL . "'
						set @vendedor = '" . $VENDEDOR . "'
					
						
						select
						ISNULL(sum(T.valor_pago),0) as 'VALOR_PEDIDOS_FECHADOS',
						COUNT(*) AS 'ATENDIMENTOS'
					from
						(
						select	
							lp.CODIGO_FILIAL_ORIGEM as 'cod_filial',
							concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-','')) as 'ticket',
							lpp.valor as 'valor_pago'
						from 
							loja_pedido lp
							left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
						where
							lp.tipo_pedido = 3 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0
							and lp.DATA between @dataini and @datafim and lp.CODIGO_FILIAL_ORIGEM = @filial and lp.VENDEDOR = @vendedor
							and not exists(select
												rtrim(a.CODIGO_FILIAL) as 'cod_filial',
												rtrim(a.TICKET) as 'ticket',
												a.VALOR_PAGO as 'valor_pago'
											from
												LOJA_VENDA a
											where
												A.DATA_HORA_CANCELAMENTO IS NULL
												AND A.CODIGO_FILIAL = @filial and A.DATA_VENDA between @dataini and @datafim
												and a.VENDEDOR = @vendedor
												and a.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and a.TICKET = concat('P',replicate('0',8-len(lp.pedido)),replace(lp.pedido,'-',''))
												and a.VALOR_PAGO = lpp.valor)
						) T

";
						echo "
						
							<TABLE ALIGN='' class='table table-bordered ' >
							<thead style='background-color:	#C0C0C0;'  class='thead-'>
							<TR>
							<th align='center'><div align='center' ><h6>VALOR_PEDIDOS_FECHADOS</h6></div></th>
							<th align='center'><div align='center' ><h6>ATENDIMENTOS</h6></div></th>
						
							
						</TR></thead>";

						@$ResultadoSQL = Sqlsrv_query($conn, $query);

						while (@$item = sqlsrv_fetch_array($ResultadoSQL, SQLSRV_FETCH_ASSOC)) {

							echo "
									<TR>
									<TD scope='col' align='center' class='col-md-1'><font size=2>" . floatval($item['VALOR_PEDIDOS_FECHADOS']) . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . $item['ATENDIMENTOS'] . "</font></TD> 
								
								
									</TR>";
						}

						echo "</table>"; ?>
				</div>
			</div>
			</nav>
		</div>
	</div>



	<div class="footer bg-transparent border-success" align='center'>
		<img align="center" src="img/logo.png"> <br>
		@TI.
	</div>

<?
} else {
	echo "<script language='javascript' type='text/javascript'>
    alert('Data fora do período permitido! ');
    </script>";
}
}else {
	
}

?>


	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>

	<style>
		panel {
			border: 1px solid black;
			border-radius: 5px;
			-moz-border-radius: 5px;
		}

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
