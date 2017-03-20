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
				
				<div class="form-horizontal" >
					<div class="control-group">
						<label class="control-label">ID</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['IdE'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">User ID</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['IdUser'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Activity ID</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['IdActivity'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Date</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Date'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Start Time</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['StartTime'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">End Time</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['EndTime'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Total Duration</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['TotalDuration'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Workout Duration</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['WorkoutDuration'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Max Heart Rate</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['MaxHeartRate'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Distance</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Distance'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Resistance</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Resistance'];?>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Repititions</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['Repititions'];?>
							</label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Calories Burned</label>
						<div class="controls">
							<label class="checkbox">
								<?php echo $data['CaloriesBurned'];?>
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