<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php
			session_start();
			if (empty($_SESSION['user'])) {
				echo '<title>Fitness355 - Register</title>';
			} else {
				header("location:user/");
			}
			require 'Template.php';
			Template::sources("");
			
			if (isset($_POST['register_btn'])) {
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = md5($_POST['password']);
				$password2 = md5($_POST['password2']);
				
				if ($password == $password2) {
					require 'Database.php';
					// connect to database
					$pdo = Database::connect();
					$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					// Create user
					$sql = 'INSERT
							INTO User (Username, Password, Email)
							VALUES(?, ?, ?)';			
					$q = $pdo -> prepare($sql);
					$q -> execute(array($username, $password, $email));
					Database::disconnect();
					
					session_start();
					$_SESSION['user'] = $username;
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = 'Registered as ' . $_SESSION['user'];
					header('location:user/'); // redirect to user page
				} else {
					session_start();
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = "Passwords do not match.";
					header('location:register.php');
				}
			}
		?>
	</head>
	
	<body <?php echo Template::$bg;?>>
		<?php 
			Template::navigation("");
		?>
		
		<div class="container">
			<div class="row">
				<h3>Register, login and logout user php mysql</h3>
			</div>
			<form method="post" action="register.php">
				<table>
					<tr>
						<td width="200px">Username: </td>
						<td><input type="text" name="username" class="textInput"></td>
					</tr>
					<tr>
						<td>Email: </td>
						<td><input type="email" name="email" class="textInput"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" class="textInput"></td>
					</tr>
					<tr>
						<td>Confirm Password: </td>
						<td><input type="password" name="password2" class="textInput"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<br/>
							<input class="btn btn-primary" type="submit" name="register_btn" value="Register"/>
							<a class="btn btn-default" href="login.php">Back</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>