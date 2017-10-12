<?php
	session_start();

	require_once('funciones.php');

	if(!estaLogueado()){
		header('Location: index.php'); exit;
	}

	$elUsuario = traerId($_SESSION['userId']);

	$laImagen = glob('images/avatares/' . $elUsuario['email'] . '*');

	$tituloDePagina = 'Perfil del Usuario';
	require_once('includes/head.php');
?>
		<h2>Hola <?=$elUsuario['name'];?> <?=$elUsuario['lastname'];?></h2>
		<em><?=$elUsuario['email'];?></em>
		<br>
		<br>
		<img src="<?=$laImagen[0];?>" width="100" alt="avatar" style="border-radius: 50%;">
		<br>
		<br>
		<a href="index.php" class="button">Volver al inicio</a>
		<a href="modificar.php" class="button">Modificar datos</a>
		<a href="logout.php" class="button">Salir</a>
	</body>
</html>
