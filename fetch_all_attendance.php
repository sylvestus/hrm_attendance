<?php
		
	require_once('dBConfig.php');
 	$json = file_get_contents('php://input');
  $json_data = json_decode($json); 
  
  $user_id = $json_data->user_id;
  
	
	// declare arrays
	$response = array();
	$response['error'] = false;
	$response['result'] = array(); 
	
	$query = "SELECT hs_hr_employee.employee_id,hs_hr_employee.emp_firstname,hs_hr_employee.emp_mobile,hs_hr_employee.device_number, hs_hr_employee.job_title_code,hs_hr_employee.bank,hs_hr_employee.salary_per_month,hs_hr_employee.emp_birthday,hs_hr_employee.photo,hs_hr_employee.account_number,hs_hr_employee.place_of_birth,ohrm_subunit.name as work_station,hs_hr_employee.fathers_name,hs_hr_employee.mothers_name,hs_hr_employee.father_phone_number,hs_hr_employee.mother_phone_number,hs_hr_employee.emp_gender,hs_hr_employee.fingerprint,hs_hr_employee.rifle_number,hs_hr_employee.spouse_name,hs_hr_employee.spouse_phone_number,hs_hr_employee.children,ohrm_clan.name as custom1,hs_hr_employee.emp_marital_status,hs_hr_employee.rifle_type,hs_hr_employee.custom2, hs_hr_employee.termination_id FROM hs_hr_employee left join ohrm_clan on ohrm_clan.id = hs_hr_employee.custom1 left join ohrm_subunit on ohrm_subunit.id = hs_hr_employee.work_station  WHERE hs_hr_employee.fingerprint IS NOT NULL AND hs_hr_employee.fingerprint <> '' ";
		
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
			$temp['duty_station']=$row['work_station'];
			$temp['fathers_name']=$row['fathers_name'];
			$temp['mothers_name']=$row['mothers_name'];
  	  $temp['father_phone_number']=$row['father_phone_number'];
			$temp['mother_phone_number']=$row['mother_phone_number'];
  	  $temp['gender']=$row['emp_gender'];
			$temp['fingerprint']=$row['fingerprint'];
      
      $temp['guarantor'] = 'guarantors';
			$temp['rifle_number']=$row['rifle_number'];
			$temp['spouse_name']=$row['spouse_name'];
  	  $temp['spouse_phone_number']=$row['spouse_phone_number'];
			$temp['children']=$row['children'];
  	  $temp['clan']=$row['custom1'];
			$temp['marital_status']=$row['emp_marital_status'];
      $temp['rifle_type']=$row['rifle_type'];
			$temp['sub_clan']=$row['custom2'];
      $temp['termination_id']=$row['termination_id'];
      
			
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

