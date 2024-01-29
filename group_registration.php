<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once 'dBConfig.php';

$json = file_get_contents('php://input');
$json_data = json_decode($json);

$input = $json_data;

foreach ($input as $data) {
    // personal info
    $group_name = $data->group_name;
    $registration_number = $data->registration_number; // add f
    $total_members = $data->total_members;
    $contract_number = $data->contract_number;
    $start_date = $data->start_date;
    $place_of_operation = $data->place_of_operation;
    $group_photo1 = $data->group_photo1;
    $group_photo2 = $data->group_photo2;
    $group_photo3 = $data->group_photo3;
    $member_printR = $data->member_printR;
    $member_printL = $data->member_printL;
    $signature = $data->signature; //add f
    $registration_date = $data->registration_date; //add f
    $created_by =$data->created_by;
    registerGroup($group_name,$registration_number,$total_members,$contract_number,$start_date,$place_of_operation,$group_photo1,$group_photo2,$group_photo3,$member_printR,$member_printL,$signature,$registration_date,$created_by);
}

function registerGroup($group_name,$registration_number,$total_members,$contract_number,$start_date,$place_of_operation,$group_photo1,$group_photo2,$group_photo3,$member_printR,$member_printL,$signature,$registration_date,$created_by)
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
        $query = "SELECT * FROM hs_hr_group WHERE registration_number='$registration_number'";
        $result = mysqli_query($con, $query);
        

        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO hs_hr_group
			(group_name,registration_number,total_members,contract_number,start_date,place_of_operation,group_photo1,group_photo2,group_photo3,member_printR,member_printL,signature,registration_date,created_by)
			VALUES 
			('$group_name','$registration_number','$total_members','$contract_number','$start_date','$place_of_operation','$group_photo1','$group_photo2','$group_photo3','$member_printR','$member_printL','$signature','$registration_date','$created_by')";

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
