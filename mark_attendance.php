<?php 
	require_once 'dBConfig.php';	 
	


	$json = file_get_contents('php://input');
  $json_data = json_decode($json); 
 
  $input = $json_data;
//   var_dump($json_data);
//   echo $json_data->service_number;
  
	foreach($input as $data){
	$service_number = $data->service_number;
	$date = date("Y-m-d");
	$time = date("H:i:s");

  markAttendance($service_number, $date, $time);
  } 
  
  
  function markAttendance($service_number, $date, $time){

  	//response array 
	$response = array();
	$response['error'] = false;
	$response["msg"] = "";	
	   
	//connecting to the database 
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');	

	
	try{
		$query = "SELECT * FROM ohrm_attendance_record WHERE service_number = '$service_number' AND string_date = '$date'";
	    $result = mysqli_query($con, $query);
		// var_dump($result);
		echo  mysqli_num_rows($result);
		if($result && mysqli_num_rows($result) == 0) {

			$query7 = "SELECT emp_number FROM hs_hr_employee WHERE employee_id='$service_number' ";
			$result7 = mysqli_query($con, $query7);
      	$row7 = mysqli_fetch_array($result7);

			$emp_number = $row7['emp_number'];

			// echo("Debug: Employee ID - $service_number, Date - $date, Time - $time");

		$query = "INSERT INTO ohrm_attendance_record
			(employee_id, punch_in_utc_time, service_number, string_date)
			VALUES 
			('$emp_number', '$time', '$service_number', '$date')";	

			 $result1 = mysqli_query($con, $query);

			if ($result1 ) {
					$response['error'] = false; 
					$response["msg"] = "Mark Attendance Successful";
					header('Content-type: application/json');	
									
			} else {
				$response['error'] = true; 
				$response["msg"] = "Mark Attendance Failed";
       
        header('Content-type: application/json');
			}
		
		}else {
			$response['error'] = false; 
			$response["msg"] = "Attendance Already Exist";
			header('Content-type: application/json');
		}
	}catch(Exception $e){
		$response['error'] = true; 
	$response['msg']=$e->getMessage();
 header('Content-type: application/json');

	
	} 

 echo json_encode($response);
	mysqli_close($con);
}
	