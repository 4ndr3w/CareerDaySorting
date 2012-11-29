<?php
require_once("db.php");
$_POST['first'][0] = strtoupper($_POST['first'][0]);
$_POST['last'][0] = strtoupper($_POST['last'][0]);

if ( $database->getStudent($_POST['id']) )
	die("dup");

if ( $database->addStudent($_POST['id'], $_POST['first'], $_POST['last'], $_POST['grade'], $_POST['homeroom']) )
{
	if ( $_POST['isSeniorOptOut'] == "true" )
	{
		if ( !$database->setStudentPlacement($_POST['id'], $seniorOptOutID, $seniorOptOutID, $seniorOptOutID) )
			die("fail");
	}
	else if ( !$database->setStudentChoices($_POST['id'], $_POST['c1'], $_POST['c2'], $_POST['c3'], $_POST['c4']) )
	{
		$student->resetStudent($_POST['id']);
		die("fail");
	}
}
else
	die("fail");
?>
