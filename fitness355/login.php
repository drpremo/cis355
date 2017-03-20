<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<?php
			session_start();
			require 'Template.php';
			Template::sources("");
			
			if (empty($_SESSION['user'])) {
				echo '<title>Fitness355 - Log In</title>';
			} else {
				header("location:user/");
			}
			
			if (isset($_POST['login_btn'])) {
				$username = $_POST['username'];
				$password = $_POST['password'];
				
				require 'Database.php';
				// connect to database
				$pdo = Database::quick_connect();
				$sql = "SELECT *
						FROM User
						WHERE Username = '$username'
						AND Password = '$password'";
				$result = mysqli_query($pdo, $sql);
				if (mysqli_num_rows($result) >= 1) {
					session_start();
					
					$pdo = Database::connect();
					$sql = "SELECT *
							FROM User
							WHERE Username = '$username'";
					foreach ($pdo -> query($sql) as $row) { $_SESSION['admin'] = $row['IsAdmin']; }
					Database::disconnect();
					
					$_SESSION['user'] = $username;
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = 'Logged in as ' . $_SESSION['user'];
					header("location:user/"); // redirect to user page
				} else {
					$_SESSION['showmessage'] = 2;
					$_SESSION['message'] = 'Invalid username or password.';
					header("location:login.php"); // redirect to user page
				}
	}
		?>
	</head>
	
	<body style="background-color:LightGreen">
		<?php
			Template::navigation("");
		?>
	
		<div class="container">
			<div class="row">
				<h3>Register, login and logout user php mysql</h3>
			</div>
			<form method="post" action="login.php">
				<table>
					<tr>
						<td width="200px">Username: </td>
						<td><input type="text" name="username" class="textInput"></td>
					</tr>
					<tr>
						<td>Password: </td>
						<td><input type="password" name="password" class="textInput"></td>
					</tr>
					
					
					
					
					
					
					
					
					<tr>
						<td></td>
						<td>
							<br/>
							<input class="btn btn-primary" type="submit" name="login_btn" value="Login"/>
							<a class="btn btn-default" href="register.php">Register</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>