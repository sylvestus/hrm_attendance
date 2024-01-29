<?php 
	require_once 'dBConfig.php';
 
 	$json = file_get_contents('php://input');
  $json_data = json_decode($json); 
  
  $username = $json_data->username;
  $password = $json_data->password;
 
 	$response = array();
	$response['error'] = true;
	$response["msg"] = "";
  
  $query = "SELECT * FROM ohrm_user WHERE user_name = '$username' and deleted = 0 and status = 1";  
           $result = mysqli_query($con, $query);  
           if(mysqli_num_rows($result) > 0){  
                while($row = mysqli_fetch_assoc($result)){  
                     if(password_verify($password, $row["user_password"])){    
                     $response['error'] = false; 
                     $response["msg"] = "Login successful";
                     $response["data"] = $row;
                     	header('Content-type: application/json');
                     }  
                     else{  
                     $response['error'] = true; 
                     $response["msg"] = "Password does not match";
                     	header('Content-type: application/json');
                     }  
                }  
           }  
           else {  
               $response['error'] = true; 
              	$response["msg"] = "Username does not match or disabled";
              	header('Content-type: application/json');  
           } 

echo json_encode($response);
mysqli_close($con);
 