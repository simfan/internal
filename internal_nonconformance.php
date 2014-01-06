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
	
	if(isset($_COOKIE['ID_my_site']))
	{
		$username = $_COOKIE['ID_my_site'];
		$password = $_COOKIE['Key_my_site'];
		
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $query. " . pg_last_error($conn));
		$user_info = pg_fetch_array($check_results);
		
		if($password == $user_info['car_password'])
			$password_check = 1;
	
	}
	
	$plant_values = array("K", "D", "N", "T", "R");
	$plant_names = array("Atchison", "David City", "Norristown", "Reading", "Richland");
	$plant_size = count($plant_values);
	$screen = 4;	
	$record_no = '';
	$supplier = '3MC';
	$fargo_part = '';
	$rev = '';
	$vendor_part = '';
	$order_date = '';
	$desc = '';
	$serial_no = '';
	$price = '';
	$buyer = '';
	$inspection_level = '';
	$po_no = '';
	$qty_received = '';
	$qty_rejected = '';
	$account_no = '';
	$date = '';
	$noncon = '';
	$root_cause = '';
	$eng = '';
	$s_car = '';
	$other = '';
	$none = '';
	$repair = '';
	$rework = '';
	$as_is = '';
	$scrap = '';
	$return = '';
	$replace = '';
	$actionee = '';
	$due_date = '';
	$corrective_action = '';
	$project = '';
	$project_date = '';
	$purchasing = '';
	$purchasing_date = '';
	$quality = '';
	$quality_date = '';
	$operation = '';
	$operation_date = '';
	$tech = '';
	$tech_date = '';
	$prod_control = '';
	$prod_date = '';
	$prepared_by = '';
	$prepared_date = '';
	$plant = '';	
	$submit = 'Submit';
	if ($_GET['action'] == 'view' || $_GET['action'] == 'edit')
	{
		$query = "SELECT * FROM incr WHERE incr_no = '" . $_GET['incrNo'] . "'";
		$result = pg_query($conn, $query) or die("Error in query: $query." . pg_last_error($conn));
		
		$record = pg_fetch_array($result);
		$record_no = $record['incr_no'];
		$supplier = $record['incr_supplier'];
		$fargo_part = $record['incr_fargo_part'];
		$rev = $record['incr_rev'];
		$vendor_part = $record['incr_vendor_part'];
		$order_date = $record['incr_order_date'];
		$desc = $record['incr_desc'];
		$serial_no = $record['incr_serial_no'];
		$price = $record['incr_price'];
		$buyer = $record['incr_buyer'];
		$inspection_level = $record['incr_inspection_level'];
		$po_no = $record['incr_po_no'];
		$qty_received = $record['incr_qty_received'];
		$qty_rejected = $record['incr_qty_rejected'];
		$account_no = $record['incr_account_no'];
		$date = $record['incr_date'];
		$noncon = $record['incr_noncon'];
		$root_cause = $record['incr_root_cause'];
		$eng = $record['incr_eng'];
		$s_car = $record['incr_s_car'];
		$other = $record['incr_other'];
		$none = $record['incr_none'];
		$repair = $record['incr_repair_qty'];
		$rework = $record['incr_rework_qty'];
		$as_is = $record['incr_as_is_qty'];
		$scrap = $record['incr_scrap_qty'];
		$return = $record['incr_return_qty'];
		$replace = $record['incr_replace_qty'];
		$actionee = $record['incr_actionee'];
		$due_date = $record['incr_due_date'];
		$corrective_action = $record['incr_corrective_action'];
		$project = $record['incr_project'];
		$project_date = $record['incr_project_date'];
		$purchasing = $record['incr_purchasing'];
		$purchasing_date = $record['incr_purchasing_date'];
		$quality = $record['incr_quality'];
		$quality_date = $record['incr_quality_date'];
		$operation = $record['incr_operation'];
		$operation_date = $record['incr_operation_date'];
		$tech = $record['incr_tech'];
		$tech_date = $record['incr_tech_date'];
		$prod_control = $record['incr_prod_control'];
		$prod_date = $record['incr_prod_date'];
		$prepared_by = $record['incr_prepared_by'];
		$prepared_date = $record['incr_prepared_date'];
		$plant = $record['incr_plant'];	
		$submit = 'Edit';
	}
		$fh = fopen('/u/in.dt/vendor.dt', 'rb');
		$i = 0;
		$j = 0;
		for ($line = fgets($fh); !feof($fh); $line = fgets($fh))
		{
			$line = trim($line);
			list($vendor_code[$i], $vendor_name[$i], $rest[$i]) = explode("\t", $line, 3);
			$vendor_code[$i] = trim($vendor_code[$i]);
			$i++;
		}
		$vendor_count = $i;
		fclose($fh);
		array_multisort($vendor_code, $vendor_name);
		for ($i = 0; $i < $vendor_count; $i++)
		{
			if (strlen($vendor_code[$i]) != '   ') 
			{
				$good_vendor_code[$j] = $vendor_code[$i];
				$good_vendor_name[$j] = $vendor_name[$i];
				$j++;
			}
		}
		array_multisort($good_vendor_code, $good_vendor_name);
		$good_vendor_count = $j;
		
?>
<!DOCTYPE html>
<html>
<head>
	
	<link rel="stylesheet" type="text/css" href="incr.css">
	<link rel="stylesheet" type="text/css" href="left_border.css">
	<link rel="stylesheet" type="text/css" href="incr_print.css" media = "print">
	<script src = "internal_nonconformance.js"></script>
	<script src = "left_border.js"></script>
	<!--<script type = "text/javascript">
		upload();
	</script>
	/*	var searchDisplay = "none";
		var newDisplay = "none";
	function toggleDisplay(divID)
	{	
	
		if (document.getElementById(divID).style.display == "table")
			document.getElementById(divID).style.display = "none";
			
		
		else if(document.getElementById(divID).style.display == "none")
			document.getElementById(divID).style.display = "table";
			
		
	}
	function changeVendor(vendorCode)
	{
		document.getElementById(vendorCode).value = document.getElementById('supplier').value;
	}*/
	</script>-->
</head>
<body class = "centerTable">
	
	<!--<div class = "leftBorder">
		<div>Navigation<br></div>
		<div class = "leftLinks"><a class = "left" href = "index.php">Index</a><br>
			<div class = "cat" onClick = "toggleDisplay('search')">Search</div>
			<div class = "search" id = "search" style="display: none">
				<a class = "left" href = "search_trouble.html">Internal Trouble</a><br>
				<a class = "left" href = "search.php">VCAR</a><br>
			</div>
			<?php if ($password_check == 1){?>
				<div class = "cat" onclick = "toggleDisplay('new')">New</div>
				<div class = "new" id = "new" style="display: none">
					<a class = "left" href = "internal_trouble.php" style = "font-size: 10pt">Internal Trouble Report</a>
				</div>
			<?php }?>
		</div>
	</div>-->
	<?php require "left_border.php"; ?>
	<div class = "mainTable">
	<div>
	<div class = "center">FARGO ASSEMBLY OF PA<br>
	800 W.WASHINGTON ST. NORRISTOWN, PA<br>
	VENDOR CORRECTIVE ACTION REPORT</div> 

	
	<?php if ($_GET['action'] == 'view' || $_GET['action'] == 'edit')
	{
			print "<div align = 'right'>VCAR NO: " . $record_no . "</div>";
			//print "<input type = 'hidden' name = 'incrNo' id = 'incrNo' value = $record_no />";		
	}?>
	</div>
	<form name =  'incrForm' action = 'internal_nonconformance_process.php' method = 'post' onsubmit = 'this.fPartNo.required = true; this.rev.optional = true; this.vPartNo.required = true; this.orderDate.required = true; this.orderDate.dateCheck = true; this.desc.required = true; this.serial.optional = true; this.ppu.optional = true; this.ppu.numeric = true; this.buyer.required = true; this.level.required = true; this.po.required = true; this.qtyRec.required = true; this.qtyRec.numeric = true; this.qtyRejected.required = true; this.qtyRejected.numeric = true; this.account.optional = true; this.date.required = true; this.date.dateCheck = true; descNonCon.required = true; this.rootCause.optional = true; this.repair.numeric = true; this.repair.optional = true; this.rework.numeric = true; this.rework.optional = true; this.use.numeric = true; this.use.optional = true; this.scrap.numeric = true; this.scrap.optional = true; this.returned.numeric = true; this.returned.optional = true; this.replace.numeric = true; this.replace.optional = true; this.actionee.optional = true; this.dueDate.optional = true; this.dueDate.dateCheck = true; this.project.optional = true; this.projectDate.optional = true; this.projectDate.dateCheck = true; this.purchasing.optional = true; this.purchasingDate.optional = true; this.purchasingDate.dateCheck = true; this.quality.optional = true; this.qualityDate.optional = true; this.qualityDate.dateCheck = true;this.operation.optional = true; this.operationDate.optional = true; this.operationDate.dateCheck = true; this.tech.optional = true; this.techDate.optional = true; this.techDate.dateCheck = true; this.prod.optional = true; this.prodDate.optional = true; this.prodDate.dateCheck = true; this.prep.optional = true; this.prepDate.optional = true; this.prepDate.dateCheck = true; return verify(this)'>	
	<input type = 'hidden' name = 'incrNo' id = 'incrNo' value = <?php echo $_GET['incrNo'] ?> >
	<table>
		<tr>
			<td class = "center">Supplier<br><div class = "supplierScreen"><select name = "supplier" id = "supplier" onchange = "changeVendor('supplierHidden')" <?php if($_GET['action'] == 'view'){print "disabled = 'disabled' ";	}?>><?php
			for($i = -1; $i < $good_vendor_count; $i++)
			{
				print "<option value = '" . $good_vendor_code[$i] . "' ";
				if ($good_vendor_code[$i] == $supplier)
				{
					print "selected";
				}
				print " >" . $good_vendor_code[$i] . " - " . $good_vendor_name[$i] . "</option>";
			}?>><!--<option value = 'Thermolink'>Thermolink</option><option value = 2>2</option><option value = 3>3</option>--></select><!--<input type = "text" name = "supplier" id = "supplier" size = 20 maxlength = 20/>-->
			</div>
			<div class = "supplierPrint"><?php print "<input type = 'text' name = 'supplierHidden' id = 'supplierHidden' size = 3 maxlength = 3 value = $supplier />"; ?></div></td><td>Plant<br><?php print '<select name = "plant" id = "plant"' ;
																																																		if($_GET['action'] == "view")
																																																			print "disabled = 'disabled' ";
																																																		print ">";
																																																	for($i = 0; $i < $plant_size; $i++)
																																																			{
																																																				print "<option value = '" . $plant_values[$i] ."' ";
																																																				if($plant_values[$i] == $plant)
																																																					print "selected ";
																																																				print ">$plant_names[$i]</option>";
																																																			}
																																																	?>
																																																	<!--<option value = "D">David City</option>
																																																	<option value = "N">Norristown</option>
																																																	<option value = "T">Reading</option>
																																																	<option value = "R">Richland</option>-->
																																																  </select></td>
			<td class = "center">Fargo Part Number<br><?php print '<input type = "text" name = "fPartNo" id = "fPartNo" size = 20 maxlength = 20 value = "' . $fargo_part . '"';
			 if($_GET['action'] == 'view')
			 { 
				 print "readonly = 'readonly' ";
			 }
			 print "></input>";?></td>
			<td class = "center red">Rev<br><?php print'<input type = "text" name = "rev" id = "rev" size = 4 maxlength = 4 value = "' . $rev . '"'; 
			 if($_GET['action'] == 'view')
			 { 
		 		print "readonly = 'readonly' ";
		 	 }
			 print "></input>";?>
			</td>
		</tr>
		<tr>
			<td class = "center">Vendor Part Number<br><?php print '<input type = "text" name = "vPartNo" id = "vPartNo" size = 20 maxlength = 20 value = "' . $vendor_part . '"';
			 if($_GET['action'] == 'view')
			 { 
				 print "readonly = 'readonly' ";
			 }
			 print "></input>";?>
			 </td>
			<td class = "center red">Order Date<br><?php print'<input type = "text" name = "orderDate" id = "orderDate" size = 10 maxlength = 10 value = "' . $order_date . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
	<!--	</tr>
		<tr>-->
			<td class = "center">Description<br><?php print '<input type = "text" name = "desc" id = "desc" size = 20 maxlength = 20 value = "' . $desc . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
			<td class = "center red">Serial Number<br><?php print '<input type = "text" name = "serial" id = "serial" size = 20 maxlength = 20 value = "' . $serial_no . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
		</tr>
		<tr>			
			<td class = "center red">Price Per Unit<br><?php print '<input class = "right" type = "text" name = "ppu" id = "ppu" size = 10 maxlength = 10 value = "' . $price . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
			<td class = "center">P.O. Number<br><?php print '<input type = "text" name = "po" id = "po" size = 10 maxlength = 10 value = "' . $po_no . '" onblur = "setBuyer(this.value)"'; 
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?></td>
			<td class = "center">Buyer<br><?php print '<input type =  "text" name = "buyer" id = "buyer" size = 20 maxlength = 20 value = "' . $buyer . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?></td>
			<td class = "center">Inspection Level<br><?php print '<input type = "text" name = "level" id = "level" size = 10 maxlength = 10 value = "' . $inspection_level . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?></td>
		<!--</tr>
		<tr>-->

		</tr>
		<tr>			
			<td class = "center">Qty Received<br><?php print '<input type = "text" name = "qtyRec" id  = "qtyRec" class = "right" size = 5 maxlegnth = 5 value = "' . $qty_received . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
			<td class = "center">Qty Rejected<br><?php print '<input type = "text" name = "qtyRejected" id = "qtyRejected" class = "right" size = 5 maxlength = 5 value = "' . $qty_rejected . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
			<td class = "center red">Account Number<br><?php print '<input type = "text" name = "account" id = "account" size = 10 maxlength = 10 value = "' . $account_no . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
			<td class = "center">Date Received<br><?php print '<input type = "text" name = "date" id = "date" size = 10 maxlength = 10 value = "' . $date . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print"></input>";?>
			</td>
		</tr>
		<tr>
			<td colspan = 5>Description of Nonconformance<br>
			<?php print '<textarea name = "descNonCon" id = "descNonCon" rows = 5 cols = 90 maxlength = 400  value = "' . $noncon . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print">$noncon</textarea>";?>
			</td>
		</tr>
		<tr>
			<td colspan = 5><span class = "blue">Root Cause(Analysis Results)</span><br>
			<?php print '<textarea name = "rootCause" id = "rootCause" rows = 5 cols = 90 maxlength = 400 value = "' . $root_cause . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print">$root_cause</textarea>";?>
			</td>
		</tr>			
		<tr>
			<td colspan = 5>
				<table class = "centerTable blue">
					<tr>
						<th>Disposition</th><th>Quantity</th><th>Actionee</th>
					</tr>
					<tr>
						<td rowspan = 3><label><input type = "checkbox" name = "engChange" id = "engChange" onclick = "dispositionChecked('engChange')" <?php if($eng == 1) {print "checked = 'checked' ";} if($_GET['action'] == 'view'){print "disabled = 'disabled' ";	}?>/>Engineering Change Order</label><br>
										<label><input type = "checkbox" name = "supply" id = "supply" onclick = "dispositionChecked('supply')" <?php if($s_car == 1) {print "checked = 'checked' ";} if($_GET['action'] == 'view'){print "disabled = 'disabled' ";	}?> />Supplier Corrective Action Request</label><br>
										<label><input type = "checkbox" name = "other" id = "other" onclick = "otherChecked()" <?php if($other == 1) {print "checked = 'checked' ";} if($_GET['action'] == 'view'){print "disabled = 'disabled' ";	}?> />Other (Describe Below)</label><br><div name = "fieldArea" id = "fieldArea" class = "otherText"></div>
										<label><input type = "checkbox" name = "none" id = "none" onclick = "dispostionNoneChecked()" <?php if($none == 1) {print "checked = 'checked' ";} if($_GET['action'] == 'view'){print "disabled = 'disabled' ";	}?> />None Required</label>
						</td>
						<td rowspan = 3><label><?php print '<input type = "text" name = "repair" id = "repair" class = "right" size = 5 maxlength = 5 value = "' . $repair . '"';
												if($_GET['action'] == 'view')
												{ 
													print "readonly = 'readonly' ";
												}
												print"></input>";?>
												&nbsp Repair</label><br>
										<label><?php print '<input type = "text" name = "rework" id = "rework" class = "right" size = 5 maxlength = 5 value = "' . $rework . '"';
												if($_GET['action'] == 'view')
												{ 
													print "readonly = 'readonly' ";
												}
												print"></input>";?>
												&nbsp Rework To Dwg</label><br>
										<label><?php print '<input type = "text" name = "use" id = "use" class = "right" size = 5 maxlength = 5 value = "' . $as_is . '"';
										if($_GET['action'] == 'view')
										{ 
											print "readonly = 'readonly' ";
										}
										print"></input>";?>&nbsp Use As Is</label><br>
										<label><?php print '<input type = "text" name = "scrap" id = "scrap" class = "right" size = 5 maxlength = 5 value = "' . $scrap . '"';
										if($_GET['action'] == 'view')
										{ 
											print "readonly = 'readonly' ";
										}
										print"></input>";?>
										&nbsp Scrap</label><br>
										<label><?php print '<input type = "text" name = "returned" id = "returned" class = "right" size = 5 maxlength = 5 value = "' . $return . '"';
										if($_GET['action'] == 'view')
										{ 
											print "readonly = 'readonly' ";
										}
										print"></input>";?>
										&nbsp Return To Vendor</label><br>
										<label><?php print '<input type = "text" name = "replace" id = "replace" class = "right" size = 5 maxlength = 5 value = "' . $replace . '"';
										if($_GET['action'] == 'view')
										{ 
											print "readonly = 'readonly' ";
										}
										print"></input>";?>
										&nbsp Replacement Required</label></td>
						<td class = "center"><?php print '<input type = "text" name = "actionee" class = "center" id = "actionee" size = 25 maxlength = 25 value = "' . $actionee . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print "></input>";?>
						</td>
					</tr>
					<tr><td class = "center">Due Date</td></tr>
					<tr><td class = "center"><?php print '<input type = "text" name = "dueDate" id = "dueDate" class = "center" size = 10 maxlength = 10 value = "' . $due_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan = 5><span class = "blue">Corrective/Preventive Action</span><br>
			<?php print '<textarea name = "action" id = "action" rows = 5 cols = 90 maxlength = 400 value = "' . $corrective_action . '"';
			if($_GET['action'] == 'view')
			{ 
				print "readonly = 'readonly' ";
			}
			print">$corrective_action</textarea>";?>
			</td>
		</tr>			
		<tr>
			<td colspan = 5>
				<!--<table class = "centerTable">
					<tr>
						<td colspan = 4 class = "center">Corrective Action Committe Board</td>
					</tr>
					<tr>
						<td style = "border-right: none;" >Project: <br><?php print '<input type = "text" name = "project" id = "project" size = 25 maxlength = 25 value = "' . $project . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "projectDate" id = "projectDate" size = 10 maxlength = 10 value = "' . $project_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-right: none;">Purchasing: <br><?php print '<input type = "text" name = "purchasing" id = "purchasing" size = 25 maxlength = 25 value = "' . $purchasing . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "purchasingDate" id = "purchasingDate" size = 10 maxlength = 10 value = "' . $purchasing_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td>
					</tr>
					<tr>
						<td style = "border-right: none;">Quality: <br><?php print '<input type = "text" name = "quality" id = "quality" size = 25 maxlength = 25 value = "' . $quality . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "qualityDate" id = "qualityDate" size = 10 maxlength = 10 value = "' . $quality_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-right: none;">Operation: <br><?php print '<input type = "text" name = "operation" id = "operation" size = 25 maxlength = 25 value = "' . $operation . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "operationDate" id = "operationDate" size = 10 maxlength = 10 value = "' . $operation_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td>
					</tr>
					<tr>
						<td style = "border-right: none;">Technical: <br><?php print '<input type = "text" name = "tech" id = "tech" size = 25 maxlength = 25 value = "' . $tech . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "techDate" id = "techDate" size = 10 maxlength = 10 value = "' . $tech_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-right: none;">Prod Control: <br><?php print '<input type = "text" name = "prod" id = "prod" size = 25 maxlength = 25 value = "' . $prod_control . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td><td style = "border-left: none;">Date: <br><?php print '<input type = "text" name = "prodDate" id = "prodDate" size = 10 maxlength = 10 value = "' . $prod_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td>
					</tr>
					<tr>
						<td colspan = 2 style = "border-left: none; border-right: none; border-bottom: none;"></td><td style = "border-left: none; border-right: none; border-bottom: none;">Prepared By: <br><?php print '<input type = "text" name = "prep" id = "prep" size = 25 maxlength = 25 value = "' . $prepared_by . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?><td style = "border-left: none; border-right: none; border-bottom: none;">Date: <br><?php print '<input type = "text" name = "prepDate" id = "prepDate" size = 10 maxlength = 10 value = "' . $prepared_date . '"';
						if($_GET['action'] == 'view')
						{ 
							print "readonly = 'readonly' ";
						}
						print"></input>";?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan = 5>Minimum signatures by affected areas</td>
		</tr>
	</table>-->
	<div class = 'formButtons'><?php if($_GET['action'] <> 'view')
								{
									print '<input type = "submit" name = "submit" id = "submit" value = "' . $submit . '" class = "invisible"/>';
								}
								?>
			<input type="button"  class = "invisible" name = 'printButton' id = 'printButton' value = "Print" onClick="window.print()"></div>			
	</div>
	</tr>
	</table>
	</form>
	<form name = "upload" id = "upload" action = "upload.php" enctype = "multipart/form-data" method = "post">
	<div id = 'carFileNotify'></div>
	<div id = 'carFile' name = 'carFile' >
		<?php 
			if($_GET['action'] == 'edit' || $_GET['action'] == 'view')
			{
				if(file_exists('CARs/' . $record_no . '.pdf'))
				{
					print "<embed name = 'carDisplay' id = 'carDisplay' src = 'CARs/" .$record_no . ".pdf' width = '700' height = '700' /></div>"; 
				}

				else if(file_exists('CARs/' . $record_no . '.jpg'))
				{
					print "<img name = 'carDisplay' id = 'carDisplay' src = 'CARs/" .$record_no . ".jpg' width = '700' height = '700' />";
				}

				else
				{
					if($_GET['action'] == 'edit')
					{?>
						<input type = 'hidden' name = 'incrNo' id = 'incrNo' value = "<?php print $record_no ?>" /><input type = 'file' name = 'car' id = 'car' multiple = ''/>&nbsp<input type = "submit" name = "uploadCar" id = "uploadCar" value = "Upload Car" /><!--onClick = "uploadCAR(car.value)" --> <embed id = "carDisplay" name = "carDisplay" src = "" style = "display: none" width = '700' height = '700' /><?php 
					}
				}
			}?>
	</div>
	</form>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script src = "uploadThis.js"></script>
</body>
</html>