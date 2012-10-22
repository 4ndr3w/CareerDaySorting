<?php
require_once "db.php";

function attemptSchedule($scheduledCareers, $newCareerID, $student, $careers)
{
	$thisStudentSortSuccess = false;
	for ( $_a = 0; $_a < 3; $_a++ )
	{
		$a = $_a;
		if ( $scheduledCareers[0]->isStatic() ) // Don't move static events!
			$a = 0;
		for ( $_b = 0; $_b < 3; $_b++ )
		{
			$b = $_b;
			if ( $scheduledCareers[1]->isStatic() ) // Don't move static events!
				$b = 1;
			for ( $_c = 0; $_c < 3; $_c++ )
			{
				$c = $_c;
				if ( $scheduledCareers[2]->isStatic() ) // Don't move static events!
					$c = 2;
											
				if ( uniqueIteration($a, $b, $c) )
				{
					$itsRan++;
					$invalid = false;
					//echo "In This Iteration: ".$a." - ".$b." - ".$c."\n";
					$thisScheduleIteration = array($a=>$scheduledCareers[0], $b => $scheduledCareers[1], $c => $scheduledCareers[2]);	
											
					for ($k = 0; $k < 4; $k++ )
					{
						$careerObj = $thisScheduleIteration[$k];
						$blockNum = $k;
						$careerID = 0;
						if ( is_object($careerObj) )
							$careerID = $careerObj->id;
						if ( $careerID != 0 )
						{
							if ( $careers[$careerID]->blockIsFull($blockNum) )
								$invalid = true;
						}
											
					}
					if ( !$invalid )
					{
						$thisStudentSortSuccess = true;
						if ( $thisStudentSortSuccess )
						{
							$student->placements = $thisScheduleIteration;
													
							for ( $z = 0; $z < 3; $z++ )
							{
								if ( $thisScheduleIteration[$z]->id == $newCareerID )
									$careers[$thisScheduleIteration[$z]->id]->addToBlock($z);
							}
													
							break;
						}
					}
				}
										
			}
			if ( $thisStudentSortSuccess ) break;
		}
		if ( $thisStudentSortSuccess ) break;
	}
							
	if ( !$thisStudentSortSuccess )
	{
		$student->choices[$highestChoiceNumber]->possible = false;
	}
	return $thisStudentSortSuccess;
}
?>