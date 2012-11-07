<?php
require_once "../db.php";
?>
<html>
<head>
	<title>Admin</title>
</head>
<body>
	<center>
		<h1>Admin</h1>
		<hr>
		<br>
		<br>
		<h2>Manage...</h2>
			<a href="manageCareers.php">careers</a>
		<h2>Sort...</h2>
			<a href="autosort.php">automatically</a> - <a href="resolveBlanks.php">manually</a>
		<h2>Schedule...</h2>
			<a href="genScheduleHandouts.php">by student</a> - <a href="genScheduleHandouts.php">by career block</a>
		<h2>Reset...</h2>
			<a href="resetStudent.php">a student</a>			
		<br>
		<br>
			<?php
			if ( $database->hasStatistics() )
			{
				$stats = $database->getStatistics();
				echo "<strong>Statistics from most recent sort:</strong><br>\n";
				foreach ( $stats as $stat )
				{
					echo $stat['name']." - ".$stat['value']."<br>\n";
				}
			}
			?>

	</center>
</body>
</html>