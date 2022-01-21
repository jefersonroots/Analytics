<? 
	   $PEDIDO2 = isset($_POST['PEDIDO']);
       echo $PEDIDO2;



       ?>
       <link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

<!-- Modal --><div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
<?
		
	   $SendPesq = filter_input(INPUT_POST, 'SendPesq', FILTER_SANITIZE_STRING);
	   if(!$SendPesq){
	   $PEDIDO = filter_input(INPUT_POST, 'PEDIDO', FILTER_SANITIZE_STRING);
	   $PEDIDO = $item['PEDIDO'];
	   $PEDIDO2 = isset($_POST['PEDIDO']);
	   echo $PEDIDO2;
	   echo $item['PEDIDO'];
	  $sql1 = " --PESQUISA POR PEDIDO DETALHANDO OS PRODUTOS
	  declare @pedido char(8)
	  set @pedido = '".$PEDIDO2."'
	  
	  select
		  p.REFER_FABRICANTE as 'REFERENCIA',
		  p.DESC_PROD_NF as 'DESCRICAO',
		  pc.DESC_COR_PRODUTO as 'COR',
		  p.PRODUTO as 'PRODUTO',
		  case when cp.QTDE_ENTREGUE = 0 then
			  'TOTAL'
			  when cp.QTDE_ENTREGAR < cp.QTDE_ORIGINAL then
			  'PARCIAL'
		  else
			  ''
		  end as 'STATUS'
	  from
		  COMPRAS_PRODUTO cp
		  inner join PRODUTOS p on p.PRODUTO = cp.PRODUTO
		  inner join PRODUTO_CORES pc on pc.PRODUTO = cp.PRODUTO and pc.COR_PRODUTO = cp.COR_PRODUTO
	  where
		  cp.PEDIDO = @pedido
		  and cp.QTDE_ENTREGAR > 0 and cp.QTDE_CANCELADA = 0
	  order by
		  REFERENCIA
	   "; 
	   
$exec = sqlsrv_query($conn1, $sql1);
if( $exec === false) {
	die( print_r( sqlsrv_errors(), true) );
}
echo"<div class='panel panel-danger'>

<div class='panel-footer'>
<TABLE ALIGN='center' class='table table-bordered table-hover  text-align'>
<thead>
		<TR>
			<th align='center'><div align='center' ><h5>REFERENCIA</h5></div></th>
			<th align='center'><div align='center'><h5>DESCRIÇÃO</h5></div></th>
			<th align='center'><div align='center'><h5>COR</h5></div></th>
			<th align='center'><div align='center'><h5>PRODUTO</h5></div></th>
			<th align='center'><div align='center'><h5>STATUS</h5></div></th>
						
		</TR></thead>";	

while( $item = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)){

  echo "
  
  <TR >
  <TD align='center' class='col-md-1'><font size=2>".$item['REFERENCIA']."</font></TD> 
  <TD align='center' class='col-md-3'><font size=2>".$item['DESCRICAO']."</font></TD>
  <TD align='center' class='col-md-1'><font size=2>".$item['COR']."</font></TD>
  <TD align='center' class='col-md-1'><font size=2>".$item['PRODUTO']."</font></TD>
  <TD align='center' class='col-md-1'><font size=2>".$item['STATUS']."</font></TD>
  </TR>
  " ;

}
echo"</table></div></div>"; ?>
<?}?>