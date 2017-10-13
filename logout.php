<?php
	session_start();
	session_destroy();
	setcookie('userId', '', time() - 10);
	header('Location: index.php'); exit;
