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