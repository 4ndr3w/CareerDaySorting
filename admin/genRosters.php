<?php
require_once("../db.php");
$careers = $database->getCareers();

?>

<html>
	<head>
		<title>Rosters</title>
		<link rel="stylesheet" href="admin.css">
	</head>

	<body>
	<center>
	<?php
	foreach ( $careers as $career )
	{
		if ( $career['id'] != $assemblyID )
		{
			for ( $i = 0; $i < 3; $i++ )
			{
				$students = $database->getStudentsIn($career['id'], $i);
				?>
				<table class="schedule" border="1">
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
	?>

	<table class="schedule" border="1">
		<tr>
			<td colspan="100%" class="title"><strong>Sorting Statistics</strong></td>
		</tr>
	
		<?php
		$stats = $database->getStatistics();
		foreach ( $stats as $stat )
		{
		?>
		<tr>
			<td><?php echo $stat['name']; ?></td>
			<td><?php echo $stat['value']; ?></td>
		</td>
		<?php
		}
		?>
	</table>
	</center>
</body>
</html>
