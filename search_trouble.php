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
	$screen = 1;
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
		<script src = "left_border.js"></script>
	</head>
	<body>
		<?php require "left_border.php";?>
		<div class = "mainTable">
		<h2>Search Internal Trouble</h2>
		<form  name = "itrSearch" action = "internal_trouble_table.php" method = post >
			<table>
				<tr>
					<td>ITR Number</td>
					<td><input type = "text" name = "itrNo" id = "itrNo" size = 6 maxlength = 6 /></td>
				</tr>
				<tr>
					<td>Inspection Date</td>
					<td><input type = "text" name = "iDate" id = "iDate" size = 10 maxlegth = 10 /></td>
				</tr>
				<tr>
					<td>Station</td>
					<td><input type = "text" name = "station" id = "station" size = 20 maxlength = 20 /></td>
				</tr>
				<tr>
					<td>Plant</td>
					<td><!--<input type = "text" name = "manager1" id = "manager1" size = 25 maxlength = 25 />--><select name = "plant" id = "plant">
																													<option value = "All">All</option>
																													<option value = "K">Atchison</option>
																													<option value = "D">David City</option>
																													<option value = "N">Norristown</option>
																													<option value = "T">Reading</option>
																													<option value = "R">Richland</option>
																												</select></td>
				</tr>
				<tr>
					<td>Inspector</td>
					<td><input type = "text" name = "inspector" id = "inspector" size = 25 maxlength = 25 /></td>
				</tr>
				<tr>
					<td>Part No.</td>
					<td><input type = "text" name = "partNo" id = "partNo" size = 20 maxlength = 20 /></td>
				</tr>
				<tr>
					<td>Defect Code</td>
					<td><select name = "defect" id = "defect">
					<?php for ($i = 0; $i < $defect_count; $i++)
							{
								print "<option value = '$code[$i]'>$code[$i]-$code_desc[$i]</option>";
							}
					?>
						</select>
				<tr>
					<td>Operator</td>
					<td><input type = "text" name = "operator" id = "operator" size = 25 maxlength = 25 /></td>
				</tr>
				<tr>
					<td>Reviewed By</td>
				</tr>
				<tr>
					<td>Department Manager</td>
					<td><input type = "text" name = "manager2" id = "manager2" size = 25 maxlength = 25 /></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type = "text" name = "reviewDate1" id = "reviewDate2" size = 10 maxlength = 10 /></td>
				</tr>
				<tr>
					<td>QA Department Head</td>
					<td><input type = "text" name = "deptHead" id = "deptHead" size = 25 maxlength = 25 /></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type = "text" name = "reviewDate2" id = "reviewDate2" size = 10 maxlength = 10 /></td>
				</tr>
				<?php if ($password_check == 1)
				{
				?>	
				<tr>
					<td>Check:</td><td><label><input type = "checkbox" name = "records" id = "record" value = "My Records" />My Records</label></td>
				</tr>
				<?php } ?>
			</table>
			<input type = "submit" name = "submit" id = "submit" value = "Search" />
		</form>
		</div>
	</body>
	<?php ob_flush();?>
</html>	