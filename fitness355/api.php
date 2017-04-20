<?php
	require 'Database.php';
	$pdo = Database::connect();
	if($_GET['idU']) { 
		$sql = 'SELECT *
				FROM User
				WHERE id = ' . $_GET['idU'];
	} else {
		$sql = "SELECT *
				FROM User";
	}
	
	$arr = array();
	foreach ($pdo -> query($sql) as $row) {
		array_push($arr, $row['LName'] . ' ' . $row['FName']);
	}
	Database::disconnect();
	echo '{"names":' . json_encode($arr) . '}';
?>