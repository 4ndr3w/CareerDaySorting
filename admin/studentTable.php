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

require_once("../db.php");
$students = $database->getStudents();
$pivot = array();
foreach ( $students as $k=>$v )
{
	$pivot[$k] = $v['homeroom'];
}

array_multisort($pivot, SORT_DESC, $students);

?>
<br>
<h3>Current List of Students</h3>
<a href="findMissing.php">Find Missing Students</a><br>
Printable lists: <a href="printables.php?by=student">by Student</a> - <a href="printables.php?by=career">by Career</a><br>
<table id="table-Students">

<?php
foreach ( $students as $student )
{
	$placements = $database->getStudentPlacement($student['id']);
	echo "<th colspan='3'>ID: ".$student['id']." ".$student['first']." ".$student['last']." HR: ".$student['homeroom']."</th><tr>";
	for ( $i = 1; $i < 4; $i++ )
	{
		$career = $database->getCareer($placements["p".$i]);
		echo "<td colspan='1'>".$career['name']." - ".$career['location']."</td>";
	}
	echo "</tr></tr>";
?>
<?php
}
?>
</table>
