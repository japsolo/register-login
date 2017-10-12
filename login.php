<?php
	session_start();

	require_once('funciones.php');

	if(estaLogueado()){
		header('Location: perfil-usuario.php'); exit;
	}

	if ($_POST) {

		// Validación - La función validarUsuario retorna un array
		$erroresFinales = validarLogin($_POST);

		if (empty($erroresFinales)) {
			// Guardo en la variable $usuario los datos del usuario que se quiere loguear
	      $usuario = comprobarEmail($_POST["email"]);

		 	// Guardo al ID del usuario en $_SESSION.
	      loguear($usuario);

			// Seteo la cookie
			if (isset($_POST["recordame"])) {
	        setcookie('userId', $usuario['id'], time() + 60 * 60 * 24 * 30);
	      }

			// Ok redirecciono
			header('location: perfil-usuario.php'); exit;
		}
	}

	$tituloDePagina = 'Login';
	require_once('includes/head.php');
?>

		<form method="post" class="data-form">
			<label>Correo electrónico:</label> <br>
			<input type="text" name="email">
			<br>

			<label>Password:</label> <br>
			<input type="password" name="pass">
			<br>

			<label>
				<input type="checkbox" name="recordame">
				Recordame
			</label>
			<br><br>

			<?php if (isset($erroresFinales['email'])): ?>
				<span style="color: red;"><i class="ion-ios-close"></i></span>
				<span style="color: red;"><?=$erroresFinales['email'];?></span>
			<?php endif; ?>

			<br><br>
			<button type="submit" class="button">ENVIAR</button>
		</form>

		<?php if (isset($erroresFinales) && !empty($erroresFinales)): ?>
			<div class="div-errores">
				<ul>
					<?php foreach ($erroresFinales as $clave => $error): ?>
						<li> <?=$error?> </li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</body>
</html>
