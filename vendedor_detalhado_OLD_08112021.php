<script type="text/javascript">
	window.history.go(1);
</script>

<link rel="shortcut icon" href="img/favicon2.ico" type="image/x-icon" />

<br />
<title>Analitico Vendedores</title>
<link rel="stylesheet" href="./../ticketDev/css/bootstrap.min.css" crossorigin="anonymous" />
<script src="./../ticketDev/js/jquery-2.2.4.min.js"></script>
<script src="./../ticketDev/js/popper.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="./../ticketDev/js/jquery-3.4.1.min.js"></script>
<script type=text/javascript" src="./../ticketDev/js/jquery-3.4.1.js"></script>
<link rel="stylesheet" href="./../ticketDev/js/bootstrap.min.css" >
<script src="./../ticketDev/js/jquery-2.2.4.min.js" ></script>
<script src="./../ticketDev/js/popper.min.js" ></script>
<scr ipt type="text/javascript" src="./../ticketDev/js/jquery.min.js"></script>

 <!-- Latest compiled and minified JavaScript -->
<script src="./../ticketDev/js/bootstrap2.min.js" ></script>

<link href="./../ticketDev/css/bootstrap.min.css" rel="stylesheet">
<?
// include "visual.html";
// include "listaLoja.php";
?>
<div class="col-sm-2" ALIGN="center"></div>
<div class="container-fluid col-md-8" ALIGN="center">
	<div class='panel panel-success border-primary'>
		<div class="panel-heading" ALIGN="center">
			<h4>CAMPOS DE PESQUISA</h4>
		</div>
		<nav class="navbar navbar-light bg-light" ALIGN="center">
			<form action="" name="form1" method="post">
            <div class="col-md-1" ALIGN="center"></div>
				<div name="DIV-COD_BARRA" class="col-md-3">
					<LABEL class="label-input100">
				
						<h5><strong>DATA:</strong> </h5>
					</label>
					<input id="DATA" required name="DATA"  class="form-control form-control-sm" onblur="funcao1();" type="date" placeholder="dd/mm/aaaa" aria-label="Search" />
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
				<div class="col-md-3">
					<label>
						<h5><strong>VENDEDOR:</strong></h5>
					</label>
					<input id="VENDEDOR" required name="VENDEDOR" class="form-control form-control-sm" onblur="funcao1();" type="number" placeholder="Código do Vendedor" aria-label="Search" />
				</div>
				</br>
				<div class="col-md-12" align="center">
					<INPUT class='btn btn-success' title=" " name="SendPesqUser" id="SendPesqUser" onblur="funcao1();" align="right" value="PESQUISAR" type='submit'></input>
                
                <button onclick="window.location.href='vendedor_detalhado.php';" class='btn btn-success' type="reset"
						title="Limpar" name="limpar" 
						href='http://110.100.2.9/analitico/vendedor_detalhado.php'>LIMPAR</button>
                        </div>
	</div>
</div>
</div>
</form>
<div class="modal fade  bd-example-modal-lg" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title " align="center"  id="exampleModalLongTitle">DETALHAMENTO DOS PRODUTOS DA VENDA</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span id="visualiza"> </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- adicionando codigo javascript para Onblur-->
<script>
	function funcao1() {

		var DATA = document.getElementById("DATA");
		var COD_FILIAL = document.getElementById("COD_FILIAL");
		var VENDEDOR = document.getElementById("VENDEDOR");
		var SendPesqUser = document.getElementById("SendPesqUser");

	}
</script>

<?php

@$DATA = date('Ymd', strtotime($_POST['DATA']));
@$COD_FILIAL = $_POST['COD_FILIAL'];
@$VENDEDOR = $_POST['VENDEDOR'];

$dataLimiteMin = date("Ymd",  strtotime('-2 day'));
$dataLimiteMax= date("Ymd",  strtotime('-1 day'));

$SendPesqUser = filter_input(INPUT_POST, 'SendPesqUser', FILTER_SANITIZE_STRING);
if ($SendPesqUser) {
	$COD_FILIAL = filter_input(INPUT_POST, 'COD_FILIAL', FILTER_SANITIZE_STRING);
if (@$DATA <= $dataLimiteMax && $DATA >= $dataLimiteMin) {
// se data selecionada for menor ou igual a data minima 

          //  sqlsrv_close( $conn1 ); // FECHA $CONN PARA COMEÇAR UMA NOVA

           switch ($COD_FILIAL) {
                case '000203':
                    $ipLOJA = '110.100.3.2';
                    break;
                case '000205':
                    $ipLOJA = '110.100.5.2';
                    break;
                case '000209':
                        $ipLOJA = '110.100.9.2';
                        break;
                case '000210':
                    $ipLOJA = '110.100.10.2';
                    break;
                case '000211':
                    $ipLOJA = '110.100.11.2';
                    break;
                case '000213':
                    $ipLOJA = '110.100.13.2';
                    break;
                case '000215':
                    $ipLOJA = '110.100.15.2';
                    break;
                case '000217':
                    $ipLOJA = '110.100.17.2';
                    break;
                case '000218':
                    $ipLOJA = '110.100.18.2';
                    break;
                case '000219':
                    $ipLOJA = '110.100.34.2';
                    break;
                case '000220':
                    $ipLOJA = '110.100.20.2';
                    break;
                case '000221':
                    $ipLOJA = '110.100.3.2';
                    break;
                case '000223':
                    $ipLOJA = '110.100.30.2';
                    break;
                case '000224':
                    $ipLOJA = '110.100.24.2';
                    break;
                case '000226':
                    $ipLOJA = '110.100.6.2';
                    break;
                case '000227':
                    $ipLOJA = '110.100.39.2';
                    break;
                case '000228':
                    $ipLOJA = '110.100.38.2';
                    break;
                case '000229':
                    $ipLOJA = '110.100.55.2';
                    break;
                case '000230':
                    $ipLOJA = '110.100.43.2';
                    break;
                case '000231':
                    $ipLOJA = '110.100.42.2';
                    break;
                case '000232':
                    $ipLOJA = '110.100.56.2';
                    break;
                case '000233':
                    $ipLOJA = '110.100.27.2';
                    break;
                case '000234':
                    $ipLOJA = '110.100.28.2';
                    break;
                case '000235':
                    $ipLOJA = '110.100.37.2';
                    break;
                case '000237':
                    $ipLOJA = '110.100.58.2';
                    break;
                case '000240':
                    $ipLOJA = '110.100.59.2';
                    break;
                case '000241':
                    $ipLOJA = '110.100.66.2';
                    break;
                case '000242':
                    $ipLOJA = '110.100.74.2';
                    break;
                case '000243':
                    $ipLOJA = '110.100.41.2';
                    break;
                case '000244':
                    $ipLOJA = '110.100.63.2';
                    break;
				case '000245':
                    $ipLOJA = '110.100.14.2';
                    break;
                    // ------ --- AQUI COMEÇA MARANHÃO --- ---- //
            
                case '000303':
                    $ipLOJA = '110.100.162.2';
                    break;
                case '000304':
                    $ipLOJA = '110.100.190.2';
                    break;
                case '000305':
                    $ipLOJA = '110.100.164.2';
                    break;
                case '000306':
                    $ipLOJA = '110.100.165.2';
                    break;
                case '000307':
                    $ipLOJA = '110.100.158.2';
                    break;
                case '000308':
                    $ipLOJA = '110.100.151.2';
                    break;
                case '000309':
                    $ipLOJA = '110.100.180.2';
                    break;
                case '000310':
                    $ipLOJA = '110.100.152.2';
                    break;
                case '000311':
                    $ipLOJA = '110.100.185.2';
                    break;
                case '000312':
                    $ipLOJA = '110.100.157.2';
                    break;
                case '000313':
                    $ipLOJA = '110.100.156.2';
                    break;
                case '000314':
                    $ipLOJA = '110.100.155.2';
                    break;
                case '000315':
                    $ipLOJA = '110.100.154.2';
                    break;
                case '000316':
                    $ipLOJA = '110.100.161.2';
                    break;
            
                    // ------ --- AQUI COMEÇA TK'S E MF'S --- ---- //
                case '000402':
                    $ipLOJA = '110.100.31.2';
                    break;
                case '000406':
                    $ipLOJA = '110.100.40.2';
                    break;
            
                    // ------ --- MF'S --- ---- //	
            
                case '000506':
                    $ipLOJA = '110.100.68.2';
                    break;
                    
                case '000507':
                    $ipLOJA = '110.100.44.2';
                    break;
                    
                case '000509':
                    $ipLOJA = '110.100.21.2';
                    break;
                            
                // case '000510':
                //     $ipLOJA = '110.100.62.2';
                //     break;
            
                 default:
                        echo "<script language='javascript' type='text/javascript'>
                        alert('Loja não encontrada.');
                        </script>";
          
            }
            if(@$ipLOJA !=''){
            $host1 = $ipLOJA;
            $user = 'sa';
            $senha = '';
            $base = 'PDV';
            $con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
            $conn = sqlsrv_connect($host1, $con);
            
            if ($conn === false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                //echo "conexão realizada com sucesso";
            }
        
                if(  // CONDIÇÃO PARA SEM OU COM DSK
                $COD_FILIAL == "000303"||
                $COD_FILIAL == "000303"||
                $COD_FILIAL == "000304"||
                $COD_FILIAL == "000305"||
                $COD_FILIAL == "000306"||
                $COD_FILIAL == "000307"||
                $COD_FILIAL == "000308"||
                $COD_FILIAL == "000309"||
                $COD_FILIAL == "000310"|| 
                $COD_FILIAL == "000312"|| 
                $COD_FILIAL == "000313"||
                $COD_FILIAL == "000314"||
                $COD_FILIAL == "000315"||
                $COD_FILIAL == "000316"||
                $COD_FILIAL == "000203"
                ){ 
                    //'".$DATAINICIO."' 

                  //--EXECUTAR SOMENTE NAS LOJAS 000303, 000304, 000305, 000306, 000307, 000308, 000309, 000310, 000312, 000313, 000314, 000315, 000316 E 000203.
                   
                    $queryParte1 = "declare @data date,  @filial varchar(06), @vendedor char(04)
                    --set @data = cast(getdate() as date)		--PADRÃO DATA DO DIA
                    set @data = '".$DATA."'		--ESCOLHA DO DIA
                    set @filial = '".$COD_FILIAL."'					--INFORMAR A LOJA A SER PESQUISADA
                    set @vendedor = '".$VENDEDOR."' ";
                   
                   $queryParte2 = "  --APRESENTA VALOR PREVISTO DE VENDA DO VENDEDOR
					if @data = cast((getdate()-1) as date) or @data = cast((getdate()-2) as date)
                    select
                        v.nome_vendedor as 'VENDEDOR',
                        convert(varchar,@data,103) as 'DATA',
                        format(isnull(dbo.fx_valor_venda_vendedor(@filial,@data,@vendedor),0.00), 'C', 'pt-br')  AS 'VALOR_PREVISTO'
                    from
                        loja_vendedores v
                    where 
                        v.vendedor = @vendedor and v.data_desativacao is null ";

                      $queryParte3 ="  --INFORMA VENDA A VENDA TODOS OS REGISTROS ORIGINADOS COMO PRE-VENDA
                        if @data = cast((getdate()-1) as date) or @data = cast((getdate()-2) as date)
						select
                            lp.PEDIDO as 'REGISTRO',
                            convert(varchar,lp.DATA_DIGITACAO,114) as 'HORARIO',
                            ISNULL(lp.identificacao_cliente,'NAO IDENTIFICADO') AS 'CLIENTE',
                            case when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                                'PEDIDO FECHADO'
                                when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                                'PRE-VENDA LINX'
                                when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 10 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                                'PRE-VENDA DSK'
                                when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 1 then
                                'VENDA LINX'
                                when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA = 0 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 0 and lp.ENTREGUE = 0 then
                                'PRE-VENDA ABERTO SALAO'
                                when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA = 10 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 0 and lp.ENTREGUE = 0 then
                                'PRE-VENDA ABERTO CAIXA'
                                when lp.tipo_pedido = 3 and lp.cancelado = 1 then
                                'PRE-VENDA CANCELADO'
                            ELSE
                                'VERIFICAR'
                            END AS 'STATUS',
                            format(lp.VALOR_TOTAL, 'C', 'pt-br')  AS 'VALOR_PEDIDO',
                            case when lpv.TICKET is not null then
                                (select format(lv.VALOR_PAGO, 'C', 'pt-br') from loja_venda lv where lv.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and lv.TICKET = lpv.TICKET)
                                when lpv.TICKET is null and (lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0) then
                                format(lp.VALOR_TOTAL, 'C', 'pt-br')
                            else
                                format(0.00, 'C', 'pt-br')
                            end as 'VALOR_REGISTRADO',
                            lp.usuario as 'TERMINAL',
                            case when lpv.TICKET is not null then
                                (select case when lv.VALOR_TROCA > 0 then 'SIM' else 'NAO' end from loja_venda lv where lv.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and lv.TICKET = lpv.TICKET)
                            else
                                'NAO'
                            end as 'TEM_TROCA'
                        from
                            loja_pedido lp
                            left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
                            left join LOJA_PEDIDO_VENDA lpv on lpv.CODIGO_FILIAL_ORIGEM = lp.CODIGO_FILIAL_ORIGEM and lpv.PEDIDO = lp.pedido
                        where
                            lp.DATA = @data and lp.VENDEDOR = @vendedor and lp.CODIGO_FILIAL_ORIGEM = @filial
                        
                        union all
                        --INFORMA VENDA A VENDA TODOS OS REGISTROS ORIGINADOS COMO VENDA DIRETAMENTE NO LINX, SEM REGISTRO DE PEDIDO.
                        select
                            lv.TICKET as 'REGISTRO',
                            convert(varchar,lv.DATA_DIGITACAO,114) as 'HORARIO',
                            isnull(cv.CLIENTE_VAREJO,'NAO IDENTIFICADO') as 'CLIENTE',
                            case when lv.DATA_HORA_CANCELAMENTO is null then
                                'VENDA LINX DIRETO'
                            else
                                'LINX CANCELADO'
                            end as 'STATUS',
                            case when lv.DATA_HORA_CANCELAMENTO is null then
                                FORMAT(LV.VALOR_CANCELADO, 'C', 'pt-br')
                            else
                                FORMAT(LV.VALOR_VENDA_BRUTA, 'C', 'pt-br')
                            end as 'VALOR_PEDIDO',
                            FORMAT(lv.VALOR_PAGO, 'C', 'pt-br') as 'VALOR_REGISTRADO',
                            LV.TERMINAL,
                            case when lv.VALOR_TROCA > 0 then
                                'SIM'
                            else
                                'NAO'
                            end as 'TEM_TROCA'
                        from
                            LOJA_VENDA lv
                            left join CLIENTES_VAREJO cv on cv.CODIGO_CLIENTE = lv.CODIGO_CLIENTE
                        where
                            lv.CODIGO_FILIAL = @filial and lv.VENDEDOR = @vendedor and lv.DATA_VENDA = @data
                            and lv.TICKET not in (select lpv.TICKET from LOJA_PEDIDO_VENDA lpv where lpv.CODIGO_FILIAL_ORIGEM = @filial and lpv.DATA_VENDA = @data)
                                                    
                        order by
                            HORARIO
                        
                    ";
                       $recebePart1 = $queryParte1.$queryParte2;
                       $recebePart2 = $queryParte1.$queryParte3;
                     @$ResultadoSQL = Sqlsrv_query($conn, $recebePart1);
                     @$ResultadoSQL2 = Sqlsrv_query($conn, $recebePart2); // atribuindo a query certa para o resultado entrar no fetch_array
                     

                   
                 }else 
                 {

                  //  --EXECUTAR EM TODAS AS LOJAS, COM EXCEÇÃO DAS LOJAS 000303, 000304, 000305, 000306, 000307, 000308, 000309, 000310, 000312, 000313, 000314, 000315, 000316 E 000203.

                     $queryParte1 = "declare @data date,  @filial varchar(06), @vendedor char(04)
                     set @data = '".$DATA."'		--ESCOLHA DO DIA
                     set @filial = '".$COD_FILIAL."'					--INFORMAR A LOJA A SER PESQUISADA
                     set @vendedor = '".$VENDEDOR."' ";
                    
                    $queryParte2 = " --APRESENTA VALOR PREVISTO DE VENDA DO VENDEDOR
					if @data = cast((getdate()-1) as date) or @data = cast((getdate()-2) as date)
                    select
                        v.nome_vendedor as 'VENDEDOR',
                        convert(varchar,@data,103) as 'DATA',
                        format(isnull(dbo.fx_valor_venda_vendedor(@filial,@data,@vendedor),0.00), 'C', 'pt-br')  AS 'VALOR_PREVISTO'
                    from
                        loja_vendedores v
                    where 
                        v.vendedor = @vendedor and v.data_desativacao is null ";
 
                       $queryParte3 =" 
                       --INFORMA VENDA A VENDA TODOS OS REGISTROS ORIGINADOS COMO PRE-VENDA
					   if @data = cast((getdate()-1) as date) or @data = cast((getdate()-2) as date)
                       select
                           lp.PEDIDO as 'REGISTRO',
                           (select convert(varchar,max(lpo.DATA_INCLUSAO),114) from loja_pedido_produto lpo where lpo.codigo_filial_origem = lp.codigo_filial_origem and lpo.PEDIDO = lp.PEDIDO)  as 'HORARIO',
                           ISNULL(lp.identificacao_cliente,'NAO IDENTIFICADO') AS 'CLIENTE',
                           case when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                               'PEDIDO FECHADO'
                               when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                               'PRE-VENDA LINX'
                               when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 10 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0 then
                               'PRE-VENDA DSK'
                               when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 1 then
                               'VENDA LINX'
                               when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA = 0 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 0 and lp.ENTREGUE = 0 then
                               'PRE-VENDA ABERTO SALAO'
                               when lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA = 10 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 0 and lp.ENTREGUE = 0 then
                               'PRE-VENDA ABERTO CAIXA'
                               when lp.tipo_pedido = 3 and lp.cancelado = 1 then
                               'PRE-VENDA CANCELADO'
                               when lp.tipo_pedido = 1 and lp.LX_TIPO_PRE_VENDA = 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 0 and lp.ENTREGUE = 0 then
                               'PRE-VENDA SUSPENSO'
                           ELSE
                               'VERIFICAR'
                           END AS 'STATUS',
                           format(lp.VALOR_TOTAL, 'C', 'pt-br')  AS 'VALOR_PEDIDO',
                           case when lpv.TICKET is not null then
                               (select format(lv.VALOR_PAGO, 'C', 'pt-br') from loja_venda lv where lv.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and lv.TICKET = lpv.TICKET)
                               when lpv.TICKET is null and (lp.tipo_pedido = 3 and lp.LX_TIPO_PRE_VENDA <> 2 and lp.cancelado = 0 and lp.DIGITACAO_ENCERRADA = 1 and lp.ENTREGUE = 0) then
                               format(lp.VALOR_TOTAL, 'C', 'pt-br')
                           else
                               format(0.00, 'C', 'pt-br')
                           end as 'VALOR_REGISTRADO',
                           case when lpv.TICKET is not null then
                               (select lv.TERMINAL from loja_venda lv where lv.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and lv.TICKET = lpv.TICKET)
                           else
                               'SALAO'
                           end as 'TERMINAL',
                           case when lpv.TICKET is not null then
                               (select case when lv.VALOR_TROCA > 0 then 'SIM' else 'NAO' end from loja_venda lv where lv.CODIGO_FILIAL = lp.CODIGO_FILIAL_ORIGEM and lv.TICKET = lpv.TICKET)
                           else
                               'NAO'
                           end as 'TEM_TROCA'
                       from
                           loja_pedido lp
                           left join loja_pedido_pgto lpp on lpp.codigo_filial_origem = lp.codigo_filial_origem and lpp.pedido = lp.pedido 
                           left join LOJA_PEDIDO_VENDA lpv on lpv.CODIGO_FILIAL_ORIGEM = lp.CODIGO_FILIAL_ORIGEM and lpv.PEDIDO = lp.pedido
                       where
                           lp.DATA = @data and lp.VENDEDOR = @vendedor and lp.CODIGO_FILIAL_ORIGEM = @filial
                       
                       union all
                       --INFORMA VENDA A VENDA TODOS OS REGISTROS ORIGINADOS COMO VENDA DIRETAMENTE NO LINX, SEM REGISTRO DE PEDIDO.
                       select
                           lv.TICKET as 'REGISTRO',
                           convert(varchar,lv.DATA_DIGITACAO,114) as 'HORARIO',
                           isnull(cv.CLIENTE_VAREJO,'NAO IDENTIFICADO') as 'CLIENTE',
                           case when lv.DATA_HORA_CANCELAMENTO is null then
                               'VENDA LINX DIRETO'
                           else
                               'LINX CANCELADO'
                           end as 'STATUS',
                           case when lv.DATA_HORA_CANCELAMENTO is null then
                               FORMAT(LV.VALOR_CANCELADO, 'C', 'pt-br')
                           else
                               FORMAT(LV.VALOR_VENDA_BRUTA, 'C', 'pt-br')
                           end as 'VALOR_PEDIDO',
                           FORMAT(lv.VALOR_PAGO, 'C', 'pt-br') as 'VALOR_REGISTRADO',
                           LV.TERMINAL,
                           case when lv.VALOR_TROCA > 0 then
                               'SIM'
                           else
                               'NAO'
                           end as 'TEM_TROCA'
                       from
                           LOJA_VENDA lv
                           left join CLIENTES_VAREJO cv on cv.CODIGO_CLIENTE = lv.CODIGO_CLIENTE
                       where
                           lv.CODIGO_FILIAL = @filial and lv.VENDEDOR = @vendedor and lv.DATA_VENDA = @data
                           and lv.TICKET not in (select lpv.TICKET from LOJA_PEDIDO_VENDA lpv where lpv.CODIGO_FILIAL_ORIGEM = @filial and lpv.DATA_VENDA = @data)
                                                   
                       order by
                           HORARIO
                           
                         
                     ";
                        $recebePart1 = $queryParte1.$queryParte2;
                        $recebePart2 = $queryParte1.$queryParte3;
                      @$ResultadoSQL = Sqlsrv_query($conn, $recebePart1);
                      @$ResultadoSQL2 = Sqlsrv_query($conn, $recebePart2); // atribuindo a query certa para o resultado entrar no fetch_array
                 } ?><div class="panel-body"></div>
                 <div class="panel-footer">
                     <div class="fluid" float="">
                         <div class='panel panel-primary'>
                             <div class="panel-heading" ALIGN="center">
                                 <h4>VALOR PREVISTO DE VENDA </h4>
                             </div>
                             <nav class="navbar navbar-light bg-light" ALIGN="center"><?php

				echo "
						
							<TABLE ALIGN=''  style='overflow: auto;' class='table table-bordered responsive'>
							<thead style='background-color:	 #EBEBEB;'  >
							<TR>
							<th align='center'><div align='center' ><h6><b>VENDEDOR</b></h6></div></th>
							<th align='center'><div align='center' ><h6><b>DATA</b></h6></div></th>
							<th align='center'><div align='center' ><h6><b>VALOR PREVISTO</b></h6></div></th>
							</TR></thead>";

			

				while (@$item = sqlsrv_fetch_array($ResultadoSQL, SQLSRV_FETCH_ASSOC)) {

					echo "
									<TR>
									<TD scope='col' align='center' class='col-md-1'><font size=2>" . $item['VENDEDOR'] . "</font></TD> 
									<TD align='center' class='col-md-1'><font size=2>" . $item['DATA'] . "</font></TD> 
                                    <TD align='center' class='col-md-1'><font size=2>" . $item['VALOR_PREVISTO'] . "</font></TD>							
                                    </TR>";
				}

				echo "</table>"; ?>
		</div>

		<!-- FIM DO PRIMEIRO BLOCO -->
		<div style='overflow: auto;' class="panel-body" align="center">
		<!-- <div class="col-md-2" align="center" float=""></div> -->
			<div  style='overflow: auto;'class="col-md-12 fluid " align="center" float="">
				<div  style='overflow: auto;'class='panel panel-primary'>
					<div  style='overflow: auto;'class="panel-heading" ALIGN="center">
						<h4>REGISTROS DE VENDA E PRÉ-VENDA</h4>
					</div>
					<nav class="navbar  style='overflow: auto;' navbar-light bg-light" ALIGN="center">
						<?
						echo "
						
							<TABLE  style='overflow: auto;' ALIGN='' class='table table-bordered responsive'  >
							<thead style='background-color: #EBEBEB ;' >
							<TR >
							<th align='center'><div align='center'> <h6><b>REGISTRO</b></h6></div></th>
							<th align='center'><div align='center'>  <h6><b>HORÁRIO</h6></b> </th>
							<th align='center'><div align='center'>  <h6><b>CLIENTE</h6></b> </th>
                            <th align='center'><div align='center'>  <h6><b>STATUS</h6></b> </th>
                            <th align='center'><div align='center'>  <h6><b>VALOR PEDIDO</h6></b> </th>
                            <th align='center'><div align='center'>  <h6><b>VALOR REGISTRADO</h6></b> </th>
                            <th align='center'><div align='center'>  <h6><b>TERMINAL</h6></b> </th>
                            <th align='center'><div align='center'>  <h6><b>TEM TROCA</h6></b> </th>
                            
                        </TR></thead>";
                        
        
						while (@$itemc = sqlsrv_fetch_array($ResultadoSQL2, SQLSRV_FETCH_ASSOC)) {

							echo "
									<TR>
									<TD scope='col' align='center' class='col-md-2'><font size=2>" . $itemc['REGISTRO'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['HORARIO'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['CLIENTE'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['STATUS'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['VALOR_PEDIDO'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['VALOR_REGISTRADO'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['TERMINAL'] . "</font></TD> 
                                    <TD align='center' class='col-md-2'><font size=2>" . $itemc['TEM_TROCA'] . "</font></TD>"; ?>
                                    <TD align="center" class='col-md-2'>
                                        <button type="button" class="btn btn-info view_data" 
                                        id="<?php  echo $COD_FILIAL; ?>"
                                        id3="<?php echo $DATA; ?>"
                                        id2="<?php echo $itemc['REGISTRO']; ?>"                                                               
                                        id4="<?php echo  $itemc['STATUS']; ?>"                                       
                                        id5="<?php echo  $ipLOJA; ?>"

                                        >Detalhar</button>
                                    </TD>

                                <?php
									echo "</TR>";
						}

                        echo "</table>"; 
                        
                        
                        ?>
				</div>
			</div>
			</nav>
		</div>
	</div>

    <script>
	
	$(document).ready(function(){
		$(document).on('click','.view_data',function(){
			var filial = $(this).attr("id");
            var registro = $(this).attr("id2");
            var data = $(this).attr("id3");
            var status = $(this).attr("id4");
            var iploja = $(this).attr("id5");

			if(filial !== ''){
				var dados = {
					filial: filial,
                    registro: registro,
                    data : data,
                    status : status,
                    iploja : iploja
				};

				$.post('visualizar.php',dados,function(retorna){
					$("#visualiza").html(retorna);
						$('#visualizar').modal('show');
				});
			}
		});
	});


</script>

	<div class="footer bg-transparent border-success" align='center'>
		<img align="center" src="img/logo.png"> <br>
		@TI.
	</div>

<?
}
} else {
	echo "<script language='javascript' type='text/javascript'>
    alert('Data fora do período permitido! ');
    </script>";
}
} 

?>




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
</html>