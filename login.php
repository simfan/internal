<?php
	ob_start();
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}

	$location = $_GET['location'];
	$plant = $_GET['plant'];
	if ($location == 'index')
		$page = 'index.php';
	else
	{
		$status = $_GET['status'];
		if($location == 'select')
			$page = 'location.php?status=' . $status;
		elseif($location == 'table')
		{
			$page = 'CAR_table.php?location='. $plant . '&archive=' . $status;
		}
		else
			$page = 'index.php';
	}
		
	//checks if there is a login cookie
	print $location;
	if(isset($_COOKIE['ID_my_site']))
	//if there is, it logs you in and returns you to the previous page.
	{
		$username = $_COOKIE['ID_my_site'];
		$password = $_COOKIE['Key_my_site'];
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $check. " . pg_last_error($conn));
		$info = pg_fetch_array($check_results);
		if($password == $info['car_password'])
		{
			header("Location: $page");
		}
		else
			print "The password is not correct";
	}
	
	
	if (isset($_POST['submit']))
	{
		if(!$_POST['user'] | !$_POST['pass'])
		{
			print 'You did not fill in a required field';
		}
		$username = $_POST['user'];
		
		$check = "SELECT * FROM car_login WHERE car_username = '$username'";
		$check_results = pg_query($conn, $check) or die("Error in query: $check. " . pg_last_error());
		
		$check2 = pg_num_rows($check_results);

		if($check2 == 0)
		{
			print "No user";
			//die('The username and password combination does not match');
		}
		else
		{
			$info = pg_fetch_array($check_results);
			if($_POST['pass'] == $info['car_password'])
			{
				$hour = time() + 3600;
				setcookie(ID_my_site, $_POST['user'], $hour);
				setcookie(Key_my_site, $_POST['pass'], $hour);

			//print $location;			
			header("Location: $page");	
			}
			else
			{
				print "Incorrect password";
				//die('The username and password combination does not match');
			}
		}	
	}
?>
<html>
<body>
	<h1 align = "center">Internal Login</h1>
	</br>
	<p align = "center">You must login to edit an internal nonconformance</p>
	<form name = 'loginForm' action = "<?php $_SERVER['PHP_SELF']; ?>" method = "post">
		<table align = "center">
			<tr>
				<td align = "right">User</td><td><input type = "text" name = "user" id = "user" size = "10" maxlength = "10"/></td>
			</tr>
			<tr>
				<td align = "right">Password</td><td><input type = "password" name = "pass" id = "pass" size = "10" maxlength = "10"/></td>
			</tr>
			<tr>
				<td colspan = 2><input type = "submit" name = "submit" id = "submit" value = "Submit"/></td>
			</tr>
		</table>
	</form>
</body>
</html>
<?php ob_end_flush();?>