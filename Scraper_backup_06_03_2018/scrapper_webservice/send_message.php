<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$text=$obj->text;
$from_user=$obj->from_user;
$to_user=$obj->to_user;
$list_ids=$obj->list_ids;


if($_SERVER['REQUEST_METHOD'] == "POST"){

	// Insert data into data base
	if(!empty($text) && !empty($from_user) && !empty($to_user) && !empty($list_ids) ){
		$sql1="select user_id from users where user_id=".$from_user;
		$rs = mysqli_query($conn,$sql1);
		
		$sql2="select user_id from users where user_id=".$to_user;
		$rs1= mysqli_query($conn,$sql2);		
		if( $res=mysqli_fetch_array($rs) && $res1=mysqli_fetch_array($rs1) )
		{
			$sql = "INSERT INTO messages(text,from_user,to_user,list_ids)";
			$sql = $sql." VALUES('".$text."', ".$from_user.", ".$to_user.", '".$list_ids."');";
			//echo  '<br>'. $sql;
			$qur = mysqli_query($conn,$sql);
			if($qur){
				$json = array("status" => 0, "msg" => "messages added Successfully");
			}else{
				$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
			}
	    }
		else{
			$json = array("status" => 1, "msg" => "from_user or to_user is not exist");
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