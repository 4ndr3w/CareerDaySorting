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

<html>
<head>
<title>Schedule Handouts</title>
<style type="text/css">
.schedule
{
	page-break-inside:avoid;
}
</style>

</head>

<body>
<?php
foreach ( $students as $student )
{
?>
<p class="schedule">
<?php
	$placements = $database->getStudentPlacement($student['id']);
	echo "ID: ".$student['id']." - <strong>".$student['first']." ".$student['last']."</strong> - HR: ".$student['homeroom']."<br>";
	for ( $i = 1; $i < 4; $i++ )
	{
		$career = $database->getCareer($placements["p".$i]);
		echo $career['name']." - ".$career['location']."<br>";
	}
?>
</p>
<?php
}
?>


