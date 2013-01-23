<?php
	require_once("../db.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
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
	
	#stats
	{
		margin:auto;
		text-align:center;
		margin-bottom: 40px;
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
		if ( $placements["p1"] != $seniorOptOutID )
		{
			echo "<div class=\"datablock ".($cur>($total/2)?"right":"left")."\">";
			echo $student['id']." - ".$student['first']." ".$student['last']." - HR: ".$student['homeroom']."<br>\n";
			for ( $i = 1; $i < 4; $i++ )
			{
				$career = $database->getCareer($placements["p".$i]);
				echo $i." - ".$career['location']." - ".$career['name']."<br>\n";
			}
			echo "<br></div>\n";
			$cur++;
		}
	}
}
else if ( $_GET['by'] == "career" )
{
	$stats = $database->getStatistics();
	echo "<div id=\"stats\">Statistics:<br>\n";
	foreach ( $stats as $stat )
	{
		echo $stat['name']." - ".$stat['value']."<br>\n";
	}
	echo "</div>";
		
	$careers = $database->getCareers();
	foreach ( $careers as $career)
	{
		if ( $career['id'] != $assemblyID )
		{
			for ( $i = 0; $i < 3; $i++ )
			{
				if ( $career['id'] == $seniorOptOutID && $i != 0 )
					break;
				$students = $database->getStudentsIn($career['id'], $i);
				$num = count($students);
				?>
				<table border="1" class="datablock">
				<tr>
					<td colspan="100%" class="title"><?php echo "<strong>".$career['name']." - Block ".($i+1)." - ".$num." students</strong><br>\n"; ?></td>
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
