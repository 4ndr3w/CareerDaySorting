<?php
require_once "../db.php";

switch ($_POST['action'])
{
	case 'add':
		$database->addCareer($_POST['name'], $_POST['location'], $_POST['maxStudents']);
	break;
	
	case 'del':
		$database->removeCareer($_POST['id']);
	break;
}


$careers = $database->getCareers();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Manage Careers</title>
		<link rel="stylesheet" href="admin.css" />
	</head>
	
	<body>
		<table class="centered" id="info" border="1">
			<tr>
				<td><span class="columnHeader">Name</span></td>
				<td><span class="columnHeader">Location</span></td>
				<td><span class="columnHeader">Limit</span></td>
				<td><span class="columnHeader">Actions</span></td>
			</tr>
	<?php
	foreach ( $careers as $career )
	{
	?>
		<tr>
			<form action="" method="post">
				<input type="hidden" name="action" value="del">
				<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
				<td><?php echo $career['name']; ?></td>
				<td><?php echo $career['location']; ?></td>
				<td><?php echo $career['maxStudents']; ?></td>
				<td><input type="submit" value="Delete" class="tableInput"></td>
			</form>
		</tr>
	<?php
	}
	?>
		<tr>
			<form action="" method="post">
				<input type="hidden" name="action" value="add">
				<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
				<td><input type="text" name="name" class="tableInput"></td>
				<td><input type="text" name="location" class="tableInput"></td>
				<td><input type="text" name="maxStudents" class="tableInput"></td>
				<td><input type="submit" value="Add" class="tableInput"></td>
			</form>
		</tr>
	</table>
	<br>
	<a href="importCareers.php">Import careers from CSV</a>
	</body>
</html>