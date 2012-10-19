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
			die("DB Connection Failed");
		mysql_select_db($databaseInfo['database'], $this->conn);
		echo mysql_error();
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
	
	function genaricGetSet($table)
	{
		$result = mysql_query("SELECT * FROM ".$table);
		$output = array();
		while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( !empty($d) )
				$output[] = $d;
		}
		return $output;
	}
	
	function genaricRemove($table, $id)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		mysql_query("DELETE FROM `".$table."` WHERE `id` = ".$id);
	}
	
	
	
	function addCareer($name, $location, $limit)
	{
		$name = mysql_real_escape_string($name);
		$location = mysql_real_escape_string($location);
		if ( ($limit = intval($limit)) == 0 )
			return false;
			
		return mysql_query("INSERT INTO `careers` (name, location, maxStudents) VALUES('".$name."', '".$location."', ".$limit.")");
	}
	
	function getCareer($id)
	{
		return $this->genaricGet("careers", $id);
	}
	
	function getCareers()
	{
		return $this->genaricGetSet("careers");
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
	
	function setStudentPlacement($id, $p1, $p2, $p3, $p3)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
			
		if ( ($p1 = intval($p1)) == 0 )
			return false;
		if ( ($p2 = intval($p2)) == 0 )
			return false;
		if ( ($p3 = intval($p3)) == 0 )
			return false;
		if ( ($p4 = intval($p4)) == 0 )
			return false;
			
		return mysql_query("INSERT INTO `placements` (id, p1,p2,p3,p4) VALUES (".$id.", ".$p1.", ".$p2.", ".$p3.", ".$p4.")");	
	}
	
	function getStudentPlacement($id)
	{
		return $this->genaricGet("placements", $id);
	}
	
	function clearStudentPlacement($id)
	{
		return $this->genaricGet("placements", $id);
	}
	
	function addStudent($id, $first, $last, $grade, $homeroom)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		if ( ($grade = intval($grade)) == 0 )
			return false;
		if ( ($homeroom = intval($homeroom)) == 0 )
			return false;
			
		$first = mysql_real_escape_string($first);
		$last = mysql_real_escape_string($last);
		
		if ( empty($first) || empty($last) )
			return false;
		
		return mysql_query("INSERT INTO `students` (id, first, last, grade, homeroom) VALUES(".$id.", '".$first."', '".$last."', ".$grade.", ".$homeroom.")");
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
		return $this->genaricGetSet("students");
	}
	
	function removeStudent($id)
	{
		return $this->genaricRemove("students", $id);
	}
	
	function resetStudent($id)
	{
		if ( ($id = intval($id)) == 0 )
			return false;
		
		$this->removeStudent($_POST['id']);
		$this->clearStudentChoices($_POST['id']);
		$this->clearStudentPlacement($_POST['id']);
		return true;
	}
	
	function addStatistic($name, $value)
	{
		mysql_query("INSERT INTO `statistics` (name, value) VALUES('".$name."', '".$value."')");
	}
	
	function getStatistics()
	{
		return $this->genaricGetSet("statistics");
	}
	
	function resetStatistics()
	{
		mysql_query("DELETE FROM `statistics`");
	}
	
}

$database = new Database();
