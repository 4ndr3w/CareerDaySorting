<?php
require_once "../db.php";

switch ($_POST['action'])
{
	case 'add':
		$database->addCareer($_POST['name'], $_POST['location'], $_POST['maxStudents']);
	break;
	
	case 'del':
		$database->removeCareer($_POST['id']);
	break;
}


$careers = $database->getCareers(false);
$careersSortPivot = array();

foreach ( $careers as $k=>$v )
{
	$careersSortPivot[$k] = $v['name'];
}

array_multisort($careersSortPivot, SORT_ASC, $careers);
?>
<h3>Add a Career</h3>
<form>
	<input type="hidden" name="action" value="add">
	<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
	<td colspan="4"><input type="text" id="name" class="tableInput" placeholder="Name">
	<input type="text" id="location" class="tableInput" placeholder="Location">
	<input type="text" id="maxStudents" class="tableInput" placeholder="Student Limit">
	<button type="button" onclick="addCareer()">Add</button></td>
</form>
<a href="importCareers.php">Import from CSV</a>
<br>
<br>
<a href="manageHomerooms.php">Manage Homerooms</a>

<h3>Current List of Careers</h3>
<table class="centered" id="table-Careers">
		<thead>
			<tr>
				
				<th><span class="columnHeader">Name</span></th>
				<th><span class="columnHeader">Location</span></th>
				<th><span class="columnHeader"># Signed Up</span></th>
				<th><span class="columnHeader">Limit</span></th>
				<th><span class="columnHeader">Actions</span></th>
			<tr>
		</thead>
		<tbody>
<?php
foreach ( $careers as $career )
{
?>
	<tr>
		<form>
			<input type="hidden" name="action" value="del">
			<input type="hidden" name="id" value="<?php echo $career['id']; ?>">
			<td><?php echo $career['name']; ?></td>
			<td><?php echo $career['location']; ?></td>
			<td><?php echo $database->getNumberOfStudentsSignedUpForCareer($career['id']); ?>
			<td><?php echo $career['maxStudents']; ?></td>
			<td><button type="button" onclick="deleteCareer(<?php echo $career['id']; ?>)">Delete</button></td>
		</form>
	</tr>
<?php
}			
?>		
	</tbody>
</table>
