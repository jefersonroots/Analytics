<?php

if(isset($_POST["registro"]) && isset($_POST["data"]) && isset($_POST["filial"])  ){

	$host1 = $_POST['iploja'];
	$user = 'sa';
	$senha = '';
	$base = 'PDV';
	$con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
	$conn1 = sqlsrv_connect($host1, $con);

	
	$query1= "--

    declare @data date,  @filial varchar(06), @registro char(08), @status varchar(50)
    --set @data = cast(getdate() as date)		--PADRÃO DATA DO DIA
    set @data = '".$_POST["data"]."'		--IDENTIFICAR O DIA
    set @filial = '".$_POST["filial"]."'		--IDENTIFICAR A LOJA A SER PESQUISADA
    set @registro = '".$_POST["registro"]."'	--IDENTIFICAR O NUMERO DO REGISTRO, DE ACORDO COM A TELA ANTERIOR
    set @status = '".$_POST["status"]."'	--IDENTIFICAR O STATUS DA VENDA, DE ACORDO COM A TELA ANTERIOR
    if left(@registro,1) = '-' and @status = 'VENDA LINX'
	select
		lpv.PEDIDO as 'REGISTRO',
		lvp.CODIGO_BARRA as 'CODIGO_BARRA',
		p.DESC_PROD_NF as 'DESCRICAO',
		pc.DESC_COR_PRODUTO as 'COR',
		(select distinct max(pb.GRADE) from PRODUTOS_BARRA pb where pb.CODIGO_BARRA = lvp.CODIGO_BARRA) as 'GRADE',
		lvp.QTDE,
		lvp.PRECO_LIQUIDO,
		'NAO' as 'TROCA',
		case when lvp.ITEM_EXCLUIDO > 0 or lvp.QTDE_CANCELADA > 0 then
			'SIM'
		else
			'NAO'
		end as 'CANCEL'
	from
		loja_pedido_venda lpv
		left join loja_venda_produto lvp on lvp.CODIGO_FILIAL = lpv.CODIGO_FILIAL_ORIGEM and lvp.TICKET = lpv.TICKET
		left join PRODUTOS p on p.PRODUTO = lvp.PRODUTO
		left join PRODUTO_CORES pc on pc.PRODUTO = lvp.PRODUTO and pc.COR_PRODUTO = lvp.COR_PRODUTO
	where 
		lpv.CODIGO_FILIAL_ORIGEM = @filial and lpv.PEDIDO = @registro
	UNION ALL
	select
		lpv.PEDIDO as 'REGISTRO',
		lvt.CODIGO_BARRA as 'CODIGO_BARRA',
		p.DESC_PROD_NF as 'DESCRICAO',
		pc.DESC_COR_PRODUTO as 'COR',
		(select distinct max(pb.GRADE) from PRODUTOS_BARRA pb where pb.CODIGO_BARRA = lvt.CODIGO_BARRA) as 'GRADE',
		(lvt.QTDE * -1) as 'QTDE',
		(lvt.PRECO_LIQUIDO * -1) as 'PRECO_LIQUIDO',
		'SIM' as 'TROCA',
		case when lvt.ITEM_EXCLUIDO > 0 or lvt.QTDE_CANCELADA > 0 then
			'SIM'
		else
			'NAO'
		end as 'CANCEL'
	from
		loja_pedido_venda lpv
		left join LOJA_VENDA_TROCA lvt on lvt.CODIGO_FILIAL = lpv.CODIGO_FILIAL_ORIGEM and lvt.TICKET = lpv.TICKET
		left join PRODUTOS p on p.PRODUTO = lvt.PRODUTO
		left join PRODUTO_CORES pc on pc.PRODUTO = lvt.PRODUTO and pc.COR_PRODUTO = lvt.COR_PRODUTO
	where 
		lpv.CODIGO_FILIAL_ORIGEM = @filial and lpv.PEDIDO = @registro
		AND LVT.QTDE IS NOT NULL 
else 
	if @status = 'VENDA LINX DIRETO' or @status = 'LINX CANCELADO'
		select
			lvp.TICKET as 'REGISTRO',
			lvp.CODIGO_BARRA as 'CODIGO_BARRA',
			p.DESC_PROD_NF as 'DESCRICAO',
			pc.DESC_COR_PRODUTO as 'COR',
			(select distinct max(pb.GRADE) from PRODUTOS_BARRA pb where pb.CODIGO_BARRA = lvp.CODIGO_BARRA) as 'GRADE',
			lvp.QTDE,
			case when lvp.FATOR_DESCONTO_VENDA > 0 then
				CAST(round(lvp.PRECO_LIQUIDO * (1-lvp.FATOR_DESCONTO_VENDA),2) AS NUMERIC(11,2))
			else
				lvp.PRECO_LIQUIDO
			end AS 'PRECO_LIQUIDO',
			'NAO' as 'TROCA',
			case when lvp.ITEM_EXCLUIDO > 0 or lvp.QTDE_CANCELADA > 0 then
				'SIM'
			else
				'NAO'
			end as 'CANCEL'
		from
			LOJA_VENDA_PRODUTO lvp
			left join PRODUTOS p on p.PRODUTO = lvp.PRODUTO
			left join PRODUTO_CORES pc on pc.PRODUTO = lvp.PRODUTO and pc.COR_PRODUTO = lvp.COR_PRODUTO
		where
			lvp.CODIGO_FILIAL = @filial and lvp.TICKET = concat(REPLICATE('0',8-len(@registro)),@registro)
		UNION ALL
		select
			lvt.ticket as 'REGISTRO',
			lvt.CODIGO_BARRA as 'CODIGO_BARRA',
			p.DESC_PROD_NF as 'DESCRICAO',
			pc.DESC_COR_PRODUTO as 'COR',
			(select distinct max(pb.GRADE) from PRODUTOS_BARRA pb where pb.CODIGO_BARRA = lvt.CODIGO_BARRA) as 'GRADE',
			(lvt.QTDE * -1) as 'QTDE',
			(lvt.PRECO_LIQUIDO * -1) as 'PRECO_LIQUIDO',
			'SIM' as 'TROCA',
			case when lvt.ITEM_EXCLUIDO > 0 or lvt.QTDE_CANCELADA > 0 then
				'SIM'
			else
				'NAO'
			end as 'CANCEL'
	from
		LOJA_VENDA_TROCA lvt
		left join PRODUTOS p on p.PRODUTO = lvt.PRODUTO
		left join PRODUTO_CORES pc on pc.PRODUTO = lvt.PRODUTO and pc.COR_PRODUTO = lvt.COR_PRODUTO
	where 
		lvt.CODIGO_FILIAL = @filial and lvt.TICKET = concat(REPLICATE('0',8-len(@registro)),@registro)
		AND LVT.QTDE IS NOT NULL
else
	select
		lpp.PEDIDO as 'REGISTRO',
		lpp.CODIGO_BARRA as 'CODIGO_BARRA',
		p.DESC_PROD_NF as 'DESCRICAO',
		pc.DESC_COR_PRODUTO as 'COR',
		(select distinct max(pb.GRADE) from PRODUTOS_BARRA pb where pb.CODIGO_BARRA = lpp.CODIGO_BARRA) as 'GRADE',
		lpp.QTDE,
		lpp.PRECO_LIQUIDO,
		'NAO' as 'TROCA',
		case when lpp.QTDE = 0 or lpp.CANCELADO > 0 then
			'SIM'
		else
			'NAO'
		end as 'CANCEL'
	from
		LOJA_PEDIDO_PRODUTO lpp
		left join PRODUTOS p on p.PRODUTO = lpp.PRODUTO
		left join PRODUTO_CORES pc on pc.PRODUTO = lpp.PRODUTO and pc.COR_PRODUTO = lpp.COR_PRODUTO
	where
		lpp.CODIGO_FILIAL_ORIGEM = @filial and lpp.PEDIDO = @registro
	

    ";

	$exec = sqlsrv_query($conn1, $query1);
	if( $exec == false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	echo"<div class='panel panel-danger'>
	<div class='panel-footer'>
	<TABLE ALIGN='center' class='table table-bordered responsive' style='display:block; overflow-x: auto;'>
	<thead>
			<TR style='background-color:   #EBEBEB ;'>
            <th align='center'><div align='center' ><h5><b>REGISTRO</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>CÓDIGO DE BARRA</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>DESCRIÇÃO</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>COR</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>GRADE</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>QTDE</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>PREÇO LIQUIDO</b></h5></div></th>
            <th align='center'><div align='center'><h5><b>TROCA</b></h5></div></th>    
            <th align='center'><div align='center'><h5><b>CANCEL</b></h5></div></th>
            </thead>			
                
			</TR>";	
	while( $item = sqlsrv_fetch_array($exec, SQLSRV_FETCH_ASSOC)){
	  echo "
	  <TR >
	  <TD  align='center'class='col-md-1'><font size=2>".$item['REGISTRO']."</font></TD> 
	  <TD  align='center'class='col-md-2'><font size=2>".$item['CODIGO_BARRA']."</font></TD>
	  <TD  align='center'class='col-md-1'><font size=2>".$item['DESCRICAO']."</font></TD>
	  <TD  align='center'class='col-md-1'><font size=2>".$item['COR']."</font></TD>
      <TD  align='center'class='col-md-1'><font size=2>".$item['GRADE']."</font></TD>
      <TD  align='center'class='col-md-2'><font size=2>".$item['QTDE']."</font></TD>
      <TD  align='center'class='col-md-2'><font size=2>".$item['PRECO_LIQUIDO']."</font></TD>
      <TD  align='center'class='col-md-2'><font size=2>".$item['TROCA']."</font></TD>
      <TD  align='center'class='col-md-2'><font size=2>".$item['CANCEL']."</font></TD>
      </TR>"; 
    }
    echo"</table>";
} 