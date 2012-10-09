<?php
require_once("db.php");

$db = new Database();
$notice = "";
if ( array_key_exists("submit", $_POST) )
{
	$result = $db->addStudent($_POST['id'], $_POST['first'], $_POST['last'], $_POST['grade'], $_POST['homeroom']);
	echo mysql_error();
	if ( $result )
	{
		if ( !$db->setStudentChoices($_POST['id'], $_POST['c1'], $_POST['c2'], $_POST['c3'], $_POST['c4']) )
			$notice = "Please choose a unique perference for all four choices.";
		else
			$notice = "Success!";
	}
	else
	{
		$notice = "Invalid Student Data";
	}
}

$careers = $db->getCareers();


if ( !empty($notice) )
{
	echo $notice."<br><br>";
	if ( $notice != "Success!")
	{
		$database->resetStudent($_POST['id']);
	}
}
?>

<form method="post" action="">
	Student ID#: <input type="text" name="id"><br>
	First Name: <input type="text" name="first"><br>
	Last Name: <input type="text" name="last"><br>
	Homeroom #: <input type="text" name="homeroom"><br>
	Grade: <select name="grade">
		<option value="0" selected="selected" disabled="disabled">Select One</option>
		<option value="9">Freshman</option>
		<option value="10">Sophomore</option>
		<option value="11">Junior</option>
		<option value="12">Senior</option>
	</select><br><br>
	
	Selections: <br>
	
	<?php 
	for ( $i = 0; $i < 4; $i++ )
	{
		$n = $i+1;
	?>
		Choice <?php echo $n; ?>: 
		<select name="c<?php echo $n; ?>">
			<option value="0" selected="selected" disabled="disabled">Select One</option>
			<?php 
			foreach ( $careers as $career )
			{
			?>
				<option value="<?php echo $career['id']; ?>"><?php echo $career['name']; ?></option>
			<?php
			}
			?>
		</select><br>
	<?php
	}
	?>
<br>

	<input type="submit" name="submit" value="Signup">
</form>