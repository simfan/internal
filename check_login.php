<?php
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
?>