<?php
require_once "db.php";
$startTime = microtime(true);

function uniqueIteration($a, $b, $c)
{
	return ( $a != $b && $a != $c && $b != $c );
}


class Placement
{
	function __construct($id, $weight, $isStatic = false)
	{
		$this->id = $id;
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
	
	function isStatic()
	{
		return $this->isStatic;
	}
}

class Choice
{
	function __construct($id, $weight)
	{
		$this->id = $id;
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
	function __construct($id, $grade, $c1, $c2, $c3, $c4)
	{
		$this->id = $id;
		$this->grade = $grade;
		$this->choices = array();
		$this->choices[0] = new Choice($c1, 4);
		$this->choices[1] = new Choice($c2, 3);
		$this->choices[2] = new Choice($c3, 2);
		$this->choices[3] = new Choice($c4, 1);	
		$this->placements = array();
		$this->placements[0] = new Placement(0, -1);
		$this->placements[1] = new Placement(0, -1);
		$this->placements[2] = new Placement(0, -1);
	}
	
	function assignBlock($blockNum, $placement)
	{
		$this->placements[$blockNum] = $placement;
	}
	
	function blockIsOpen($blockNum)
	{
		if ( !is_object($this->placements[$blockNum]) )
			return true;
		if ( $this->placements[$blockNum]->id > 0 )
			return false;
		return true;
	}
	
	function isFullySorted()
	{
		foreach ( $this->placements as $placement )
		{
			if ( $placement == 0 )
				return false;
		}
		return true;
	}
	
	function getHighestChoiceNumber()
	{
		foreach ($this->choices as $k=>$choice)
		{
			$isThisChoice = true;
			foreach ( $this->placements as $p )
			{
				if ( $p->id == $choice->id )
					$isThisChoice = false;
			}
			if ( $isThisChoice )
				return $k;
		}
		return -1;
	}
}

class Career
{
	function __construct($id, $max)
	{
		$this->id = $id;
		$this->max = $max;
		
		$this->blockSizes = array(0,0,0);
	}
	
	function addToBlock($blockNum)
	{
		$this->blockSizes[$blockNum]++;
	}
	
	function removeFromBlock($blockNum)
	{
		$this->blockSizes[$blockNum]--;
	}
	
	function blockIsFull($blockNum)
	{
		return $this->blockSizes[$blockNum] >= $this->max;
	}
}


$_students = $database->getStudents();
$students = array();
$_careers = $database->getCareers();
$careers = array();
$assemblyID = 99; // TODO: Change this to be dynamic
$order = array(11,10,9,12);

foreach ( $_careers as $career )
{
	$careers[$career['id']] = new Career($career['id'], $career['maxStudents']);
}

// Convert mysql data to an array of Student objects, also assign the assembly block as static
foreach ( $_students as $student )
{
	$choices = $database->getStudentChoices($student['id']);
	
	$thisStudent = new Student($student['id'], $student['grade'], $choices['s1'], $choices['s2'], $choices['s3'], $choices['s4']);
	switch ( $thisStudent->grade )
	{
		case 9:
			$thisStudent->assignBlock(0, new Placement($assemblyID, 100, true));
			break;
		case 10:
			$thisStudent->assignBlock(1, new Placement($assemblyID, 100, true));
			break;
		case 11:
			$thisStudent->assignBlock(2, new Placement($assemblyID, 100, true));
			break;
	}
	$students[$thisStudent->id] = $thisStudent;
}

$itsRan = 0;

for ( $i = 0; $i < 4; $i++ )
{
	foreach ($order as $currentSortingGrade )
	{
		foreach ( $students as $student )
		{
			if ( $student->grade == $currentSortingGrade )
			{
				//echo "Sorting student ".$student->id." - Choice ".$i." - in grade ".$currentSortingGrade."\n";
				
				$skip = false;
				$blocksFilled = 0;
				for ( $z = 0; $z < 3; $z++ )
				{
					if ( !$student->blockIsOpen($z) )
						$blocksFilled++;
				}
				$skip = ($blocksFilled==3);
				$highestChoiceNumber = $student->getHighestChoiceNumber();
				if ( $highestChoiceNumber == -1 )
					$skip = true;
				if ( !$skip )
				{
					
						$highestChoiceID = $student->choices[$highestChoiceNumber]->id;
						$thisChoice = new Placement($highestChoiceID, $highestChoiceNumber);
						$scheduledCareers = array();
						
						for ( $z = 0; $z < 3; $z++ )
						{
							if ( !$student->blockIsOpen($z) )
								$scheduledCareers[] = $student->placements[$z];
							else
								$scheduledCareers[$z] = new Placement(0, 0);
						}
						
						
						
						for ( $z = 0; $z < 3; $z++ )
						{
							if ( $scheduledCareers[$z]->id == 0 )
							{
								$scheduledCareers[$z] = $thisChoice;
								break;
							}
						}

						$thisStudentSortSuccess = false;
						for ( $_a = 0; $_a < 3; $_a++ )
						{
							$a = $_a;
							if ( $scheduledCareers[0]->isStatic() )
								$a = 0;
							for ( $_b = 0; $_b < 3; $_b++ )
							{
								$b = $_b;
								if ( $scheduledCareers[1]->isStatic() )
									$b = 1;
								for ( $_c = 0; $_c < 3; $_c++ )
								{
									$c = $_c;
									if ( $scheduledCareers[2]->isStatic() )
										$c = 2;
									if ( uniqueIteration($a, $b, $c) )
									{
										$itsRan++;
										$thisScheduleIteration = array($a=>$scheduledCareers[0], $b => $scheduledCareers[1], $c => $scheduledCareers[2]);	
										foreach ( $thisScheduleIteration as $blockNum => $careerObj )
										{
											$careerID = 0;
											if ( is_object($careerObj) )
												$careerID = $careerObj->id;
												
											if ( $careerID != 0 )
											{
												if ( $careers[$careerID]->blockIsFull($blockNum) || !$student->blockIsOpen($blockNum) )
													break;
											}
										}
										$thisStudentSortSuccess = true;
										
										$students[$student->id]->placements = $thisScheduleIteration;
										break;
									}
								}
								if ( $thisStudentSortSuccess ) break;
							}
							if ( $thisStudentSortSuccess ) break;
						}

				
					/*
					for ( $bl = 0; $bl < 3; $bl++ )
					{
						$highestChoiceNumber = $student->getHighestChoiceNumber();
						if ( $highestChoiceNumber != -1 )
						{
							$highestChoiceID = $student->choices[$highestChoiceNumber]->id;
							if ( $student->blockIsOpen($bl) && !$careers[$highestChoiceID]->blockIsFull($bl) )
							{
								echo "Assigned ".$student->id." to ".$highestChoiceID." for block ".$b."\n";
								$students[$student->id]->assignBlock($bl, new Placement($highestChoiceID, (4-$highestChoiceNumber), false));
								$careers[$highestChoiceID]->addToBlock($bl);
								break;
							}
						}
					}*/
				}
				else
				{
					//echo "Skipping...\n";
				}
			}
		}
	}
}

echo "--------------------\n";
echo "\n\nSorting output";

foreach ( $students as $student)
{
	echo "\n\nStudent: ".$student->id."\n";
	for ( $i = 0; $i<3; $i++ )
	{
		echo ($i+1)." - ".$student->placements[$i]->id."\n";
	}
}
echo "Iterations ran: ".$itsRan."\n";
echo "Completed in: ".round(microtime(true)-$startTime, 5)." microseconds.\n";
?>