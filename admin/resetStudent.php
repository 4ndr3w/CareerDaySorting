<?php
require_once "db.php";
if ( array_key_exists("submit", $_POST) )
{
	$database->removeStudent($_POST['id']);
	$database->clearStudentChoices($_POST['id']);
	$database->clearStudentPlacement($_POST['id']);
}
?>

<form method="post" action="">
	ID: <input type="text" name="id"><br>
	<input type="submit" name="submit" value="Reset">
</form>