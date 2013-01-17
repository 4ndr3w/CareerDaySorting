<?php
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
