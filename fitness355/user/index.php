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
	</head>

	<body style="background-color:LightGreen">
		<?php
			Template::navigation("../");
		?>
		
		<div class="container">
    		<div class="row">
    			<h3>Users</h3>
    		</div>
			<div class="row">				
				<table class="table table-striped table-bordered" style="background-color:lightgrey">
					<thead>
						<tr>
							<th style="min-width:275px">Actions</th>
							<th>Name</th>
							<th>Username</th>
							<th>Mobile Number</th>
							<th>Email Address</th>
							<th>Date of Birth</th>
							<th>Gender</th>
							<th>Height</th>
							<th>Weight</th>
							<th>Activity Level</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$pdo = Database::connect();
							$sql = 'SELECT *
							        FROM User
									ORDER BY LName, FName';
							foreach ($pdo->query($sql) as $row) {
								if ($_SESSION['admin'] == 1 || $_SESSION['user'] == $row['Username']) {
									echo '<tr>';
									echo '    <td width=273>';
									echo '    <a class="btn btn-default" href="read.php?Id='.$row['Id'] . '"><i class="fa fa-newspaper-o"></i> Read</a>';
									echo '    <a class="btn btn-success" href="update.php?Id='.$row['Id'].'"><i class="fa fa-pencil"></i> Update</a>';
									echo '    <a class="btn btn-danger" href="delete.php?Id='.$row['Id'].'"><i class="fa fa-trash"></i> Delete</a>';
									echo '    </td>';
									echo '    <td>' . $row['FName'] . ' ' . $row['LName'] . '</td>';
									echo '    <td>' . $row['Username'] . '</td>';
									echo '    <td>' . $row['Mobile'] . '</td>';
									echo '    <td>' . $row['Email'] . '</td>';
									echo '    <td>' . $row['Birth'] . '</td>';
									echo '    <td>' . $row['Gender'] . '</td>';
									echo '    <td>' . $row['Height'] . '</td>';
									echo '    <td>' . $row['Weight'] . '</td>';
									echo '    <td>' . $row['ActivityLevel'] . '</td>';
									echo '</tr>';
								}
							}
							Database::disconnect();
						?>
					</tbody>
				</table>
			</div>
			<p>
				<a href="create.php" class="btn btn-primary"><i class="fa fa-plus fa-lg"></i> Create New</a>
			</p>
		</div> <!-- /container -->
	</body>
</html>