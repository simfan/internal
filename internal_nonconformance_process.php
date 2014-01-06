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
	require "check_login.php";
	//incr_no will either be 0 or the number to be updated	
	$form_type = $_POST['formType'];

	$record_no = htmlentities($_POST['incrNo']);
	$table = 'incr';
	$record_type = 'incr_no';

	$supplier = htmlentities($_POST['supplier']);
	$f_part_no = htmlentities($_POST['fPartNo']);
	//print $f_part_no;
	$rev = htmlentities($_POST['rev']);
	$v_part_no = htmlentities($_POST['vPartNo']);
	$order_date = htmlentities($_POST['orderDate']);
	$desc = htmlentities($_POST['desc']);
	$serial_no = htmlentities($_POST['serial']);
	$price = htmlentities($_POST['ppu']);
	$buyer = htmlentities($_POST['buyer']);
	$level = htmlentities($_POST['level']);
	$po_no = htmlentities($_POST['po']);
	$qty_rec = htmlentities($_POST['qtyRec']);
	$qty_rejected = htmlentities($_POST['qtyRejected']);
	$account_no = htmlentities($_POST['account']);
	$date = htmlentities($_POST['date']);
	$non_con = htmlentities($_POST['descNonCon']);
	$root_cause = htmlentities($_POST['rootCause']);
	if (isset($_POST['engChange']))
		$eng = 1;
	else 
		$eng = 0;
	if (isset($_POST['supply']))
		$s_car = 1;
	else
		$s_car = 0;
	if (isset($_POST['other']))
		$other = 1;
	else
		$other = 0;
	if (isset($_POST['none']))
		$none = 1;
	else
		$none = 0;
	$repair = htmlentities($_POST['repair']);
	$rework = htmlentities($_POST['rework']);
	$use = htmlentities($_POST['use']);
	$scrap = htmlentities($_POST['scrap']);
	$return = htmlentities($_POST['returned']);
	$replace = htmlentities($_POST['replace']);
	$actionee = htmlentities($_POST['actionee']);
	$due_date = htmlentities($_POST['dueDate']);
	$action = htmlentities($_POST['action']);
	$project = htmlentities($_POST['project']);
	$project_date = htmlentities($_POST['projectDate']);
	$purchasing = htmlentities($_POST['purchasing']);
	$purchasing_date = htmlentities($_POST['purchasingDate']);
	$quality = htmlentities($_POST['quality']);
	$quality_date = htmlentities($_POST['qualityDate']);
	$operation = htmlentities($_POST['operation']);
	$operation_date = htmlentities($_POST['operation_date']);
	$tech = htmlentities($_POST['tech']);
	$tech_date = htmlentities($_POST['techDate']);
	$prod_control = htmlentities($_POST['prod']);
	$prod_date = htmlentities($_POST['prodDate']);
	$prep = htmlentities($_POST['prep']);
	$prep_date = htmlentities($_POST['prepDate']);
	$plant = htmlentities($_POST['plant']);
	if($_POST['submit'] == 'Submit')
	{
		
		$query_max = "SELECT * FROM " . $table . " ORDER BY incr_no DESC LIMIT 1";
		$result_max = pg_query($conn, $query_max) or die("Error in query: $query_max. " . pg_last_error($conn));
		$max_row = pg_fetch_array($result_max);
		//print $max_row;
		$max_incr_no = intval($max_row['incr_no']);
		//print $max_incr_no;
		//print "&nbsp" . $max_incr_no + 1;
	
		$incr_no = $max_incr_no + 1;
		if ($incr_no < 10)
		{
			$incr_no = '00000' . $incr_no;
		}
		elseif($incr_no < 100)
		{	
			$incr_no = '0000' . $incr_no;
		}
		elseif($incr_no < 1000)
		{
			$incr_no = '000' . $incr_no;
		}
		elseif($incr_no < 10000)
		{
			$incr_no = '00' . $incr_no;			
		}
		elseif($incr_no < 100000)
		{
			$incr_no = '0' . $incr_no;
		}	

		print $incr_no;
	
		$query = "INSERT INTO incr(incr_no, incr_plant, incr_supplier, incr_fargo_part, incr_rev, incr_vendor_part, incr_order_date, incr_desc, incr_serial_no, incr_price, incr_buyer, incr_inspection_level, incr_po_no, incr_qty_received, incr_qty_rejected, incr_account_no, incr_date, incr_noncon, incr_root_cause, incr_eng, incr_s_car, incr_other, incr_none, incr_repair_qty, incr_rework_qty, incr_as_is_qty, incr_scrap_qty, incr_return_qty, incr_replace_qty, incr_actionee, incr_due_date, incr_corrective_action, incr_project, incr_project_date, incr_purchasing, incr_purchasing_date, incr_quality, incr_quality_date, incr_operation, incr_operation_date, incr_tech, incr_tech_date, incr_prod_control, incr_prod_date, incr_prepared_by, incr_prepared_date, vcar_created_by) VALUES('" . $incr_no . "', '" . $plant . "', '" . $supplier . "', '" . $f_part_no . "', '" .  $rev . "', '" .  $v_part_no . "', '" .  $order_date . "', '" .  $desc . "', '" .  $serial_no . "', '" .  $price . "', '" .  $buyer . "', '" .  $level . "', '" .  $po_no . "', '" .  $qty_rec . "', '" .  $qty_rejected . "', '" .  $account_no . "', '" .  $date . "', '" .  $non_con . "', '" .  $root_cause . "', '" .  $eng . "', '" .  $s_car . "', '" .  $other . "', '" .  $none . "', '" .  $repair . "', '" .  $rework . "', '" .  $use . "', '" .  $scrap . "', '" . $return . "', '" . $replace . "', '" .  $actionee . "', '" . $due_date . "', '" . $action . "', '" . $project . "', '" . $project_date . "', '" . $purchasing . "', '" . $purchasing_date  . "', '" . $quality . "', '" . $quality_date . "', '" . $operation . "', '" . $operation_date . "', '" . $tech . "', '" . $tech_date . "', '" . $prod_control . "', '" . $prod_date . "', '" . $prep . "', '" . $prep_date . "', '" . $username . "' )";
	}	
	
	
	if($_POST['submit'] == 'Edit')
	{
		$query = "UPDATE incr SET incr_plant = '" . $plant . "', incr_supplier = '" . $supplier . "', incr_fargo_part = '" . $f_part_no . "', incr_rev = '" . $rev . "', incr_vendor_part = '" . $v_part_no . "', incr_order_date = '" . $order_date . "', incr_desc = '" . $desc . "', incr_serial_no = '" . $serial_no . "', incr_price = '" . $price . "', incr_buyer = '" . $buyer . "', incr_inspection_level = '" . $level . "', incr_po_no = '" . $po_no . "', incr_qty_received = '" . $qty_rec . "', incr_qty_rejected = '" . $qty_rejected . "', incr_account_no = '" . $account_no . "', incr_date = '" . $date . "', incr_noncon = '" . $non_con . "', incr_root_cause = '" . $root_cause . "', incr_eng = '" . $eng . "', incr_s_car = '" . $s_car . "', incr_other = '" . $other . "', incr_none = '" . $none . "', incr_repair_qty = '" . $repair . "', incr_rework_qty = '" . $rework . "', incr_as_is_qty = '" . $use . "', incr_scrap_qty = '" . $scrap . "', incr_return_qty = '" . $return . "', incr_replace_qty = '" . $replace . "', incr_actionee = '" . $actionee . "', incr_due_date = '" . $due_date . "', incr_corrective_action = '" . $action . "', incr_project = '" . $project . "', incr_project_date = '" . $project_date . "', incr_purchasing = '" . $purchasing . "', incr_purchasing_date = '" . $purchasing_date . "', incr_quality = '" . $quality . "', incr_quality_date = '" . $quality_date . "', incr_operation = '" . $operation . "', incr_operation_date = '" . $operation_date . "', incr_tech = '" . $tech . "', incr_tech_date = '" . $tech_date . "', incr_prod_control = '" . $prod_control . "', incr_prod_date = '" . $prod_date . "', incr_prepared_by = '" . $prep . "', incr_prepared_date = '" . $prep_date . "' WHERE incr_no = '" . $record_no . "'";
	}
	
	if($_GET['action'] == 'delete')
	{
		$car_no = $_GET['carno'];
		$query = "DELETE FROM incr WHERE incr_no  = '" . $record_no . "'";
	}
	print $query;
	$result = pg_query($conn, $query) or die("Error in query: $query." . pg_last_error($conn));

	
	//$result = ($conn, $query) or die("Error in query: $query" . pg_last_error($conn));
	$query = "COMMIT";
	if($_POST['submit'] == 'Submit')
	{
		$email_subject = "A VCAR has just been submitted.<br/><a href = '192.168.10.129/internaldefect/internal_nonconformance.php?incrNo=" . $incr_no . "&action=view'>VCAR #". $incr_no . "</a>";
		$email_subject .= 	"<p>FARGO ASSEMBLY OF PA<br/>800 W.WASHINGTON ST. NORRISTOWN, PA<br/>VENDOR CORRECTIVE ACTION REPORT</p><table style = 'border: 1px solid black; border-collapse:collapse;'><tr><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Supplier</span><br/>$supplier</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Plant</span><br/>$plant</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Fargo Part Number</span><br/>$f_part_no</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Rev</span><br/>$rev</td></tr><tr><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Vendor Part Number</span><br/>$v_part_no</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Order Date</span><br/>$order_date</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Description</span><br/>$desc</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Serial Number</span><br/>$serial_no</td></tr><tr><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Price Per Unit</span><br/>$price</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Buyer</span><br/>$buyer</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Inspection Level</span><br>$level</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>P.O. Number</span><br/>$po_no</td></tr><tr><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Qty Received</span><br/>$qty_rec</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Qty Rejected</span><br/>$qty_rejected</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Account Number</span><br/>$account_no</td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Date</span><br/>$date</td></tr><tr><td colspan = 4 style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Description of Nonconformance</span><br/><p>$non_con</p></td></tr><tr><td colspan = 4 style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Root Cause(Analysis Results)</span><br/><p>$root_cause</p></td></tr><tr><td colspan = 4 style = 'border: 1px solid black; border-collapse:collapse;'><table style = 'border: 1px solid black; border-collapse:collapse;'><tr><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Disposition</span></td><td style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Quantity</span></td><td rowspan = 2 style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Actionee</span><br/>$actionee</td></tr><tr><td rowspan = 3 style = 'border: 1px solid black;'><input type = 'checkbox' />Engineering Change Order<br/><input type = 'checkbox' />Supplier Corrective Action Request<br/><input type = 'checkbox' />Other(Describe Here)<br/><input type = 'checkbox' />None Required</td><td rowspan = 3 style = 'border: 1px solid black;'><input type = 'checkbox' />Repair<br/><input type = 'checkbox' />Rework To Dwg<br/><input type = 'checkbox' />Use As Is<br/><input type = 'checkbox' />Scrap<br/><input type = 'checkbox' /> Return To Vendor<br/><input type = 'checkbox' />Replacement Required</td></tr><tr><td style = 'border: 1px solid black;' rowspan = 2><span style = 'font-weight: bold;'>Due Date</span><br/>$due_date</td></tr></table></td></tr><tr><td colspan = 4 style = 'border: 1px solid black;'><span style = 'font-weight: bold;'>Corrective/Preventive Action</span><br/><p>$action</p></td></tr></table><br/>Vendor Signature:";
		$email_query = "SELECT * FROM car_login WHERE car_username = '" . $username . "'";
		$email_result = pg_query($conn, $email_query) or die("Error in email query: $email_query" . pg_last_error($conn));
		$email = pg_fetch_array($email_result);
		$first_name = $email['first_name'];
		$last_name = $email['last_name'];
		$email_from = $email['email'];
		$po_lead = substr($po_no, 0, 1);
		switch($po_lead)
		{
			case 1:
				$send_to = "michellek@fargopa.com";
				$email_switch = 1;
				break;
			case 3:
				$send_to = "bobs@fargopa.com";	
				$email_switch = 1;
				break;
			case 4:
				$send_to = "gerrir@fargopa.com";
				$email_switch = 1;
				break;
			default:
				$email_switch = 0;
				break;
		}
		if($email_switch == 1)
		{
			$headers = "From: $first_name $last_name <" . $email_from . ">\r\n";
			$headers .= "Cc: $first_name $last_name <" . $email_from . ">\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			mail($send_to, "VCAR Submitted", $email_subject, $headers);
			//print "mail($send_to, VCAR Submitted, $email_subject, $headers)";
		}
	}	
	$result = pg_query($conn, $query) or die("Error in query: $query" . pg_last_error($conn));
	header("Location: index.php");
	ob_end_flush();
?>