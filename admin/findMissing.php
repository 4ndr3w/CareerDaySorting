<?php
require_once("../db.php");
$students = $database->getStudents();
$missingList = "";
if ( array_key_exists("uploadedfile", $_FILES) )
{
	$data = @file_get_contents($_FILES['uploadedfile']['tmp_name']);
	if ( $data )
	{
		$lines = explode("\n", $data);
		foreach ( $lines as $line )
		{
			$has = false;
			$data = explode(",", $line);
			foreach ( $students as $student )
			{
				if ( $data[0] == $student['id'] )
				{
					$has = true;
					break;
				}
			}
			if ( !$has )
				$missingList .= "Missing ".$data[0]." ".$data[1]." ".$data[2]."<br>\n";
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
	<title>Missing Students</title>
	<link rel="stylesheet" href="admin.css">
</head>
<body>
	<div id="container">
		<br><br>
		<?php
		echo $missingList;
		?>
		<br>
		<form enctype="multipart/form-data" action="" method="POST">
			Choose a CSV file to use: <input name="uploadedfile" type="file" /><br />
			<input type="submit" value="Upload File" />
		</form>
		<br>
		The format of the CSV file should be the following, with no column headers:<br>
		id,first,last<br>
		<br>
		<br>
		<a href="index.html">Back</a>
	</div>
</body>
</html>