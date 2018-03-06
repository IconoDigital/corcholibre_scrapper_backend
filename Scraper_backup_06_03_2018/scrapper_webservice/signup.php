<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_REQUEST["JSON_data"];
$obj=json_decode($json_data);

$first_name=$obj->first_name;
$last_name=$obj->last_name;
$email=$obj->email;
$fb_token=$obj->fb_token;
$birth_year=$obj->birth_year;
$gender=$obj->gender;
$country=$obj->country;
$firebase_token=$obj->firebase_token;


	// Get data
	//$first_name = isset($_POST['first_name']) ? mysqli_real_escape_string($conn,$_POST['first_name']) : "";
	//echo $first_name;

	// Insert data into data base
		if(isset($email) &&!empty($email)  ){
		$sql = "select user_id,first_name,last_name,email,fb_token,birth_year,gender,country from users where email='".$email."';";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		{
			if(mysqli_num_rows($qur)>0){
				//Update user info
			  $result =array();
			  while($r = mysqli_fetch_array($qur)){
			  $result[] = array("user_id"=>$r[0], "first_name" => $r[1], "last_name" => $r[2], 'email' => $r[3] , 'fb_token' => $r[4] , 'birth_year' => $r[5] , 'gender' => $r[6] , 'country' => $r[7] );
			  }
			  $json = array("status" => 0, "user_info" => $result);
			  
			  $sql = "UPDATE users set first_name='".$first_name."',last_name='".$last_name."',fb_token='".$fb_token."'".
			  ",birth_year='".$birth_year."',gender='".$gender."',country='".$country."', firebase_token='".$firebase_token."' WHERE email='".$email."'";
			  
			  
			  $qur = mysqli_query($conn,$sql);
						if($qur){
							$json = array("status" => 0, "msg" => "User updated Successfully");
						}else{
							$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
						}
			  
			}
			else{
				//insert new user in DB
					if(!empty($first_name) && !empty($last_name) && !empty($email) && !empty($fb_token) && !empty($birth_year) && !empty($gender) && !empty($country) ){
						$sql = "INSERT INTO users(first_name,last_name,email,fb_token,birth_year,gender,country,firebase_token)";
						$sql = $sql." VALUES('".$first_name."', '".$last_name."', '".$email."', '".$fb_token."', '".$birth_year."', '".$gender."', '".$country."','".$firebase_token."');";
						//echo  '<br>'. $sql;
						$qur = mysqli_query($conn,$sql);
						if($qur){
							$json = array("status" => 0, "msg" => "User added Successfully");
						}else{
							$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
						}
					 }
					 else{
					   $json = array("status" => 1, "msg" => "all fields must be filled with values");
					 }			
				
			}
	      
		}
     }
	 


   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>