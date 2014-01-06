<?php

	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database');
	}
	
	require "check_login.php";
	$location_list['K'] = 'Atchison';
	$location_list['D'] = 'David City';
	$location_list['N'] = 'Norristown';
	$location_list['T'] = 'Reading';
	$location_list['R'] = 'Richland';
	if(isset($_COOKIE['ID_my_site']))
	{
		$username = $_COOKIE['ID_my_site'];
		$password = $_COOKIE['Key_my_site'];
		
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $query. " . pg_last_error($conn));
		$user_info = pg_fetch_array($check_results);
		
		if($password == $user_info['car_password'])
		{
			for($i = 0; $i < 4; $i++)
			{
				$j = $i + 1;
				$plant_variable = 'plant' . $j;
				if ($user_info[$plant_variable] != ' ' && $user_info[$plant_variable] != '' && !is_null($user_info[$plant_variable]) )
				{
					$plant_letter = $user_info[$plant_variable];
					if ($plant_letter != 'A')
					{
						$locations[$i] = $user_info[$plant_variable];
						$location_names[$i] = $location_list[$plant_letter];
					}
					else
					{
						$locations = array('K', 'D', 'N', 'T', 'R');
						$location_names = array('Atchison', 'David City', 'Norristown', 'Reading', 'Richland');
						$j = 5;
						break;
					}		
				}	
				else
				{
					$j = $i;
					break;
				}
			}
			//$row_count = $i + 1;
			//print $row_count;
		}
		else
		{
			$locations = array('K', 'D', 'N', 'T', 'R');
			$location_names = array('Atchison', 'David City', 'Norristown', 'Reading', 'Richland');
			$j = 5;
		}		
	}	
	else
	{
		$locations = array('K', 'D', 'N', 'T', 'R');
		$location_names = array('Atchison', 'David City', 'Norristown', 'Reading', 'Richland');
		$j = 5;
	}		
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	//if (strpos($user_agent, 'MSIE 9') !== false	
	$screen = 3;
	$itr_no = '';
	$inspection_date = '';
	$station = '';
	$dept_manager_1 = '';
	$inspector = '';
	$part_no =  '';
	$operator = '';
	$inspection_summary = '';
	$discrepancies = '';
	$interim_containment = '';
	$root_cause = '';
	$corrective_action = '';
	$result = '';
	$cost_pn_1 = '';
	$cost_pn_2 = '';
	$cost_pn_3 = '';
	$cost_pn_4 = '';
	$cost_pn_5 = '';
	$cost1 = '';
	$cost2 = '';
	$cost3 = '';
	$cost4 = '';
	$cost5 = '';
	$cost_qty1 = '';
	$cost_qty2 = '';
	$cost_qty3 = '';
	$cost_qty4 = '';
	$cost_qty5 = '';
	$total_cost1 = '';
	$total_cost2 = '';
	$total_cost3 = '';
	$total_cost4 = '';
	$total_cost5 = '';
	$mfg_hours = '';
	$dept_manager_2 = '';
	$manager_review_date = '';
	$qa_dept_head = '';
	$head_review_date = '';	
	$defect = '00';
	$defect_qty = '';
	$rework_cost = '';
	$submit = "Submit";
    if (strpos($user_agent, 'MSIE 9') === false && strpos($user_agent, 'MSIE 7') === false)
    	$rows = 1;
	else
		$rows = 4;
	if ($_GET['action'] == 'edit' || $_GET['action'] == 'view')
	{
		$query = "SELECT * FROM itr WHERE itr_no = '" . $_GET['itrNo'] . "'";
		$result = pg_query($conn, $query) or die("Error in query: $query " . pg_last_error($conn));
		//$rows = pg_num_rows($result);
		$itr = pg_fetch_array($result);
		$itr_no = $itr['itr_no'];
		$inspection_date = $itr['inspection_date'];
		if($inspection_date != '')
		{
			list($year, $month, $day) = explode("-", $inspection_date);
			$inspection_date = "$month-$day-$year";
		}
		$station = $itr['station'];
		$dept_manager_1 = $itr['dept_manager_1'];
		$inspector = $itr['inspector'];
		$part_no =  $itr['part_no'];
		$operator = $itr['operator'];
		$inspection_summary = $itr['inspection_summary'];
		$discrepancies = $itr['discrepancies'];
		$interim_containment = $itr['interim_containment'];
		$root_cause = $itr['root_cause'];
		$corrective_action = $itr['corrective_action'];
		$result = $itr['result'];
		$cost_pn_1 = $itr['cost_pn1'];
		$cost_pn_2 = $itr['cost_pn2'];
		$cost_pn_3 = $itr['cost_pn3'];
		$cost_pn_4 = $itr['cost_pn4'];
		$cost_pn = array($cost_pn_1,$cost_pn_2,$cost_pn_3,$cost_pn_4);
		$i = 0;
	    if (strpos($user_agent, 'MSIE 9') === false && strpos($user_agent, 'MSIE 7') === false)
		{
			while($cost_pn[$i] != '')
			{
				$rows = $i + 1;
				$i++;
			}
		}

		$cost1 = $itr['cost1'];
		$cost2 = $itr['cost2'];
		$cost3 = $itr['cost3'];
		$cost4 = $itr['cost4'];
		$cost = array($cost1, $cost2, $cost3, $cost4);
		$cost_qty1 = $itr['cost_qty1'];
		$cost_qty2 = $itr['cost_qty2'];
		$cost_qty3 = $itr['cost_qty3'];
		$cost_qty4 = $itr['cost_qty4'];
		$cost_qty = array($cost_qty1, $cost_qty2, $cost_qty3, $cost_qty4);
		if($cost1 != '' && $cost_qty1 != '')
			$total_cost1 = $cost1 * $cost_qty1;
		else
			$total_cost1 = '';
		if($cost2 != '' && $cost_qty2 != '')			
			$total_cost2 = $cost2 * $cost_qty2;
		else
			$total_cost2 = '';
		if($cost3 != '' && $cost_qty3 != '')
			$total_cost3 = $cost3 * $cost_qty3;
		else
			$total_cost3 = '';			
		if($cost4 != '' && $cost_qty4 != '')
			$total_cost4 = $cost4 * $cost_qty4;
		else
			$total_cost4 = '';			
		$total_cost = array($total_cost1, $total_cost2, $total_cost3, $total_cost4);
		$mfg_hours = $itr['mfg_hours'];
		$rate = .3;
		$rework_cost = $mfg_hours * $rate;
		$rework_cost = number_format((float)$rework_cost, 2, '.', '');
		//$rework_cost = $itr['rework_cost'];
		$dept_manager_2 = $itr['dept_manager_2'];
		$manager_review_date = $itr['manager_review_date'];
		if($manager_review_date != '')
		{
			list($year, $month, $day) = explode("-", $manager_review_date);
			$manager_review_date = "$month-$day-$year";
		}
		$qa_dept_head = $itr['dept_head'];
		$head_review_date = $itr['head_review_date'];
		if($head_review_date != '')
		{
			list($year, $month, $day) = explode("-", $head_review_date);
			$head_review_date = "$month-$day-$year";
		}
		$defect = $itr['defect_code'];
		$defect_qty = $itr['defect_qty'];
		$plant = $itr['plant'];
		if ($_GET['action'] == 'edit')
			$submit = "Edit";
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
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="left_border.css">
	<style>
			p.headers
			{
				font-size: 16pt;
				font-weight: bold;
			}
			td.left
			{
				position: relative;
				bottom: 0px;
				left: 0px;
				width: 350px;
				height: 25px;
				text-align: left;
				vertical-align: text-bottom;
			}
			
			td.right
			{
				position: relative;
				bottom: 0px;
				height: 25px;				
				text-align: right;
				vertical-align: text-bottom;
			}

			.mainForm
			{
				position: absolute;
				top: 200px;
				left: 50px;
			}
			
			p
			{
				font-size: 12pt;
				font-weight: bold;
			}
			.underline
			{
				margin-left: 10px;
			}
			input.underline
			{
				border-bottom: 1px solid black;
				border-top: none;
				border-left: none;
				border-right: none;
			}
			
			.tempBorder
			{
				border: 1px solid black;
			}
			.right
			{
				text-align: right;
			}
			
			.print
			{
				display: none;
			}
			
			.screen
			{
				display: inline;
			}
		@media print
		{
			.invisible
			{
				display: none;
			}
			
			/*.pictures
			{
				page-break-after: always;
			}
			*/
			input[type = text], textarea
			{
				border: none;
			}
			
			p.headers
			{
				font-size: 14pt;
			}
			
			td.left
			{
				left: 0px
			}
			
			td.right
			{
				left: 450px;
				top: 0px;
				width: 300px;
				position: absolute;
			}
			
			.print
			{
				display: inline;
			}
			
			.screen
			{
				display: none;
			}
			/*.right .headers
			{
				color: red;
			}*/
			
		}
	</style>
	<script src = "internal_trouble.js"></script>
	<script src = "left_border.js"></script>
	<script type = "text/javascript">
		var browser;
		<?php if (strpos($user_agent, 'MSIE 9') === false && strpos($user_agent, 'MSIE 7') === false)
			{ ?>
			browser = "Other";
			
			<?php }
			else{ ?>
			browser = "IE";
			<? } ?>
			function changeDefect(defectId)
			{
				document.getElementById(defectId).value = document.getElementById("defect").value;
			}
	</script>		
					
</head>
<body>
	<?php require "left_border.php";?>
	<div class = "mainTable">
	
	<?php //IE	
		if (strpos($user_agent, 'MSIE 9') !== false	|| strpos($user_agent, 'MSIE 7') !== false)
		{
			if ($_GET['action'] == "add")
			{?>
		<form name =  'incrForm' action = 'internal_trouble_process.php' method = 'post' enctype = "multipart/form-data" onsubmit = 'this.iDate.dateCheck = true; this.plant.optional = true; this.defectQty.numeric = true; this.discrepancies.optional = true; this.containment.optional = true; this.rootCause.optional = true; this.action.optional = true; this.result.optional = true; this.costPN1.costCheck = true; this.costPN1.optional = true; this.cost1.costCheck = true; this.cost1.numeric = true; this.cost1.optional = true; this.costQty1.costCheck = true; this.costQty1.numeric = true; this.costQty1.optional = true; this.costPN2.costCheck = true; this.costPN2.optional = true; this.cost2.costCheck = true; this.cost2.numeric = true; this.cost2.optional = true; this.costQty2.costCheck = true; this.costQty2.numeric = true; this.costQty2.optional = true; this.costPN3.costCheck = true; this.costPN3.optional = true; this.cost3.costCheck = true; this.cost3.numeric = true; this.cost3.optional = true; this.costQty3.costCheck = true; this.costQty3.numeric = true; this.costQty3.optional = true;this.costPN4.costCheck = true; this.costPN4.optional = true; this.cost4.costCheck = true; this.cost4.numeric = true; this.cost4.optional = true; this.costQty4.costCheck = true; this.costQty4.numeric = true; this.costQty4.optional = true; this.mfgHours.numeric = true; this.manager2.optional = true; this.manager2.signTest = true; this.reviewDate1.optional = true; this.reviewDate1.dateCheck = true; this.reviewDate1.signTest = true; this.reviewDate2.dateCheck = true; this.picture1.optional = true; this.picture2.optional = true; this.picture3.optional = true; return verify(this, rowCount.value)'>
	<?php }
		  else
		  {?>
			 		<form name =  'incrForm' action = 'internal_trouble_process.php' method = 'post' enctype = "multipart/form-data" onsubmit = 'this.iDate.dateCheck = true; this.plant.optional = true; this.defectQty.numeric = true; this.discrepancies.optional = true; this.containment.optional = true; this.rootCause.optional = true; this.action.optional = true; this.result.optional = true; this.costPN1.costCheck = true; this.costPN1.optional = true; this.cost1.costCheck = true; this.cost1.numeric = true; this.cost1.optional = true; this.costQty1.costCheck = true; this.costQty1.numeric = true; this.costQty1.optional = true; this.costPN2.costCheck = true; this.costPN2.optional = true; this.cost2.costCheck = true; this.cost2.numeric = true; this.cost2.optional = true; this.costQty2.costCheck = true; this.costQty2.numeric = true; this.costQty2.optional = true; this.costPN3.costCheck = true; this.costPN3.optional = true; this.cost3.costCheck = true; this.cost3.numeric = true; this.cost3.optional = true; this.costQty3.costCheck = true; this.costQty3.numeric = true; this.costQty3.optional = true;this.costPN4.costCheck = true; this.costPN4.optional = true; this.cost4.costCheck = true; this.cost4.numeric = true; this.cost4.optional = true; this.costQty4.costCheck = true; this.costQty4.numeric = true; this.costQty4.optional = true; this.mfgHours.numeric = true; this.manager2.optional = true; this.manager2.signTest = true; this.reviewDate1.optional = true; this.reviewDate1.dateCheck = true; this.reviewDate1.signTest = true; this.reviewDate2.dateCheck = true; return verify(this, rowCount.value)'> 
		<?php 
		}
	}
	else
	{
		if ($_GET['action'] == "add")
		{?>
	
	<form name =  'incrForm' action = 'internal_trouble_process.php' method = 'post' enctype = "multipart/form-data" onsubmit = 'this.iDate.dateCheck = true; this.plant.optional = true; this.defectQty.numeric = true; this.discrepancies.optional = true; this.containment.optional = true; this.rootCause.optional = true; this.action.optional = true; this.result.optional = true; this.costPN1.costCheck = true; this.costPN1.optional = true; this.cost1.costCheck = true; this.cost1.numeric = true; this.cost1.optional = true; this.costQty1.costCheck = true; this.costQty1.numeric = true; this.costQty1.optional = true; this.mfgHours.numeric = true; this.manager2.optional = true; this.manager2.signTest = true; this.reviewDate1.optional = true; this.reviewDate1.dateCheck = true; this.reviewDate1.signTest = true; this.reviewDate2.dateCheck = true; this.picture1.optional = true; this.picture2.optional = true; this.picture3.optional = true; return verify(this, rowCount.value)'>
	
	<?php }
		else
		{
			?>
		<form name =  'incrForm' action = 'internal_trouble_process.php' method = 'post' enctype = "multipart/form-data" onsubmit = 'this.iDate.dateCheck = true; this.plant.optional = true; this.defectQty.numeric = true; this.discrepancies.optional = true; this.containment.optional = true; this.rootCause.optional = true; this.action.optional = true; this.result.optional = true; this.costPN1.costCheck = true; this.costPN1.optional = true; this.cost1.costCheck = true; this.cost1.numeric = true; this.cost1.optional = true; this.costQty1.costCheck = true; this.costQty1.numeric = true; this.costQty1.optional = true; this.mfgHours.numeric = true; this.manager2.optional = true; this.manager2.signTest = true; this.reviewDate1.optional = true; this.reviewDate1.dateCheck = true; this.reviewDate1.signTest = true; this.reviewDate2.dateCheck = true; return verify(this, rowCount.value)'>
		<?php
		}
	}
	print "<input type = 'hidden' name = 'itrNo' id = 'itrNo' value = $itr_no />"; 
		
	print "<input type = 'hidden' name = 'rowCount' id = 'rowCount' value = $rows />";?>
	<table>
		<tr>
			<td colspan = 2 class = 'left'><p class = "headers">INTERNAL TROUBLE REPORT</p></td><td colspan = 2  class = "right"><?php if ($_GET['action'] == 'edit' || $_GET['action'] == 'view'){ print "<p class = 'headers'>ITR NO: $itr_no </p>"; }?></td>
		</tr>
		<div class = "mainForm">
		<tr><td colspan = 4><hr/></td></tr>
		<tr>
			<?php print '<td>Inspection Date </br><input type = "text" name = "iDate" id = "iDate" size = 10 maxlength = 10 value = "' . $inspection_date . '"';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			//print ' /></td><td>Station </br><input type = "text" name = "station" id = "station" value = "' . $station . '" size = 20 maxlength = 20 ';
			print ' /></td><td>Department </br><input type = "text" name = "station" id = "station" value = "' . $station . '" size = 20 maxlength = 20 ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' /></td>';//<!--<td>Dept. Manager </br><input type = "text" name = "manager1" id = "manager1" value = "' . $dept_manager_1 . '" size = 25 maxlength = 25 ';-->
			/*if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' /></td>*/
			print '<td>Plant</br><select name = "plant" id = "plant" ';
			if ($_GET['action'] == "view")
				print 'disabled = "disabled" ';
			print ">";
					
			for($i = 0; $i < $j; $i++)
			{
				print "<option value = $locations[$i] ";
				if ($locations[$i] == $plant)
				{
					print "selected";
				}
				print ">$location_names[$i]</option>";
			}
			print '</select></td><td>Inspector: </br><input type = "text" name = "inspector" id = "inspector" value = "' . $inspector . '" size = 25 maxlength = 25 ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print '/></td>';?>
		</tr>
		<tr>
			<?php print '<td>Part No. </br><input type = "text" name = "partNo" id = "partNo" size = 20 maxlength = 20 value = "' . $part_no . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			} 
			print ' /></td><td>Operator </br><input type = "text" name = "operator" id = "operator" size = 25 maxlength = 25 value = "' . $operator . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print '/></td>';
			print '<td valign = "baseline" class = "bold">Defect Type</br><select name = "defect" id = "defect" class = "screen" onchange = changeDefect("defectPrint") ' ;
			if ($_GET['action'] == 'view')
				print 'disabled = "disabled" ';
			print ">";
			
			for ($i = 0; $i < $desc_count; $i++)
			{
				print '<option value = "' . $code[$i] . '" ';
				if ($code[$i] == $defect)
					print 'selected ';
				print '>'. $code[$i] . '-' . $code_desc[$i] . '</option>';
			}
			print '</select><input type = "text" name = "defectPrint" id = "defectPrint" class = "print" value = "' . $defect . '"/></td>';
			print '<td>Defect Qty</br><input type = "text" name = "defectQty" id = "defectQty" value = "' . $defect_qty . '" ';
			if ($_GET['action'] == 'view')
				print 'readonly = "readonly"';
			print '/></td>';?>
		</tr>
		<tr>
			<?php print '<td colspan = 4 valign = "baseline" class = "bold">Inspection Summary</br><textarea rows = 3 cols = 107 name = "summary" id = "summary" maxlength = 300 ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' >' . $inspection_summary . '</textarea></td>';?>
		</tr>
		<tr>
			<?php print "<td colspan = 4 valign = 'baseline' class = 'bold'>Discrepancies</br><textarea rows = 3 cols = 107 name = 'discrepancies' id = 'discrepancies' maxlength = 300 ";
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print " >$discrepancies</textarea></td>";?>
		</tr>
		<tr>
			<?php print "<td colspan = 4 valign = 'baseline' class = 'bold'>Interim Containment</br><textarea rows = 4 cols = 107 name = 'containment' id = 'containment' maxlength = 400 ";
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print " >$interim_containment</textarea></td>";?>
			
		</tr>
		<tr>
			<?php print "<td colspan = 4 valign = 'baseline' class = 'bold'>Root Cause</br><textarea rows = 4 cols = 107 name = 'rootCause' id = 'rootCause' maxlength = 400 ";
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print " >$root_cause</textarea></td>";?>			
		<tr>
			<?php print "<td colspan = 4 valign = 'baseline' class = 'bold'>Corrective Action/Preventive Action</br><textarea rows = 7 cols = 107 name = 'action' id = 'action' maxlength = 700 ";
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print " >$corrective_action</textarea></td>"; ?>
		</tr>
		<tr>
			<?php print "<td colspan = 4 valign = 'baseline' class = 'bold'>Result of Preventive Action</br><textarea rows = 10 cols = 107 name = 'result' id = 'result' maxlength = 1000 ";
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print " >$result</textarea></td>"; ?>
		</tr>
		<tr>
			<td colspan = 2>
			<table id = "materialCosts" name = "materialCosts">
				<tr>
					<td colspan = 4>Material Costs</td>
				</tr>
				<tr>
					<td>Part Number</td><td>Cost</td><td>Quantity</td><td>Total Cost</td><!--<td>Manufacturing Hours</td>-->
				</tr>
				<tr>
					<?php print "<td><input type = 'text' name = 'costPN1' id = 'costPN1' class = 'underline' size = 20 maxlength = 20 value = '" . $cost_pn[0] . "'";
					if($_GET['action'] == 'view')
					{
						print 'readonly = readonly';
					}
					print " /></td>";
					print "<td><input type = 'text' name = 'cost1' id = 'cost1' class = 'underline right' size = 8 maxlength = 8 value = '" . $cost[0] ."'";
					if($_GET['action'] == 'view')
					{
						print 'readonly = readonly';
					}
					else
					{
						print " onBlur = 'calculateTotal(cost1, costQty1, totalCost1, 2, browser)";
					}
					print "'/>";
					print "</td><td><input type = 'text' name = 'costQty1' id = 'costQty1' class = 'underline right' size = 4 maxlength = 4 value = '" . $cost_qty[0] . "'";
					if($_GET['action'] == 'view')
					{
						print 'readonly = readonly';
					}
					else
					{
						print " onBlur = 'calculateTotal(cost1, costQty1, totalCost1, 2, browser)'";
					}
					print "/></td>";
					print "<td><p name = 'totalCost1' id = 'totalCost1' class = 'right'>$total_cost1</p></td></tr>";//<td><input type = 'text' name = 'mfgHours' id = 'mfgHours' class = 'underline' size = 3 maxlength = 3 value = '" . $mfg_hours . "'";
					//if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
					if (strpos($user_agent, 'MSIE 9') !== false	|| strpos($user_agent, 'MSIE 7') !== false)
					{
						$total_rows = 4;
					}
					else
						$total_rows = $rows;
					for ($i = 1; $i < $total_rows; $i++)
						{
							$j = $i + 1;
							$k = $j + 1;
							print "<tr><td><input type = 'text' name = 'costPN" . $j . "' id = 'costPN" . $j . "' class = 'underline' size = 20 maxlength = 20 value = '" . $cost_pn[$i] . "'";
							if($_GET['action'] == 'view')
							{
								print 'readonly = readonly';
							}
							print " /></td>";
							print "<td><input type = 'text' name = 'cost" . $j . "' id = 'cost" . $j . "' class = 'underline right' size = 8 maxlength = 8 value = '" . $cost[$i] . "' ";
							if($_GET['action'] == 'view')
							{
								print 'readonly = readonly ';
							}
							else
							{
								print "onBlur = 'calculateTotal(cost". $j . ", costQty" . $j . ", totalCost" . $j . ", " . $k . ", browser)";
							}
							print "'/>";
							print "</td><td><input type = 'text' name = 'costQty" . $j . "' id = 'costQty" . $j . "' class = 'underline right' size = 4 maxlength = 4 value = '" . $cost_qty[$i] . "'";
							if($_GET['action'] == 'view')
							{
								print 'readonly = readonly';
							}
							else
							{
								print " onBlur = 'calculateTotal(cost" . $j . ", costQty" . $j  . ", totalCost" . $j . ", " . $k . ", browser)'";
							}
							print "/></td>";
							print "<td><p name = 'totalCost" . $j . "' id = 'totalCost" . $j . "' class = 'right'>$total_cost[$i]</p></td></tr>";
						}
					
					/*if($_GET['action'] == 'view')
				//	{
				//		print 'readonly = readonly';
				//	}
					print " /></td>";*/
					?>
				</tr>
			</table>
		</td>
		<td colspan = 2 valign = "top">
			<table>
				<tr><td>&nbsp</td></tr>
				<tr>
					<td>Manufacturing Minutes</td><td style = "padding-left: 10px">Rework Cost</td>
				</tr>
				<tr>
					<?php print "<td><input type = 'text' name = 'mfgHours' id = 'mfgHours' class = 'underline' size = 3 maxlength = 3 value = '" . $mfg_hours . "'";
					if($_GET['action'] == 'view')
					{
						print 'readonly = readonly';
					}
					print " onBlur = 'calculateRework(mfgHours, reworkCost)'/></td><td><p name = 'reworkCost' id = 'reworkCost' class = 'right' >$rework_cost</p></td>";
					?>
				</tr>
			</table>
		</td>	
		</tr>
		<?php
		if ((strpos($user_agent, 'MSIE 9') === false && strpos($user_agent, 'MSIE 7') === false) && ($_GET['action'] != 'view'))
    {?>
		<tr>
			<td colspan = 4 class = "screen"><input type = 'button' name = 'add' id = 'add' value = "Add Row" onClick = "addRow(materialCosts, add)" /></td>
		</tr>
		<?php }?>
		<tr>
			<td colspan = 4>Reviewed By</td>
		</tr>
		<tr>
			<td colspan = 2>Department Manager: <?php print '<input type = "text" name = "manager2" id = "manager2" size = 25 maxlength = 25 value = "' . $dept_manager_2 . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' />';?>
			</td><td colspan = 2>Date: <?php print '<input type = "text" name = "reviewDate1" id = "reviewDate1" size = 10 maxlength = 10 value = "' . $manager_review_date . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' />';?></td>
		</tr>
		<tr>
			<td colspan = 2>QA Department Head: <?php print '<input type = "text" name = "deptHead" id = "deptHead" size = 25 maxlength = 25 value = "' . $qa_dept_head . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' />';?></td><td colspan = 2>Date: <?php print '<input type = "text" name = "reviewDate2" id = "reviewDate2" size = 10 maxlength = 10 value = "' . $head_review_date . '" ';
			if($_GET['action'] == 'view')
			{
				print 'readonly = readonly';
			}
			print ' />&nbsp';
			/*print '<select name = "defect" id = "defect">';
			for ($i = 0; $i < $desc_count; $i++)
			{
				print '<option value = "' . $code[$i] . '">'. $code[$i] . '-' . $code_desc[$i] . '</option>';
			}*/?></td>
		</tr>
		<tr>
		<?php 
			if ($_GET['action'] == 'add')
			{
				
				$picture_class = 'uploadPic';
				print "<td>Upload Pictures of Internal Trouble</td></tr><tr>";		
				print "<td class = '" . $picture_class . ", screen' ><input type = 'file' name = 'picture1' id = 'picture1' ></br><input type = 'file' name = 'picture2' id = 'picture2' ></br><input type = 'file' name = 'picture3' id = 'picture3' ></td>";
			}?>
			</tr>	
			 
		</div>		
	</table>
	<?php if ($_GET['action'] == 'view') { print '<input type="button" class = "invisible" value="Back" onClick="history.go(-1);return true;">'; }else { print "<input type = 'submit' name = 'submit' id = 'submit' value = $submit class = 'invisible' />"; } ?>

	
	<table>
	
	<?php
	if ($_GET['action'] != 'add')
			{
				$picture_class = 'pictures';
				print "<tr><td class = '" . $picture_class . "' >";
				if (file_exists("images/" . $itr_no . "a.JPG"))
				{
					print "<img src = 'images/" . $itr_no . "a.JPG' height = 225 width = 300></td></tr>";
				}
				else
				{
					if (file_exists("images/" . $itr_no . "a.jpg"))
						print "<tr><td><img src = 'images/" . $itr_no . "a.jpg' height = 225 width = 300>";
				}					
				if (file_exists("images/" . $itr_no . "b.JPG"))
				{
					print "&nbsp<tr><td><img src = 'images/" . $itr_no . "b.JPG' height = 225 width = 300>";
				}
				else
				{
					if (file_exists("images/" . $itr_no . "b.jpg"))
						print "&nbsp<tr><td><img src = 'images/" . $itr_no . "b.jpg' height = 225 width = 300>";
				}					
				
				if (file_exists("images/" . $itr_no . "c.JPG"))
				{
					print "&nbsp<tr><td><img src = 'images/" . $itr_no . "c.JPG' height = 225 width = 300>";
					
				}
				else
				{
					if (file_exists("images/" . $itr_no . "c.jpg"))
						print "&nbsp<img src = 'images/" . $itr_no . "c.jpg' height = 225 width = 300>";
				}					
				
				print "</td>";
			}
		?>
		</tr>
	</table>
	</form>	
	</div>

</body>
</html>