<?php 
	require '../Database.php';
	$Id = 0;
	
	if (!empty($_GET['Id'])) {
		$Id = $_REQUEST['Id'];
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<?php
			session_start();
			if (empty($_SESSION['user'])) {
				header("location:../login.php");
			} else {
				echo '<title>Fitness355 - ' . $_SESSION['user'] . '</title>';
			}
			require '../Template.php';
			Template::sources("../");
			
			if (!empty($_POST)) {
				// keep track post values
				$Id = $_POST['Id'];
				
				// delete data
				$pdo = Database::connect();
				$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = "SELECT *
						FROM User
						WHERE Id = '$Id'";
				foreach ($pdo -> query($sql) as $row) { $FName = $row['FName']; }
				foreach ($pdo -> query($sql) as $row) { $LName = $row['LName']; }
				
				
				$sql = "DELETE FROM User
						WHERE Id = ?";
				$q = $pdo -> prepare($sql);
				$q -> execute(array($Id));
				Database::disconnect();
				session_start();
				$_SESSION['showmessage'] = 2;
				$_SESSION['message'] = 'Deleted user ' . $FName . " " . $LName;
				header("Location: .");
			} 
		?>
	</head>

	<body style="background-color:LightGreen">
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">

			<div class="span10 offset1">
				<div class="row">
					<h3>Delete a User</h3>
				</div>
				</br>
				<form class="form-horizontal" action="delete.php" method="post">
					<input type="hidden" name="Id" value="<?php echo $Id;?>"/>
					<p class="alert alert-error">Are you sure to delete user #<?php echo $Id;?>?</p>
					
					<div class="form-actions">
						<br/>
					    <button class="btn btn-danger" type="submit">Yes</button>
						<a class="btn btn-default" href=".">No</a>
					</div>
				</form>
			</div>
		</div> <!-- /container -->
	</body>
</html>