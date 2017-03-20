<html>
	<head>
	</head>
	<body>
		<div class="header">
			<h1>Register, login and logout user php mysql</h1>
		</div>
	</body>	

	<?php
		if(isset($_SESSION['message'])) {
			echo "<div id='error_msg'>".$_SESSION['message']."</div>";
			unset($_SESSION['message']);
		}
	?>
	
	<h1>Home</h1>
	<div>
		<h4>Welcome <?php echo $_SESSION['username']; ?></h4>
	</div>
	<a href="logout.php">Log Out</a>
</html>
