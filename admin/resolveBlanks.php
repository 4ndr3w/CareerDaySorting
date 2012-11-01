<?php
require_once "db.php";

if ( array_key_exists("id", $_POST) )
	$database->updateStudentPlacement($_POST['id'], $_POST['p1'], $_POST['p2'], $_POST['p3']);


$students = $database->getStudents();
$careers = $database->getCareers();
$student = array();
$placements = array();
$choices = array();
foreach ( $students as $_student )
{
	$studentIncomplete = false;
	$_placements = $database->getStudentPlacement($_student['id']);
	foreach ( $_placements as $p )
	{
		if ( $p == 0 )
		{
			$studentIncomplete = true;
			break;
		}
	}
	if ( $studentIncomplete )
	{
		$student = $_student;
		$placements = $_placements;
		$choices = $database->getStudentChoices($student['id']);
		break;
	}
}

if ( empty($_student) )
	die("All students have been successfuly sorted");


?>
<html>
	<head>
		<title>Manual Sort</title>
	</head>
	<body>
		<center>
		<table id="info" border="1">
			<tr>
				<td colspan="100%"><center><strong>Student Info</strong></center></td>
			</tr>
			
			<tr>
				<td><strong>ID</strong></td>
				<td><?php echo $student['id']; ?></td>
			</tr>
		
			<tr>
				<td><strong>Name</strong></td>
				<td><?php echo $student['first']." ".$student['last']; ?></td>
			</tr>
		
			<tr>
				<td><strong>Grade</strong></td>
				<td><?php echo $student['grade']; ?></td>
			</tr>
		</table>
		<br>
		
		<table id="choices" border="1">
			<tr>
				<td colspan="100%"><center><strong>Choices</strong></center></td>
			</tr>
			<?php 
			for ($i = 0; $i < 4; $i++ )
			{
				$thisChoice = $choices['s'.($i+1)];
			?>
				<tr>
					<td><strong><?php echo $i+1; ?></strong></td>
					<td><?php echo $careers[$thisChoice]['name']; ?></td>
				</tr>
			<?php
			}
			?>
		</table>
		
		<br>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $student['id']; ?>">
		<table class="schedule" border="1">
			<tr>
				<td colspan="100%"><center><strong>Schedule</strong></center></td>
			</tr>
			<tr>
				<td><center><strong>Block</strong></center></td>
				<td><center><strong>Career</strong></center></td>
			</tr>
			<?php
			for ( $i = 0; $i < 3; $i++ )
			{
				$thisBlock = ($i+1);
			?>
			<tr>
				<td><?php echo $thisBlock; ?></td>
				<td>
					<select name="p<?php echo $thisBlock; ?>">
					<?php 
					if ( $placements['p'.$thisBlock] == $assemblyID ) 
					{ ?>
						<option selected="selected" id="<?php echo $assemblyID; ?>">Assembly</option>
					<?php
					}
					else
					{
						if ( $placements['p'.$thisBlock] == 0 )
						{
						?>
						<option value="0" selected="selected" disabled="disabled">Select One</option> 
						<?php
						}
						
						foreach ( $careers as $career )
						{
							$full = ($database->getNumberOfStudentsInCareer($career['id'], $thisBlock) >= $career['maxStudents']);
							if ( !$full || $placements['p'.$thisBlock] == $career['id'] )
							{
						?>
						<option value="<?php echo $career['id']; ?>" <?php if ( $career['id'] == $placements['p'.$thisBlock] ) echo "selected=\"selected\""; ?>><?php echo $career['name']; if ( $full ) echo " --FULL--"; ?></option>
						<?php
							}
						}
					}
					?>
					</select>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<br>
		<input type="submit" name="submit" value="Submit">
	</form>
	</center>
	</body>
</html>
