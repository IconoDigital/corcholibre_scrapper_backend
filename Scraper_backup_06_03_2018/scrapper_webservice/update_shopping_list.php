<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$list_id=$obj->list_id;
$list_data=$obj->list_data;

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// update data in data base
	if(!empty($list_id) && !empty($list_data) ){
		$sql1 = "select list_id from user_shopping_list where list_id=".$list_id;
		$rs=mysqli_query($conn,$sql1);
		if($res=mysqli_fetch_array($rs))
		{
			$sql = "update user_shopping_list set list_data='".$list_data."' where list_id=".$list_id.";";
			//echo  '<br>'. $sql;
			$qur = mysqli_query($conn,$sql);
			if($qur){
				$json = array("status" => 0, "msg" => "data updated successfully");
			}else{
				$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
			}
		}
		else{
			$json = array("status" => 1, "msg" => "list id does not exist");
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