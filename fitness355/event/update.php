<?php
	require '../Database.php';

	$id = null;
	if (!empty($_GET['Id'])) {
		$id = $_REQUEST['Id'];
	}
	
	if (null == $id) {
		header("Location: .");
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
				// keep track validation errors
				
				// keep track post values
				$User = $_POST['User'];
				$Activity = $_POST['Activity'];
				$Date = $_POST['Date'];
				$StartTime = $_POST['StartTime'];
				$EndTime = $_POST['EndTime'];
				$TotalDuration = $_POST['TotalDuration'];
				$WorkoutDuration = $_POST['WorkoutDuration'];
				$MaxHeartRate = $_POST['MaxHeartRate'];
				
				// validate input
				$valid = true;
				if (empty($Date)) {
					$DateError = 'Please enter a date.';
					$valid = false;
				}
				
				if (empty($StartTime)) {
					$StartTimeError = 'Please enter a start time.';
					$valid = false;
				}
				
				if (empty($EndTime)) {
					$EndTimeError = 'Please enter a end time.';
					$valid = false;
				}
				
				if (empty($TotalDuration)) {
					$TotalDurationError = 'Please enter a total duration.';
					$valid = false;
				}
				
				if (empty($WorkoutDuration)) {
					$WorkoutDurationError = 'Please enter a workout time.';
					$valid = false;
				}
				
				if (empty($MaxHeartRate)) {
					$MaxHeartRateError = 'Please enter a workout time.';
					$valid = false;
				}
				
				// HasDistance, HasResistance, and HasRepititions did not need validation because
				// null values return 0.	
				
				// update data
				if ($valid) {
					$pdo = Database::connect();
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					
					$sql = "SELECT *
							FROM Event
							WHERE IdE = '$id'";
					foreach ($pdo -> query($sql) as $row) { $ActivityId = $row['IdActivity']; }
					foreach ($pdo -> query($sql) as $row) { $UserId = $row['IdUser']; }
							
					$sql = "SELECT *
							FROM Activity
							WHERE IdA = '$ActivityId'";
					foreach ($pdo -> query($sql) as $row) { $ActivityName = $row['Name']; }
					$sql = "SELECT *
							FROM User
							WHERE IdU = '$UserId'";
					foreach ($pdo -> query($sql) as $row) { $UserName = ($row['FName'] . ' ' . $row['LName']); }
					
					$sql = "UPDATE Activity set Name = ?, Intensity = ?, HasDistance = ?, HasResistance = ?, HasRepititions = ?
							WHERE IdA = ?";
					$q = $pdo->prepare($sql);
					$q->execute(array($Name,$Intensity,$HasDistance,$HasResistance,$HasRepititions,$id));
					Database::disconnect();
					session_start();
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = 'Updated event ' . $ActivityName . ' for ' . $UserName . '.';
					header("Location: .");
				}
			} else {
				$pdo = Database::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "SELECT *
						FROM Event where IdE = ?";
				$q = $pdo -> prepare($sql);
				$q -> execute(array($id));
				$data = $q -> fetch(PDO::FETCH_ASSOC);
				$Date = $data['Date'];
				$StartTime = $data['StartTime'];
				$EndTime = $data['EndTime'];
				$TotalDuration = $data['TotalDuration'];
				$WorkoutDuration = $data['WorkoutDuration'];
				$MaxHeartRate = $data['MaxHeartRate'];
				$HasDistance = $data['HasDistance'];
				$Distance = $data['Distance'];
				$HasResistance = $data['HasResistance'];
				$Resistance = $data['Resistance'];
				$HasRepititions = $data['HasRepititions'];
				$Repititions = $data['Repititions'];
				Database::disconnect();
			}
		?>
		<style>
			input {
				min-width: 200px;
				min-height: 25.6px;
			}
			select {
				min-width: 200px;
				min-height: 25.6px;
			}
		</style>
	</head>

	<body style="background-color:LightGreen" onload="hideDetails()">
		<?php 
			Template::navigation("../");
		?>
		
		<div class="container">
			<div class="span10 offset1">
				<div class="row">
					<h3>Update an Event</h3>
				</div>
				
				<form class="form-horizontal" action="update.php?Id=<?php echo $id;?>" method="post">
				
					<div class=col-lg-3>
						<div class="control-group <?php echo !empty($NameError)?'error':'';?>">
							<label class="control-label">Name</label>
							<div class="controls">
								<select name="User">
									<?php
										$pdo = Database::connect();
										$sql = 'SELECT *
												FROM User
												ORDER BY LName';
										foreach ($pdo->query($sql) as $row) {
											if (!empty($row['FName']) || !empty($row['lname'])) { 
												if ($row['IdU'] == $data['IdUser']) {
													echo '<option value="' . $row['IdU'] . '" selected="selected">' . $row['LName'] . ', ' . $row['FName'] . '</option>';
												} else {
													echo '<option value="' . $row['IdU'] . '">' . $row['FName'] . ' ' . $row['LName'] . '</option>';
												}
											}
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="control-group <?php echo !empty($ActivityError)?'error':'';?>">
							<label class="control-label">Activity</label>
							<div class="controls">
								<select name="Activity"
										onchange="hideDetails()" id="inActivity" >
									<?php
										$pdo = Database::connect();
										$sql = 'SELECT *
												FROM Activity
												ORDER BY Name';
										foreach ($pdo->query($sql) as $row) {
											if ($row['IdA'] == $data['IdActivity']) {
												echo '<option value="' . $row['IdA'] . '" selected="selected">' . $row['Name'] . '</option>';
											} else {
												echo '<option value="' . $row['IdA'] . '">' . $row['Name'] . '</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Date</label>
							<div class="controls">
								<input name="Date" type="date" placeholder="Date"
									   value="<?php echo !empty($Date)?$Date:'';?>"/>
								<?php if (!empty($DateError)): ?>
									<span class="help-inline"><?php echo $DateError;?></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Start Time</label>
							<div class="controls">
								<input name="StartTime" type="time" placeholder="Start Time"
									   value="<?php echo !empty($StartTime)?$StartTime:'';?>"
									   onkeyup="updateDuration()" id="inStartTime"/>
								<?php if (!empty($StartTimeError)): ?>
									<span class="help-inline"><?php echo $StartTimeError;?></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">End Time</label>
							<div class="controls">
								<input name="EndTime" type="time" placeholder="End Time"
									   value="<?php echo !empty($EndTime)?$EndTime:'';?>"
									   onkeyup="updateDuration()" id="inEndTime">
								<?php if (!empty($EndTimeError)): ?>
									<span class="help-inline"><?php echo $EndTimeError;?></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Total Duration</label>
							<div class="controls">
								<input name="TotalDuration" type="number" placeholder="Total Duration"
									   value="<?php echo !empty($TotalDuration)?$TotalDuration:'';?>" 
										id="inTotalDuration">
								<?php if (!empty($TotalDurationError)): ?>
									<span class="help-inline"><?php echo $TotalDurationError;?></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Workout Duration</label>
							<div class="controls">
								<input name="WorkoutDuration" type="number" placeholder="Workout Duration"
									   value="<?php echo !empty($WorkoutDuration)?$WorkoutDuration:'';?>"
									   onchange="setDurationManual()" id="inWorkoutDuration">
								<?php if (!empty($WorkoutDurationError)): ?>
									<span class="help-inline"><?php echo $WorkoutDurationError;?></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Max Heart Rate</label>
							<div class="controls">
								<input name="MaxHeartRate" type="number" placeholder="Max Heart Rate"
									   value="<?php echo !empty($MaxHeartRate)?$MaxHeartRate:'';?>"
									   id="inMaxHeartRate"/>
								<?php if (!empty($MaxHeartRateError)): ?>
									<span class="help-inline"><?php echo $MaxHeartRateError;?></span>
								<?php endif; ?>
							</div>
						</div>
					
						<div class="form-actions">
							<br/>
							<button type="submit" class="btn btn-success">Update</button>
							<a class = "btn btn-default" href = ".">Back</a>
						</div>
					</div>
					
					<div class="col-lg-6">
						<br/>
						<br/>
						<br/>
						<div class="control-group" id="Distance" style="display:block">
							<label class="control-label">Distance</label>
							<div class="controls">
								<input name="Distance" type="number" placeholder="Distance"
									   value="<?php echo !empty($Distance)?$Distance:'';?>"
									   id="inDistance"/>
							</div>
						</div>
						
						<div class="control-group" id="Resistance" style="display:block">
							<label class="control-label">Resistance</label>
							<div class="controls">
								<input name="Resistance" type="number" placeholder="Resistance"
									   value="<?php echo !empty($Resistance)?$Resistance:'';?>"
									   id="inResistance"/>
							</div>
						</div>
						
						<div class="control-group" id="Repititions" style="display:block">
							<label class="control-label">Repititions</label>
							<div class="controls">
								<input name="Repititions" type="number" placeholder="Repititions"
									   value="<?php echo !empty($Repititions)?$Repititions:'';?>"
									   id="inRepititions"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div> <!-- /container -->
		
		<script>
			var durationManual = false;
		
			var hasDistance = new Array();
			var hasResistance = new Array();
			var hasRepititions = new Array();
			var intensity = new Array();
			<?php
				$i = 0;
				$pdo = Database::connect();
				$sql = 'SELECT *
						FROM Activity
						ORDER BY Name';
				foreach ($pdo->query($sql) as $row) {
					echo 'hasDistance[' . $i . '] = ' . $row['HasDistance'] . ';';
					echo 'hasResistance[' . $i . '] = ' . $row['HasResistance'] . ';';
					echo 'hasRepititions[' . $i . '] = ' . $row['HasRepititions'] . ';';
					echo 'intensity[' . $i . '] = ' . $row['Intensity'] . ';';
					$i++;
				}
			?>
			
			function updateDuration() {
				var start = document.getElementById("inStartTime").value;
				var startHour = parseInt(start.substring(0, 2)) * 60;
				var startMin = parseInt(start.substring(3, 5));
				var end = document.getElementById("inEndTime").value;
				var endHour = parseInt(end.substring(0, 2)) * 60;
				var endMin = parseInt(end.substring(3, 5));
				var totalDuration = (endHour + endMin) - (startHour + startMin);
				
				if (!isNaN(totalDuration)) {
					document.getElementById("inTotalDuration").value = totalDuration;
					if (durationManual == false) document.getElementById("inWorkoutDuration").value = totalDuration;
				}
			}
		
			function setDurationManual() {
				durationManual = true;
			}
			
			function hideDetails() {
				var iA = document.forms[0].elements["inActivity"].selectedIndex;
				
				if (hasDistance[iA] == 0) {
					document.getElementById("inDistance").value = "";
					document.getElementById("Distance").style.display = 'none';
				}
				if (hasDistance[iA] == 1) document.getElementById("Distance").style.display = 'block';
				
				if (hasResistance[iA] == 0) {
					document.getElementById("inResistance").value = "";
					document.getElementById("Resistance").style.display = 'none';
				}
				if (hasResistance[iA] == 1) document.getElementById("Resistance").style.display = 'block';

				if (hasRepititions[iA] == 0) {
					document.getElementById("inRepititions").value = "";
					document.getElementById("Repititions").style.display = 'none';
				}
				if (hasRepititions[iA] == 1) document.getElementById("Repititions").style.display = 'block';
			}
		</script>
	</body>
</html>