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
		
		<div class="container" style="width:88%">
    		<div class="row">
    			<h3>Events</h3>
    		</div>
			<div class="row">
				<table class="table table-striped table-bordered" style="background-color:lightgrey">
					<thead>
						<tr>
							<th>Actions</th>
							<th>User</th>
							<th>Activity</th>
							<th>Date</th>
							<th>Start Time</th>
							<th>End Time</th>
							<th>Workout Duration</th>
							<th>Max Heart Rate</th>
							<th>Distance</th>
							<th>Resistance</th>
							<th>Repititions</th>
							<th>Calories Burned</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$pdo = Database::connect();
							$sql = 'SELECT *
									FROM `Event` AS e
									INNER JOIN `Activity` AS a
									ON e.IdActivity = a.Id
									INNER JOIN `User` AS u
									ON e.IdUser = u.Id
									ORDER BY e.Date, e.StartTime';
							foreach ($pdo->query($sql) as $row) {
								
								echo '<tr>';
								echo '    <td width=273>';
								echo '        <a class="btn btn-default" href="read.php?Id='.$row['Id'] . '"><i class="fa fa-newspaper-o"></i> Read</a>';
								if ($_SESSION['admin'] == 1 || $_SESSION['user'] == $row['Username']) {
									echo '    <a class="btn btn-success" href="update.php?Id='.$row['Id'].'"><i class="fa fa-pencil"></i> Update</a>';
									echo '    <a class="btn btn-danger" href="delete.php?Id='.$row['Id'].'"><i class="fa fa-trash"></i> Delete</a>';
								}
								echo '    </td>';
								echo '    <td>' . $row['FName'] . ' ' . $row['LName'] . '</td>';
								echo '    <td>' . $row['Name'] . '</td>';
								echo '    <td>' . $row['Date'] . '</td>';
								echo '    <td>' . $row['StartTime'] . '</td>';
								echo '    <td>' . $row['EndTime'] . '</td>';
								echo '    <td>' . $row['WorkoutDuration'] . '</td>';
								echo '    <td>' . $row['MaxHeartRate'] . '</td>';
								if ($row['HasDistance'] == 1) {
									echo '<td>' . $row['Distance'] . '</td>';
								} else {
									echo '<td>-</td>';
								}
								if ($row['HasResistance'] == 1) {
									echo '    <td>' . $row['Resistance'] . '</td>';
								} else {
									echo '<td>-</td>';
								}
								if ($row['HasRepititions'] == 1) {
									echo '    <td>' . $row['Repititions'] . '</td>';
								} else {
									echo '<td>-</td>';
								}
								echo '    <td>' . $row['CaloriesBurned'] . '</td>';
								echo '</tr>';
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