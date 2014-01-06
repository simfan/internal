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
	$range = $_POST['time'];
	list($year, $quarter, $month) = explode("-", $range);
	print "Year $year";
	if($quarter > 0)
	{
		$end_month = $quarter * 3;
		$start_month = $end_month - 2;
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
		}
	}
//	if ($_GET['record'] == 'previous')
//	{
		//$last_month = date('m') - 1;

		//$year = date('Y');
		switch($end_month)
		{
			/*case(0):
				$year = date('Y') - 1;
				$last_month = 12;*/
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
	//}
	/*else
	{
		$current_date = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$first_of_month = mktime(0,0,0,date('m'),1,date('Y'));		
	}*/

	$current_date = strftime('%Y-%m-%d', $current_date);
	$first_of_month = strftime('%Y-%m-%d', $first_of_month);		
	$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_date >= '" . $first_of_month . "' AND inspection_date <= '" . $current_date . "' GROUP BY defect_code ORDER BY defect_count DESC, defect_code";
	//$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr GROUP BY defect_code ORDER BY defect_count DESC, defect_code";	
	print $defect_query;
	$defect_result = pg_query($conn, $defect_query) or die("Error in query: $defect_query . " . pg_last_error($conn));
	$defect_rows = pg_num_rows($defect_result);
	
	//$filename ="excelreport.xls";
	//$contents = "Defect Code \t Defect Description \t Description Count \t \n";
	$data_rows = '';
	$defect_data = array();
	if($defect_rows > 0)
	{
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
					//$contents .= "$defect_code \t $desc \t $defect_count \t \n";
					$data_rows .= "<tr><td>$defect_code</td><td>$desc</td><td>$defect_count</td></tr>";
					break;
				}
			}
		}
	}
	else
		$data_rows = "No Defects for the timeframe currently selected";
		
    //$contents = "testdata1 \t testdata2 \t testdata3 \t \n";
    //header('Content-type: application/ms-excel');
    //header('Content-Disposition: attachment; filename='.$filename);
    //echo $contents;
    /*$img_width = 900;
    $img_height = 600;
    $margins = 40;

    //find the size of the graph by subtracting the size of borders
    $graph_width = $img_width - $margins * 2;
    $graph_height = $img_height - $margins * 2;
    $img = imagecreate($img_width, $img_height);
    
    $bar_width = 20;
    $total_bars = count($defect_data);
    $gap = ($graph_width - $total_bars * $bar_width)/($total_bars + 1);
    
    //Define Colors
    $bar_color = imagecolorallocate($img, 0, 64, 128);
    $background_color = imagecolorallocate($img, 240, 240, 255);
    $border_color = imagecolorallocate($img, 200, 200, 200);
    $line_color = imagecolorallocate($img, 220, 220, 220);
    $other_color = imagecolorallocate($img, 0, 0, 0);
   //create the border
    imagefilledrectangle($img, 1, 1, $img_width-2, $img_height-2, $border_color);
    imagefilledrectangle($img, $margins, $margins, $img_width-1-$margins, $img_height-1-$margins, $background_color);
    
    //determine the max value and ratio
    $max_value = max($defect_data);
   // print "Max Value: $max_value";
    $ratio = $graph_height/$max_value;
    //print "Ratio: $ratio";
    //create scale and draw horizontal lines
    $horizontal_lines = 20;
    $horizontal_gap = $graph_height/$horizontal_lines;
    
    for($i = 1; $i <=$horizontal_lines; $i++)
    {
	    $y = $img_height - $margins - $horizontal_gap * $i;
	    imageline($img, $margins, $y, $img_width-$margins, $y, $line_color);
	    $v = intval($horizontal_gap * $i / $ratio);
	    imagestring($img, 0, 5, $y-5, $v, $bar_color);
    }
    
    //draw the bars
    for ($i = 0; $i < $total_bars; $i++)
    {
	    list($key, $value) = each($defect_data);
	    //print $value;
	    $x1 = $margins + $gap + $i * ($gap + $bar_width);
	    $x2 = $x1 + $bar_width;
	    $y1 = $margins + $graph_height - intval($value * $ratio);
	    $y2 = $img_height - $margins;
	   // print "($x1, $y1)($x2, $y2) ";
	    imagestring($img, 0, $x1+3, $y1-10, $value, $bar_color);
	    imagestring($img, 0, $x1+3, $img_height-15, $key, $other_color);
	    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $bar_color);
    }

    header("Content-type:image/png");
	imagepng($img);*/
?>
<html>
<body>
<!--<div class = "defectTable">-->
<table>
	<tr><th>Defect Code</th><th>Description</th><th>Count</th></tr> 
	<?php
	
	print $data_rows;
	?>
<!--/*	for ($i = 0; $i < $defect_rows; $i++)
	{
		$defects = pg_fetch_array($defect_result);
		$defect_code = $defects['defect_code'];
		$defect_count = $defects['defect_count'];
		for($j = 0; $j < $desc_count; $j++)
		{
			if ($defect_code == $code[$j])
			{
				$desc = $code_desc[$j];
				print "<tr><td>$defect_code</td><td>$desc</td><td>$defect_count</td></tr>";
				break;	
			}
		}	

	}
	?>-->
	<!--<tr><td colspan = 3><img src = "defect_pie_chart.php" /></td></tr>-->
</table>
</body>
</html>	
	