<?php
  	foreach($_FILES["car"]["error"] as $key => $error)
     {
    	if ($error == UPLOAD_ERR_OK)
    	{
  			move_uploaded_file($_FILES['car']['tmp_name'][$key], "CARs/" . $_POST['incrNo'] . ".pdf");
  			chmod("CARs/" . $_POST['incrNo'] . ".pdf", 0744);
    	}
     }
	 echo '<h2>Successfully Uploaded Image to CARs/' . $_POST['incrNo'] . '.pdf </h2>';
	  
?>