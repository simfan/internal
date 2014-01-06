<!DOCTYPE html>
<html>
<head>
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
					
					if($location == $user_info[$plant_name])// || $location = "A")
					{
						$match = 1;
						break;
					}
				}
			}
		}
	}	
	$fh = fopen('defect_code_list.txt', 'rb');
	$i = 0;
	for ($line = fgets($fh); ! feof($fh); $line = fgets($fh))
	{
		$line = trim($line);
		list($code[$i], $code_desc[$i]) = explode('|', $line);
		$full_code[$code[$i]] = $code[$i] . "-" . $code_desc[$i];
		$i++;
	}
	$desc_count = $i;
	$db_fields = array('itr_no', 'inspection_date', 'station', 'plant', 'inspector', 'part_no', 'operator', 'inspection_summary', 'discrepancies', 'interim_containment', 'defect_code', 'root_cause', 'corrective_action', 'result', 'dept_manager_2', 'manager_review_date', 'dept_head', 'head_review_date');
	//						0		1		2			3			4			5			6			7			8				9				10			11					12			13			14				15		16		
	$html_fields = array('itrNo', 'iDate', 'station', 'plant', 'inspector', 'partNo', 'operator', 'summary', 'discrepancies', 'containment', 'defectCode','rootCause', 'correctiveAction', 'result', 'manager2', 'reviewDate1', 'deptHead', 'reviewDate2');
	if($password_check == 1)
		$html_fields[17] = 'records';
	$table_headings = array('ITR NO', 'Inspection Date', 'Department', 'Plant', 'Inspector', 'Part No', 'Operator', 'Inspection Summary', 'Discrepancies', 'Interim Containment', 'Defect Code', 'Root Cause', 'Corrective/<br/>Preventive Action', 'Result of<br/>Preventive Action', 'Department Manager', 'Date', 'QA Department Head', 'Date');
	
	$size = count($html_fields);
	
	$query = "SELECT * FROM itr ";
	$field_count = 0;
	for($i = 0; $i < $size; $i++)
	{
		$link_switch = 0;
		$enter_switch = 0;
							
		if($i != 17)
		{
			if($_POST[$html_fields[$i]])	
				$field_value[$i] = $_POST[$html_fields[$i]];
			if($_GET[$html_fields[$i]])
				$field_value[$i] = $_GET[$html_fields[$i]];
		}
		else
		{
			if($_POST[$html_fields[$i]] || $_GET[$html_fields[$i]])	
			{
				$field_value[$i] = $username;
				$match = 1;
			}
		}
		if($i != 3)
		{
			if ($_POST[$html_fields[$i]] != '')
			{
				$link_switch = 1;
				$enter_switch = 1;
			}
		}
		else
		{
			if ($_POST[$html_fields[$i]] != 'All')
			{
				$link_switch = 1;
				$enter_switch = 1;
			}
		}
		//print $enter_switch;				
		if($enter_switch == 1)
		{
			if($field_count == 0)
				$query_conditions = "WHERE ";
			if ($field_count > 0)
				$query_conditions .= "AND ";
			switch($i)
			{
				case(1):
				case(14):
				case(16):
					$query_conditions .= "$db_fields[$i] = '" . $field_value[$i] . "' " ;
					break;
				case(17):
					$query_conditions .= "itr_created_by = '" . $field_value[$i] . "' " ;
					break;
			//if($i == 1 || $i == 14 || $i == 16 || $i == 17)
			//	$query_conditions .= "$db_fields[$i] = '" . $field_value[$i] . "' " ;
				default:
					$query_conditions .= "$db_fields[$i] LIKE '%" . $field_value[$i] . "%' ";
					break;
			}
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
	$query .= "ORDER BY itr_no";
 	$result = pg_query($conn, $query) or die("Error in query: $query " . pg_last_error($conn));
	$rows = pg_num_rows($result);


?>
<script type = "text/javascript">
	var i;
	var match = <?php echo $match;?> ;
	var itrNo = new Array();
	var inspectionDate = new Array();
	var station = new Array();
	var deptManager1 = new Array();
	var plant = new Array();
	var inspector = new Array();
	var partNo = new Array();
	var operator = new Array();
	var summary = new Array();
	var discrepancies = new Array();
	var containment = new Array();
	var defectCode = new Array();
	var rootCause = new Array();
	var correctiveAction = new Array();
	var result = new Array();
	var deptManager2 = new Array();
	var reviewDate1 = new Array();
	var qaDeptHead = new Array();
	var reviewDate2 = new Array();
	var itr = new Array();
	var fieldNames = new Array();
	var fieldValues = new Array();

<?php
	$special_chars = array("\n", "\r", "\t", "'");
	for ($i = 0; $i < $rows; $i++)
	{
		//$itr = pg_fetch_array($result);
		$itr = pg_fetch_array($result);
		$itr_no = $itr['itr_no'];
		if ($itr_no == '')
			$itr_no = '&nbsp';
		$inspection_date = $itr['inspection_date'];
		if ($inspection_date == '')
			$inspection_date = '&nbsp';
		else
			$inspection_date = str_replace("-", "/", $inspection_date);
		$station = $itr['station'];
		if ($station == '')
			$station = '&nbsp';
		$dept_manager_1 = $itr['dept_manager_1'];
		if ($dept_manager_1 == '')
			$dept_manager_1 = '&nbsp';
		$plant = $itr['plant'];
		//if ($plant
		$inspector = $itr['inspector'];
		if ($inspector == '')
			$inspector = '&nbsp';
		$part_no = $itr['part_no'];
		if ($part_no == '')
			$part_no = '&nbsp';
		$operator = $itr['operator'];
		if ($operator == '')
			$operator = '&nbsp';
		$summary = $itr['inspection_summary'];
		if ($summary == '')
			$summary = '&nbsp';
		else
			$summary = str_replace($special_chars, ' ', $summary);
		$discrepancies = $itr['discrepancies'];
		if ($discrepancies == '')
			$discrepancies = '&nbsp';
		else
			$discrepancies = str_replace($special_chars, ' ', $discrepancies);
		$containment = $itr['interim_containment'];
		if ($containment == '')
			$containment = '&nbsp';
		else
			$containment = str_replace($special_chars, ' ', $containment);
		$root_cause = $itr['root_cause'];
		if ($root_cause == '')
			$root_cause = '&nbsp';
		else
			$root_cause = str_replace($special_chars, ' ', $root_cause);	
		$defect_code = $itr['defect_code'];	
		if ($defect_code == '')
			$defect_code = '&nbsp';
		else
			$defect_code = str_replace($special_chars, ' ', $defect_code);	
		$corrective_action = $itr['corrective_action'];
		if ($corrective_action == '')
			$corrective_action = '&nbsp';
		else
			$corrective_action = str_replace($special_chars, ' ', $corrective_action);			
		$results = $itr['result'];
		if ($results == '')
			$results = '&nbsp';
		else
			$results = str_replace($special_chars, ' ', $results);			
		$dept_manager_2 = $itr['dept_manager_2'];
		if ($dept_manager_2 == '')
			$dept_managar_2 = '&nbsp';
		$manager_review_date = $itr['manager_review_date'];
		if ($manager_review_date == '')
			$manager_review_date = '&nbsp';
		else
			$manager_review_date = str_replace("-", "/", $manager_review_date);			
		$dept_head = $itr['dept_head'];
		if ($dept_head == '')
			$dept_head = '&nbsp';
		$head_review_date = $itr['head_review_date'];
		if ($head_review_date == '')
			$head_review_date = '&nbsp';
		else
			$head_review_date = str_replace("-", "/", $head_review_date);
		?>
			i = <?php echo $i; ?>;
			itrNo[i] = <?php echo "'" . $itr_no . "'"; ?>;
			inspectionDate[i] = <?php echo "'" . $inspection_date . "'"; ?>;
			station[i] = <?php echo "'" . $station . "'"; ?>;
			//deptManager1[i] = <?php echo "'" . $dept_manager_1 . "'"; ?>;
			plant[i] = <?php echo "'" . $plant . "'"; ?>;
			inspector[i] = <?php echo  "'" . $inspector . "'"; ?>;
			partNo[i] = <?php echo "'" . $part_no . "'"; ?>;
			operator[i] = <?php echo "'" . $operator . "'"; ?>;
			summary[i] = <?php echo "'" . $summary . "'"; ?>;
			discrepancies[i] = <?php echo "'" . $discrepancies . "'"; ?>;
			containment[i] = <?php echo "'" . $containment . "'"; ?>;
			defectCode[i] = <?php echo "'" . $full_code[$defect_code] . "'"; ?>;
			rootCause[i] = <?php echo "'" . $root_cause . "'"; ?>;
			correctiveAction[i] = <?php echo "'" . $corrective_action . "'"; ?>;
			result[i] = <?php echo "'" . $results . "'"; ?>;
			deptManager2[i] = <?php echo "'" . $dept_manager_2 . "'";?>;
			reviewDate1[i] = <?php echo "'" . $manager_review_date . "'"; ?>;
			qaDeptHead[i] = <?php echo "'" . $dept_head . "'"; ?>;
			reviewDate2[i] = <?php echo "'" . $head_review_date . "'"; ?>;
			itr[i] = [itrNo[i], inspectionDate[i], station[i], plant[i], inspector[i], partNo[i], operator[i], summary[i], discrepancies[i], containment[i], defectCode[i], rootCause[i], correctiveAction[i], result[i], deptManager2[i], reviewDate1[i], qaDeptHead[i], reviewDate2[i]];
		<?php 
		}
					
		for ($i = 0; $i <$size; $i++)
		{?>
			i = <?php echo $i; ?>;
			fieldNames[i] = <?php echo "'" . $table_headings[$i] . "'"; ?>;
			fieldValues[i] = <?php echo "'" . $html_fields[$i]  . "'"; ?>;
			//alert ("Field Values: " + fieldValues[i]);
		<?php
		}
		
		for ($i = 0; $i < $rows; $i++)
		{?>	
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
				var sortText = fieldValues[tableField] + "Sort";
				var direction = document.getElementById(sortText).value;
				var fieldCount = <?php echo $size;?>;
				var fieldName;
				var viewText;
				var editText;
				var deleteText;
				itr.sort(function(a,b)
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
				itr.reverse();
				for(var i = 0; i < fieldCount; i++)
				{	
					fieldName = fieldValues[i] + "Sort";
					//document.getElementById(fieldValues[i]).style.color = "black";
					document.getElementById(fieldValues[i]).innerHTML = fieldNames[i];
					document.getElementById(fieldName).value = 1
				}
				for(var i = 0; i < tableRows; i++)
				{
					viewText = "viewLink" + i;
					editText = "editLink" + i;
					deleteText = "deleteLink" + i;
			
					document.getElementById(viewText).href = "internal_trouble.php?itrNo="+itr[i][0]+"&action=view";
					if (match == 1)
					{
						document.getElementById(editText).href = "internal_trouble.php?itrNo="+itr[i][0]+ "&action=edit";
					}	
					for(var j = 0; j < fieldCount; j++)
					{
						documentText = fieldValues[j] + i;
						document.getElementById(documentText).innerHTML = itr[i][j];
					}
				}
				if (direction == 1)
				{
					newDirection = -1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
				}
				if (direction == -1)
				{
					newDirection = 1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
				}
				document.getElementById(sortText).value = newDirection;
			}
			function sortTableNum(tableField, sorted)
			{
				var sortText = fieldValues[tableField] + "Sort";
				var direction = document.getElementById(sortText).value;
				var fieldCount = <?php echo $size;?>;
				var fieldName;
				var viewText;
				var editText;
				var deleteText;
				itr.sort(function(a,b)
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
					itr.reverse();

				for(var i = 0; i < fieldCount; i++)
				{	
					fieldName = fieldValues[i] + "Sort";
					//document.getElementById(fieldValues[i]).style.color = "black";
					document.getElementById(fieldValues[i]).innerHTML = fieldNames[i];
					document.getElementById(fieldName).value = 1;
				}
				for(var i = 0; i < tableRows; i++)
				{
					viewText = "viewLink" + i;
					editText = "editLink" + i;
					deleteText = "deleteLink" + i;
			
					document.getElementById(viewText).href = "internal_trouble.php?itrNo="+itr[i][0]+"&action=view";
					if (match == 1)
					{
						document.getElementById(editText).href = "internal_trouble.php?itrNo="+itr[i][0]+"&action=edit";
					}
					for(var j = 0; j < fieldCount; j++)
					{
						documentText = fieldValues[j] + i;
						document.getElementById(documentText).innerHTML = itr[i][j];
					}
				}
				if (direction == 1)
				{
					newDirection = -1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
				}
				if (direction == -1)
				{
					newDirection = 1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
				}
				document.getElementById(sortText).value = newDirection;		
			}
			function sortTableDate(tableField, sorted)
			{
				var sortText = fieldValues[tableField] + "Sort";
				var direction = document.getElementById(sortText).value;
				var fieldCount = <?php echo $size;?>;
				var fieldName;
				var viewText;
				var editText;
				var deleteText;
		
				itr.sort(function(a,b)
				{
					var fieldArrayA
					var fieldArrayB
					var fieldA;
					var fieldB;
					var field1;
					var field2;
					if(a[tableField] == "&nbsp")
					{
						fieldA = "00000000";
					}
					else
					{
						fieldArrayA = a[tableField].split("/")
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
				itr.reverse();
				for(var i = 0; i < fieldCount; i++)
				{	
					fieldName = fieldValues[i] + "Sort";
					//document.getElementById(fieldValues[i]).style.color = "black";
					document.getElementById(fieldValues[i]).innerHTML = fieldNames[i];
					document.getElementById(fieldName).value = 1;
				}
				for(var i = 0; i < tableRows; i++)
				{
					viewText = "viewLink" + i;
					editText = "editLink" + i;
					deleteText = "deleteLink" + i;
		
					document.getElementById(viewText).href = "internal_trouble.php?itrNo="+itr[i][0]+ "&action=view";
			
					if (match == 1)
					{
						document.getElementById(editText).href = "internal_trouble.php?itrNo="+itr[i][0]+ "&action=edit";
					}	
					/*document.getElementById(deleteText).href = "internal_trouble_process.php?itrNo="+itr[i][0]+ "&action=delete";*/
					for(var j = 0; j < fieldCount; j++)
					{
						documentText = fieldValues[j] + i;
						document.getElementById(documentText).innerHTML = itr[i][j];
					}
				}
				if (direction == 1)
				{
					newDirection = -1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &uarr;";
				}
				if (direction == -1)
				{
					newDirection = 1;
					document.getElementById(fieldValues[tableField]).innerHTML = fieldNames[tableField] + " &darr;";
				}
				document.getElementById(sortText).value = newDirection;
			}
		</script>
		<link rel="stylesheet" type="text/css" href="table.css">

</head>
<body>
	<h1>Internal Trouble Table</h1>
	<table><tr>
		<!--Table Headings Go Here.  Can Click To Sort Table -->
		<!--<th>ITR NO</th><th>Inspection Date</th><th>Station</th><th>Dept Manager</th><th>Inspector</th><th>Part No</th><th>Operator</th><th>Inspection Summary</th><th>Discrepancies</th><th>Interim Containment</th><th>Corrective/<br/>Preventve Action</th><th>Result of <br/>Preventive Action</th><th>Department Manager</th><th>Date</th><th>QA Department Head</th><th>Date</th>-->
		<script type = "text/javascript">
			var tableFields = <?php echo $size; ?>;
			var sortAlpha;
			var sortDate;
			var sortNum;
			var tableRows = <?php echo $rows;?>;
			var sortType;
			var textClass;
			var headingClass;
			if(match == 1)
			{
				document.write("<th colspan = 2 class = 'linkText'></th>");
			}
			else
				document.write("<th class = 'linkText'></th>");
			for(i = 0; i < tableFields; i++)
			{
					sortNum = "sortTableNum(" + i + ", "+ fieldValues[i] + ")";
					sortDate = "sortTableDate(" + i + ", " + fieldValues[i] + ")";
					sortAlpha = "sortTableAlpha(" + i + ", " + fieldValues[i] + ")";
					switch(i)
					{
						case 0:
							sortType = 'numeric';
							break;
						case 1:
						case 14: 
						case 16:
							sortType = 'date';
							break;
						default:
							sortType = 'alpha';
							break;
					}
					if (i >= 7 && i <= 12)
						headingClass = 'headingLarge';
					else
						headingClass = 'headingText';
				if (sortType == "numeric")
					document.write("<th name = '" + fieldValues[i] + "' id = '" + fieldValues[i] + "' onClick = '" + sortNum + "' class = 'headingText'> " + fieldNames[i] + "</th>");
				if (sortType == "date")
					document.write("<th name = '" + fieldValues[i] + "' id = '" + fieldValues[i] + "' onClick = '" + sortDate + "' class = 'headingText'> " + fieldNames[i] + "</th>");
				if (sortType == "alpha")
					document.write("<th name = '" + fieldValues[i] + "' id = '" + fieldValues[i] + "' onClick = '" + sortAlpha + "' class = '" + headingClass + "' > " + fieldNames[i] + "</th>");
			}
			</script>
			</tr><tr>
			<script type = "text/javascript">
		
			//var fieldValue;
			
			for(var i = 0; i < tableRows; i++)
			{
				document.write("<tr><td class = 'linkText'><a name = 'viewLink" + i + "' id = 'viewLink" + i + "' href = 'internal_trouble.php?itrNo=" + itr[i][0] + "&action=view'>View</a></td>");
				if(match == 1)
				{
					document.write("<td class = 'linkText'><a name = 'editLink" + i + "' id = 'editLink" + i + "' href = 'internal_trouble.php?itrNo=" + itr[i][0] + "&action=edit'>Edit</a></td>");
					/*if(carPlantOne == 'A')
					{
						document.write("<td class = 'linkText'><a name = 'deleteLink" + i + "' id = 'deleteLink" + i + "'href = 'internal_trouble_process.php?itrNo=" + itr[i][0] + "action=delete'>Delete</a></td>");
					}*/
				}				
				for (var j = 0; j < tableFields; j++)
				{
					switch(j)
					{
						case 7:
						case 8:
						case 9:
						case 10:
						case 11:
						case 12:
							textClass = "largeText";
							break;
						default:
							textClass = "tableText";
							break;
					}	
					document.write("<td name = '" + fieldValues[j] + i + "' id = '" + fieldValues[j] + i + "' class = '" + textClass + "' >" + itr[i][j] + "</td>");
				}
				document.write("</tr>");
			}
			for(var i = 0; i < tableFields; i++)
				document.write("<input type = 'hidden' name = '" + fieldValues[i] + "Sort' id = '" + fieldValues[i] + "Sort' value = 1 />");	
		</script>
	</table>
</body>