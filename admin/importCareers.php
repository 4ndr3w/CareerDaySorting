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

require_once("../db.php");

if ( array_key_exists("uploadedfile", $_FILES) )
{
	$data = @file_get_contents($_FILES['uploadedfile']['tmp_name']);
	if ( $data )
	{
		$lines = explode("\n", $data);
		foreach ( $lines as $line )
		{
			$values = explode(",", $line);
			if ( !$database->addCareer($values[0], $values[1], $values[2], $values[3]) )
				echo "Line: ".$line." failed to import ".mysql_error()."<br>\n";
			else
				echo "Added ID ".mysql_insert_id()."<br>\n";
		}
	}
	else
	{
		echo "Upload failed.\n";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Import Careers</title>
	<link rel="stylesheet" href="admin.css">
</head>
<body>
	<div id="container">
		<br><br>
		<form enctype="multipart/form-data" action="" method="POST">
			Choose a CSV file to upload: <input name="uploadedfile" type="file" /><br />
			<input type="submit" value="Upload File" />
		</form>
		<br>
		The format of the CSV file should be the following, with no column headers:<br>
		name,location,maxStudents,group<br>
		<br>
		Name and Location are strings<br>
		maxStudents and group are integers<br>
		<br>
		<a href="index.html">Back</a>
	</div>
</body>
</html>