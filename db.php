<?php
require_once "config.php";

class Database
{
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
			mysql_close($this->conn);
	}
	
	function addCareer($name, $location, $limit)
	{
		
	}
	
	function getCareer($id)
	{
		
	}
	
	function getCareers()
	{
		
	}
	
	function setStudentChoices($id, $c1, $c2, $c3, $c4)
	{
		
	}
	
	function getStudentChoices($id)
	{
		
	}
	
	function clearStudentChoices($id)
	{
		
	}
	
	function setStudentPlacement($id, $p1, $p2, $p3, $p3)
	{
		
	}
	
	function getStudentPlacement($id)
	{
		
	}
	
	function clearStudentPlacement($id)
	{
		
	}
	
	
}

$database = new Database();
