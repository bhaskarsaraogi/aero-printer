<?php 
function connect($host = 'localhost', $username, $password, $db = "")
{
	$conn = mysql_connect($host, $username, $password);
	if(!empty($db)) {
		mysql_select_db($db, $conn);
		return $conn;		
	} 
	return false;
}

function query($query, $conn)
{
	$results = mysql_query($query, $conn);
	if ($results) {
		$rows = array();
		while ($row = mysql_fetch_object($results)) {
			$rows[] = $row;
		}
		return $rows;			
	}
	return false;	
}

 ?>