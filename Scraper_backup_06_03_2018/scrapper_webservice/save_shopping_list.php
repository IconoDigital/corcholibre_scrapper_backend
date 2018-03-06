<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$user_id=$obj->user_id;
$list_data=$obj->list_data;

if($_SERVER['REQUEST_METHOD'] == "POST"){


	// Insert data into data base
	if(!empty($user_id) && !empty($list_data) ){
		$sql1 = "select user_id from users where user_id=".$user_id;
		$rs=mysqli_query($conn,$sql1);
		if($res=mysqli_fetch_array($rs))
		{
			$sql = "INSERT INTO user_shopping_list(user_id,list_data)";
			$sql = $sql." VALUES('".$user_id."', '".$list_data."');";
			//echo  '<br>'. $sql;
			$qur = mysqli_query($conn,$sql);
			if($qur){
				$json = array("status" => 0, "msg" => "data saved successfully");
			}else{
				$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
			}
		}	
		else{
			$json = array("status" => 1, "msg" => "user id does not exist");
		}
     }
	 else{
	   $json = array("status" => 1, "msg" => "all fields must be filled with values");
	 }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>