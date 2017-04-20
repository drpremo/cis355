<?php
	$id = null;
	if (!empty($_GET['Id'])) {
		$id = $_REQUEST['Id'];
	}
	if ($id == null) {
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
				header("Location:../login.php");
			} else {
				echo '<title>Fitness355 - ' . $_SESSION['user'] . ' - Update User</title>';
			}
			require '../Template.php';
			Template::sources("../");
			
			if (!empty($_POST)) {
				// keep track validation errors
				$FNameError = null;
				$LNameError = null;
				$MobileError = null;
				$EmailError = null;
				$BirthError = null;
				$GenderError = null;
				$HeightError = null;
				$WeightError = null;
				$ActivityLevelError = null;
				
				// keep track post values
				$FName = $_POST['FName'];
				$LName = $_POST['LName'];
				$Mobile = $_POST['Mobile'];
				$Email = $_POST['Email'];
				$Birth = $_POST['Birth'];
				$Gender = $_POST['Gender'];
				$Height = $_POST['Height'];
				$Weight = $_POST['Weight'];
				$ActivityLevel = $_POST['ActivityLevel'];
				
				// validate input
				$valid = true;
				
				if (empty($Email)) {
					$EmailError = 'Please enter an email address';
					$valid = false;
				} else if ( !filter_var($Email,FILTER_VALIDATE_EMAIL) ) {
					$EmailError = 'Please enter a valid email address';
					$valid = false;
				}
				
				// update data
				if ($valid) {
					require '../Database.php';
					$pdo = Database::connect();
					$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sql = "UPDATE User set FName = ?, LName = ?, Mobile = ?, Email = ?, Birth = ?, Gender = ?, Height = ?, Weight = ?, ActivityLevel = ?
							WHERE IdU = ?";
					$q = $pdo -> prepare($sql);
					$q -> execute(array($FName, $LName, $Mobile, $Email, $Birth, $Gender, $Height, $Weight, $ActivityLevel, $id));
					Database::disconnect();
					
					if($_FILES['Image']['size']>0 && $_FILES['Image']['size'] < 2000000) {
						// create variables for $_FILES elements
						$filename = $_FILES['Image']['name'];
						$tempname = $_FILES['Image']['tmp_name'];
						$filesize = $_FILES['Image']['size'];
						$filetype = $_FILES['Image']['type'];
											
						// make sure slashes are correct
						$filetype = (get_magic_quotes_gpc() == 0
									? mysql_real_escape_string($filetype) 
									: mysql_real_escape_string(stripslashes($_FILES['Image'])));
						
						//open the file that was uploaded
						$fp = fopen($tempname, 'r');
						$content = fread($fp, filesize($tempname));
						
						$content = str_replace('\0', ' ', $content);
						
						fclose($fp);
					
						if (!get_magic_quotes_gpc()) {
							$filename = addslashes($filename);
						}
						
						// connect to database
						$pdo = Database::connect();
						$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						
						// if connection was successful, insert the contents into the content (blob) field.
						$sql = "UPDATE User set Image = ?
								WHERE IdU = ?";
						$q = $pdo -> prepare($sql);
						$q -> execute(array($content, $id));
						Database::disconnect();
					}
					session_start();
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = 'Updated user ' . $FName . " " . $LName;
					header("Location: .");
				}
			} else {
				require '../Database.php';
				$pdo = Database::connect();
				$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "SELECT *
						FROM User
						WHERE IdU = ?";
				$q = $pdo->prepare($sql);
				$q->execute(array($id));
				$data = $q->fetch(PDO::FETCH_ASSOC);
				$FName = $data['FName'];
				$LName = $data['LName'];
				$Mobile = $data['Mobile'];
				$Email = $data['Email'];
				$Birth = $data['Birth'];
				$Gender = $data['Gender'];
				$Height = $data['Height'];
				$Weight = $data['Weight'];
				$ActivityLevel = $data['ActivityLevel'];
				Database::disconnect();
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
					<h3>Update a User</h3>
				</div>
		
				<form class="form-horizontal" action="update.php?Id=<?php echo $id;?>" enctype="multipart/form-data" method="post">
					<div class="row">
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
							<div class="control-group <?php echo !empty($FNameError)?'error':'';?>">
								<label class="control-label">First Name</label>
								<div class="controls">
									<input name="FName" type="text"  placeholder="First Name" value="<?php echo !empty($FName)?$FName:'';?>">
									<?php if (!empty($FNameError)): ?>
										<span class="help-inline"><?php echo $FNameError;?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="control-group <?php echo !empty($LNameError)?'error':'';?>">
								<label class="control-label">Last Name</label>
								<div class="controls">
									<input name="LName" type="text"  placeholder="Last Name" value="<?php echo !empty($LName)?$LName:'';?>">
									<?php if (!empty($LNameError)): ?>
										<span class="help-inline"><?php echo $LNameError;?></span>
									<?php endif; ?>
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
							<div class="control-group <?php echo !empty($MobileError)?'error':'';?>">
								<label class="control-label">Mobile Number</label>
								<div class="controls">
									<input name="Mobile" type="text"  placeholder="Mobile Number" value="<?php echo !empty($Mobile)?$Mobile:'';?>">
									<?php if (!empty($MobileError)): ?>
										<span class="help-inline"><?php echo $MobileError;?></span>
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
									<input name="Birth" type="text"  placeholder="Date of Birth" value="<?php echo !empty($Birth)?$Birth:'';?>">
									<?php if (!empty($BirthError)): ?>
										<span class="help-inline"><?php echo $BirthError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="control-group <?php echo !empty($GenderError)?'error':'';?>">
								<label class="control-label">Gender</label>
								<div class="controls">
									<input name="Gender" type="text"  placeholder="Gender" value="<?php echo !empty($Gender)?$Gender:'';?>">
									<?php if (!empty($GenderError)): ?>
										<span class="help-inline"><?php echo $GenderError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="control-group <?php echo !empty($HeightError)?'error':'';?>">
								<label class="control-label">Height</label>
								<div class="controls">
									<input name="Height" type="text"  placeholder="Height" value="<?php echo !empty($Height)?$Height:'';?>">
									<?php if (!empty($HeightError)): ?>
										<span class="help-inline"><?php echo $HeightError;?></span>
									<?php endif;?>
								</div>
							</div>
							<div class="control-group <?php echo !empty($WeightError)?'error':'';?>">
								<label class="control-label">Weight</label>
								<div class="controls">
									<input name="Weight" type="text"  placeholder="Weight" value="<?php echo !empty($Weight)?$Weight:'';?>">
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
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
						</div>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
							<div class="control-group">
								<div class="controls">
									<input name="Image" id="Image" type="file" value="Upload Image">
								</div>
							</div>
						</div>
					</div>
					<hr/>
					<div class="form-actions">
						<br/>
						<button type="submit" class="btn btn-success">Update</button>
						<a class="btn btn-default" href=".">Back</a>
					</div>
				</form>
			</div>
		</div> <!-- /container -->
	</body>
</html>