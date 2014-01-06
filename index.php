<?php
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	$login = "login.php";
	$login_text = "Log In";
	if(isset($_COOKIE['ID_my_site']))
	{
		$username = $_COOKIE['ID_my_site'];
		$password = $_COOKIE['Key_my_site'];
		
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $query. " . pg_last_error($conn));
		$user_info = pg_fetch_array($check_results);
		
		if($password == $user_info['car_password'])
		{
			$login = "logout.php?location=index";
			$login_text = "Log Out";
			$password_check = 1;
		}
	}
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="left_border.css">
	<style>
		a:link, a:visited
		{
			color:blue;
			text-decoration: none;
		}

		a:hover
		{
			color:blue;
			text-decoration: underline;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div class = "header"><?php print "<a class = 'headerLink' href = $login>$login_text</a>";?></div>
	<!--<?php //require 'login_border.php'; 
	?>-->
	</br>
	<div class = "main">
	<h1 align = "center">Internal Nonconformance Home</h1>
	<table align = "center">
		<tr>
			<?php if($password_check == 1) {?><td><a href = "internal_nonconformance.php?action=add">New VCAR</a></td>&nbsp&nbsp&nbsp&nbsp <td><a href = "internal_trouble.php?action=add">New Internal Trouble Report</a></td><?php }?><td><a href = "search.php">Search VCAR Table</a></td><td><a href = "search_trouble.php">Search Internal Trouble Table</a></td>
		</tr>
	<tr><td colspan = 2 ><a href = "report_range_2.php">Internal Trouble Reports</a><!--<a href = "write_internal_report.php?record=now">Internal Trouble Reports - Current Month</a></td><!--<td colspan = 2 ><a href = "write_internal_report.php?record=previous">Internal Trouble Reports - Last Month</a></td>--></tr>
	</table>
	</div>
	<a href = "http://192.168.10.129/quality/">Back to Quality</a>
</body>
</html>