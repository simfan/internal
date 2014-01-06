<?php
ob_start();
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	
	//$fh = fopen('monthly_defects.csv', 'wb');
//	$current_date = mktime(0,0,0,date('m'),date('d'),date('Y'));
	//$first_of_month = mktime(0,0,0,date('m'),1,date('Y'));
	if ($_GET['record'] == 'previous')
	{
		$fh = fopen('previous_monthly_defects.csv', 'wb');
		$last_month = date('m') - 1;

		$year = date('Y');
		switch($last_month)
		{
			case(0):
				$year = date('Y') - 1;
				$last_month = 12;
			case(1):
			case(3):
			case(5):
			case(7):
			case(8):
			case(10):
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
		$current_date = mktime(0,0,0,$last_month,$day,$year);
		$first_of_month = mktime(0,0,0,$last_month,1,$year);
	}
	else
	{
		$fh = fopen('monthly_defects.csv', 'wb');
		$current_date = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$first_of_month = mktime(0,0,0,date('m'),1,date('Y'));		
	}

	$current_date = strftime('%Y-%m-%d', $current_date);
	$first_of_month = strftime('%Y-%m-%d', $first_of_month);

	$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_date >= '" . $first_of_month . "' AND inspection_date <= '" . $current_date . "' GROUP BY defect_code ORDER BY defect_code";
	
	//$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_da GROUP BY defect_code ORDER BY defect_code";
	print $defect_query;
	//$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr GROUP BY defect_code ORDER BY defect_code";
	$defect_result = pg_query($conn, $defect_query) or die("Error in query: $defect_query . " . pg_last_error($conn));
	$defect_rows = pg_num_rows($defect_result);
	for($i = 0; $i < $defect_rows-1; $i++)
	{
		$defect = pg_fetch_array($defect_result);
		//$line = make_csv_line($defect);
		$line = "$defect[0], $defect[1]\n";
		print $line;
	
		fwrite($fh, $line);
	}
	fclose($fh);
	if($_GET['record'] == 'previous')
		header("Location: internal_trouble_reports.php?record=previous");
	else
		header("Location: internal_trouble_reports.php?record=current");
	
function make_csv_line($values)
{
	//if a value contains a comma, a quote, a space, a tab (\t), a newLine (\n) or a linefeed (\r),
	//then surrond it with quotes and replace any quotes inside it with two quotes
	foreach($values as $i=> $value)
	{
		if ((strpos($value, ',') !== false) || (strpos($value, '"') !== false) || (strpos($value, ' ') !== false) || (strpos($value, "\t") !== false) || (strpos($value, "\n") !== false) || (strpos($value, "\r") !== false))
			$values[$i] = '"' . str_replace('"', '""', $value) . '"';
	}
	return implode(',', $values) . "\n";
}
ob_end_flush();
?>