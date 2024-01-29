<?php


	$json = file_get_contents('php://input');
	$obj = json_decode($json);
		
	require_once('dBConfig.php');
	
	// declare arrays
	$response = array();
	$response['error'] = false;
	$response['result'] = array(); 
	
	$query = "SELECT id, job_title FROM ohrm_job_title";
		
	$result = mysqli_query($con, $query);
	if(mysqli_num_rows($result) > 0) {
		
			while($row = mysqli_fetch_array($result)){
			$temp = array(); 
			$temp['id']=$row['id'];
			$temp['title']=$row['job_title'];
			array_push($response['result'],$temp);
		}
		// response in json format
		
		header('Content-type: application/json');
		$response['error'] = false;
		echo json_encode($response);
		
	} else {
		$response['error'] = true; 
		echo json_encode($response);
	}
	 
	
	
		
?>

