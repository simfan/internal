<html>
<head>
	<!--<link rel="stylesheet" type="text/css" href="incr_table_print.css" media = "print">-->
	<style>
			
	@media screen
	{
		table
		{
			width: 3500px;
			table-layout: fixed;
		}
	}
	</style>
	<!--<style>
	
		table
		{
			border-collapse: collapse;
			width: 3500px;
			table-layout: fixed;
		}

		
		tr, td
		{
			padding: 5px;
			border: solid black 1px;
		}
		
	
		@media print
		{
			.tableText
			{
				font-size: 8pt;
			}
			
			.headingText
			{
				font-size: 6pt;
			}
		}
	</style>-->
	<link rel="stylesheet" type="text/css" href="table.css">

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
		$location = $_POST['plant'];
		$login = "login.php?location=table&status=" . $status . "&plant=" .$location;
	$login_text = "Log In";	
	$match = 0;
	//checks to see if a user is logged in.
	if(isset($_COOKIE['ID_my_site']))
	{
		$username = $_COOKIE['ID_my_site'];
		$password = $_COOKIE['Key_my_site'];
		
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $query. " . pg_last_error($conn));
		$user_info = pg_fetch_array($check_results);
		
		if($password == $user_info['car_password'])
		{
			$password_check = 1;
			$login = "logout.php?location=table&status=" . $status . "&plant=" . $location;
			$login_text = "Log Out";
			if ($user_info['plant1'] == 'A')
			{
				$match = 1;
			}
			else
			{
				for($i == 0; $i < 4; $i++)
				{
					$j = $i + 1;
					$plant_name = 'plant' . $j;
					
					if($user_info[$plant_name] == $location)// || $location = "A")
					{
						$match = 1;
						break;
					}
				}
			}
		}
	}
	//                  0             1               2                  3           4                    5                  6         7                8                9             10                      11            12                   13                    14               15              16       17             18               19     20           21                    21               22                   23              24                 25                 26                    27
	$db_field = array('incr_no', 'incr_plant', 'incr_supplier', 'incr_fargo_part', 'incr_rev', 'incr_vendor_part', 'incr_order_date', 'incr_desc', 'incr_serial_no', 'incr_price', 'incr_buyer', 'incr_inspection_level', 'incr_po_no', 'incr_qty_received', 'incr_qty_rejected', 'incr_account_no', 'incr_date', 'incr_eng', 'incr_s_car', 'incr_other', 'incr_none', 'incr_repair_qty', 'incr_rework_qty', 'incr_as_is_qty', 'incr_scrap_qty', 'incr_return_qty', 'incr_replace_qty', 'incr_actionee', 'incr_due_date');
	$html_field = array('incrNo', 'plant', 'supplier', 'fPartNo', 'rev', 'vPartNo', 'orderDate', 'desc', 'serialNo', 'ppu', 'buyer', 'level', 'poNo', 'qtyRec', 'qtyRejected', 'account', 'date', 'eng', 'scar', 'other', 'none', 'repair', 'rework', 'use', 'scrap', 'return', 'replace', 'actionee', 'dueDate');
	if($password_check == 1)
	{
		if($_POST['records'] == 'All Records')
			$html_field[21] = 'records';
	}	
	$table_fields = array('INCR No', 'Plant', 'Supplier', 'Fargo Part Number', 'Rev', 'Vendor Part Number', 'Order Date', 'Description', 'Serial Number', 'Price Per Unit', 'Buyer', 'Inspection Level', 'P.O. Number', 'Qty Received', 'Qty Rejected', 'Account Number', 'Date', 'Engineering Change Order', 'Supplier Corrective Action Request', 'Other', 'None Required', 'Repair', 'Rework to Dwg', 'Use As Is', 'Scrap', 'Return to Vendor', 'Replacement Required', 'Actionee', 'Due Date');
	$size =  count($html_field);
	$query = "SELECT * FROM incr ";
	$field_count = 0;
	for ($i = 0; $i < $size; $i++)
	{
		$link_switch = 0;
		$enter_switch = 0;
		if($_POST[$html_field[$i]])	
			$field_value[$i] = $_POST[$html_field[$i]];
		if($_GET[$html_field[$i]])
			$field_value[$i] = $_GET[$html_field[$i]];
		switch($i)
		{
		case(1):
		if($field_value[$i] != "All")
		{
			$link_switch = 1;
			$enter_switch = 1;
		}	
		break;	
		case(2):		
			if($field_value[$i] <> '000')
			{
				$link_switch = 1;
				$enter_switch = 1;
			}
			break;
		
		case(17):
		case(18):
		case(19):
		case(20):
			if (isset($_POST[$html_field[$i]]))
			{
				$link_switch = 1;
				$enter_switch = 1;
			}
			break;
		case(21):
			if ($_POST['records'] == 'My Records')
			{
				$link_switch = 1;
				if($field_count == 0)
					$query_conditions = "WHERE ";
				if($field_count > 0)
					$query_conditions .= "AND ";
				$query_conditions .= "vcar_created_by = '" . $username . "' ";
				$field_count++;
				$match = 1;
			}
		default:
			if ($_POST[$html_field[$i]] != '')
			{
				$link_switch = 1;
				$enter_switch = 1;
			}
			break;
		}
		//print $enter_switch;				
		if($enter_switch == 1)
		{
			if($field_count == 0)
				$query_conditions = "WHERE ";
			if ($field_count > 0)
				$query_conditions .= "AND ";
			$query_conditions .= "$db_field[$i] = '" . $field_value[$i] . "' " ;
			$field_count++;
		}
			
		if($link_switch == 1)
		{
			if($switch_count == 0)
				$link = "?" . $html_field[$i] . "=" . $field_value[$i];
			if($switch_count > 0)
				$link .= "&" . $html_field[$i] . "=" . $field_value[$i];
			$switch_count++;
		}
		
		
	}
	if($password_check == 1)
		$size = $size - 1;
	$query .= $query_conditions;
	$query .= "ORDER BY incr_no";
	print $query;
	$result = pg_query($conn, $query) or die("Error in query: $query " . pg_last_error($conn));
	$rows = pg_num_rows($result);
	?>
	<script type = "text/javascript">
		var i;
		var j;
		var incrNo = new Array();
		var plant = new Array();
		var supplier = new Array();
		var fPartNo = new Array();
		var rev = new Array();
		var vPartNo = new Array();
		var orderDate = new Array();
		var desc = new Array();
		var serialNo = new Array();
		var ppu = new Array();
		var buyer = new Array();
		var level = new Array();
		var poNo = new Array();
		var qtyRec = new Array();
		var qtyRejected = new Array();
		var account = new Array();
		var date = new Array();
		var eng = new Array();
		var scar = new Array();
		var other = new Array();
		var none = new Array();
		var repair = new Array();
		var rework = new Array();
		var use = new Array();
		var scrap = new Array();
		var returnToVendor = new Array();
		var replace = new Array();
		var actionee =  new Array();
		var dueDate = new Array();
		var incr = new Array();
		var fieldNames = new Array();
		var fieldValue = new Array();
		var formType = <?php echo "'" . $_POST['formType'] . "'"; ?>;
		var match = <?php echo $match; ?>;
	<?php
	for ($i = 0; $i < $rows; $i++)
	{
		$incr = pg_fetch_array($result);
	
		$incr_no = $incr['incr_no'];
		//print "INCR Number $incr_no";
		$plant = $incr['incr_plant'];
		$supplier = $incr['incr_supplier'];
		if ($supplier == '')
			$supplier = "&nbsp";
		$fargo_part = $incr['incr_fargo_part'];
		if ($fargo_part == '')
			$fargo_part = "&nbsp";
		$rev = $incr['incr_rev'];
		if ($rev == '')
			$rev = "&nbsp";
		$vendor_part = $incr['incr_vendor_part'];
		if ($vendor_part == '')
			$vendor_part = "&nbsp";
		$order_date = $incr['incr_order_date'];
		if ($order_date == '')
			$order_date = "&nbsp";
			//$order_date = "00/00/0000";
		$desc = $incr['incr_desc'];
		if ($desc == '')
			$desc = "&nbsp";
		$serial_no = $incr['incr_serial_no'];
		if ($serial_no == '')
			$serial_no = "&nbsp";
		$price = $incr['incr_price'];
		if ($price == '')
			$price = "0";
		$buyer = $incr['incr_buyer'];
		if ($buyer == '')
			$buyer = "&nbsp";
		$level = $incr['incr_inspection_level'];
		if ($level == '')
			$level = "&nbsp";
		$po_no = $incr['incr_po_no'];
		if ($po_no == '')
			$po_no = "0";
		$qty_rec = $incr['incr_qty_received'];
		if ($qty_rec == '')
			$qty_rec = "0";
		$qty_rejected = $incr['incr_qty_rejected'];
		if ($qty_rejected == '')
			$qty_rejected = "0";
		$date = $incr['incr_date'];
		if ($date == '')
			$date = "&nbsp";
			//$date = "00/00/0000";
		$eng = $incr['incr_eng'];
		if ($eng == '')
			$eng = "&nbsp";
		$s_car = $incr['incr_s_car'];
		if ($s_car == '')
			$s_car = "&nbsp";
		$other = $incr['incr_other'];
		if ($other == '')
			$other = "&nbsp";
		$none = $incr['incr_none'];
		if ($none == '')
			$none = "&nbsp";
		$repair = $incr['incr_repair_qty'];
		if ($repair == '')
			$repair = "0";
		$rework = $incr['incr_rework_qty'];
		if ($rework == '')
			$rework = "0";
		$use = $incr['incr_as_is_qty'];
		if ($use == '')
			$use = "0";
		$scrap = $incr['incr_scrap_qty'];
		if ($scrap == '')
			$scrap = "0";
		$return = $incr['incr_return_qty'];
		if ($return == '')
			$return = "0";
		$replace = $incr['incr_replace_qty'];
		if ($replace == '')
			$replace = "0";
		$actionee = $incr['incr_actionee'];
		if ($actionee == '')
			$actionee = "&nbsp";
		$due_date = $incr['incr_due_date'];
		if ($due_date == '')
			$due_date = "&nbsp";
			//$due_date = "00/00/0000";
	?>
	i = <?php echo $i;?>;
	incrNo[i] = <?php echo "'" . $incr_no . "'";?>;
	plant[i] = <?php echo "'" . $plant . "'";?>;
	supplier[i] = <?php echo "'" . $supplier . "'";?>;
	fPartNo[i] = <?php echo "'" . $fargo_part . "'";?>;
	rev[i] = <?php echo "'" . $rev . "'";?>;
	vPartNo[i] = <?php echo "'" . $vendor_part  . "'"; ?>;
	orderDate[i] = <?php echo "'" . $order_date . "'"; ?>;
	desc[i] = <?php echo "'" . $desc . "'"; ?>;
	serialNo[i] = <?php echo "'" . $serial_no . "'";?>;
	ppu[i] = <?php echo "'" . $price . "'"; ?>;
	buyer[i] = <?php echo "'" . $buyer . "'"; ?>;
 	level[i] = <?php echo "'" . $level . "'"; ?>;
	poNo[i] = <?php echo "'" . $po_no . "'";?>;
	qtyRec[i] = <?php echo "'" . $qty_rec . "'";?>;
	qtyRejected[i] = <?php echo "'" . $qty_rejected . "'";?>;
	account[i] = <?php echo "'" . $account . "'"; ?>;
	date[i] = <?php echo "'" . $date . "'"; ?>;
	eng[i] = <?php echo "'" . $eng . "'"; ?>;
	scar[i] = <?php echo "'" . $s_car . "'";?>;
	other[i] = <?php echo "'" . $other . "'";?>;
	none[i] = <?php echo "'" . $none . "'";?>;
	repair[i] = <?php echo "'" . $repair . "'";?>;
	rework[i] = <?php echo "'" . $rework . "'";?>;
	use[i] = <?php echo "'" . $use . "'";?>;
	scrap[i] = <?php echo "'" . $scrap . "'";?>;
	returnToVendor[i] = <?php echo "'" . $return . "'";?>;
	replace[i] = <?php echo "'" . $replace . "'"; ?>;
	actionee[i] =  <?php echo "'" . $actionee . "'";?>;
	dueDate[i] = <?php echo "'" . $due_date . "'";?>;
	incr[i] = [incrNo[i], plant[i], supplier[i], fPartNo[i], rev[i], vPartNo[i], orderDate[i], desc[i], serialNo[i], ppu[i], buyer[i], level[i], poNo[i], qtyRec[i], qtyRejected[i], account[i], date[i], eng[i], scar[i], other[i], none[i], repair[i], rework[i], use[i], scrap[i], returnToVendor[i], replace[i], actionee[i], dueDate[i]];
	
	<?php }
	
	for ($i = 0; $i < $size; $i++)
	{
		?>
	i= <?php echo $i; ?>;
	fieldNames[i] = <?php echo "'" . $table_fields[$i] . "'"; ?>;
	fieldValue[i] = <?php echo "'" . $html_field[$i] . "'"; ?>;
	<?php } ?>
		
	<?php 
		for($i = 0; $i < $rows; $i++)
		{
	?>	
			i = <?php echo $i; ?>;
	<?php
			for($j = 0; $j < $size; $j++)	
			{
	?>
				j = <?php echo $j; ?>;
				
	<?php
			}
		}
	?>
	function sortTableAlpha(tableField, sorted)
	{
		var sortText = fieldValue[tableField] + "Sort";
		var direction = document.getElementById(sortText).value;
		var fieldCount = <?php echo $size;?>;
		var fieldName;
		var viewText;
		var editText;
		var deleteText;
		incr.sort(function(a,b)
		{
			var fieldA = a[tableField].toLowerCase(), fieldB = b[tableField].toLowerCase()
			if (fieldA < fieldB)
				return -1
			if (fieldA > fieldB)
				return 1
			if (fieldA === fieldB)
				return(a[0] - b[0])
		})
		if (direction == -1)
			incr.reverse();
		for(var i = 0; i < fieldCount; i++)
		{	
			fieldName = fieldValue[i] + "Sort";
			//alert(fieldValue[i] + ' ' + fieldNames[i]);
			//document.getElementById(fieldValue[i]).innerHTML = fieldNames[i];
			document.getElementById(fieldName).value = 1
		}
		for(var i = 0; i < tableRows; i++)
		{
			viewText = "viewLink" + i;
			editText = "editLink" + i;
			deleteText = "deleteLink" + i;
			
			document.getElementById(viewText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+"&action=view";
			if (match == 1)
			{
				document.getElementById(editText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+"&action=edit";
			}	
			/*document.getElementById(deleteText).href = "internal_nonconformance_process.php?incrNo="+incr[i][0]+"&action=delete";*/
			for(var j = 0; j < fieldCount; j++)
			{
				documentText = fieldValue[j] + i;
				document.getElementById(documentText).innerHTML = incr[i][j];
			}
			
		//document.getElementById(incrText).innerHTML = totalRows;
			
		}
		if (direction == 1)
		{
			newDirection = -1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
		}
		if (direction == -1)
		{
			newDirection = 1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
		}
			
		document.getElementById(sortText).value = newDirection;
	}
	function sortTableNum(tableField, sorted)
	{
		var sortText = fieldValue[tableField] + "Sort";
		var direction = document.getElementById(sortText).value;
		var fieldCount = <?php echo $size;?>;
		var fieldName;
		var viewText;
		var editText;
		var deleteText;
		incr.sort(function(a,b)
		{
			var fieldA
			var fieldB
			if (a[tableField] == '')
				fieldA = 0
			else
				fieldA = a[tableField].toLowerCase()
			
			if (b[tableField] == '')
				fieldB = 0
			else
				fieldB = b[tableField].toLowerCase()
				
			if (fieldA === fieldB)
				return(a[0] - b[0])
			else
				return(fieldA - fieldB)
		})
		
		if (direction == -1)
			incr.reverse();
			
		for(var i = 0; i < fieldCount; i++)
		{	
			fieldName = fieldValue[i] + "Sort";
			//document.getElementById(fieldValue[i]).innerHTML = fieldNames[i];
			document.getElementById(fieldName).value = 1;
		}
		
		for(var i = 0; i < tableRows; i++)
		{
			viewText = "viewLink" + i;
			editText = "editLink" + i;
			deleteText = "deleteLink" + i;
			
			document.getElementById(viewText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+"&action=view";
			if (match == 1)
			{
				document.getElementById(editText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+"&action=edit";
			}
			/*document.getElementById(deleteText).href = "internal_nonconformance_process.php?incrNo="+incr[i][0]+"&action=delete";*/
			for(var j = 0; j < fieldCount; j++)
			{
				documentText = fieldValue[j] + i;
				document.getElementById(documentText).innerHTML = incr[i][j];
			}
				//	incrText = "incrNo" + i;
			//...
			
			
			//document.getElementById(incrText).innerHTML = totalRows;
			
		}
		if (direction == 1)
		{
			newDirection = -1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
		}
		if (direction == -1)
		{
			newDirection = 1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
		}
		document.getElementById(sortText).value = newDirection;		
	}
	function sortTableDate(tableField, sorted)
	{
		var sortText = fieldValue[tableField] + "Sort";
		var direction = document.getElementById(sortText).value;
		var fieldCount = <?php echo $size;?>;
		var fieldName;
		var viewText;
		var editText;
		var deleteText;
		
		incr.sort(function(a,b)
		{
			var fieldArrayA
			var fieldArrayB
			var fieldA;
			var fieldB;
			
			if(a[tableField] == "&nbsp")
			{
				fieldA = "00000000";
			}
			else
			{
				fieldArrayA= a[tableField].split("/")
				fieldA = fieldArrayA[2] + fieldArrayA[0] + fieldArrayA[1]
			}
			/*else
			{
				fieldA = a[tableField]
			}
			*/
			if(b[tableField] == "&nbsp")
			{
				fieldB = "00000000";
			}
			else
			{
				fieldArrayB = b[tableField].split("/")
				fieldB = fieldArrayB[2] + fieldArrayB[0] + fieldArrayB[1]				
			}
			
			/*else
			{
				fieldB = b[tableField]
			}*/
				
			if (fieldA == fieldB)
				return(a[0] - b[0])
			else
				return(fieldA - fieldB)
		})
		
		if (direction == -1)
			incr.reverse();
		for(var i = 0; i < fieldCount; i++)
		{	
			fieldName = fieldValue[i] + "Sort";
			//document.getElementById(fieldValue[i]).innerHTML = fieldNames[i];
			document.getElementById(fieldName).value = 1;
		}
		for(var i = 0; i < tableRows; i++)
		{
			viewText = "viewLink" + i;
			editText = "editLink" + i;
			deleteText = "deleteLink" + i;
			
			document.getElementById(viewText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+ "&action=view";
			
			if (match == 1)
			{
				document.getElementById(editText).href = "internal_nonconformance.php?incrNo="+incr[i][0]+ "&action=edit";
			}	
			/*document.getElementById(deleteText).href = "internal_nonconformance_process.php?incrNo="+incr[i][0]+ "&action=delete";*/
			for(var j = 0; j < fieldCount; j++)
			{
				documentText = fieldValue[j] + i;
				document.getElementById(documentText).innerHTML = incr[i][j];
			}
			
		//document.getElementById(incrText).innerHTML = totalRows;
			
		}
		if (direction == 1)
		{
			newDirection = -1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
		}
		if (direction == -1)
		{
			newDirection = 1;
			//document.getElementById(fieldValue[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
		}
		document.getElementById(sortText).value = newDirection;
	}		
	
	</script>	
</head>
<body>
<?php if ($rows > 0)
{
	?>
	<h1>VCAR Results</h1>
	<table>
		<tr>
			<script type = "text/javascript">
				var tableFields = <?php echo $size;?>;
				var sortNum;
				var sortDate;
				var sortAlpha;
				
				if(match == 1)
				{
					/*if(carPlantOne == 'A')
					//	document.write("<th colspan = 3 class = 'linkText'></th>");
					//else*/
						document.write("<th colspan = 2 class = 'linkText'></th>");
				}
				else
					document.write("<th class = 'linkText'></th>");
				//document.write("<th></th>");
				for (var i = 0; i < tableFields; i++)
				{
					if (i == 24)
					{
						fieldValueTemp = fieldValue[i];
						fieldValue[i] = "returnToVendor";
					}
					sortNum = "sortTableNum(" + i + ", "+ fieldValue[i] + ")";
					sortDate = "sortTableDate(" + i + ", " + fieldValue[i] + ")";
					sortAlpha = "sortTableAlpha(" + i + ", " + fieldValue[i] + ")";
					switch(i)
					{
						case 0:
						case 8:
						case 11:
						case 12:
						case 13:
						case 20:
						case 21:
						case 22:
						case 23:
						case 24:
						case 25:
							sortType = "numeric";
							break;
						case 5:
						case 15:
						case 27:
							sortType = "date";
							break;
						default:
							sortType = "alpha";
							break;
					}
					//alert("After Switch");
					if(sortType == "numeric")
						document.write("<th onClick = '" + sortNum + "' class = 'headingText'>"+fieldNames[i]+"</th>");	
					if(sortType == "date")
						document.write("<th onClick = '" + sortDate + "' class = 'headingText'>"+fieldNames[i]+"</th>");	
					if(sortType == "alpha")
						document.write("<th onClick = '" + sortAlpha + "' class = 'headingText'>"+fieldNames[i]+"</th>");
				}
			</script>
		</tr>
		<script type = "text/javascript">
			var tableRows = <?php echo $rows;?>;
			var sortType;
			//var fieldValue;
			for(var i = 0; i < tableRows; i++)
			{
				document.write("<tr><td class = 'linkText'><a name = 'viewLink" + i + "' id = 'viewLink" + i + "' href = 'internal_nonconformance.php?incrNo=" + incr[i][0] + "&action=view'>View</a></td>");
				if(match == 1)
				{
					document.write("<td class = 'linkText'><a name = 'editLink" + i + "' id = 'editLink" + i + "' href = 'internal_nonconformance.php?incrNo=" + incr[i][0] + "&action=edit'>Edit</a></td>");
					/*if(carPlantOne == 'A')
					{
						document.write("<td class = 'linkText'><a name = 'deleteLink" + i + "' id = 'deleteLink" + i + "'href = 'internal_nonconformance_process.php?incrNo=" + incr[i][0] + "action=delete'>Delete</a></td>");
					}*/
				}				
				for (var j = 0; j < tableFields; j++)
					document.write("<td name = '" + fieldValue[j] + i + "' id = '" + fieldValue[j] + i + "' class = 'tableText'><span>" + incr[i][j] + "</span></td>");
				
				document.write("</tr>");
			}
			for(var i = 0; i < tableFields; i++)
				document.write("<input type = 'hidden' name = '" + fieldValue[i] + "Sort' id = '" + fieldValue[i] + "Sort' value = 1 />");
		</script>
	</table>
	<?php }
	else{
		print "No fields matched your search criteria";
		print "</br><a href = 'search.php'>Back</a>";
	}?>
</body>
</html>