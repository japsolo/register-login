<header class="main-header">
	<h1>Welcome to the Paradise City</h1>
	<div class="perfil">
		<?php if (isset($usuario)): ?>
			<a class="button" href="perfil-usuario.php">Mi Perfil</a>
			<a class="button" href="logout.php">Salir</a>
			<img src="<?=$laImagen[0];?>" alt="avatar" width="50" style="border-radius: 50%;">
			<h3>Hola <?=$usuario['name'];?></h3>
		<?php else: ?>
			<a class="button" href="register.php">Regístrate</a>
			<a class="button" href="login.php">Ingresá</a>
		<?php endif; ?>
	</div>
</header>
