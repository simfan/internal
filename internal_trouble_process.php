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
	$itr_no = htmlentities($_POST['itrNo']);
	$inspection_date = htmlentities($_POST['iDate']);
	if($inspection_date != '')
	{
		$inspection_date = str_replace("/", "-", $inspection_date);
		list($month, $day, $year) = explode("-", $inspection_date);
		$inspection_date = "$year-$month-$day";
	}
	//print $inspection_date;
	$station = htmlentities($_POST['station']);
	$dept_manager_1 = htmlentities($_POST['manager1']);
	$inspector = htmlentities($_POST['inspector']);
	$part_no = htmlentities($_POST['partNo']);
	$operator = htmlentities($_POST['operator']);
	$inspection_summary = htmlentities($_POST['summary']);
	$discrepancies = htmlentities($_POST['discrepancies']);
	$interim_containment = htmlentities($_POST['containment']);
	$root_cause = htmlentities($_POST['rootCause']);
	$corrective_action = htmlentities($_POST['action']);
	$result = htmlentities($_POST['result']);
	$cost_pn1 = htmlentities($_POST['costPN1']);
	$cost1 = htmlentities($_POST['cost1']);
	$cost_qty1 = htmlentities($_POST['costQty1']);
	$cost_pn2 = htmlentities($_POST['costPN2']);
	$cost2 = htmlentities($_POST['cost2']);
	$cost_qty2 = htmlentities($_POST['costQty2']);
	$cost_pn3 = htmlentities($_POST['costPN3']);
	$cost3 = htmlentities($_POST['cost3']);
	$cost_qty3 = htmlentities($_POST['costQty3']);	
	$cost_pn4 = htmlentities($_POST['costPN4']);
	$cost4 = htmlentities($_POST['cost4']);	
	$cost_qty4 = htmlentities($_POST['costQty4']);	
	$mfg_hours = htmlentities($_POST['mfgHours']);
	$rework_cost = htmlentities($_POST['reworkCost']);
	$dept_manager_2 = htmlentities($_POST['manager2']);
	$manager_review_date = htmlentities($_POST['reviewDate1']);
	$plant = htmlentities($_POST['plant']);
	if ($manager_review_date != '')
	{
		$manager_review_date = str_replace("/", "-", $manager_review_date);
		list($month, $day, $year) = explode("-", $manager_review_date);
		$manager_review_date = "$year-$month-$day";
	}
	print " $manager_review_date";
	$dept_head = htmlentities($_POST['deptHead']);
	$head_review_date = htmlentities($_POST['reviewDate2']);
	if ($head_review_date != '')
	{
		$head_review_date = str_replace("/", "-", $head_review_date);
		list($month, $day, $year) = explode("-", $head_review_date);
		$head_review_date = "$year-$month-$day";
	}
	print " $head_review_date";
	$defect_code = htmlentities($_POST['defect']);
	$defect_qty = htmlentities($_POST['defectQty']);
	if ($_POST['submit'] == "Submit")
	{
		$query_max = "SELECT * FROM itr ORDER BY itr_no DESC LIMIT 1";
		$result_max = pg_query($conn, $query_max) or die("Error in query: $query_max. " . pg_last_error($conn));
		$max_row = pg_fetch_array($result_max);
		$max_itr_no = $max_row['itr_no'];
		$itr_no = $max_itr_no + 1;
		if ($itr_no < 10)
		{
			$itr_no = '00000' . $itr_no;
		}
		elseif($itr_no < 100)
		{	
			$itr_no = '0000' . $itr_no;
		}
		elseif($itr_no < 1000)
		{
			$itr_no = '000' . $itr_no;
		}
		elseif($itr_no < 10000)
		{
			$itr_no = '00' . $itr_no;			
		}
		elseif($itr_no < 100000)
		{
			$itr_no = '0' . $itr_no;
		}
		
		if($_FILES['picture1'])
		{
			$picture_1 = "images/" . $itr_no . "a.JPG";
			if(!file_exists($picture_1))
			{
				print "File Name " .$_FILES['picture1']['tmp_name'];
				move_uploaded_file($_FILES["picture1"]["tmp_name"], $picture_1);
			}
		}
		
		if($_FILES['picture2'])
		{
			$picture_2 = "images/" . $itr_no . "b.JPG";
			if(!file_exists($picture_2))
			{
				print "File Name " .$_FILES['picture2']['tmp_name'];
				move_uploaded_file($_FILES["picture2"]["tmp_name"], $picture_2);
			}
		}
		if($_FILES['picture3'])
		{
			$picture_3 = "images/" . $itr_no . "c.JPG";
			if(!file_exists($picture_3))
			{
				print "File Name " .$_FILES['picture3']['tmp_name'];
				move_uploaded_file($_FILES["picture3"]["tmp_name"], $picture_3);
			}
		}	
					
		$query = "INSERT INTO itr (itr_no, inspection_date, station, dept_manager_1, inspector, part_no, operator, inspection_summary, discrepancies, interim_containment, root_cause, corrective_action, result, cost_pn1, cost1, cost_qty1, cost_pn2, cost2, cost_qty2, cost_pn3, cost3, cost_qty3,cost_pn4, cost4, cost_qty4, mfg_hours, rework_cost, dept_manager_2, manager_review_date, dept_head, head_review_date, itr_created_by, defect_code, defect_qty, plant) VALUES('" . $itr_no . "', '" . $inspection_date . "', '" . $station . "', '" . $dept_manager_1 . "', '" . $inspector . "', '" . $part_no . "', '" . $operator . "', '" . $inspection_summary . "', '" . $discrepancies . "', '" . $interim_containment . "', '" . $root_cause . "', '" . $corrective_action . "', '" . $result . "', '" . $cost_pn1 . "', '" . $cost1 . "', '" . $cost_qty1 . "', '" . $cost_pn2 . "', '" . $cost2 . "', '" . $cost_qty2 . "', '" . $cost_pn3 . "', '" . $cost3 . "', '" . $cost_qty3 . "', '" . $cost_pn4 . "', '" . $cost4 . "', '" . $cost_qty4 . "', '" . $mfg_hours . "', '" . $rework_cost . "', '" . $dept_manager_2 . "', '" . $manager_review_date . "', '" . $dept_head . "', '" . $head_review_date . "', '" . $username . "', '" . $defect_code . "', " . $defect_qty . ", '" . $plant . "')";
	}
	
	if ($_POST['submit'] == "Edit")
	{
		$query = "UPDATE itr SET inspection_date = '" . $inspection_date . "', station = '" . $station . "', dept_manager_1 = '" . $dept_manager_1 . "', inspector = '" . $inspector . "', part_no = '" . $part_no . "', operator = '" . $operator . "', inspection_summary = '" . $inspection_summary . "', discrepancies = '" . $discrepancies . "', interim_containment = '" . $interim_containment . "', corrective_action = '" . $corrective_action . "', result = '" . $result . "', cost_pn1 = '" . $cost_pn1 . "', cost1 = '" . $cost1 . "', cost_qty1 = '" . $cost_qty1 . "', cost_pn2 = '" . $cost_pn2 . "', cost2 = '" . $cost2 . "', cost_qty2 = '" . $cost_qty2 . "', cost_pn3 = '" . $cost_pn3 . "', cost3 = '" . $cost3 . "', cost_qty3 = '" . $cost_qty3 . "', cost_pn4 = '" . $cost_pn4 . "', cost4 = '" . $cost4 . "', cost_qty4 = '" . $cost_qty4 . "', mfg_hours = '" . $mfg_hours . "', rework_cost = '" . $rework_cost . "', dept_manager_2 = '" . $dept_manager_2 . "', manager_review_date = '" . $manager_review_date . "', dept_head = '" . $dept_head . "', head_review_date = '" . $head_review_date . "', defect_code = '" . $defect_code . "', defect_qty = " . $defect_qty . ", plant = '" . $plant . "', root_cause = '" . $root_cause . "' WHERE itr_no = '" . $itr_no . "'";
	}
	
	print $query;
	$result = pg_query($conn, $query) or die("Error in query: $query." . pg_last_error($conn));
	$query = "COMMIT";
	$result = pg_query($conn, $query) or die("Error in query: $query" . pg_last_error($conn));
	header("Location: index.php");
	ob_end_flush();
?>