<?php
	$current_year = date('Y');
	$current_month = date('m');
	$current_day = date('d');
	
	$year = array($current_year, $current_year - 1);
	/*for($i = 0; $i < $current_month; $i++)
	{
		$month[$i] = $current_month - $i;
		print $month[$i];
	}*/	
	/*switch($current_month);
		case(1):
		case(2):
		case(3):
			$current_quarter = 1;
			break;
		case(4):
		case(5):
		case(6):
			$current_quarter = 2;
		case(7):
		case(8):
		case(9):
			$current_quarter = 3;
		case(10):
		case(11):
		case(12):
			$current_quarter = 4;
	*/
	//$current_quarter = ceil($current_month/3);
	for($i = 0; $i < $current_quarter; $i++)
		$quarter[$i] = $current_quarter - $i;
		
	$month_name = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
	$quarter_name = array(1 => "First Quarter", 2 => "Second Quarter", 3 => "Third Quarter", 4 => "Fourth Quarter");
		
?>
<html>
	<body>
		<form name = "range_form" action = "internal_trouble_reports.php" method = "post">
			Select a timeframe for the report&nbsp&nbsp<select name = "time" id = "time">
			<?php 
				for ($i = 0; $i < 2; $i++)
				{
					$range = $year[$i] . "-0-0";
					print "<option value = $range>$year[$i]</option>";
					print "<optgroup label = ''>";
					if ($current_year == $year[$i])
						$start_month = $current_month;
					else
						$start_month = 12;
					for($j = 0; $j < $start_month; $j++)
						$month[$j] = $start_month - $j;
					for($j = 0; $j < $start_month; $j++)
					{
						$quarter = ceil($month[$j]/3);
						$high_month = $month[$j]%3;
						if ($start_month == $month[$j] || $high_month == 0)
						{	
							if($start_month != $month[$j] && $high_month == 0)
								print "</optgroup><optgroup label = ''>";
							$range = $year[$i] . "-" . $quarter . "-0";							
							print "<option value = $range>$quarter_name[$quarter]</option>";
							//print "<option value = $range>$quarter</option>";
							print "<optgroup label = '' style = 'padding-left: 15px; padding-right: 15px;'>";
						}
						$range = $year[$i] . "-0-" . $month[$j];
						print "<option value = $range>" . $month_name[$month[$j]] . "</option>";
					}
					print "</optgroup>";
				}
			?>
			</select>
			</br>
			<input type = "submit" name = "submit" id = "submit" value = "Submit" />
		</form>
	</body>
</html>
		
				