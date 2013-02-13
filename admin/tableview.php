<?php
/*
	Career Day Sorting - Career Day Signup and Scheduling system
    Copyright (C) 2013 Andrew Lobos and Benjamin Thomas

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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