<?php
require_once "config.php";

class Database
{
	private $conn;
	function __construct()
	{
		global $databaseInfo;
		$this->conn = mysql_connect($databaseInfo['hostname'], $databaseInfo['username'], $databaseInfo['password']);
		if ( !$this->conn )
			die("DB Connection Failed: "+mysql_error());
		if ( !mysql_select_db($databaseInfo['database'], $this->conn) )
			die("mysql_select_db failed: "+mysql_error());
	}
	
	function __destruct()
	{
		if ( $this->conn )
			@mysql_close($this->conn);
	}
	
	function genaricGet($table, $id)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		$result = mysql_query("SELECT * FROM `".$table."` WHERE `id` = ".$id);
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	function genaricGetSet($table,$idAsKey = false)
	{
		$result = mysql_query("SELECT * FROM ".$table);
		$output = array();
		while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( !empty($d) )
			{
				if ( array_key_exists("id", $d) )
					$output[$d['id']] = $d;
				else
					$output[] = $d;
			}
		}
		return $output;
	}
	
	function genaricRemove($table, $id)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		mysql_query("DELETE FROM `".$table."` WHERE `id` = ".$id);
	}
	
	
	
	function addCareer($name, $location, $limit, $group = 1)
	{
		$name = mysql_real_escape_string($name);
		$location = mysql_real_escape_string($location);
		if ( ($limit = intval($limit)) == 0 )
			return false;
		if ( ($group = intval($group)) == 0 )
			return false;
		return mysql_query("INSERT INTO `careers` (`name`, `location`, `maxStudents`, `group`) VALUES('".$name."', '".$location."', ".$limit.", ".$group.")");
	}
	
	function getCareer($id)
	{
		return $this->genaricGet("careers", $id);
	}
	
	function getCareers($includeHidden = true)
	{
		$result = 0;
		if ( $includeHidden )
			$result = mysql_query("SELECT * FROM careers");
		else
			$result = mysql_query("SELECT * FROM careers WHERE `hidden` = 0");
		
		$output = array();
		while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( $idAsKey && !empty($d) )
				$output[$d['id']] = $d;
			else if ( !empty($d) )
				$output[] = $d;
		}
		return $output;
		
	}
	
	function getGroupForCareer($id)
	{
		$result = $this->genaricGet("careers", $id);
		return $result['group'];
	}
	
	function getCareersInGroup($groupID)
	{
		if ( ($groupID = intval($groupID)) == 0 )
			return false;
		$result = mysql_query("SELECT * FROM `careers` WHERE `group` = ".$groupID);
		
		$output = array();
		while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( !empty($d) )
				$output[] = $d;
		}
		return $output;
		
	}
	
	function removeCareer($id)
	{
		return $this->genaricRemove("careers", $id);
	}
	
	function getNumberOfStudentsInCareer($id, $block)
	{
		if ( ($block = intval($block)) == 0 )
			return false;

		$result = mysql_query("SELECT * FROM `placements` WHERE `p".$block."` = ".$id);
		return mysql_num_rows($result);
	}
	
	function getNumberOfStudentsSignedUpForCareer($id)
	{
		$num = 0;
		for ( $i = 1; $i <= 4; $i++ )
			$num += mysql_num_rows(mysql_query("SELECT * FROM `selections` WHERE `s".$i."` = ".$id));
		return $num;
	}
	
	function setStudentChoices($id, $c1, $c2, $c3, $c4)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
			
		if ( ($c1 = intval($c1)) == 0 )
			return false;
		if ( ($c2 = intval($c2)) == 0 )
			return false;
		if ( ($c3 = intval($c3)) == 0 )
			return false;
		if ( ($c4 = intval($c4)) == 0 )
			return false;
			
		$data = array($c1, $c2, $c3, $c4);
		foreach ( $data as $ak => $a )
		{
			foreach ( $data as $bk => $b )
			{
				if ( $ak != $bk && $a == $b )
					return false;
			}
		}
			
		return mysql_query("INSERT INTO `selections` (id, s1,s2,s3,s4) VALUES (".$id.", ".$c1.", ".$c2.", ".$c3.", ".$c4.")");	
	}
	
	function getStudentChoices($id)
	{
		return $this->genaricGet("selections", $id);
	}
	
	function clearStudentChoices($id)
	{
		return $this->genaricRemove("selections", $id);
	}
	
	function setStudentPlacement($id, $p1, $p2, $p3)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
			
		if ( ($p1 = intval($p1)) == 0 )
			return false;
		if ( ($p2 = intval($p2)) == 0 )
			return false;
		if ( ($p3 = intval($p3)) == 0 )
			return false;
			
		return mysql_query("INSERT INTO `placements` (id, p1,p2,p3) VALUES (".$id.", ".$p1.", ".$p2.", ".$p3.")");	
	}
	
	function updateStudentPlacement($id, $p1, $p2, $p3)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
			
		if ( ($p1 = intval($p1)) == 0 )
			return false;
		if ( ($p2 = intval($p2)) == 0 )
			return false;
		if ( ($p3 = intval($p3)) == 0 )
			return false;
		
		if ( !mysql_query("UPDATE `placements` SET `p1` = ".$p1." WHERE `id` = ".$id) )
			return false;
		if ( !mysql_query("UPDATE `placements` SET `p2` = ".$p2." WHERE `id` = ".$id) )
			return false;
		if ( !mysql_query("UPDATE `placements` SET `p3` = ".$p3." WHERE `id` = ".$id) )
			return false;
		return true;
	}
	
	function getStudentPlacement($id)
	{
		return $this->genaricGet("placements", $id);
	}
	
	function clearStudentPlacement($id)
	{
		return $this->genaricRemove("placements", $id);
	}
	
	function addStudent($id, $first, $last, $grade, $homeroom)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		if ( ($grade = intval($grade)) == 0 )
			return false;
			
		$first = mysql_real_escape_string($first);
		$last = mysql_real_escape_string($last);
		
		$homeroom = mysql_real_escape_string($homeroom);
		
		if ( empty($first) || empty($last) )
			return false;
		
		return mysql_query("INSERT INTO `students` (id, first, last, grade, homeroom) VALUES(".$id.", '".$first."', '".$last."', ".$grade.", '".$homeroom."')");
	}
	
	function getStudent($id)
	{
		return $this->genaricGet("students", $id);
	}
	
	function getStudentsIn($class, $block)
	{
		$block = intval($block);
		if ( ($class = intval($class)) == 0 )
			return false;
		$block = "p".($block+1);
		
		$result = mysql_query("SELECT * FROM `placements` WHERE `".$block."` =  ".$class);
		$output = array();
		while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( !empty($d) )
				$output[] = $d;
		}
		return $output;
	}
	
	function getStudents()
	{
		return $this->genaricGetSet("students", true);
	}
	
	function removeStudent($id)
	{
		return $this->genaricRemove("students", $id);
	}
	
	function resetStudent($id)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		
		$this->removeStudent($id);
		$this->clearStudentChoices($id);
		$this->clearStudentPlacement($id);
		return true;
	}
	
	function addStatistic($name, $value)
	{
		$name = mysql_real_escape_string($name);
		$value = mysql_real_escape_string($value);
		return mysql_query("INSERT INTO `statistics` (name, value) VALUES('".$name."', '".$value."')");
	}
	
	function getStatistics()
	{
		return $this->genaricGetSet("statistics");
	}
	
	function hasStatistics()
	{
		return mysql_num_rows(mysql_query("SELECT * FROM `statistics`")) != 0;
	}
	
	function resetStatistics()
	{
		mysql_query("DELETE FROM `statistics`");
	}
	
	function getHomerooms()
	{
		$homerooms = $this->genaricGetSet("homerooms");
		$pivot = array();

		foreach ( $homerooms as $k=>$v )
		{
			$pivot[$k] = $v['name'];
		}

		array_multisort($pivot, SORT_ASC, $homerooms);
		return $homerooms;
	}
	
	function addHomeroom($name)
	{
		$name = mysql_real_escape_string($name);
		return mysql_query("INSERT INTO `homerooms` (name) VALUES('".$name."')");
	}
	
	function removeHomeroom($id)
	{
		return $this->genaricRemove("homerooms", $id);
	}
	
}

$database = new Database();
?>
