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
						FROM Event
						WHERE IdE = ?";
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
					<h3>Read an Event</h3>
				</div>
				
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>ID</label>
						<div class="text-right"><?php echo $data['IdE'];?></div>
						<br/>
						<label>User ID</label>
						<div class="text-right"><?php echo $data['IdUser'];?></div>
						<br/>
						<label>Activity ID</label>
						<div class="text-right"><?php echo $data['IdActivity'];?></div>
						<br/>
						<label>Date</label>
						<div class="text-right"><?php echo $data['Date'];?></div>
						<br/>
						<label>Start Time</label>
						<div class="text-right"><?php echo $data['StartTime'];?></div>
						<br/>
						<label>End Time</label>
						<div class="text-right"><?php echo $data['EndTime'];?></div>
					</div>
					<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					</div>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
						<label>Total Duration</label>
						<div class="text-right"><?php echo $data['TotalDuration'];?></div>
						<br/>
						<label>Workout Duration</label>
						<div class="text-right"><?php echo $data['WorkoutDuration'];?></div>
						<br/>
						<label>Max Heart Rate</label>
						<div class="text-right"><?php echo $data['MaxHeartRate'];?></div>
						<br/>
						<label>Distance</label>
						<div class="text-right"><?php echo $data['Distance'];?></div>
						<br/>
						<label>Resistance</label>
						<div class="text-right"><?php echo $data['Resistance'];?></div>
						<br/>
						<label>Repititions</label>
						<div class="text-right"><?php echo $data['Repititions'];?></div>
						<br/>
						<label>Calories Burned</label>
						<div class="text-right"><?php echo $data['CaloriesBurned'];?></div>
					</div>
				</div>
				<hr/>
				<a class="btn btn-default" href=".">Back</a>
			</div>
		</div> <!-- /container -->
	</body>
</html>