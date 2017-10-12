<?php
	session_start();

	require_once('funciones.php');

	if(!estaLogueado()){
		header('Location: index.php'); exit;
	}

	$sexos = [
		'F' => 'Femenino',
		'M' => 'Masculino',
		'O'=>'Otro'
	];

	$elUsuario = traerId($_SESSION['userId']);

	$laImagen = glob('images/avatares/' . $elUsuario['email'] . '*');

	if ($_POST) {

		// Validación - La función validarUpdate retorna un array
		$erroresFinales = validarUpdate($_POST, $_FILES);
	}

	$tituloDePagina = 'Perfil del Usuario';
	require_once('includes/head.php');
?>

	<form method="post" class="data-form" enctype="multipart/form-data">
		<label>Nombre:</label> <br>
		<input type="text" name="name" value="<?=$elUsuario['name'];?>">
		<?php if (isset($erroresFinales['nombre'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['nombre'];?></span>
		<?php endif; ?>
		<br>

		<label>Apellido:</label> <br>
		<input type="text" name="apellido" value="<?=$elUsuario['lastname'];?>">
		<?php if (isset($erroresFinales['apellido'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['apellido'];?></span>
		<?php endif; ?>
		<br>

		<label>Correo electrónico:</label> <br>
		<input type="text" name="email" value="<?=$elUsuario['email'];?>" readonly>
		<span style="color: orange;">Campo no editable</span>
		<br>

		<label>Usuario:</label> <br>
		<input type="text" name="username" value="<?=$elUsuario['username'];?>" readonly>
		<span style="color: orange;">Campo no editable</span>
		<br>

		<label>Sexo:</label> <br>
		<select name="sexo">
			<option value="">Elegir</option>
			<?php foreach ($sexos as $letra => $valor): ?>
				<?php if (isset($elUsuario['gender']) && $elUsuario['gender'] == $letra): ?>
					<option selected value="<?=$letra;?>"><?=$valor;?></option>
				<?php else: ?>
					<option value="<?=$letra;?>"><?=$valor;?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php if (isset($erroresFinales['sexo'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['sexo'];?></span>
		<?php endif; ?>
		<br>

		<label>Password:</label> <br>
		<input type="password" name="pass">
		<?php if (isset($erroresFinales['pass'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['pass'];?></span>
		<?php endif; ?>
		<br>

		<label>Repetir Password:</label> <br>
		<input type="password" name="repass">
		<?php if (isset($erroresFinales['repass'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['repass'];?></span>
		<?php endif; ?>
		<br>

		<label>Subí tu avatar</label> <br>
		<input type="file" name="avatar" multiple>
		<?php if (isset($erroresFinales['imagen'])): ?>
			<span style="color: red;"><i class="ion-ios-close"></i></span>
			<span style="color: red;"><?=$erroresFinales['imagen'];?></span>
		<?php endif; ?>

		<br><br>
		<button type="submit" class="button">ENVIAR</button>
		<br><br>

		<a href="perfil-usuario.php" class="button"><< Regresar</a>
	</form>
	</body>
</html>
