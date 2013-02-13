<?php
/*
	Career Day Sorting - Career Day Signup and Scheduling system
    Copyright (C) 2013 Andrew Lobos and Benjamin Thomas

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
