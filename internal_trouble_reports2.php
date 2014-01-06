<?php
	
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	
	$fh = fopen('defect_code_list.txt', 'rb');
	$i = 0;
	for ($line = fgets($fh); ! feof($fh); $line = fgets($fh))
	{
		$line = trim($line);
		list($code[$i], $code_desc[$i]) = explode('|', $line);
		$i++;
	}
	$desc_count = $i;
	
	fclose($fh);
	$quarter_name = array("First Quarter", "Second Quarter", "Third Quarter", "Fourth Quarter");
	$month_name = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$range = $_GET['time'];
	$plant = $_GET['plant'];
	list($year, $quarter, $month) = explode("-", $range);
	$calendar = '';
	if($quarter > 0)
	{
		$end_month = $quarter * 3;
		$start_month = $end_month - 2;
		$calendar .= $quarter_name[$quarter-1] . " ";
	}
	else
	{
		if($month == 0)
		{
			$end_month = 12;
			$start_month = 1;
		}
		else
		{
			$end_month = $month;
			$start_month = $month;
			$calendar .= $month_name[$month-1] . " ";
		}
	}
	$calendar .= $year;

	switch($end_month)
	{
		case(1):
		case(3):
		case(5):
		case(7):
		case(8):
		case(10):
		case(12):
			$day = 31;
			break;
		case(2):
			$leap = $year%4;
			$century = $year%100;
			$four_cent = $year%400;
			if($four_cent == 0)
				$day = 29;
			else
			{
				if($century == 0)
					$day = 28;
				else
				{
					if($leap == 0)
						$day = 29;
					else
						$day = 28;
				}
			}
			break;
		default:
			$day = 30;
			break;
	}
	$current_date = mktime(0,0,0,$end_month,$day,$year);
	$first_of_month = mktime(0,0,0,$start_month,1,$year);

	$current_date = strftime('%Y-%m-%d', $current_date);
	$first_of_month = strftime('%Y-%m-%d', $first_of_month);		
	$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_date >= '" . $first_of_month . "' AND inspection_date <= '" . $current_date . "'";
	if ($plant != "All")
		$defect_query .= " AND plant = '" . $plant . "'";
	$defect_query .= " GROUP BY defect_code HAVING SUM(defect_qty) > 0 ORDER BY defect_count DESC, defect_code";
	$defect_result = pg_query($conn, $defect_query) or die("Error in query: $defect_query . " . pg_last_error($conn));
	$defect_rows = pg_num_rows($defect_result);
	
	$data_rows = '';
	$defect_data = array();
	
	if($defect_rows > 0)
	{
		print "Defect Data for $calendar";
		$data_rows = "<table border = 1><tr><th>Code Number</th><th>Description</th><th>Qty</th></tr>";
		for ($i = 0; $i < $defect_rows; $i++)
		{
			$defects = pg_fetch_array($defect_result);
			$defect_code = $defects['defect_code'];
			$defect_count = $defects['defect_count'];
			$defect_data[$defect_code] = $defect_count;
			for($j = 0; $j < $desc_count; $j++)
			{
				if ($defect_code == $code[$j])
				{
					$desc = $code_desc[$j];
					$data_rows .= "<tr><td align = right>$defect_code</td><td>$desc</td><td align = right>$defect_count</td></tr>";
					break;
				}
			}
		}
		$data_rows .= "</table></br><img src = 'defect_graph_2.php?time=" . $range . "&plant=" . $plant ."' />";
	}
	else
		$data_rows = "No Defects for the timeframe currently selected";
	pg_close($conn);
	print $data_rows;
	//print $defect_query;
?>	