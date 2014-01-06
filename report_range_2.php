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
<head>
	<script>
		function displayTable(str, plant)
		{
			var xmlhttp;
			if(str.length==0)
			{
				document.getElementById("txtHint").innerHTML="";
				return;
			}
			if(window.XMLHttpRequest)
			{
				//code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				//code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "internal_trouble_reports2.php?time="+str+"&plant="+plant, true);
			xmlhttp.send();
		}
	</script>
</head>
	<body>
		<form name = "range_form" action = "">
			Plant:<select name = "plant" id = "plant" onchange="displayTable(time.value, this.value)">
					<option value = "All">All</option>
					<option value = "K">Atchison</option>
					<option value = "D">David City</option>
					<option value = "N">Norristown</option>
					<option value = "T">Reading</option>
					<option value = "R">Richland</option>
				</select>
			Select a timeframe for the report&nbsp&nbsp<select name = "time" id = "time" onchange="displayTable(this.value, plant.value)">
			<?php 
				for ($i = 0; $i < 2; $i++)
				{
					$range = $year[$i] . "-0-0";
					print "<option value = $range>$year[$i]</option>";
					print "<optgroup label = '======================'>";
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
								print "</optgroup><optgroup label = '----------------------'>";
							$range = $year[$i] . "-" . $quarter . "-0";							
							print "<option value = $range>$quarter_name[$quarter]</option>";
							//print "<option value = $range>$quarter</option>";
							print "<optgroup label = '----------------------' style = 'padding-left: 15px; padding-right: 15px;'>";
						}
						$range = $year[$i] . "-0-" . $month[$j];
						print "<option value = $range>" . $month_name[$month[$j]] . "</option>";
					}
					print "</optgroup>";
				}
			?>
			</select>
		</form>
		</br>
		<div id = "txtHint"	></div>
	</body>
</html>
		
				