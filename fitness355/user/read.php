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
		$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT *
				FROM User
				WHERE IdU = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($Id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		Database::disconnect();
		
							$pdo = Database::quick_connect();
							$sql = "SELECT *
									FROM User
									WHERE IdU = $Id";
							$result = mysqli_query($pdo, $sql);
							$content = mysql_result($result, 0, "Image");
		
		//echo '<img '
		//print_r(base64_encode($content));
		//exit();
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

	<body <?php echo Template::$bg;?>>
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">
    
			<div class="span10 offset1">
				<div class="row">
					<h3>Read a User</h3>
				</div>
				
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>ID</label>
						<div class="text-right"><?php echo $data['IdU'];?></div>
						<br/>
						<label>Name</label>
						<div class="text-right"><?php echo $data['FName'] . ' ' . $data['LName'];?></div>
						<br/>
						<label>Username</label>
						<div class="text-right"><?php echo $data['Username'];?></div>
						<br/>
						<label>Email Address</label>
						<div class="text-right"><?php echo $data['Email'];?></div>
						<br/>
						<label>Mobile Number</label>
						<div class="text-right"><?php echo $data['Mobile'];?></div>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>Date of Birth</label>
						<div class="text-right"><?php echo $data['Birth'];?></div>
						<br/>
						<label>Gender</label>
						<div class="text-right"><?php echo $data['Gender'];?></div>
						<br/>
						<label>Height</label>
						<div class="text-right"><?php echo $data['Height'];?></div>
						<br/>
						<label>Weight</label>
						<div class="text-right"><?php echo $data['Weight'];?></div>
						<br/>
						<label>Activity Level</label>
						<div class="text-right"><?php echo $data['ActivityLevel'];?></div>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>Picture</label>
						<div class="text-right"><?php echo '<img height="auto" width="50%" src="data:image/jpeg;base64,' . base64_encode($data['Image']) . '">' ?></div>
					</div>
				</div>
				<hr/>
				<a class="btn btn-default" href=".">Back</a>
			</div>
		</div> <!-- /container -->
	</body>
</html>