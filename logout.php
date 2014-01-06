<?php 
	ob_start();
	$past = time() - 100; 
	
	$location = $_GET['location'];
	print $location;
	if ($location == 'index')
		$page = 'index.php';
	else
	{
		$status = $_GET['status'];
		if ($location == 'select')
		{
			$page = 'location.php?location=select&status=' . $status;
		}
		elseif ($location == 'table')
		{
			$plant = $_GET['plant'];
			$page = 'CAR_table.php?location='. $plant . '&archive=' . $status;
		}
		else
			$page = 'index.php';
	}
	//this makes the time in the past to destroy the cookie 
	setcookie(ID_my_site, gone, $past); 
	setcookie(Key_my_site, gone, $past); 
	header("Location:$page"); 
	ob_end_flush();
?> 