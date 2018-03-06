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
	if(!empty($user_id)  ){
		$sql = "select count(*) unread_message_count from messages where to_user=".$user_id." and status=1 ;";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql))
		{
	      $result =array();
		  $val = mysqli_fetch_array($qur);
          $row_count = $val["unread_message_count"];
		  //$json["total_count"]=$row_count;
		  $json = array("status" => 0, "unread_message_count" => $row_count);
		}
		else{
			$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
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