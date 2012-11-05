<?php
require_once("db.php");

if ( $database->addStudent($_POST['id'], $_POST['first'], $_POST['last'], $_POST['grade'], $_POST['homeroom']) )
{
	if ( $_POST['isSeniorOptOut'] == "true" )
	{
		if ( !$database->setStudentPlacement($_POST['id'], $seniorOptOutID, $seniorOptOutID, $seniorOptOutID) )
			die("fail");
	}
	else if ( !$database->setStudentChoices($_POST['id'], $_POST['c1'], $_POST['c2'], $_POST['c3'], $_POST['c4']) )
		die("fail");
}
else
	die("fail");
?>
