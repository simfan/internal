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
	$range = $_GET['time'];
	$plant = $_GET['plant'];
	$plant_name["K"] = "Atchison";
	$plant_name["D"] = "David City";
	$plant_name["N"] = "Norristown";
	$plant_name["T"] = "Reading";
	$plant_name["R"] = "Richland";
	
	list($year, $quarter, $month) = explode("-", $range);
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
	
	
	$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_date >= '" . $first_of_month . "' AND inspection_date <= '" . $current_date . "' ";
	if ($plant != 'All')
		$defect_query .= " AND plant = '" . $plant . "' ";
	$defect_query .= "GROUP BY defect_code HAVING SUM(defect_qty) > 0 ORDER BY defect_code";
	$defect_result = pg_query($conn, $defect_query) or die("Error in query: $defect_query . " . pg_last_error($conn));
	$defect_rows = pg_num_rows($defect_result);
	$defect_data = array();
	for ($i = 0; $i < $defect_rows; $i++)
	{
		$defects = pg_fetch_array($defect_result);
		$defect_code = $defects['defect_code'];
		$defect_data[$defect_code] = $defects['defect_count'];
		for($j = 0; $j < $desc_count; $j++)
		{
			if ($defect_code == $code[$j])
			{
				$desc = $code_desc[$j];
				$data_rows .= "<tr><td>$defect_code</td><td>$desc</td><td>$defect_count</td></tr>";
				break;
			}
		}
	}
    
    
    $img_width = 675;
    $img_height = 470;
    $margins = 30;

    //find the size of the graph by subtracting the size of borders
    $graph_width = $img_width - $margins * 2;
    $graph_height = $img_height - $margins * 2;
    $img = imagecreate($img_width, $img_height);
    
    //$bar_width = 50;
    $total_bars = count($defect_data);
    $bar_width = (3 * $graph_width)/(4 * $total_bars + 1);
    $gap = $bar_width/3;
    $font = 'arial.ttf';
    
    //Define Colors
    $bar_color = imagecolorallocate($img, 0, 64, 128);
    $background_color = imagecolorallocate($img, 240, 240, 255);
    $border_color = imagecolorallocate($img, 200, 200, 200);
    $line_color = imagecolorallocate($img, 220, 220, 220);
    $other_color = imagecolorallocate($img, 0, 0, 0);
    
    //create the border
    imagefilledrectangle($img, 1, 1, $img_width-2, $img_height-2, $border_color);
    imagefilledrectangle($img, $margins, $margins+10, $img_width-1-$margins, $img_height+10-$margins, $background_color);
    
    //determine the max value and ratio
    $max_value = max($defect_data);
    $ratio = $graph_height/$max_value;
    
    //create scale and draw horizontal lines
    if($max_value > 20)
    	$horizontal_lines = 20;
    else
    	$horizontal_lines = $max_value;
    $horizontal_gap = $graph_height/$horizontal_lines;
    $txt_info = "Defects Sorted By Type From $start_month/1/$year to $end_month/$day/$year";
    if ($plant != "All")
    	$txt_info .= " in $plant_name[$plant]";
    else
    	$txt_info .= " in All Plants";
    //imagestring($img, 5, 150, 0, "Defects Sorted By Type $start_month/1/$year - $end_month/$day/$year in $plant_name[$plant]", $bar_color);
    imagestring($img, 5, 75, 0, $txt_info, $bar_color);
    for($i = 1; $i <=$horizontal_lines; $i++)
    {
	    $y = $img_height - $margins+10 - $horizontal_gap * $i;
	    imageline($img, $margins, $y, $img_width-$margins, $y, $line_color);
	    $v = intval($horizontal_gap * $i / $ratio);
	    imagestring($img, 3, 5, $y-5, $v, $bar_color);
    }
    
    //draw the bars
    for ($i = 0; $i < $total_bars; $i++)
    {
	    list($key, $value) = each($defect_data);
	    $x1 = $margins + $gap + $i * ($gap + $bar_width);
	    $x2 = $x1 + $bar_width;
	    $y1 = $margins+10 + $graph_height - intval($value * $ratio);
	    $y2 = $img_height - $margins+10;

	    imagestring($img, 3, $x1+$bar_width/2 - 5 , $y1-20, $value, $bar_color);
	    imagestring($img, 3, $x1+$bar_width/2 - 5, $img_height-15, $key, $other_color);
	    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $bar_color);
    }
    //imagestring($img, 5, 0, 0, $defect_data, $bar_color);
    header("Content-type:image/png");
	imagepng($img);
?>