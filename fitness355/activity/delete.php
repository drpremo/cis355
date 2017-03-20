<?php 
	require '../Database.php';
	$id = 0;
	
	if ( !empty($_GET['Id'])) {
		$id = $_REQUEST['Id'];
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
			
			if ( !empty($_POST)) {
				// keep track post values
				$id = $_POST['Id'];
				
				// delete data
				$pdo = Database::connect();
				$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = "SELECT *
						FROM Activity
						WHERE IdA = '$id'";
				foreach ($pdo -> query($sql) as $row) { $name = $row['Name']; }
				
				$sql = "DELETE
						FROM Activity
						WHERE IdA = ?";
				$q = $pdo -> prepare($sql);
				$q -> execute(array($id));
				Database::disconnect();
				session_start();
				$_SESSION['showmessage'] = 2;
				$_SESSION['message'] = 'Deleted activity ' . $name;
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
					<h3>Delete an Activity</h3>
				</div>
				</br>
				
				<form class="form-horizontal" action="delete.php" method="post">
					<input type="hidden" name="Id" value="<?php echo $id;?>"/>
					<p class="alert alert-error">Are you sure to delete activity #<?php echo $id;?>?</p>
					
					<div class="form-actions">
					    <button type="submit" class="btn btn-danger">Yes</button>
						<a class="btn btn-default" href=".">No</a>
					</div>
				</form>
			</div>
				
		</div> <!-- /container -->
	</body>
</html>