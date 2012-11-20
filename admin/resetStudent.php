<?php
require_once "../db.php";
if ( array_key_exists("id", $_POST) )
{
	$database->removeStudent($_POST['id']);
	$database->clearStudentChoices($_POST['id']);
	$database->clearStudentPlacement($_POST['id']);
	
	echo "<span class='important'>Deleted student ".$_POST['id']."</span> <br>";
}
?>

<form>
	<input id="deleteButton" type="text" onkeypress="return validateKeypress(event,'num',999)" name="id" placeholder="ID">
	<button type='button' onclick='deleteStudent()'>Reset</button>
</form>