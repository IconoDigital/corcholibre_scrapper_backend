<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$list_id=$obj->list_id;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	if(!empty($list_id)  ){
		$sql = "select list_id,user_id,list_data,created from user_shopping_list where list_id=".$list_id.";";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($r = mysqli_fetch_array($qur)){
		  $result[] = array("list_id"=>$r[0], "user_id" => $r[1], "list_data" => $r[2], 'created' => $r[3] );
		  }
		  $json = array("status" => 0, "shopping_list_info" => $result);
		}
     }
	 else{
			 $json = array("status" => 1, "msg" => "invalid list id");
		 }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>