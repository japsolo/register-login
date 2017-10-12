<?php

	// Chequeo si está la cookie seteada y se la paso a session para auto-logueo
	if (isset($_COOKIE['userId'])) {
		$_SESSION['userId'] = $_COOKIE['userId'];
	}

	// == FUNCTION - validarUsuario ==
	/*
		- Recibe dos parámetros
		- $_POST y $_FILES
		- Para validar en el 1er submit que todos los campos son obligatorios
		- Usa la función comprobarEmail() para verificar que no haya registros con el mismo email
		- Retorna un array de errores que puede estar vacio
	*/
	function validarUsuario($data, $files){
		$errores = [];

		if (trim($data['name']) == '') {
			$errores['nombre'] = 'Che escribí el nombre!';
		}
		if (trim($data['apellido']) == '') {
			$errores['apellido'] = 'Che escribí el apellido!';
		}

		if (trim($data['email']) == '') {
			$errores['email'] = 'Che escribí el email!';
		} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errores['email'] = 'Che el email ES INVALIDO!';
		} elseif (comprobarEmail($data['email']) != false) {
			$errores['email'] = 'Amigx el email ya existe';
		}

		if (trim($data['username']) == '') {
			$errores['username'] = 'Escribí un nombre de usuario!';
		}

		if (trim($data['pass']) == '') {
			$errores['pass'] = 'Che escribí la contraseña!';
		}

		if (trim($data['repass']) == '') {
			$errores['repass'] = 'Che escribí la contraseña de nuevo!';
		} elseif (trim($data['pass']) != trim($data['repass'])) {
			$errores['repass'] = 'Las contraseñas no coinciden!';
		}

		if (trim($data['sexo']) == '') {
			$errores['sexo'] = 'Che elegí un sexo!';
		}

		if ($files['avatar']['error'] != UPLOAD_ERR_OK) {
			$errores['imagen'] = 'Ché subí una imagen';
		}

		return $errores;
	}


	// == FUNCTION - crearUsuario ==
	/*
		- Recibe un parámetro
		- $_POST
		- Con estos datos, genera un array nuevo, que será el usuario en si
		- Retorna el array con el usuario final
	*/
	function crearUsuario($datos){
		$usuarioFinal = [
			'id' => generarId(),
			'name' => $datos['name'],
			'lastname' => $datos['apellido'],
			'email' => $datos['email'],
			'username' => $datos['username'],
			'password' => password_hash($datos['pass'], PASSWORD_DEFAULT),
			'gender' => $datos['sexo']
		];

		return $usuarioFinal;
	}


	// == FUNCTION - traerTodos ==
	/*
		- NO recibe dos parámetros
		- Lee el JSON y arma un array de arrays de usuarios, cada línea en el JSON será un array de 1 usuario
		- Retorna el array con todos los usuarios
	*/
	function traerTodos(){
		// Obtengo el contenido del JSON
		$archivo = file_get_contents("usuarios.json");

		// esto me arma un array con todos los usuarios
      $usuariosJSON = explode(PHP_EOL, $archivo);

		// Saco el último elemento que es una línea vacia
		array_pop($usuariosJSON);

		// Creo un array vacio, para guardar los usuarios
		$usuariosFinal = [];

		// Recorremos el array y generamos por cada usuario un array del usuario
		foreach ($usuariosJSON as $usuario) {
			$usuariosFinal[] = json_decode($usuario, true);
		}

		return $usuariosFinal;
	}


	// == FUNCTION - generarId ==
	/*
		- NO recibe parámetros
		- Usa la función traerTodos()
		- Retorna un número. En el 1er usuario registrado devuelve 1 y en los siguientes al ID actual le suma 1
	*/
	function generarId(){
		// me traigo todos los usuarios
		$usuarios = traerTodos();

		if (count($usuarios) == 0) {
			return 1;
		}

		// en caso de que haya usuarios agarro el ultimo usuario
		$elUltimo = array_pop($usuarios);

		// pregunto por le id de ese ultimo usuario
		$id = $elUltimo['id'];

		// a ese ID le sumo 1, para asignarle el nuevo ID al usuario  que se esta registrando
		return $id + 1;
	}


	// == FUNCTION - comprobarEmail ==
	/*
		- Recibe un parámetro
		- $_POST['email']
		- Usa la función traerTodos()
		- Retorna un array del usuario si encuentra el email. De no encontrarlo, retorna false
	*/
	function comprobarEmail($mail){
		// Traigo todos los usuario
		$usuarios = traerTodos();

		// Recorro ese array
		foreach ($usuarios as $unUsuario) {
			// Si el mail del usuario en el array es igual al que me llegó por POST, devuelvo al usuario
			if ($unUsuario['email'] == $mail) {
				return $unUsuario;
			}
		}

		return false;
	}


	// == FUNCTION - guardarUsuario ==
	/*
		- Recibe un parámetro
		- $usuario: array creado con la función crearUsuario()
		- No retorna nada, se encarga de guardar en el JSON el usuario recibido
	*/
	function guardarUsuario($usuario){
		// Tomar el array PHP y lo convierte en eun objeto JSON
		$usuarioFinal = json_encode($usuario);
		// Inserta el objeto JSON en nuestro archivo de usuarios
		file_put_contents('usuarios.json', $usuarioFinal . PHP_EOL, FILE_APPEND); //PHP_EOL = Salto de linea
	}


	// == FUNCTION - guardarImagen ==
	/*
		- Recibe dos parámetros
		- $laImagen: el nombre del input de la imagen - $errores: el array de errores
		- Se encarga de guardar el archivo de imagen en la ruta definida
		- Retorna un array de errores si los hay
	*/
	function guardarImagen($laImagen, $errores){
		if ($_FILES[$laImagen]['error'] == UPLOAD_ERR_OK) {
			// Capturo el nombre de la imagen, para obtener la extensión
			$nombreImagen = $_FILES[$laImagen]['name'];
			// Obtengo la extensión de la imagen
			$ext = pathinfo($nombreImagen, PATHINFO_EXTENSION);
			// Capturo el archivo temporal
			$archivoImagen = $_FILES[$laImagen]['tmp_name'];

			// Pregunto si la extensión es la deseada
			if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
				// Armo la ruta donde queda gurdada la imagen
				$rutaArchivo = dirname(__FILE__) . '/images/avatares/' . $_POST['email'] . '.' . $ext;

				// Subo la imagen definitivamente
				move_uploaded_file($archivoImagen, $rutaArchivo);
			} else {
				$errores['imagen'] = 'El formato tiene que ser JPG, JPEG, PNG o GIF';
			}
		} else {
			// Genero error si no se puede subir
			$errores['imagen'] = 'No se pudo subir la imagen';
		}

		return $errores;
	}


	// == FUNCTION - traerId ==
	/*
		- Recibe un parámetro
		- $id:
	*/
	function traerId($id){
		// me traigo todos los usuarios
		$usuarios = traerTodos();

		// Recorro el array de todos los usuarios
		foreach ($usuarios as $unUsuario) {
			if ($id == $unUsuario['id']) {
				return $unUsuario;
			}
		}

		return false;
	}


	// == FUNCTION - validarLogin ==
	/*
		- Recibe un parámetros
		- $_POST
		- Usa la función comprobarEmail()
		- Retorna un array de errores que puede estar vacio
	*/
	function validarLogin($data){
		$errores = [];

		if (trim($data['email']) == '') {
			$errores['email'] = 'Che escribí el email!';
		} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errores['email'] = 'Che escribí un email valido!';
		} else if (comprobarEmail($data['email']) == false) { // Busco el email que se está queriendo loguear, si no existe, tiro error
      	$errores["email"] = "No existe el usuario";
     	} else {
      	// si el mail existe, me guardo al usuario dueño del mismo
      	$usuario = comprobarEmail($data['email']);
 			// pregunto si coindice la password escrita con la guardada en el JSON
      	if (password_verify($data["pass"], $usuario["password"]) == false) {
         	$errores["email"] = "La información ingresada es erronea. Verifica tus datos.";
      	}
     	}

		return $errores;
	}


	// FUNCTION - loguear
	function loguear($usuario) {
		// Guardo en $_SESSION el ID del USUARIO
	   $_SESSION['userId'] = $usuario['id'];
	}


	// FUNCTION - estaLogueado
	function estaLogueado() {
		return isset($_SESSION['userId']);
	}


	// == FUNCTION - validarUpdate ==
	function validarUpdate($data, $files){
		$errores = [];

		if (trim($data['name']) == '') {
			$errores['nombre'] = 'Che escribí el nombre!';
		}
		if (trim($data['apellido']) == '') {
			$errores['apellido'] = 'Che escribí el apellido!';
		}

		if (trim($data['pass']) == '') {
			$errores['pass'] = 'Che escribí la contraseña!';
		}

		if (trim($data['repass']) == '') {
			$errores['repass'] = 'Che escribí la contraseña de nuevo!';
		} elseif (trim($data['pass']) != trim($data['repass'])) {
			$errores['repass'] = 'Las contraseñas no coinciden!';
		}

		if (trim($data['sexo']) == '') {
			$errores['sexo'] = 'Che elegí un sexo!';
		}

		if ($files['avatar']['error'] != UPLOAD_ERR_OK) {
			$errores['imagen'] = 'Ché subí una imagen';
		}

		return $errores;
	}
