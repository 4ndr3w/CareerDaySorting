<?php
require_once("../db.php");

if ( array_key_exists("action", $_POST) )
{
	switch ( $_POST['action'] )
	{
		case "add":
			$database->addHomeroom($_POST['name']);
			break;
		case "del":
			$database->removeHomeroom($_POST['id']);
			break;
	}
}
$homerooms = $database->getHomerooms();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Homerooms</title>
	<link rel="stylesheet" href="admin.css">
</head>
<body>
	<div id="container">
		<br>
		<a href="index.html">Back</a>
		<br><br>
		<table>
			<thead>
				<tr>
					<th class="columnHeader">Name</th>
					<th class="columnHeader">Actions</th>
				</tr>
			</thead>
			
			<?php foreach ( $homerooms as $homeroom )
			{ 
			?>
			<tr>
				<td><?php echo $homeroom['name']; ?></td>
				<td class="centered">
					<form action="" method="post">
						<input type="hidden" name="id" value="<?php echo $homeroom['id']; ?>">
						<input type="hidden" name="action" value="del">
						<input type="submit" value="Delete">
					</form>
			</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<form action="" method="post">
					<input type="hidden" name="action" value="add">
					<td><input type="text" value="" name="name"></td>
					<td class="centered"><input type="submit" value="Add"></td>
				</form>
			</tr>

		</table>
	</div>
</body>