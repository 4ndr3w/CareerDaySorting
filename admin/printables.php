<?php
	require_once("../db.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Printable Data</title>
	<style type="text/css">
	.datablock
	{
		page-break-inside:avoid;
		width: 50%;
	}

	.left
	{
		float:left;
	}

	.right
	{
		float: right;
	}
	
	table
	{
		margin:auto;
		text-align:center;
	}
	</style>
</head>
<body>
<?php
echo "<a href=\"index.html\">Back</a><br><br>\n";
if ( $_GET['by'] == "student" )
{
	$students = $database->getStudents();

	$pivot = array();
	foreach ( $students as $k=>$v )
	{
		$pivot[$k] = $v['homeroom'];
	}

	array_multisort($pivot, SORT_DESC, $students);
	$total = count($students);
	$cur = 0;
	foreach ( $students as $student)
	{
		$placements = $database->getStudentPlacement($student['id']);

		echo "<div class=\"datablock ".($cur>($total/2)?"right":"left")."\">";
		echo "ID: ".$student['id']." - ".$student['first']." ".$student['last']." - HR: ".$student['homeroom']."<br>\n";
		for ( $i = 1; $i < 4; $i++ )
		{
			$career = $database->getCareer($placements["p".$i]);
			echo $i." - ".$career['name']." - ".$career['location']."<br>\n";
		}
		echo "<br></div>\n";
		$cur++;
	}
}
else if ( $_GET['by'] == "career" )
{
	$careers = $database->getCareers();
	foreach ( $careers as $career)
	{
		if ( $career['id'] != $assemblyID )
		{
			for ( $i = 0; $i < 3; $i++ )
			{
				$students = $database->getStudentsIn($career['id'], $i);
				?>
				<table border="1" class="datablock">
				<tr>
					<td colspan="100%" class="title"><?php echo "<strong>".$career['name']." - Block ".($i+1)."</strong><br>\n"; ?></td>
				</tr>
			
				<tr>
					<td><strong>ID</strong></td>
					<td><strong>Name</strong></td>
				</tr>
				<?php
				foreach ($students as $student)
				{
					$studentInfo = $database->getStudent($student['id']);
				?>
				<tr>
					<td><?php echo $studentInfo['id']; ?></td>
					<td><?php echo $studentInfo['first']." ".$studentInfo['last']; ?></td>
				</tr>
				<?php
				}
				?>
				</table>
				<br><br>
			<?php
			}
		}
	}
}
else
{
	die("Invalid URL");
}
?>
</body>
</html>