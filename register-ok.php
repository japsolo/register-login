<?php
	session_start();
	require_once('./funciones.php');
	if(!estaLogueado()){
		header('Location: index.php'); exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<head>
			<meta charset="utf-8">
			<title>Repaso de PHP</title>
			<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
			<link rel="stylesheet" href="css/styles.css">
		</head>
	</head>
	<body>
		<h2>Te has registrado con EXITO CAPX!</h2>
		<a href="perfil-usuario.php" class="button">Ir a tu perfil</a>
	</body>
</html>
