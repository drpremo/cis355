<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<?php
			session_start();
			if (empty($_SESSION['user'])) {
				echo '<title>Fitness355 - Home Page</title>';
			} else {
				echo '<title>Fitness355 - ' . $_SESSION['user'] . '</title>';
			}
			require 'Template.php';
			Template::sources("");
		?>
	</head>
	
	<body <?php echo Template::$bg;?>>
		<?php 
			Template::navigation("");
		?>
		
		<div class="container">
			<div class="row">
				<h1>Welcome to Fitness355</h1>
				<?php
					if (empty($_SESSION['user'])) {
						echo '<h4>Please <a href="login.php">log in</a> or <a href="register.php">register</a> to access this site.</h4>';
					} else {
						echo '<h4>View <a href="user/">users</a>, <a href="activity/">activities</a>, or <a href="event/">events</a>.</h4>';
					}
				?>
			</div>
		</div>
   	</body>
</html>