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
	function __construct($id, $weight, $group)
	{
		$this->id = $id;
		$this->weight = $weight;
		$this->possible = true;
	}
	
	function getGroup()
	{
		return $this->group;
	}

	function getWeight()
	{
		return $this->weight;
	}
	
	function getAsPlacement()
	{
		return new Placement($this->id, $this->weight, false);
	}
	
	function isPossible()
	{
		return $this->possible;
	}
}


class Student
{
	function __construct($id, $grade, $c1, $c2, $c3, $c4)
	{
		$this->id = $id;
		$this->grade = $grade;
		$this->choices = array();
		$this->choices[0] = new Choice($c1, 4, 0);
		$this->choices[1] = new Choice($c2, 3, 0);
		$this->choices[2] = new Choice($c3, 2, 0);
		$this->choices[3] = new Choice($c4, 1, 0);	
		$this->placements = array();
		$this->placements[0] = new Placement(0, -1);
		$this->placements[1] = new Placement(0, -1);
		$this->placements[2] = new Placement(0, -1);
	}
	
	function getMostPopularChoiceGroup()
	{
		$choiceGroups = array();
		for ($i = 0; $i < 4; $i++ )
		{
			$choiceGroups[$this->choices[$i]->getGroup()]++;
		}
		$highestCGCount = 0;
		$highestCGID = 0;
		foreach ( $choiceGroups as $k => $cg )
		{
			if ( $k > $highestCGCount )
			{
				$highestCGID = $cg;
				$highestCGCount = $k;
			}
		}
		return $highestCGID;
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
			if ( $placement->id == 0 )
				return false;
		}
		return true;
	}
	
	function getHighestChoiceNumber()
	{
		foreach ($this->choices as $k=>$choice)
		{
			$isThisChoice = true;
			if ( $choice->isPossible() )
			{
				foreach ( $this->placements as $p )
				{
					if ( $p->id == $choice->id )
						$isThisChoice = false;
				}
				if ( $isThisChoice )
					return $k;
			}
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
$order = array(11,10,9,12);


foreach ( $_careers as $career )
{
	$careers[$career['id']] = new Career($career['id'], $career['maxStudents'], $career['group']);
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

require_once("iteration.php");

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
				$highestChoiceNumber = $i;
				if ( $highestChoiceNumber == -1 )
					$skip = true;
				if ( !$skip )
				{
						$highestChoiceNumber = $student->getHighestChoiceNumber();
						$highestChoiceID = $student->choices[$highestChoiceNumber];
						//echo "using choice ".$highestChoiceNumber." ID: ".$highestChoiceID->id."\n";
						if ( $highestChoiceID != -1)
						{
							$thisChoice = new Placement($highestChoiceID->id, $highestChoiceNumber);
							$scheduledCareers = array();
							
							for ( $z = 0; $z < 3; $z++ ) // Fill empty slots
							{
								if ( !$student->blockIsOpen($z) )
									$scheduledCareers[] = $student->placements[$z];
								else
									$scheduledCareers[$z] = new Placement(0, 0);
							}
							
							for ( $z = 0; $z < 3; $z++ ) // Add next choice to list
							{
								if ( $scheduledCareers[$z]->id == 0 )
								{
									//echo "Added choice in slot ".$z."\n";
									$scheduledCareers[$z] = $thisChoice;
									break;
								}
							}
	
							attemptSchedule($scheduledCareers);	
						}
				}
			}
		}
	}
}


foreach ( $students as $student )
{
	if ( !$student->isFullySorted() )
	{
		$choiceGroup = $student->getMostPopularChoiceGroup();
		$careersInGroup = $database->getCareersInGroup($choiceGroup);
		
	}
}


echo "--------------------\n";
$stats = array("success"=>0, "failed"=>0, "total"=>0);
foreach ( $students as $student)
{
	$stats['total']++;
	if ( $student->isFullySorted() )
	{
		$stats['success']++;
		mysql_query("DELETE FROM `placements` WHERE `id` = ".$student->id);
		mysql_query("INSERT INTO `placements` (id, p1, p2, p3) VALUES(".$student->id.", ".$student->placements[0]->id.", ".$student->placements[1]->id.", ".$student->placements[2]->id.")");
	}
	else 
		$stats['failed']++;
}

echo "Statistics:\n";

$database->resetStatistics();

$database->addStatistic("Successful", (($stats['success']/$stats['total'])*100));
$database->addStatistic("Failed", (($stats['failed']/$stats['total'])*100));
$database->addStatistic("Total", $stats['total']);
$database->addStatistic("Iterations", $itsRan);
$database->addStatistic("Time to complete", round(microtime(true)-$startTime, 5)." ms");

$stats = $database->getStatistics();
foreach ( $stats as $stat )
{
	echo $stat['name']." - ".$stat['value']."\n";
}
echo "--------------------\n";
?>