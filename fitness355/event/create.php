<?php 
	require '../Database.php';
	session_start();

	$username = $_SESSION['user'];
	
	if (!empty($_POST)) {
		// keep track validation errors
		$DateError = null;
		$StartTimeError = null;
		$EndTimeError = null;
		$TotalDurationError = null;
		$WorkoutDurationError = null;
		$MaxHeartRateError = null;
		
		// keep track post values
		$Name_User = $_POST['User'];
		$Name_Activity = $_POST['Activity'];
		$Date = $_POST['Date'];
		$StartTime = $_POST['StartTime'];
		$EndTime = $_POST['EndTime'];
		$TotalDuration = $_POST['TotalDuration'];
		$WorkoutDuration = $_POST['WorkoutDuration'];
		$MaxHeartRate = $_POST['MaxHeartRate'];
		$Distance = $_POST['Distance'];
		$Resistance = $_POST['Resistance'];
		$Repititions = $_POST['Repititions'];
		
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
		
		if ($valid == true) {
			// connect to database
			$pdo = Database::connect();
			
			// access user table and assign attributes to variables
			$sql = "SELECT *
					FROM User
					WHERE IdU = '$Name_User'";
			foreach ($pdo -> query($sql) as $row) { $UserName = $row['FName'] . ' ' . $row['LName']; }
			foreach ($pdo -> query($sql) as $row) { $birth = $row['Birth']; }
			foreach ($pdo -> query($sql) as $row) { $gender = $row['Gender']; }
			foreach ($pdo -> query($sql) as $row) { $height = $row['Height']; }
			foreach ($pdo -> query($sql) as $row) { $weight = $row['Weight']; }
			foreach ($pdo -> query($sql) as $row) { $level = $row['ActivityLevel']; }
			
			// access activity table and assign attributes to variables
			$sql = "SELECT *
					FROM Activity
					WHERE IdA = '$Name_Activity'";
			foreach ($pdo -> query($sql) as $row) { $ActivityName = $row['Name']; }
			foreach ($pdo -> query($sql) as $row) { $intensity = $row['Intensity']; }
			foreach ($pdo -> query($sql) as $row) { $hasDistance = $row['HasDistance']; }
			foreach ($pdo -> query($sql) as $row) { $hasResistance = $row['HasResistance']; }
			foreach ($pdo -> query($sql) as $row) { $hasRepititions = $row['HasRepititions']; }
			if (empty($hasDistance)) $hasDistance = 0;
			if (empty($hasResistance)) $hasResistance = 0;
			if (empty($hasRepititions)) $hasRepititions = 0;
			if (empty($Distance)) $Distance = 0;
			if (empty($Resistance)) $Resistance = 0;
			if (empty($Repititions)) $Repititions = 0;
			
			// generate age and optimal heart rate from date of birth
			$age = (strtotime($Date) - strtotime($birth)) / 60 / 60 / 24 / 365.25;
			$heartLow = (220 - $age) * 0.5;
			$heartMid = (220 - $age) * 0.7;
			$heartHigh = (220 - $age) * 0.85;
			
			// fix level multiplier
			if ($level == 0) $level = 1.2;
			if ($level == 1) $level = 1.375;
			if ($level == 2) $level = 1.55;
			if ($level == 3) $level = 1.725;
			if ($level == 4) $level = 1.9;
			
			// calculate dailyBMR using the Harris-Benedict BMR equation revised by Roza and Shizgal in 1984
			// https://en.wikipedia.org/wiki/Harris%E2%80%93Benedict_equation
			if ($gender == "M") $dailyBMR = (88.362 + (6.07677698 * $weight) + (12.18946 * $height) - (5.677 * $age)) * $level;
			if ($gender == "F") $dailyBMR = (655.1 + (4.1943686 * $weight) + (7.86892 * $height) - (4.330 * $age)) * $level;

			$idleTime = $TotalDuration - $WorkoutDuration;
			$CaloriesBurned = $dailyBMR / 24 / 60 * ($idleTime + $WorkoutDuration * $intensity) * (($MaxHeartRate / $heartMid) ** 0.5);
			
			// insert data
			if ($valid) {
				$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = "INSERT
						INTO Event(IdActivity, IdUser, Date, StartTime, EndTime, TotalDuration, WorkoutDuration, MaxHeartRate, Distance, Resistance, Repititions, CaloriesBurned)
						VALUES(             ?,      ?,    ?,         ?,       ?,             ?,               ?,            ?,        ?,          ?,           ?,              ?)";
				$q = $pdo -> prepare($sql);
				$q -> execute(array($Name_Activity, $Name_User, $Date, $StartTime, $EndTime, $TotalDuration, $WorkoutDuration, $MaxHeartRate, $Distance, $Resistance, $Repititions, $CaloriesBurned));
				Database::disconnect();
				session_start();
				$_SESSION['showmessage'] = 2;
				$_SESSION['message'] = 'Created event ' . $ActivityName . ' for ' . $UserName . '.';
				header("Location: .");
			}
		}
	}


	// Program Intensity < heartLow: Easy
	//                   < heartMid: Moderate
	//                   < heartHight: Vigorous
	//                   else Dangerous
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

	<body style="background-color:LightGreen">
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">
			<div class="span10 offset1">
				<div class="row">
					<h3>Create an Event</h3>
				</div>
				
				<form class="form-horizontal" action="create.php" method="post">
				
					<div class="control-group <?php echo !empty($NameError)?'error':'';?>">
						<label class="control-label">Name</label>
						<div class="controls">
							<select name="User">
								<?php
									$pdo = Database::connect();
									$sql = 'SELECT *
									        FROM User
											ORDER BY FName';
									foreach ($pdo->query($sql) as $row) {
										if (!empty($row['FName']) || !empty($row['lname'])) { 
											echo '<option value="' . $row['Id'] . '">' . $row['FName'] . ' ' . $row['LName'] . '</option>';
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
										echo '<option value="' . $row['Id'] . '">' . $row['Name'] . '</option>';
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
								   readonly id="inTotalDuration">
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
					
					<div class="control-group" id="Distance" style="display:none">
						<label class="control-label">Distance</label>
						<div class="controls">
							<input name="Distance" type="number" placeholder="Distance"
								   value="<?php echo !empty($Distance)?$Distance:'';?>"/>
						</div>
					</div>
					
					<div class="control-group" id="Resistance" style="display:block">
						<label class="control-label">Resistance</label>
						<div class="controls">
							<input name="Resistance" type="number" placeholder="Resistance"
								   value="<?php echo !empty($Resistance)?$Resistance:'';?>"/>
						</div>
					</div>
					
					<div class="control-group" id="Repititions" style="display:block">
						<label class="control-label">Repititions</label>
						<div class="controls">
							<input name="Repititions" type="number" placeholder="Repititions"
								   value="<?php echo !empty($Repititions)?$Repititions:'';?>"/>
						</div>
					</div>
					
					<div class="form-actions">
						<br/>
						<button type="submit" class="btn btn-success">Create</button>
						<a class = "btn btn-default" href = ".">Back</a>
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
				foreach ($pdo -> query($sql) as $row) {
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
				
				if (hasDistance[iA] == 0) document.getElementById("Distance").style.display = 'none';
				if (hasResistance[iA] == 0) document.getElementById("Resistance").style.display = 'none';
				if (hasRepititions[iA] == 0) document.getElementById("Repititions").style.display = 'none';
				
				if (hasDistance[iA] == 1) document.getElementById("Distance").style.display = 'block';
				if (hasResistance[iA] == 1) document.getElementById("Resistance").style.display = 'block';
				if (hasRepititions[iA] == 1) document.getElementById("Repititions").style.display = 'block';
			}
		</script>
	
	</body>
</html>