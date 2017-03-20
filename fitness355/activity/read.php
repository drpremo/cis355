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
				
				<div class="form-horizontal" >
					<div class="control-group">
						<label class="control-label">ID</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['IdA'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Name</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Name'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Intensity</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Intensity'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Has Distance?</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo ($data['HasDistance'])?'Yes':'No';?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Has Resistance?</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo ($data['HasResistance'])?'Yes':'No';?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Has Repititions?</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo ($data['HasRepititions'])?'Yes':'No';?>
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