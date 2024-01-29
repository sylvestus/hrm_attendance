<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
	require_once('dBConfig.php');
 
 	$json = file_get_contents('php://input');
  $data = json_decode($json); 
  
 	$designation = $data->designation;
	// declare arrays
	$response = array();
	$response['error'] = false;
	$response['result'] = array(); 
	
	$query = "SELECT * FROM hs_hr_employee
	WHERE fingerprint IS NOT NULL AND fingerprint <> '' AND job_title_code='$designation' ";
		
	$result = mysqli_query($con, $query);
echo mysqli_error($con);

	if(mysqli_num_rows($result) > 0) {
		
			while($row = mysqli_fetch_array($result)){
			$temp = array(); 
			$temp['service_number']=$row['employee_id'];
			$temp['full_name']=$row['emp_firstname'];
			$temp['phone_no']=$row['emp_mobile'];
			$temp['device_number']=$row['device_number'];
			$temp['designation']=$row['job_title_code'];
			$temp['bank']=$row['bank'];
			$temp['salary_per_month']=$row['salary_per_month'];
      $temp['date_of_birth']=$row['emp_birthday'];
			$temp['photo']=$row['photo'];
			$temp['account_number']=$row['account_number'];
			$temp['place_of_birth']=$row['place_of_birth'];
			$temp['duty_station']=$row['duty_station'];
			$temp['fathers_name']=$row['fathers_name'];
			$temp['mothers_name']=$row['mothers_name'];
  	  $temp['father_phone_number']=$row['father_phone_number'];
			$temp['mother_phone_number']=$row['mother_phone_number'];
  	  $temp['gender']=$row['emp_gender'];
			$temp['fingerprint']=$row['fingerprint'];
   
			
			array_push($response['result'],$temp);
		}
		
		header('Content-type: application/json');
		$response['error'] = false;
		echo json_encode($response);
		
	} else {
		$response['error'] = false; 
		echo json_encode($response);
	}
	 
	
	
		
?>

