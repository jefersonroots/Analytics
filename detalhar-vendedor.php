<?php 
 include "listalojaIP.php";

@$matricula = $_POST['matricula'];
@$nome_vendedor = $_POST['nomevendedor'];

@$filial = $_POST['loja'];

@$datainicio = $_POST['datainicio'];

@$datafim = $_POST['datafim'];

$grupo = 100;




@$datainiciomenu = date('d/m/Y', strtotime($_POST['datainicio']));
@$datafimmenu = date('d/m/Y', strtotime($_POST['datafim']));
@$LOJA = (string)$_POST['loja']; 


    $DATA = filter_input(INPUT_POST, 'DATA', FILTER_SANITIZE_STRING);
    $query = "

SET NOCOUNT ON  EXEC dbo.LX_POLYELLE_META_CATEGORIA_FILIAL_VENDEDOR '".$grupo."','".$datainicio."','".$datafim."','".$filial."','".$matricula."'


";


echo " <div  style='overflow: auto;' class='panel panel-primary  ' align='center' >
<div    class='panel-heading '  ><h4>Consulta de $datainiciomenu - $datafimmenu - $nome_vendedor</h4></div>
    <nav style=' width: 100%;overflow:auto;float: none;
    display: block;' class='navbar navbar-light bg-light' align='center'>
        <TABLE ALIGN='center'  style='overflow: auto;'  class='table table-bordered table-hover'>
        <thead>
            <TR  style='background-color:   #EBEBEB ;'>
                 <th scope='col' class='col-md-1'><div align='center'><h5><b/>AGRUPAMENTO</h5></div></th>
                 <th scope='col' class='col-md-3'><div align='center'><h5><b/>DESC. AGRUPAMENTO</h5></div></th>
                 <th scope='col' class='col-md-1'><div align='center'><h5><b/>META VALOR  </h5></div></th>
                 <th scope='col' class='col-md-1'><div align='center'><h5><b/>QTDE. LÍQUIDO</h5></div></th>
                 <th scope='col' class='col-md-2'><div align='center'><h5><b/>VALOR LÍQUIDO</h5></div></th>
                 <th scope='col' class='col-md-1'><div align='center'><h5><b/>DIFERENÇA</h5></div></th>
                 <th scope='col' class='col-md-1'><div align='center'><h5><b/>%</h5></div></th>
                     </TR>
        </thead>";

$stmtAC2 = Sqlsrv_query($conn1, $query) or die(print_r(sqlsrv_errors()));

while ($item = sqlsrv_fetch_array($stmtAC2, SQLSRV_FETCH_ASSOC)) {

    echo "<TR >
<TD align='center' class='col-md-1'><font size=2>" . $item['AGRUPAMENTO'] . "</font></TD> 
<TD align='center' class='col-md-2'><font size=2>" . $item['DESC_AGRUPAMENTO'] . "</font></TD>
<TD align='center' class='col-md-2'><font size=2>" . $item['META_VALOR'] . "</font></TD>
<TD align='center' class='col-md-2'><font  size=2>" . $item['QTDE_LQUIDO'] . "</font></TD>
<TD align='center'   class='col-md-1'><font size=2>" . $item['VALOR_LIQUIDO'] . "</font></TD>
<TD align='center'   class='col-md-1'><font size=2>" . $item['DIFERENCA'] . "</font></TD>
<TD align='center' class='col-md-1'><font size=2>" .  floatval($item['%']) . "%</font></TD>


";

}