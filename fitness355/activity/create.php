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
				$NameError = null;
				$IntensityError = null;
				
				// keep track post values
				$Name = $_POST['Name'];
				$Intensity = $_POST['Intensity'];
				$HasDistance = $_POST['HasDistance'];
				$HasResistance = $_POST['HasResistance'];
				$HasRepititions = $_POST['HasRepititions'];
				
				// validate input
				$valid = true;
				
				if (empty($Name)) {
					$NameError = 'Please enter a name.';
					$valid = false;
				}
				
				if (empty($Intensity)) {
					$IntensityError = 'Please enter an intensity.';
					$valid = false;
				}
				
				// HasDistance, HasResistance, and HasRepititions did not need validation because
				// null values return 0.

				// insert data
				if ($valid) {
					require '../Database.php';
					$pdo = Database::connect();
					$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sql = "INSERT INTO Activity (Name, Intensity, HasDistance, HasResistance, HasRepititions) values(?, ?, ?, ?, ?)";
					$q = $pdo -> prepare($sql);
					$q -> execute(array($Name, $Intensity, $HasDistance, $HasResistance, $HasRepititions));
					Database::disconnect();
					session_start();
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = "Created activity " . $Name;
					header("Location: .");
				}
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
					<h3>Create a User</h3>
				</div>
				
				<form class="form-horizontal" action="create.php" method="post">
				
					<div class="control-group <?php echo !empty($NameError)?'error':'';?>">
						<label class="control-label">Name</label>
						<div class="controls">
							<input name="Name" type="text" placeholder="Name" value="<?php echo !empty($Name)?$Name:'';?>">
							<?php if (!empty($NameError)): ?>
								<span class="help-inline"><?php echo $NameError;?></span>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="control-group <?php echo !empty($IntensityError)?'error':'';?>">
						<label class="control-label">Intensity</label>
						<div class="controls">
							<input name="Intensity" type="text" placeholder="Intensity" value="<?php echo !empty($Intensity)?$Intensity:'';?>">
							<?php if (!empty($IntensityError)): ?>
								<span class="help-inline"><?php echo $IntensityError;?></span>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Has Distance?</label>
						<div class="controls">
							<input name="HasDistance" type="text" placeholder="Has Distance?" value="<?php echo !empty($HasDistance)?$HasDistance:'';?>">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Has Resistance?</label>
						<div class="controls">
							<input name="HasResistance" type="text" placeholder="Has Resistance?" value="<?php echo !empty($HasResistance)?$HasResistance:'';?>">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Has Repititions</label>
						<div class="controls">
							<input name="HasRepititions" type="text" placeholder="Has Repititions?" value="<?php echo !empty($HasRepititions)?$HasRepititions:'';?>">
						</div>
					</div>
					
					<div class="form-actions">
						<br/>
						<button type="submit" class="btn btn-success">Create</button>
						<a class="btn btn-default" href=".">Back</a>
					</div>
				</form>
			</div>
		</div> <!-- /container -->
	</body>
</html>