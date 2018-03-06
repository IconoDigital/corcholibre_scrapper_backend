<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$message_id=$obj->message_id;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	if(!empty($message_id)  ){
		$sql = "select message_id,text,from_user,to_user,list_ids,sent from messages where message_id=".$message_id.";";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql))
		{
	      $result =array();
		  while($r = mysqli_fetch_array($qur)){
		  $result[] = array("message_id"=>$r[0], "text" => $r[1], "from_user" => $r[2], 'to_user' => $r[3],'list_ids' =>$r[4], 'sent' => $r[5] );
		  }
		  $json = array("status" => 0, "message_info" => $result);
		}
		else{
			$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
		}
     }
	 else{
			 $json = array("status" => 1, "msg" => "invalid message id");
		 }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>