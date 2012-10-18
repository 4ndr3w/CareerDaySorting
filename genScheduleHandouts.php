<?php
require_once("db.php");
$students = $database->getStudents();

function sortHelper($a, $b)
{
	if ($a['homeroom'] == $b['homeroom']) {
		return 0;
	}
	return ($a['homeroom'] < $b['homeroom']) ? -1 : 1;
}


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
	-webkit-column-break-inside: avoid;
	-moz-column-break-inside: avoid;
	column-break-inside: avoid;
	page-break-after:avoid;
	page-break-before:avoid;
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
	echo "ID: ".$student['id']." - ".$student['first']." ".$student['last']." - HR: ".$student['homeroom']."<br>";
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


