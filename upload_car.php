<?php
		print $_FILES['car'];
		$car = str_replace("\'", '',$_FILES['car']['name']);
		print $car;
		$car_num = $_POST['incrNo'];
		$car_file = explode('.', $car);
		$car_count = count($car_file);
		$file_ext = $car_file[$car_count-1];
		//print $car_file[1];
		//print $file_ext;
		$car_final = "CARs/" . $car_num;
		$upload = 0;	
		switch($file_ext)
		{
			case("pdf"):	
			case("PDF"):
				$car_final .= ".pdf";
				$upload = "1";
				break;
				
			case("jpg"):
			case("JPG"):
				$car_final .= ".jpg";
				$upload = "1";
				break;
			default:
				print "File is Not Compatible";
				break;
		}
	
		if($upload == "1")
		{
		if(!file_exists($car_final))
		{
			print "File Name " . $_FILES['car']['tmp_name'];
			//print $car_final;
			move_uploaded_file($_FILES['car']['tmp_name'], $car_final);
			print "Upload Complete<br/>";
			if($file_ext == "pdf" || $file_ext == "PDF")
				//print "PDF";
				print "<embed src = '" . $car_final . "' height = '800' width = '700' />";
			if($file_ext == "jpg" || $file_ext == "JPG")
				print "<img src = '" . $car_final . "' height = '800' width = '700' />";
		}
	}
	
?>