<?php
require_once "db.php";
$careers = $database->getCareers();
$sane = true;
foreach ( $careers as $career )
{
	for ( $b = 0; $b < 3; $b++ )
	{
		$result = mysql_query("SELECT * FROM placements WHERE `p".($b+1)."` = ".$career['id']);
		if ( mysql_num_rows($result) > $career['maxStudents'] && $career['id'] != $assemblyID && $career['id'] != $seniorOptOutID )
		{
			echo "Career ID ".$career['id']." has greater than ".$career['maxStudents']." students in block ".($b+1)." (".mysql_num_rows($result).")\n";
			$sane = false;
		}
	}
}

if ( $sane )
{
	echo "Sane\n";
}