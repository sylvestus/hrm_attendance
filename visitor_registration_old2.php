<?php 
	require_once 'dBConfig.php';	 
	
	$json = file_get_contents('php://input');
  $json_data = json_decode($json); 
  
  $input = $json_data->data;
  
	foreach($input as $data){
 	$image = $data->image;
	$full_name =$data->full_name;
	$service_number = $data->service_number;
	$phone_no = $data->phone_no;
	$device_number = $data->device_number;
	$bank = $data->bank;
	$account_number = $data->account_number;
	$designation = $data->designation;
	$salary_per_month =$data->salary_per_month;
	$date_of_birth = $data->date_of_birth;
	$place_of_birth = $data->place_of_birth;
	$duty_station = $data->duty_station;
	$fathers_name = $data->fathers_name;
	$mothers_name = $data->mothers_name;
	$father_phone_number = $data->father_phone_number;
	$mother_phone_number = $data->mother_phone_number;
	$gender = $data->gender;
  $template_string = $data->template_string;

$guarantor = $data->guarantor;
$rifle_number = $data->rifle_number;
$spouse_name = $data->spouse_name;
$spouse_phone_number = $data->spouse_phone_number;
$children = $data->children;
$clan = $data->clan;
$marital_status = $data->marital_status;

$rifle_type = $data->rifle_type;
$sub_clan = $data->sub_clan;
$staff_id = $data->user_id;
  
  registerEmployee($image, $full_name, $service_number, $phone_no, $device_number, $bank, $account_number, $designation, $salary_per_month, $date_of_birth, $place_of_birth, $duty_station, $fathers_name, $mothers_name, $father_phone_number, $mother_phone_number, $gender, $template_string,
  $guarantor, $rifle_number, $spouse_name, $spouse_phone_number, $children, $clan, $marital_status, $rifle_type, $sub_clan, $staff_id);
  } 
  
  
  function registerEmployee($image, $full_name, $service_number, $phone_no, $device_number, $bank, $account_number, $designation, $salary_per_month, $date_of_birth, $place_of_birth, $duty_station, $fathers_name, $mothers_name, $father_phone_number, $mother_phone_number, $gender, $template_string,
  $guarantor, $rifle_number, $spouse_name, $spouse_phone_number, $children, $clan, $marital_status, $rifle_type, $sub_clan, $staff_id){

  	//response array 
	$response = array();
	$response['error'] = false;
	$response["msg"] = "";	
	 
	//connecting to the database 
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');	
 
  $input_gender = $gender == 'Male' ? '1' : '2';
    // $path = "./uploads/$service_number.png";
     //   $final_path = "https://techsavanna.net:8181/somali_attendance_app/uploads/".$service_number.".png";
	
	try{
      //  if(file_put_contents($path, base64_decode($image))){
		$query = "SELECT * FROM hs_hr_employee WHERE employee_id='$service_number'";
	    $result = mysqli_query($con, $query);
	
		if(mysqli_num_rows($result)==0 ) {
			$query = "INSERT INTO hs_hr_employee
			(employee_id, emp_firstname, emp_hm_telephone, emp_mobile, emp_work_telephone, device_number, bank, photo, 
			account_number,  job_title_code, salary_per_month, emp_birthday, place_of_birth,
			work_station, fathers_name, mothers_name, father_phone_number, mother_phone_number,
			emp_gender, fingerprint,
      rifle_number, spouse_name, spouse_phone_number, children, custom1, emp_marital_status, rifle_type, custom2, staff_id)
			VALUES 
			('$service_number', '$full_name', '$phone_no', '$phone_no', '$phone_no', '$device_number','$bank', '$image', '$account_number', '$designation', '$salary_per_month', '$date_of_birth',
			'$place_of_birth', '$duty_station', '$fathers_name', '$mothers_name', '$father_phone_number',
			'$mother_phone_number', '$input_gender', '$template_string',
      '$rifle_number', '$spouse_name', '$spouse_phone_number', '$children', '$clan', '$marital_status', '$rifle_type', '$sub_clan', '$staff_id' )";	
			
			 $result1 = mysqli_query($con, $query);
			if ($result1 ) {
      $last_id = $con->insert_id;
      	foreach($guarantor as $guaran){
       $guarantor_name = $guaran->name;
       $guarantor_phone = $guaran->phone;
       $guarantor_address = $guaran->address;
       
       	$query9 = "INSERT INTO hs_hr_emp_emergency_contacts
			(emp_number, eec_name, eec_mobile_no, eec_home_no, eec_relationship)
			VALUES 
			('$last_id', '$guarantor_name', '$guarantor_phone', '$guarantor_address', 'guarantor')";	
			
			 mysqli_query($con, $query9);
       }
      
					$response['error'] = false; 
					$response["msg"] = "Registration successful";
					header('Content-type: application/json');	
									
			} else {
				$response['error'] = true; 
				$response["msg"] = "Insertion Failed";
       
        header('Content-type: application/json');
			}
		}else{
   $response['error'] = false; 
					$response["msg"] = "Record already exist";
					header('Content-type: application/json');	
   }
  //  }
	}catch(Exception $e){
		$response['error'] = true; 
	$response['msg']=$e->getMessage();
 header('Content-type: application/json');

	
	} 

 echo json_encode($response);
	mysqli_close($con);
}
	