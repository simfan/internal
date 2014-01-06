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
	$screen = 2;
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="incr_search.css">
	<link rel="stylesheet" type="text/css" href="left_border.css">
	<script src = "left_border.js"></script>
	<!--<script type = "text/javascript">
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
	}-->
	</script>
</head>
<body>
	<?php require "left_border.php"; ?>
	<!--<div class = "leftBorder">
		<div>Navigation</br></div>
		<div class = "leftLinks"><a class = "left" href = "index.php">Index</a></br>
			<div class = "cat" onClick = "toggleDisplay('search')">Search</div>
			<div class = "search" id = "search" style="display: none">
				<a class = "left" href = "search_trouble.html">Internal Trouble</a><br>
			</div>
			<?php if ($password_check == 1){?>
				<div class = "cat" onclick = "toggleDisplay('new')">New</div>
				<div class = "new" id = "new" style="display: none">
					<a class = "left" href = "internal_trouble.php" style = "font-size: 10pt">Internal Trouble Report</a></br>
					<a class = "left" href = "internal_nonconformance.php">VCAR</a> 
				</div>
			<?php }?>
		</div>		</div>
	</div>-->
	<div class = "mainTable">
	<h2>Search INCR</h2>
	<form name = "incrSearch" action = "internal_table.php" method = post >
		<table>
			<tr>
				<td>Incr No</td><td><input type = "text" name = "incrNo" id = "incrNo" size = 6 maxlength = 6 /></td>
			</tr>
			<tr>
				<td>Plant</td><td><select name = "plant" id = "plant">
									<option value = "All">All</option>
									<option value = "K">Atchison</option>
									<option value = "D">David City</option>
									<option value = "N">Norristown</option>
									<option value = "T">Reading</option>
									<option value = "R">Richland</option>
								</select></td>
			</tr>
			<tr>
				<td>Supplier</td><td><select name = "supplier" id = "supplier" >
										<option value = '000'>All</option>
										<optgroup label = '-------------------------------------------------------'></optgroup>
				<?php for($i = 0; $i < $good_vendor_count; $i++)
						{
							print "<option value = $good_vendor_code[$i]>$good_vendor_code[$i] -- $good_vendor_name[$i]</option>";
						}
				?>
				</select>	
				<!--<input type = "text" name = "supplier" id = "supplier" size = 50 maxlength = 50 />--></td>
			</tr>
			<tr>
				<td>Fargo Part Number</td><td><input type = "text" name = "fPartNo" id = "fPartNo" size = 20 maxlength = 20 /></td>
			</tr>	
			<tr>
				<td>Rev</td><td><input type = "text" name = "rev" id = "rev" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Vendor Part Number</td><td><input type = "text" name = "vPartNo" id = "vPartNo" size = 20 maxlength = 20 /></td>
			</tr>	
			<tr>
				<td>Order Date</td><td><input type = "text" name = "orderDate" id = "orderDate" size = 10 maxlength = 10 /></td>
			</tr>
			<tr>
				<td>Description</td><td><input type = "text" name = "desc" id = "desc" size = 20 maxlength = 20 /></td>
			</tr>
			<tr>
				<td>Serial Number</td><td><input type = "text" name = "serialNo" id = "serialNo" size = 20 maxlength = 20 /></td>
			</tr>
			<tr>
				<td>Price Per Unit</td><td><input type = "text" name = "ppu" id = "ppu" size = 10 maxlength = 10 /></td>
			</tr>
			
			<tr>
				<td>Buyer</td><td><input type = "text" name = "buyer" id = "buyer" size = 20 maxlength = 20 /></td>
			</tr>
			<tr>
				<td>Inspection Level</td><td><input type = "text" name = "level" id = "level" size = 10 maxlength = 10 /></td>
			</tr>	
			<tr>
				<td>P.O. Number</td><td><input type = "text" name = "poNo" id = "poNo" size = 7 maxlength = 7 /></td>
			</tr>
			<tr>
				<td>Qty Received</td><td><input type = "text" name = "qtyRec" id = "qtyRec" size = 5 maxlength = 5 /></td>
			</tr>	
			<tr>
				<td>Qty Rejected</td><td><input type = "text" name = "qtyRejected" id = "qtyRejected" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Account Number</td><td><input type = "text" name = "account" id = "account" size = 10 maxlength = 10 /></td>
			</tr>
			<tr>
				<td>Date</td><td><input type = "text" name = "date" id = "date" size = 10 maxlength = 10 /></td>
			</tr>
			</br>
		
			<tr>
				<td colspan = 2>
					<table class = "checkTable">
						<tr class = "group">
							<td colspan = 4>Disposition</td>
						</tr>
						<tr>
							<td align = "right"><label>Engineering Change Order<input type = "checkbox" name = "eng" id = "eng" value = 1 /></label></td>
						</tr>
						<tr>
							<td align = "right"><label>Supplier Corrective Action Request<input type = "checkbox" name = "scar" id = "scar" value = 1 /></label></td>
						</tr>
						<tr>	
							<td align = "right"><label>Other<input type = "checkbox" name = "other" id = "other" value = 1 /></label></td>
						</tr>
						<tr>
							<td align = "right"><label>None Required<input type = "checkbox" name = "none" id = "none" value = 1 /></label></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan = 2>Quantity</td>
			</tr>
			<tr>
				<td>Repair</td><td><input type = "text" name = "repair" id = "repair" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Rework to Dwg</td><td><input type = "text" name = "rework" id = "rework" size = 5 maxlength = 5 /></td>
			</tr>				 	
			<tr>
				<td>Use As Is</td><td><input type = "text" name = "use" id = "use" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Scrap</td><td><input type = "text" name = "scrap" id = "scrap" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Return to Vendor</td><td><input type = "text" name = "return" id = "return" size = 5 maxlength = 5 /></td>
			</tr>
			<tr>
				<td>Replacement Required</td><td><input type = "text" name = "replace" id = "replace" size = 5 maxlength = 5 /></td>
			</tr>
			
			<tr>
				<td>Actionee</td><td><input type = "text" name = "actionee" id = "actionee" size = 25 maxlength = 25 /></td>
			</tr>
			<tr>
				<td>Due Date</td><td><input type = "text" name = "dueDate" id = "dueDate" size = 10 maxlength = 10 /></td>
			</tr>
			<?php if ($password_check == 1)
					{
			?>
				<tr>
					<td>Search:</td><td><label><input type = "checkbox" name = "records" id = "records" value = "My Records" />My Records</label></td>
				</tr>
			<?php }?>
			
<!--				<tr>
				<td>Supplier</td><td><input type = "text" name = "supplier" id = "supplier" size = 50 maxlength = 50 /></td>
			</tr>
			<tr>
				<td>Fargo Part Number</td><td><input type = "text" name = "fPartNo" id = "fPartNo" size = 20 maxlength = 20 /></td>
			</tr>-->
		</table>
		
		<input type = "submit" name = "search" id = "search" value = "Search" />
	</form>
	</div>
</body>
</html>		