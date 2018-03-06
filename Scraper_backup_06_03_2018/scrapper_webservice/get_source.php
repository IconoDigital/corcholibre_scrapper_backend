<?php
// Include confi.php
include_once('connection.php');
header('Content-type: application/json');


if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	
		$sql_cat = "select source_id,source_name from source_master";
		//echo  '<br>'. $sql;
		if($qur_cat = mysqli_query($conn,$sql_cat)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($ret = mysqli_fetch_array($qur_cat)){
		  $result[] = array("source_id"=>$ret[0], "source_name" => $ret[1]);
		  }
		  $json = array("status" => 0, "user_info" => $result);
		}
    }
	else
	{
		$json = array("status" => 1, "msg" => "Request method not accepted");
}
mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>