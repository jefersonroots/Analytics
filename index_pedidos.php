<title>Pedidos</title>
<meta http-equiv=”Content-Type” content=”text/html; charset=utf-8″>
<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
<link rel="stylesheet" href="ux/bootstrap.min.css">
<script src="ux/jquery.min.js"></script>
<script src="ux/bootstrap.min.js"></script>
<script src="ux/jquery.min.js"></script>
<link rel="stylesheet" href="ux/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="ux/bootstrap.min.css">
		<script src="ux/popper.min.js"></script>
		<script src="ux/bootstrap.min.js"></script>
   
<?

   
include "listaLojaIP.php";
include "visual.html";


// FIM //
?>
<br>
<div class="col-md-1" align="left"></div>
<div class="col-md-12" align="center">
	<div class="panel panel-danger" align="center">
		<div class="panel-heading" style="background: rgb(180,58,58);
background: linear-gradient(90deg, rgba(180,58,58,1) 0%, rgba(215,8,8,0.958420868347339) 51%, rgba(190,45,45,1) 100%);"
			align="center" action="buscar.php">
			<h4 style="color:black; font-style: verdana;">PREENCHA OS CAMPOS PARA PESQUISAR</h4>
		</div>
		<nav class="navbar navbar-light bg-light">
			<form action="" method="post" name="form1">
				<h3 style="color: red;"></h3>

				<div align="center" class="col-md-2"> </div>
				<div align="center" class="col-md-4">
					<label>
						<h5><strong>CNPJ DO FORNECEDOR:</strong></h5>
					</label><br>
					<input required name="CNPJ" id="CNPJ" class="form-control form-control-sm" onblur="funcao1();"
						type="number" placeholder="somente números" />
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<? include('../restricao/listaLojaIP.php'); ?>

						<label for="COD_FILIAL">
							<h5><strong>LOJA:</strong></h5>
						</label><br>
						<select required name="COD_FILIAL" class="form-control" id="COD_FILIAL">
							<option>Selecione...</option>
							<?php
							while ($row_estab = sqlsrv_fetch_array($querySelect)) {
							?>
							<option required type="text" name="f.COD_FILIAL"
								value="<? echo $row_estab['cod_filial'] ?>">
								<? echo $row_estab['filial'] ?>
							</option>
							<?
							}
							?>
						</select>
					</div>

				</div>

				<div class="col-md-12" align="center">
					<br>
					<INPUT class='btn btn-success' title="PESQUISA POR PDV/CUPOM " name="SendPesqUser" id="SendPesqUser"
						onblur="funcao1();" align="right" value="PESQUISAR" type='submit'></input>
					<br>
					<br>
					<button onclick="window.location.href='index.php';" class='btn btn-success' type="reset"
						title="Limpar" name="limpar" 
						href='http://110.100.2.9/lojapedido'>LIMPAR</button>
				</div>
			</form>

	</div>
	<div class="modal fade  bd-example-modal-lg" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">Detalhes do Pedido</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span id="visualiza"> </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
	<script languege=javascript>
		function funcao1() {
			var COD_FILIAL = document.getElementById("COD_FILIAL");
			var CNPJ = document.getElementById("CNPJ");
			var SendPesqUser = document.getElementById("SendPesqUser");
			var Pedido = document.getElementById("PEDIDO");
			
		}
		
	</script>


	<!-- PARTE  1 do painel !-->
	<?

$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
	if($SendPesqUser){
	@$COD_FILIAL = $_POST['COD_FILIAL'] ;
	@$CNPJ = $_POST['CNPJ'] ;

   $sql = "
   declare @filial varchar(06), @cnpj varchar(19)
   set @filial = '".$COD_FILIAL."' 	
   set @cnpj = '".$CNPJ."'
   
select 
fo.CGC_CPF as 'CNPJ',
ccf.RAZAO_SOCIAL as 'RAZAO_SOCIAL',
fo.FORNECEDOR as 'FANTASIA',
(select max(cp.ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) as 'ENTREGA',
(select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) as 'LIMITE',
c.PEDIDO as 'PEDIDO',
case when c.TOT_QTDE_ENTREGAR = c.TOT_QTDE_ORIGINAL and c.TOT_QTDE_ENTREGAR > 0 then
	'TOTAL'
	when c.TOT_QTDE_ENTREGAR < c.TOT_QTDE_ORIGINAL and c.TOT_QTDE_ENTREGAR > 0 then
	'PARCIAL'
else
	''
end as 'STATUS',
c.APROVADOR_POR as 'RESPONSAVEL',
case when (select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) >= cast(GETDATE() - 15 as date) and (select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) <= cast(GETDATE() + 10 as date)  then
	'VERDE'
	when ((select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) >= cast(GETDATE() - 25 as date) and (select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) < cast(GETDATE() - 15 as date)) 
		OR ((select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) <= cast(GETDATE() + 20 as date) and (select max(cp.LIMITE_ENTREGA) from COMPRAS_PRODUTO cp where cp.PEDIDO = c.PEDIDO) > cast(GETDATE() + 10 as date)) then
	'AMARELO'
else
	'VERMELHO'
end as 'SINAL'
from 
compras c
inner join FILIAIS f on f.FILIAL = c.FILIAL_A_ENTREGAR
inner join FORNECEDORES fo on fo.FORNECEDOR = c.FORNECEDOR
inner join CADASTRO_CLI_FOR ccf on ccf.CLIFOR = fo.CLIFOR
where
f.COD_FILIAL = @filial and rtrim(fo.CGC_CPF) = @cnpj
and c.TOT_QTDE_ENTREGAR > 0 and c.STATUS_APROVACAO = 'A'
and rtrim(c.TIPO_COMPRA) = 'PRODUTOS ACABADOS'
order by
ENTREGA, LIMITE
"; 
				
	
	$exec = sqlsrv_query($conn1, $sql);
	if( $exec === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	echo"
	<div class='table-responsive-md'><div class='panel panel-danger'>
	<div class='panel-body'><h4>PEDIDOS DO FORNECEDOR</h4></div>
	<div class='panel-footer'>
	<TABLE ALIGN='center' class='table table-bordered table-hover' >
	<thead >
			<TR  style='background-color:   #EBEBEB ;' class='' >
				<th scope='col' align='center'><div align='center' ><h5><b>CNPJ</b></h5></div></th>
				<th align='center'  class='col-md-3'><div align='center'><h5><b>RAZÃO SOCIAL</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>FANTASIA</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>ENTREGA</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>LIMITE</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>PEDIDO</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>STATUS</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>RESPONSÁVEL</b></h5></div></th>
				<th align='center'><div align='center'><h5><b>SINAL</b></h5></strong></div></th>		
			</TR>
			</thead>";	
	while( @$item = sqlsrv_fetch_array(@$exec, SQLSRV_FETCH_ASSOC)){
		?>
			<?
			
	  echo "
	  
	  <TR >
	  <TD align='center' class='col-md-1'><font size=2>".$item['CNPJ']."</font></TD> 
	  <TD align='center' class='col-md-3'><font size=2>".$item['RAZAO_SOCIAL']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['FANTASIA']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['ENTREGA']->format('d/m/Y')."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['LIMITE']->format('d/m/Y')."</font></TD>
	  <TD align='center' id='PEDIDO' name='PEDIDO' class='col-md-1'><font size=2>".$item['PEDIDO']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['STATUS']."</font></TD>
	  <TD align='center' class='col-md-1'><font size=2>".$item['RESPONSAVEL']."</font></TD>"; 
	  if($item['SINAL']=='VERMELHO'){

		echo "<TD ><font size=2><div id='tomato' class='circulo-pequeno tomato'> </div>
		
		</font></TD>";	
	  
	  }if($item['SINAL']=='AMARELO'){

		echo "<TD ><font size=2><div  id='yelu' class='circulo-pequeno yelu'> </font></TD>";	
	  }elseif($item['SINAL']=='VERDE'){

		echo "<TD ><font size=2><div  id='lime' class='circulo-pequeno lime'> </font></TD>";	
	  }
	 
	 ?><td align="center"><button type="button" class="btn btn-info view_data" id="<?php echo $item['PEDIDO']; ?>">Detalhar</button>
	 </TD></TR></div>
	<?}

 ?>
<script>
	
	$(document).ready(function(){
		$(document).on('click','.view_data',function(){
			var pedido = $(this).attr("id");


			if(pedido !== ''){
				var dados = {
					pedido: pedido
				};
				$.post('visualizar.php',dados,function(retorna){
					$("#visualiza").html(retorna);
						$('#visualizar').modal('show');
				});
			}
		});
	});
</script>
	  
<?}?>


<!-- 		
		<div class="footer"><img align="center" src="img/logo.png"> <br>
			@TI.</div> -->

	<style>
		.footer {
			
			position: fixed;
			left: -10;
			bottom: 0;
			width: 100%;
			background-color: white;
			color: black;
			align-items: center;
			align-self: center;
			align-content: center;
		}
	</style>
	<style>
		.circulo {
	width: 100px;
	height: 100px;
	border-radius: 50%;
	overflow: hidden;

	margin: 15px;
	transition: 0.1s ease;
	align-items: center;
			align-self: center;
			align-content: center;
}


.circulo-pequeno {
	width: 20px;
	height: 20px;
	border-radius: 50%;
	margin: 10px;

	animation: circulo 1s linear infinite;
	align-items: center;
			align-self: center;
			align-content: center;
}

.circulo-pequeno.lime {
	animation: circulo-inverno 1s linear infinite;
	align-items: center;
			align-self: center;
			align-content: center;
}



.lime {
	background: green;
}
.yelu{
	background: yellow;
}
.tomato {
	background: red;
}

.container {
	max-width: 520px;
	margin: 20px auto;
}
		
	</style>
</html>