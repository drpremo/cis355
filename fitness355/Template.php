<?php
	class Template {
		
		public function sources($relation) {
			echo '<link href = "' . $relation . 'css/bootstrap.css" rel="stylesheet" type="text/css"/>';
			echo '<link href = "' . $relation . 'css/modern-business.css" rel="stylesheet" type="text/css"/>';
			echo '<link href = "http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">';
			echo '<link href = "' . $relation . 'css/toastr.css" rel="stylesheet" type="text/css"/>';
			
			echo '<script src = "' . $relation . 'js/jquery.js"></script>';
			echo '<script src = "' . $relation . 'js/bootstrap.js"></script>';
			echo '<script src = "' . $relation . 'js/toastr.js"></script>';
		}
		
		public function navigation($relation) {
			if ($_SESSION['showmessage'] > 0) {
				echo '<script>';
				echo '    toastr["info"]("' . $_SESSION['message'] . '");';
				echo '</script>';
				$_SESSION['showmessage'] -= 1;
			}
			echo '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">';
			echo '    <div class="container">';
			/* Brand and toggle get grouped for better mobile display */
			echo '        <div class="navbar-header">';
			echo '            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">';
			echo '                <span class="sr-only">Toggle navigation</span>';
			echo '                <span class="icon-bar"></span>';
			echo '                <span class="icon-bar"></span>';
			echo '                <span class="icon-bar"></span>';
			echo '            </button>';
			echo '            <a class="navbar-brand" href="' . $relation . 'index.php">Fitness355</a>';
			echo '        </div>';
			/* Collect the nav links, forms, and other content for toggling */
			echo '        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
			echo '            <ul class="nav navbar-nav navbar-left">';
			echo '                <li><a href="' . $relation . 'user/index.php">Users</a></li>';
			echo '                <li><a href="' . $relation . 'activity/index.php">Activities</a></li>';
			echo '                <li><a href="' . $relation . 'event/index.php">Events</a></li>';
			echo '            </ul>';
			echo '            <ul class="nav navbar-nav navbar-right">';
			if (empty($_SESSION['user'])) {
				echo '            <li><a href="' . $relation . 'login.php">Log In</a></li>';
				echo '            <li><a href="' . $relation . 'register.php">Register</a></li>';
			} else {
				$username = $_SESSION['user'];
				
				require_once 'Database.php';
				$pdo = Database::connect();
				$sql = "SELECT IdU
						FROM User
						WHERE Username = '$username'";
				foreach ($pdo -> query($sql) as $row) { $result = $row['IdU']; }
				
				echo '            <li><a href="' . $relation . 'user/update.php?Id=' . $result . '">' . $_SESSION['user'] . '</a></li>';
				echo '            <li><a href="' . $relation . 'logout.php">Logout</a></li>';
			}
			echo '            </ul>';
			echo '        </div>';
			/* navbar-collapse */
			echo '    </div>';
			/* container */
			echo '</nav>';
		}
	}
?>