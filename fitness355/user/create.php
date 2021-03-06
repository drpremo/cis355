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
				$EmailError = null;
				
				// keep track post values
				$FName = $_POST['FName'];
				$LName = $_POST['LName'];
				$Username = $_POST['Username'];
				$Password = $_POST['Password'];
				$Password2 = $_POST['Password2'];
				$Mobile = $_POST['Mobile'];
				$Email = $_POST['Email'];
				$Birth = $_POST['Birth'];
				$Gender = $_POST['Gender'];
				$Height = $_POST['Height'];
				$Weight = $_POST['Weight'];
				$ActivityLevel = $_POST['ActivityLevel'];
				
				// validate input
				$valid = true;
				
				if (empty($Username)) {
					$UsernameError = 'Please enter a username';
					$valid = false;
				}
				
				if (empty($Password)) {
					$PasswordError = 'Please enter a passowrd';
					$valid = false;
				} else if ($Password != $Password2) {
					$PasswordError = 'Passwords do not match';
					$valid = false;
				}
				
				if (empty($Email)) {
					$EmailError = 'Please enter an email address';
					$valid = false;
				} else if ( !filter_var($Email,FILTER_VALIDATE_EMAIL) ) {
					$EmailError = 'Please enter a valid email address';
					$valid = false;
				}
				
				// insert data
				if ($valid) {
					require '../Database.php';
					$pdo = Database::connect();
					$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// Create user
					$sql = 'INSERT
							INTO User (FName, LName, Username, Password, Mobile, Email, Birth, Gender, Height, Weight, ActivityLevel)
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
					$q = $pdo -> prepare($sql);
					$q -> execute(array($FName, $LName, $Username, $Password, $Mobile, $Email, $Birth, $Gender, $Height, $Weight, $ActivityLevel));
					Database::disconnect();
					session_start();
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = "Created user " . $FName . " " . $LName;
					header("Location: .");
				}
			}
		?>
	</head>

	<body <?php echo Template::$bg;?>>
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">
			<div class="span10 offset1">
				<div class="row">
					<h3 style="color:white;">Create a User</h3>
				</div>				
				<form class="form-horizontal" action="create.php" method="post">
					<div class="row">
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
							<div class="control-group <?php echo !empty($FNameError)?'error':'';?>">
								<label class="control-label">First Name</label>
								<div class="controls">
									<input name="FName" type="text" placeholder="First Name" value="<?php echo !empty($FName)?$FName:'';?>">
									<?php if (!empty($FNameError)): ?>
										<span class="help-inline"><?php echo $FNameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($LNameError)?'error':'';?>">
								<label class="control-label">Last Name</label>
								<div class="controls">
									<input name="LName" type="text" placeholder="Last Name" value="<?php echo !empty($LName)?$LName:'';?>">
									<?php if (!empty($LNameError)): ?>
										<span class="help-inline"><?php echo $LNameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($UsernameError)?'error':'';?>">
								<label class="control-label">Username</label>
								<div class="controls">
									<input name="Username" type="text" placeholder="Username" value="<?php echo !empty($Username)?$Username:'';?>">
									<?php if (!empty($UsernameError)): ?>
										<span class="help-inline"><?php echo $UsernameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($PasswordError)?'error':'';?>">
								<label class="control-label">Password</label>
								<div class="controls">
									<input name="Password" type="password" placeholder="Password" value="<?php echo !empty($Password)?$Password:'';?>">
									<?php if (!empty($PasswordError)): ?>
										<span class="help-inline"><?php echo $PasswordError;?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Confirm Password</label>
								<div class="controls">
									<input name="Password2" type="password" placeholder="Confirm Password" value="<?php echo !empty($Password2)?$Password2:'';?>">
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($MobileError)?'error':'';?>">
								<label class="control-label">Mobile Number</label>
								<div class="controls">
									<input name="Mobile" type="text" placeholder="Mobile Number" value="<?php echo !empty($Mobile)?$Mobile:'';?>">
									<?php if (!empty($MobileError)): ?>
										<span class="help-inline"><?php echo $MobileError;?></span>
									<?php endif;?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($EmailError)?'error':'';?>">
								<label class="control-label">Email Address</label>
								<div class="controls">
									<input name="Email" type="text" placeholder="Email Address" value="<?php echo !empty($Email)?$Email:'';?>">
									<?php if (!empty($EmailError)): ?>
										<span class="help-inline"><?php echo $EmailError;?></span>
									<?php endif;?>
								</div>
							</div>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
						</div>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
							<div class="control-group <?php echo !empty($BirthError)?'error':'';?>">
								<label class="control-label">Date of Birth</label>
								<div class="controls">
									<input name="Birth" type="text" placeholder="Date of Birth" value="<?php echo !empty($Birth)?$Birth:'';?>">
									<?php if (!empty($BirthError)): ?>
										<span class="help-inline"><?php echo $BirthError;?></span>
									<?php endif;?>
								</div>
							</div>
						
							<div class="control-group <?php echo !empty($GenderError)?'error':'';?>">
								<label class="control-label">Gender</label>
								<div class="controls">
									<input name="Gender" type="text" placeholder="Gender" value="<?php echo !empty($Gender)?$Gender:'';?>">
									<?php if (!empty($GenderError)): ?>
										<span class="help-inline"><?php echo $GenderError;?></span>
									<?php endif;?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($HeightError)?'error':'';?>">
								<label class="control-label">Height</label>
								<div class="controls">
									<input name="Height" type="text" placeholder="Height" value="<?php echo !empty($Height)?$Height:'';?>">
									<?php if (!empty($HeightError)): ?>
										<span class="help-inline"><?php echo $HeightError;?></span>
									<?php endif;?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($WeightError)?'error':'';?>">
								<label class="control-label">Weight</label>
								<div class="controls">
									<input name="Weight" type="text" placeholder="Weight" value="<?php echo !empty($Weight)?$Weight:'';?>">
									<?php if (!empty($WeightError)): ?>
										<span class="help-inline"><?php echo $WeightError;?></span>
									<?php endif;?>
								</div>
							</div>
							
							<div class="control-group <?php echo !empty($ActivityLevelError)?'error':'';?>">
								<label class="control-label">Activity Level</label>
								<div class="controls">
									<input name="ActivityLevel" type="text" placeholder="Activity Level" value="<?php echo !empty($ActivityLevel)?$ActivityLevel:'';?>">
									<?php if (!empty($ActivityLevelError)): ?>
										<span class="help-inline"><?php echo $ActivityLevelError;?></span>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
					<hr/>
					<div class="form-actions">
						<br/>
						<button class="btn btn-success" type="submit">Create</button>
						<a class="btn btn-default" href=".">Back</a>
					</div>
				</form>
			</div>
		</div> <!-- /container -->
	</body>
</html>