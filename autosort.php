<?php
require_once "db.php";

class Placement
{
	function __construct($carrerID, $weight, $isStatic = false)
	{
		$this->id = $careerID;
		$this->weight = $weight;
		$this->isStatic = $isStatic;
	}
	
	function canReplace($otherPlacement, $threshold)
	{
		if ( $this->otherPlacement->isStatic )
			return false;
		if ( $this->weight-$threshold > $otherPlacement->weight )
			return false;
		return true;
	}
}

class Choice
{
	function __construct($careerID, $weight)
	{
		$this->id = $careerID;
		$this->weight = $weight;
	}

	function getWeight()
	{
		return $this->weight;
	}
	
	function getAsPlacement()
	{
		return new Placement($this->id, $this->weight, false);
	}
}

class Student
{
	function __construct($id, $c1, $c2, $c3, $c4)
	{
		$this->id = $id;
		$this->choices = array();
		$this->choices[] = new Choice($c1, 4);
		$this->choices[] = new Choice($c2, 3);
		$this->choices[] = new Choice($c3, 2);
		$this->choices[] = new Choice($c4, 1);	
		$this->placements = array();
	}
	
	function assignBlock($blockNum, $placement)
	{
		$this->placements[$blockNum] = $placement;
	}
}


$_students = $database->getStudents();
$students = array();
$events = $database->getEvents();
$assemblyID = 99; // TODO: Change this to be dynamic
$order = array(11,10,9,12);

// Convert mysql data to an array of Student objects, also assign the assembly block as static
foreach ( $_students as $student )
{
	$choices = $database->getStudentChoices($student['id']);
	
	$thisStudent = new Student($student, $choices['c1'], $choices['c2'], $choices['c3'], $choices['c4']);
	switch ( $thisStudent['grade'] )
	{
		case 9:
			$thisStudent->assignBlock(1, new Placement($assemblyID, 100, true));
			break;
		case 10:
			$thisStudent->assignBlock(2, new Placement($assemblyID, 100, true));
			break;
		case 11:
			$thisStudent->assignBlock(3, new Placement($assemblyID, 100, true));
			break;
	}
	$students[] = $thisStudent;
}



?>