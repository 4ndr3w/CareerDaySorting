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
	$database->setCareerProperties($_POST['id'], $_POST['name'], $_POST['location'], $_POST['limit']);
	header("Location: index.html");
	die();
}
else if ( !array_key_exists("id", $_GET) )
{
	header("Location: index.html");
	die();
}

$career = $database->getCareer($_GET['id']);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Edit Career</title>
		<link rel="stylesheet" href="admin.css">
	</head>
	<body>
			<br><br><br>

		
		<br>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
		<table class="schedule" border="1">
			<tr>
				<td colspan="100%" class="columnHeader">Career ID:  <?php echo $_GET['id']; ?></td>
			</tr>
			<tr>
				<td>Name: </td>
				<td><input type="text" name="name" value="<?php echo $career['name']; ?>"></td>
			</tr>
			<tr>
				<td>Location: </td>
				<td><input type="text" name="location" value="<?php echo $career['location']; ?>"></td>
			</tr>
			<tr>
				<td>Limit: </td>
				<td><input type="text" name="limit" value="<?php echo $career['maxStudents']; ?>"></td>
			</tr>
			<tr>
				<td colspan="100%" class="centered"><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
		</form>
	</body>
</html>
