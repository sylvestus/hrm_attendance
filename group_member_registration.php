<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once 'dBConfig.php';

$json = file_get_contents('php://input');
$json_data = json_decode($json);

$input = $json_data;

foreach ($input as $data) {
    // personal info
    $full_name = $data->full_name;
    $id_number = $data->id_number; // add f
    $phone_number = $data->phone_numer;
    $date_of_birth = $data->date_of_birth;
    $place_of_birth = $data->place_of_birth;
   
    // general info
    $membership_number = $data->membership_number;
    $member_possition = $data->member_possition;  
    $signing_mandate = $data->signing_mandate;

    $image = $data->image;
    $fingerprintR = $data->fingerprintR;
    $fingerprintL = $data->fingerprintL;
    $id_front_image = $data->id_front_image; //add f
    $id_back_image = $data->id_back_image; //add f
    $signature = $data->signature; //add f
    registerGroupMember($full_name, $id_number, $phone_number, $date_of_birth, $place_of_birth, $membership_number, $member_possition, $signing_mandate, $image, $fingerprintR, $fingerprintL, $id_front_image, $id_back_image, $signature);
}

function registerGroupMember($full_name, $id_number, $phone_number, $date_of_birth, $place_of_birth, $membership_number, $member_possition, $signing_mandate, $image, $fingerprintR, $fingerprintL, $id_front_image, $id_back_image, $signature)
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
        $query = "SELECT * FROM hs_hr_employee WHERE employee_id='$id_number'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO hs_hr_employee
			(full_name, id_number, phone_number, date_of_birth, place_of_birth, membership_number, member_possition, signing_mandate, image, fingerprintR, fingerprintL, id_front_image, id_back_image, signature)
			VALUES 
			('$full_name', '$id_number', '$phone_number', '$date_of_birth', '$place_of_birth', '$membership_number', '$member_possition', '$signing_mandate', '$image', '$fingerprintR', '$fingerprintL', '$id_front_image', '$id_back_image', '$signature')";

            $result1 = mysqli_query($con, $query);
            if ($result1) {
                $response['error'] = false;
                $response["msg"] = "Registration successful";
                header('Content-type: application/json');
            } else {
                $response['error'] = true;
                $response["msg"] = "Insertion Failed";

                header('Content-type: application/json');
            }
        } else {
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
