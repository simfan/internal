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
		$current_date = mktime(0,0,0,4,30,date('Y'));
		$first_of_month = mktime(0,0,0,4,1,date('Y'));		
	}

	$current_date = strftime('%Y-%m-%d', $current_date);
	$first_of_month = strftime('%Y-%m-%d', $first_of_month);

	$defect_query = "SELECT defect_code, SUM(defect_qty) AS defect_count FROM itr WHERE inspection_date >= '" . $first_of_month . "' AND inspection_date <= '" . $current_date . "' AND defect_qty > 0 GROUP BY defect_code ORDER BY defect_code";
	$defect_result = pg_query($conn, $defect_query) or die("Error in query: $defect_query . " . pg_last_error($conn));
	$defect_rows = pg_num_rows($defect_result);
	$defect_count = 0;
	$defect_data = array();
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
	$defect_total = array_sum($defect_data);
	$img_height = 600;
	$img_width = $img_height;
    $img = imagecreate($img_width, $img_height);
    $white = imagecolorallocate($img, 255, 255, 255);
    $black = imagecolorallocate($img, 0, 0, 0);
	$red = imagecolorallocate($img, 255, 0, 0);
	//imagestring($img, 3, 250, 500, $defect_total, $red);
	//$img_height = 300;
	//$img_width = $img_height;
	$x = $img_width/2;
	$y = $x;
	$arc_start = 0;
	$slices = count($defect_data);
	//imagefilledarc($img, $x, $y, $img_width-20, $img_height - 20, 0, 360, $white, IMG_ARC_PIE);
	//print "Slices of the Pie: $slices</br>";
	$y2 = 400;
	for($i = 0; $i < $slices; $i++)
	{
		$color = imagecolorallocate($img, 255-$i*($slices+3), 255-$i*($slices), 255-$i*($slices-3));
		list($key, $value) = each($defect_data);
		//print "$key - $value</br>";
		$percent = $value/$defect_total;
		//print "Percent: $percent </br>";
		$angle = $percent * 360;
		//print "Angle: $angle </br>";
		$arc_end = $angle + $arc_start;
		//print "End Arc Degree: $arc_end </br>";
		$text_degree = $angle/2 + $arc_start;
		//print "Text Degree: $text_degree </br>";
		$small_angle = $text_degree - floor($text_degree/90)*90;
		$small_width = ($img_width-20)/2;
		$small_height = ($img_height-20)/2;
		//print "Small angle: $small_angle, Small Width: $small_width, Small Height: $small_height</br>";
		//determine the height and length for the text coordinates from the center
		if(($text_degree > 0 && $text_degree < 90) || ($text_degree > 180 && $text_degree < 270))
		{	
			//$length = (cos($small_angle)) * (($img_width-20)/2);
			//print "Cosine of angle: " . cos(deg2rad(45)) . " Sine of angle: " . cos(deg2rad(45));
			$length = cos(deg2rad($small_angle)) * ($small_width* .75);
			//$length_check = "cos";
			//$height = sin($small_angle) * (($img_height-20)/2);
			$height = sin(deg2rad($small_angle)) * ($small_width *.75);
			//print "Length: $length, Height: $height</br>";
		}
		//
		if(($text_degree > 90 && $text_degree < 180) || ($text_degree > 270 && $text_degree < 360))
		{	
			//$length = (sin($small_angle)) * (($img_width-20)/2);
			$length = sin(deg2rad($small_angle)) * ($small_width *.75);
			$length_check = "sin";
			//$height = cos($small_angle) * (($img_height-20)/2);
			$height = cos(deg2rad($small_angle)) * ($small_height * .75);
		}
		
		//calculate the x and y coordinates for the text
		//the x-coordinate - right half of pie chart
		if(($text_degree > 0 && $text_degree < 90) || ($text_degree > 270 && $text_degree < 360))
			$x_coordinate = $x + $length;
		
		//the x-coordinate - left half of pie chart
		if($text_degree > 90 && $text_degree < 270)
			$x_coordinate = $x - $length;
		
		//the x-coordinate - horizontal middle
		if($text_degree == 90 || $text_degree == 270)
			$x_coordinate = $x;
			
		//the y-coordinate - top half of pie chart
		if($text_degree > 0 && $text_degree < 180)
			$y_coordinate = $y + $height - 10;
		
		//the y-coordinate - bottom half of pie chart
		if($text_degree > 180 && $text_degree < 360)
			$y_coordinate = $y - $height -10;
		
		//the y-coordinate - vertical middle
		if($text_degree == 0 || $text_degree == 180)
			$y_coordinate = $y;
		if($arc_end == '360')
		{
			$arc_close = 1;
		}
		else 
		{
			$arc_close = $arc_end + 1;
			$arc_yes = "No";
		}		
		//print "Here are the stats for this slice of the pie: Value: $value, Percentage of total: $percent, Angle of Slice: $angle, Start Angle: $arc_start, End Angle: $arc_end</br>";
		//imagestring($img, 3, 15*i, 10, $value, $black);
		//imagefilledarc($img, $x, $y, $img_width-20, $img_height-20, $arc_start, $arc_start+.5, $black, IMG_ARC_EDGED);
		imagefilledarc($img, $x, $y, $img_width-20, $img_height-20, $arc_start, $arc_close, $color, IMG_ARC_PIE);
		imagefilledarc($img, $x, $y, $img_width-20, $img_height-20, $arc_start, $arc_close, $black, IMG_ARC_EDGED | IMG_ARC_NOFILL);
		//imagestring($img, 3, $x_coordinate, $y_coordinate, "$x_coordinate  $length_check, $y_coordinate", $red);
		$text_x[$i] = $x_coordinate;
		$text_y[$i] = $y_coordinate;
		$text_key[$i] = $key;
		$text_value[$i] = $value;
		$arc_start = $arc_end;
	}
	for ($i = 0; $i < $slices; $i++)
	{
		imagestring($img, 3, $text_x[$i], $text_y[$i], "$text_key[$i] - $text_value[$i]", $red);
		//imagestring($img, 3, 50, $y2, "$key - $value, $length, $height ($x_coordinate, $y_coordinate)", $red);
		//imagefilledarc($img, $x, $y, $img_width-20, $img_height-20, $arc_end-.5, $arc_end, $black, IMG_ARC_EDGED);

		//$y2 += 10;
	}
	
	header("Content-type:image/png");
	imagepng($img);
?>