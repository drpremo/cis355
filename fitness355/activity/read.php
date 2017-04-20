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
			
			require '../Database.php';
			$Id = null;
			if ( !empty($_GET['Id'])) {
				$Id = $_REQUEST['Id'];
			}
			
			if (null == $Id) {
				header("Location: .");
			} else {
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "SELECT *
						FROM Activity
						WHERE IdA = ?";
				$q = $pdo -> prepare($sql);
				$q -> execute(array($Id));
				$data = $q -> fetch(PDO::FETCH_ASSOC);
				Database::disconnect();
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
					<h3>Read a Activity</h3>
				</div>
				
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>ID</label>
						<div class="text-right"><?php echo $data['IdA'];?></div>
						<br/>
						<label>Name</label>
						<div class="text-right"><?php echo $data['Name'];?></div>
						<br/>
						<label>Intensity</label>
						<div class="text-right"><?php echo $data['Intensity'];?></div>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>Has Distance?</label>
						<div class="text-right"><?php echo ($data['HasDistance'])?'Yes':'No';?></div>
						<br/>
						<label>Has Resistance?</label>
						<div class="text-right"><?php echo ($data['HasResistance'])?'Yes':'No';?></div>
						<br/>
						<label>Has Repititions?</label>
						<div class="text-right"><?php echo ($data['HasRepititions'])?'Yes':'No';?></div>
					</div>
				</div>
				<hr/>
				<a class="btn btn-default" href=".">Back</a>
			</div>
		</div> <!-- /container -->
	</body>
</html>