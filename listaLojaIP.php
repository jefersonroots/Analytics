	
<? //CONEXÃO PARA PUXAR LOJAS DO BANCO DE ACORDO COM O IP//

$ipPC = substr($_SERVER['REMOTE_ADDR'], 0, -1);


if (substr(substr($ipPC, 8, 2), -1) == '.') {
	$recebeIP = substr($ipPC, 0, 9);
	
} elseif (substr(substr($ipPC, 8, 4), -1) == '.') {
	$recebeIP = substr($ipPC, 0, 11);
	
} else {
	$recebeIP = substr($ipPC, 0, 10);
	
}


//CONECTAR NO BANCO
$host1 = '110.100.50.89,1440';
$user = 'linx';
$senha = 'Poly@123';
$base = 'LINX_Prod';
$con = array("Database" => "$base", "UID" => "$user", "PWD" => "$senha");
$conn1 = sqlsrv_connect($host1, $con);

@$querySelect = sqlsrv_query($conn1, "

DECLARE @ip varchar(14)
set @ip = '" . $recebeIP . "'

select 
	*
from
	(select 
		case @ip
			when '110.100.3' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000203)
			when '110.100.5' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000205)
			when '110.100.9' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000209)
			when '110.100.10' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000210)
			when '110.100.11' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000211)
			when '110.100.13' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000213)
			when '110.100.14' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000245)
			when '110.100.15' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000215)
			when '110.100.17' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000217)
			when '110.100.18' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000218)
			when '110.100.34' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000219)
			when '110.100.20' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000220)
			when '110.100.3' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000221)
			when '110.100.63' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000244)
			when '110.100.30' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000223)
			when '110.100.24' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000224)
			when '110.100.6' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000226)
			when '110.100.39' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000227)
			when '110.100.38' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000228)
			when '110.100.55' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000229)
			when '110.100.43' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000230)
			when '110.100.42' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000231)
			when '110.100.56' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000232)
			when '110.100.27' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000233)
			when '110.100.28' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000234)
			when '110.100.37' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000235)
			when '110.100.58' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000237)
			when '110.100.59' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000240)
			when '110.100.66' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000241)
			when '110.100.74' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000242)
			when '110.100.41' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000243)
				-- Lojas MA -- 
			when '110.100.162' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000303)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000304)
			when '110.100.164' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000305)
			when '110.100.165' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000306)
			when '110.100.158' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000307)
			when '110.100.151' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000308)
			when '110.100.180' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000309)
			when '110.100.152' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000310)
			when '110.100.185' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000311)
			when '110.100.157' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000312)
			when '110.100.156' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000313)
			when '110.100.155' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000314)
			when '110.100.154' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000315)
			when '110.100.161' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000316)
				-- lojas TK's & MF's
			when '110.100.31' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000402)
			when '110.100.60' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000403)
			when '110.100.65' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000405)
			when '110.100.40' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000406)
				-- lojas MF's
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000502)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000506)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000507)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000508)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000509)
			when '110.100.190' then
				(select f.COD_FILIAL from filiais f where f.COD_FILIAL = 000510)
		end as 'cod_filial',
		case @ip
			when '110.100.3' then
				(select f.filial from filiais f where f.COD_FILIAL = 000203)
			when '110.100.5' then
				(select f.filial from filiais f where f.COD_FILIAL = 000205)
			when '110.100.9' then
				(select f.filial from filiais f where f.COD_FILIAL = 000209)
			when '110.100.10' then
				(select f.filial from filiais f where f.COD_FILIAL = 000210)
			when '110.100.11' then
				(select f.filial from filiais f where f.COD_FILIAL = 000211)
			when '110.100.13' then
				(select f.filial from filiais f where f.COD_FILIAL = 000213)
			when '110.100.14' then
				(select f.filial from filiais f where f.COD_FILIAL = 000245)
			when '110.100.15' then
				(select f.filial from filiais f where f.COD_FILIAL = 000215)
			when '110.100.17' then
				(select f.filial from filiais f where f.COD_FILIAL = 000217)
			when '110.100.18' then
				(select f.filial from filiais f where f.COD_FILIAL = 000218)
			when '110.100.34' then
				(select f.filial from filiais f where f.COD_FILIAL = 000219)
			when '110.100.20' then
				(select f.filial from filiais f where f.COD_FILIAL = 000220)
			when '110.100.3' then
				(select f.filial from filiais f where f.COD_FILIAL = 000221)
			when '110.100.63' then
				(select f.filial from filiais f where f.COD_FILIAL = 000244)
			when '110.100.30' then
				(select f.filial from filiais f where f.COD_FILIAL = 000223)
			when '110.100.24' then
				(select f.filial from filiais f where f.COD_FILIAL = 000224)
			when '110.100.6' then
				(select f.filial from filiais f where f.COD_FILIAL = 000226)
			when '110.100.39' then
				(select f.filial from filiais f where f.COD_FILIAL = 000227)
			when '110.100.38' then
				(select f.filial from filiais f where f.COD_FILIAL = 000228)
			when '110.100.55' then
				(select f.filial from filiais f where f.COD_FILIAL = 000229)
			when '110.100.43' then
				(select f.filial from filiais f where f.COD_FILIAL = 000230)
			when '110.100.42' then
				(select f.filial from filiais f where f.COD_FILIAL = 000231)
			when '110.100.56' then
				(select f.filial from filiais f where f.COD_FILIAL = 000232)
			when '110.100.27' then
				(select f.filial from filiais f where f.COD_FILIAL = 000233)
			when '110.100.28' then
				(select f.filial from filiais f where f.COD_FILIAL = 000234)
			when '110.100.37' then
				(select f.filial from filiais f where f.COD_FILIAL = 000235)
			when '110.100.58' then
				(select f.filial from filiais f where f.COD_FILIAL = 000237)
			when '110.100.59' then
				(select f.filial from filiais f where f.COD_FILIAL = 000240)
			when '110.100.66' then
				(select f.filial from filiais f where f.COD_FILIAL = 000241)
			when '110.100.74' then
				(select f.filial from filiais f where f.COD_FILIAL = 000242)
			when '110.100.41' then
				(select f.filial from filiais f where f.COD_FILIAL = 000243)
				-- Lojas MA -- 
			when '110.100.162' then
				(select f.filial from filiais f where f.COD_FILIAL = 000303)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000304)
			when '110.100.164' then
				(select f.filial from filiais f where f.COD_FILIAL = 000305)
			when '110.100.165' then
				(select f.filial from filiais f where f.COD_FILIAL = 000306)
			when '110.100.158' then
				(select f.filial from filiais f where f.COD_FILIAL = 000307)
			when '110.100.151' then
				(select f.filial from filiais f where f.COD_FILIAL = 000308)
			when '110.100.180' then
				(select f.filial from filiais f where f.COD_FILIAL = 000309)
			when '110.100.152' then
				(select f.filial from filiais f where f.COD_FILIAL = 000310)
			when '110.100.185' then
				(select f.filial from filiais f where f.COD_FILIAL = 000311)
			when '110.100.157' then
				(select f.filial from filiais f where f.COD_FILIAL = 000312)
			when '110.100.156' then
				(select f.filial from filiais f where f.COD_FILIAL = 000313)
			when '110.100.155' then
				(select f.filial from filiais f where f.COD_FILIAL = 000314)
			when '110.100.154' then
				(select f.filial from filiais f where f.COD_FILIAL = 000315)
			when '110.100.161' then
				(select f.filial from filiais f where f.COD_FILIAL = 000316)
				-- lojas TK's & MF's
			when '110.100.31' then
				(select f.filial from filiais f where f.COD_FILIAL = 000402)
			when '110.100.60' then
				(select f.filial from filiais f where f.COD_FILIAL = 000403)
			when '110.100.65' then
				(select f.filial from filiais f where f.COD_FILIAL = 000405)
			when '110.100.40' then
				(select f.filial from filiais f where f.COD_FILIAL = 000406)
				-- lojas MF's
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000502)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000506)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000507)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000508)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000509)
			when '110.100.190' then
				(select f.filial from filiais f where f.COD_FILIAL = 000510)
		end as 'filial'
	UNION ALL
	select 
		f.COD_FILIAL,f.filial 
	from 
		filiais f 
	where 
		f.REDE_LOJAS in ('03','02','04','05') and f.COD_FILIAL != '000301' and f.COD_FILIAL != '000503' and f.TIPO_FILIAL != 'defeitos'
		and @ip = '110.100.2'
	UNION ALL
	select 
		f.COD_FILIAL,f.filial 
	from 
		filiais f 
	where 
		substring(f.COD_FILIAL,4,1) = '3' and f.COD_FILIAL != '000301' and f.COD_FILIAL != '000503' and f.TIPO_FILIAL != 'defeitos'
		and @ip = '110.100.160') t
where t.cod_filial is not null ");

date('Ymd');




?>
