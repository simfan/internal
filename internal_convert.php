<?php
	ob_start();
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database');
	}

	$query[0] = "UPDATE incr SET incr_eng = 'Y' WHERE incr_eng = '1'";
	$query[1] = "UPDATE incr SET incr_eng = 'N' WHERE incr_eng = '0'";
	$query[2] = "UPDATE incr SET incr_s_car = 'Y' WHERE incr_s_car = '1'";
	$query[3] = "UPDATE incr SET incr_s_car = 'N' WHERE incr_s_car = '0'";
	$query[4] = "UPDATE incr SET incr_other = 'Y' WHERE incr_other = '1'";
	$query[5] = "UPDATE incr SET incr_other = 'N' WHERE incr_other = '0'";
	$query[6] = "UPDATE incr SET incr_none = 'Y' WHERE incr_none = '1'";
	$query[7] = "UPDATE incr SET incr_none = 'N' WHERE incr_none = '0'";		
	
	$query_com = "COMMIT";
	$rows = count($query);
	for($i = 0; $i < $rows; $i++)
	{
		$result = pg_query($conn, $query[$i]) or die("Error in query $i: $query." . pg_last_error($conn));
		$result = pg_query($conn, $query_com) or die("Error in query $i: $query_com." . pg_last_error($conn));
		
	}