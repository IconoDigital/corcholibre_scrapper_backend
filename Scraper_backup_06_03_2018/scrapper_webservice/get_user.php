<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$user_id=$obj->user_id;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	if(isset($user_id) &&!empty($user_id)  ){
		$sql = "select user_id,first_name,last_name,email,fb_token,birth_year,gender,country from users where user_id=".$user_id.";";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($r = mysqli_fetch_array($qur)){
		  $result[] = array("user_id"=>$r[0], "first_name" => $r[1], "last_name" => $r[2], 'email' => $r[3] , 'fb_token' => $r[4] , 'birth_year' => $r[5] , 'gender' => $r[6] , 'country' => $r[7] );
		  }
		  $json = array("status" => 0, "user_info" => $result);
		}
     }
	 else{
			 $json = array("status" => 1, "msg" => "invalid user id");
		 }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>