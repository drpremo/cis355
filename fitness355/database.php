<?php
class Database {
	private static $dbHost = "localhost";
	private static $dbUser = "drpremo";
	private static $dbPass = "593491";
	private static $dbName = "drpremo";
	private static $cont  = null;
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect() {
		// One connection through whole application
		if ( null == self::$cont ) {      
			try {
				self::$cont =  new PDO("mysql:host=" . self::$dbHost . ";" .
				                             "dbname=" . self::$dbName, self::$dbUser, self::$dbPass);  
			} catch(PDOException $e) {
				die($e->getMessage());  
			}
        } 
        return self::$cont;
	}
	
	public static function disconnect()	{
		self::$cont = null;
	}
	
	public static function quick_connect() {
		return mysqli_connect(self::$dbHost, self::$dbUser, self::$dbPass, self::$dbName);
	}
	
	public function importBootstrap() {
		echo '<!DOCTYPE html>';
		echo '<html lang=en>';
		echo '    <meta charset=utf-8>';
		echo '    <link href=css/bootstrap.min.css rel=stylesheet>';
		echo '    <script src=js/bootstrap.min.js></script>';
	}
	
	public function displayListHeading() {
		echo '<div class=container>';
		echo '    <div class=row>';
		echo '        <h3>Customers</h3>';
		echo '    </div>';
		echo '    <div class=row>';
		echo '        <p>';
		echo '            <a class="btn btn-success"href=create.php>Create</a>';
		echo '            <table class="table table-bordered table-striped">';
		echo '                <thead>';
		echo '                    <tr>';
		echo '                        <th>Name';
		echo '                        <th>Email Address';
		echo '                        <th>Mobile Number';
		echo '                        <th>Action<tbody>';
	}
	
	public function displayListTableContents() {
		$pdo = Database::connect();
		$sql = 'SELECT * FROM customers ORDER BY id DESC';
		foreach ($pdo->query($sql) as $row) {
			echo '<tr>';
			echo '    <td>'. $row['name'] . '</td>';
			echo '    <td>'. $row['email'] . '</td>';
			echo '    <td>'. $row['mobile'] . '</td>';
			echo '    <td width=250>';
			echo '        <a class="btn" href="read.php?id='.$row['id'].'">Readoo</a>';
			echo '        &nbsp;';
			echo '        <a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
			echo '        &nbsp;';
			echo '        <a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
			echo '    </td>';
			echo '</tr>';
		}
		Database::disconnect();
	}
	
	public function displayListFooting() {
		echo '                    </tbody>';
		echo '                </table>';
		echo '            </div>';
		echo '        </div>';
		echo '    </body>';
		echo '</html>';
	}
	
	public function displayListScreen() {
		Database::importBootstrap();
		Database::displayListHeading();
		Database::displayListTableContents();
		Database::displayListFooting();
	}
}
?>