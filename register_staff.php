<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'dBConfig.php';

$json = file_get_contents('php://input');
$json_data = json_decode($json);

$input = $json_data;

foreach ($input as $data) {
    // personall info
    $full_name = $data->full_name;
    $id_number = $data->id_number;
    $staff_phone = $data->staff_phone;
    $date_of_birth = $data->date_of_birth;
    $place_of_birth = $data->place_of_birth;
    // familly background
    $marital_status = $data->marital_status;
    $spause_name = $data->spause_name;
    $spause_phone_number = $data->spause_phone_number;
    $children_number = $data->children_number;
    $fathers_name = $data->fathers_name;
    $mothers_name = $data->mothers_name;
   //    general info
    $staff_number = $data->staff_number; // add f
    $branch = $data->branch;
    $status = $data->status;
    $signing_mandate = $data->signing_mandate;
    $image = $data->image;
    $fingerprintR = $data->fingerprintR;
    $fingerprintL = $data->fingerprintL;
    $id_front_image = $data->id_front_image; //add f
    $id_back_image = $data->id_back_image; //add f
    $signature = $data->signature; //add f
    $registration_date = $data->registration_date; //add f
    
    $title = $data->title;
    $password = $data->password;
    $role = $data->role;
    $created_by = $data->created_by;

    registerUserstaff($full_name, $id_number, $staff_phone, $date_of_birth, $place_of_birth, $marital_status, $spause_name, $spause_phone_number, $children_number, $fathers_name, $mothers_name, $staff_number, $branch, $status, $signing_mandate, $image, $fingerprintR, $fingerprintL, $id_front_image, $id_back_image, $signature, $registration_date, $title, $password, $role, $created_by);
}


function registerUserstaff($full_name, $id_number, $staff_phone, $date_of_birth, $place_of_birth, $marital_status, $spause_name, $spause_phone_number, $children_number, $fathers_name, $mothers_name, $staff_number, $branch, $status, $signing_mandate, $image, $fingerprintR, $fingerprintL, $id_front_image, $id_back_image, $signature, $registration_date, $title, $password, $role, $created_by)
 {

    //response array 
    $response = array();
    $response['error'] = false;
    $response["msg"] = "";

    //connecting to the database 
    $con = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect...');

    //$input_gender = $gender == 'Male' ? '1' : '2';
    // $path = "./uploads/$service_number.png";
    //   $final_path = "https://techsavanna.net:8181/somali_attendance_app/uploads/".$service_number.".png";

    try {
        //  if(file_put_contents($path, base64_decode($image))){
        $query = "SELECT * FROM hs_hr_employee WHERE membership_number='$staff_number'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 0) {


            $query = "INSERT INTO hs_hr_employee
			(employee_id, emp_firstname, branch, photo,id_front_image,id_back_image,membership_number, registration_date,fingerprintR,fingerprintL,emp_birthday,emp_street2,spause_name,spause_phone_number,children_number,fathers_name,mother_name,signing_mandate,signature)
			VALUES 
			('$id_number','$full_name','$branch','$image','$id_front_image','$id_back_image','$staff_number','$registration_date','$fingerprintR','$fingerprintL', '$date_of_birth', '$place_of_birth', '$spause_name','$spause_phone_number', '$children_number', '$fathers_name', '$mothers_name','$signing_mandate','$signature')";

            $result1 = mysqli_query($con, $query);
            $lastInsertedId = mysqli_insert_id($con);

            if ($result1) {


                $user_password = $password;
                // Generate a random salt
                $salt = base64_encode(openssl_random_pseudo_bytes(16));
                // Concatenate the password and salt
                $combined = $user_password ;
                // . $salt;
                // Hash the combined string using bcrypt algorithm
                $hashed_password = password_hash($combined, PASSWORD_BCRYPT);
            $query = "select * from  ohrm_user where emp_id =$lastInsertedId";
            $result = mysqli_query($con, $query);
            if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO ohrm_user
			(id,   user_name,title,branch, staff_phone, date_entered, status, user_role_id, user_password,created_by)
			VALUES 
			('$lastInsertedId','$full_name','$title','$branch','$staff_phone','$registration_date','$status','$role ','$hashed_password','$created_by')";

            $result1 = mysqli_query($con, $query);
            echo mysqli_error($con);

                    if ($result1) {
                        $response['error'] = false;
                        $response["msg"] = "Registration successful";
                        header('Content-type: application/json');
                    } else {
                        $response['error'] = true;
                        $response["msg"] = "Insertion Failed";

                        header('Content-type: application/json');
                    }

                }

                $response['error'] = false;
                $response["msg"] = "Registration successful";
                header('Content-type: application/json');
            } else {
                $response['error'] = true;
                $response["msg"] = "Insertion Failed";

                header('Content-type: application/json');
            }
                   

        } 
                
        else {
            $response['error'] = false;
            $response["msg"] = "Record already exist";
            header('Content-type: application/json');
            
        }
        //  }
    } catch (Exception $e) {
        $response['error'] = true;
        $response['msg'] = $e->getMessage();
        header('Content-type: application/json');
    }

    echo json_encode($response);
    mysqli_close($con);
}
