<?php
require_once "../db.php";

if ( array_key_exists("id", $_POST) )
	$database->updateStudentPlacement($_POST['id'], $_POST['p1'], $_POST['p2'], $_POST['p3']);


$students = $database->getStudents();
$careers = $database->getCareers();
?>
<table id="info" border="1">
	<tr>
		<td colspan="100%"><center><strong>Student Info</strong></center></td>
	</tr>
			
	<tr>
		<td><strong>ID</strong></td>
		<td><strong>Block #1</strong></td>
		<td><strong>Block #2</strong></td>
		<td><strong>Block #3</strong></td>
	</tr>
	<?php
	foreach ( $students as $student )
	{
		$placements = $database->getStudentPlacement($student['id']);
	?>
	<tr>
		<td><?php echo $student['id']; ?></td>
		<td><?php echo $careers[$placements['p1']]['name']; ?></td>
		<td><?php echo $careers[$placements['p2']]['name']; ?></td>
		<td><?php echo $careers[$placements['p3']]['name']; ?></td>
	</tr>
	<?php
	}
	?>
</table>