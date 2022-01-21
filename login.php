<?php
include 'conec.php';


if (isset($entrar)) {

	$verifica = $conn->query("
    	SELECT * 
    	FROM usuarios 
    	WHERE login = '$login' AND senha = '$senha' and grupo in (1,6,4,7,5)")

		or die("erro ao selecionar");

	if (mysqli_num_rows($verifica) <= 0) {
		echo "<script language='javascript' type='text/javascript'>
	        alert('Login e/ou senha incorretos ou você não tem acesso! ');window.location
	        .href='login.html';</script>";
		die();
	} else {
		setcookie("login", $login, time() + 1200);
		header("Location:index.php");
	}
}
