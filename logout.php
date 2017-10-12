<?php
	session_start();
	session_destroy();
	setcookie('userId', '', time() - 100);
	header('Location: index.php'); exit;
