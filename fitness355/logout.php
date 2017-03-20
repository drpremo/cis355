<?php
	session_start();
	unset($_SESSION['user']);
	$_SESSION['showmessage'] = 1;
	$_SESSION['message'] = 'Logged out';
	header("location:login.php");
?>