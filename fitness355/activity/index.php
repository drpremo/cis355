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
		<link href="../css/font-awesome.css" rel="stylesheet" type="text/css"/>
	</head>

	<body style="background-color:LightGreen">
		<?php 
			Template::navigation("../");
		?>
		
		<div class="container">
			<div class="row">
				<h3>Activities</h3>
			</div>
			<div class="row">
				<table class="table table-striped table-bordered" style="background-color:lightgrey">
					<thead>
						<tr>
							<?php if ($_SESSION['admin'] == 1)
								echo '<th style="min-width:275px">Actions</th>';
							?>
							<th>Name</th>
							<th>Intensity</th>
							<th>Has Distance?</th>
							<th>Has Resistance?</th>
							<th>Has Repititions?</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT *
							        FROM Activity
									ORDER BY Name';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								if ($_SESSION['admin'] == 1) {
									echo '<td>';
									echo '    <a class="btn btn-default" href="read.php?Id='.$row['IdA'].'"><i class="fa fa-newspaper-o"></i> Read</a>';
									echo '    <a class="btn btn-success" href="update.php?Id='.$row['IdA'].'"><i class="fa fa-pencil"></i> Update</a>';
									echo '    <a class="btn btn-danger" href="delete.php?Id='.$row['IdA'].'"><i class="fa fa-trash"></i> Delete</a>';
									echo '</td>';
								}
								echo '    <td>' . $row['Name'] . '</td>';
								echo '    <td>' . $row['Intensity'] . '</td>';
								echo '    <td>';
								echo          ($row['HasDistance'])?'Yes':'No';
								echo '    </td>';
								echo '    <td>';
								echo          ($row['HasResistance'])?'Yes':'No';
								echo '    </td>';
								echo '    <td>';
								echo          ($row['HasRepititions'])?'Yes':'No';
								echo '    </td>';
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