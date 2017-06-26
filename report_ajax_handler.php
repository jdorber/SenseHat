<?php
include_once 'connection.php';
$db = new DB_Class();

	// GET the variables from Ajax GET and use to run the correct SQL query and return Ajax data object
	$report = $_GET["report"];
	$start_date = $_GET["start_date"];
	$end_date = $_GET["end_date"];	
	
	//Use ajax get input parameter to determine correct SQL string
	//User is looked down to read only access - so no SQL injection code
	if ($report == "temperature")
	{
		$query = "SELECT DateTimeStamp,Temperature FROM EnvironmentalData ";
	}
	else if ($report == "pressure")
	{
		$query = "SELECT DateTimeStamp,Pressure FROM EnvironmentalData ";
	}
	else if ($report == "humidity")
	{
		$query = "SELECT DateTimeStamp,Humidity FROM EnvironmentalData ";
	}

	if ($start_date != '' AND $end_date != '')
	{
		$query = $query . "WHERE DateTimeStamp BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59' ORDER BY DateTimeStamp";
	}
	else
	{
		$query = $query . "ORDER BY DateTimeStamp";
	}


	// now do the actual db call
    $result = mysql_query( $query );
	$rows = array();
	while( $row = mysql_fetch_array( $result ) ) {
		$rows[] = array( '0' => $row['0'] , '1' => $row['1'] );
	}
    
    
	//return results as json
    print json_encode($rows, JSON_NUMERIC_CHECK);

?>
