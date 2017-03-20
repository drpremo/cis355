<?php 
	require '../Database.php';
	$Id = null;
	if ( !empty($_GET['Id'])) {
		$Id = $_REQUEST['Id'];
	}
	
	if ( null==$Id ) {
		header("Location: .");
	} else {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM User where Id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($Id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
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
		?>
	</head>

	<body style="background-color:LightGreen">
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">
    
			<div class="span10 offset1">
				<div class="row">
					<h3>Read a User</h3>
				</div>
				
				<div class="form-horizontal" >
					<div class="control-group">
						<label class="control-label">ID</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Id'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Name</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['FName'] . ' ' . $data['LName'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Mobile Number</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Mobile'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Email Address</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Email'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Date of Birth</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Birth'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Gender</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Gender'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Height</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Height'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Weight</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Weight'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Activity Level</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['ActivityLevel'];?>
							</label>
						</div>
					</div>
					
					<div class="form-actions">
						<br/>
						<a class="btn btn-default" href=".">Back</a>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
	</body>
</html>