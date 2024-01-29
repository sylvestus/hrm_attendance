<?php
	
	define('HOST','localhost:3306');
	
	define('USER','silvano');
	define('PASS','access');
	define('DB','homabay_hr2');
	
	
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
?>
