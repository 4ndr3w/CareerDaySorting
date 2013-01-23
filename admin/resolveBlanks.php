<?php
require_once "../db.php";

if ( array_key_exists("id", $_POST) )
{
	if ( $database->getStudentPlacement($_POST['id']) )
		$database->updateStudentPlacement($_POST['id'], $_POST['p1'], $_POST['p2'], $_POST['p3']);
	else
		$database->setStudentPlacement($_POST['id'], $_POST['p1'], $_POST['p2'], $_POST['p3']);
}

if ( array_key_exists("specific", $_GET) && array_key_exists("id", $_POST) )
	header("Location: index.html");


$students = array();
if ( array_key_exists("specific", $_GET) )
{
	$students = array($database->getStudent($_GET['specific']));
}
else
{	
	$students = $database->getStudents();
}

$careers = $database->getCareers();
$student = array();
$placements = array();
$choices = array();
foreach ( $students as $_student )
{
	$studentIncomplete = false;
	$_placements = $database->getStudentPlacement($_student['id']);
	if ( empty($_placements) )
	{
		for ( $i = 0; $i < 3; $i++ )
			$placements[0] = 0;
		$studentIncomplete = true;
	}
	else
	{
		foreach ( $_placements as $p )
		{
			if ( $p == 0 )
			{
				$studentIncomplete = true;
				break;
			}
		}
	}
	if ( array_key_exists("specific", $_GET) )
		$studentIncomplete = true;
	if ( $studentIncomplete )
	{
		$student = $_student;
		$placements = $_placements;
		$choices = $database->getStudentChoices($student['id']);
		break;
	}
}

if ( empty($student) )
	die("All students have been successfuly sorted<br>\n<a href=\"index.html\">Back</a>");


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Manual Sort</title>
		<link rel="stylesheet" href="admin.css">
	</head>
	<body>
			<br><br><br>
		<table id="info" border="1">
			<tr>
				<td colspan="100%" class="columnHeader">Student Info</td>
			</tr>
			
			<tr>
				<td class="columnHeader">ID</td>
				<td><?php echo $student['id']; ?></td>
			</tr>
		
			<tr>
				<td class="columnHeader">Name</td>
				<td><?php echo $student['first']." ".$student['last']; ?></td>
			</tr>
		
			<tr>
				<td class="columnHeader">Grade</td>
				<td><?php echo $student['grade']; ?></td>
			</tr>
		</table>
		<br>
		
		<table id="choices" border="1">
			<tr>
				<td colspan="100%" class="columnHeader">Choices</td>
			</tr>
			<?php 
			for ($i = 0; $i < 4; $i++ )
			{
				$thisChoice = $choices['s'.($i+1)];
			?>
				<tr>
					<td><span class="bolded"><?php echo $i+1; ?></td>
					<td><?php $c = $database->getCareer($thisChoice); echo $c['name']; ?></td>
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
				<td colspan="100%" class="columnHeader">Schedule</td>
			</tr>
			<tr>
				<td class="columnHeader">Block</td>
				<td class="columnHeader">Career</td>
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
						<option selected="selected" value="<?php echo $assemblyID; ?>">Assembly</option>
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
			<tr>
				<td colspan="100%" class="centered"><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
		</form>
	</body>
</html>
