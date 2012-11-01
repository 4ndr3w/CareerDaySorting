<?php
require_once "db.php";

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
<html>
<head>
	<title>Manage Careers</title>
</head>
<body>
	<table id="info" border="1">
		<tr>
			<td><center><strong>Name</strong></center></td>
			<td><center><strong>Location</strong></center></td>
			<td><center><strong>Limit</strong></center></td>
			<td><center><strong>Actions</strong></center></td>
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
			<td><input type="submit" value="Delete"></td>
		</form>
	</tr>
<?php
}
?>
	<tr>
		<form action="" method="post">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
			<td><input type="text" name="name"></td>
			<td><input type="text" name="location"></td>
			<td><input type="text" name="maxStudents"></td>
			<td><center><input type="submit" value="Add"></center></td>
		</form>
	</tr>
</table>
</body>
</html>