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