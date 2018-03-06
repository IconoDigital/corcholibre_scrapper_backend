<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$page_no=$obj->page_no;
$results_per_page=$obj->no_of_items_in_a_page;
$user_id=$obj->user_id;

if($_SERVER['REQUEST_METHOD'] == "POST"){
   
   // get all products data from database page wise
      //$results_per_page=10;
   if(!empty($page_no) && !empty($results_per_page) && !empty($user_id) ){	 
     $sql1 = "select user_id from user_shopping_list where user_id=".$user_id;
	 $rs=mysqli_query($conn,$sql1);
	 if($res=mysqli_fetch_array($rs))
	 {   
			$start_from = ($page_no-1) * $results_per_page;
			$sql = "SELECT * FROM user_shopping_list where user_id=".$user_id." ORDER BY list_id ASC LIMIT ". $start_from .", ".$results_per_page;
				if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
				{
				  $result =array();
				  while($r = mysqli_fetch_array($qur)){
				  $result[] = array('list_id'=>$r[0], 'user_id' => $r[1], 'list_data' => $r[2], 'created' => $r[3] );
				  }
				  $json = array("status" => 0, "shopping_list_info" => $result);
				}
				//get total row count from user_shopping_list table
				$sql1 = "SELECT count(*) count FROM user_shopping_list where user_id=".$user_id;
				$result1 = mysqli_query($conn,$sql1);
				$val = mysqli_fetch_array($result1);
				$row_count = $val["count"];
				$json["total_count"]=$row_count;
	  }
	else{
			$json = array("status" => 1, "msg" => "user id does not exist");
		}
   }
   else{
	    $json = array("status" => 1, "msg" => "page no and no_of_items_in_a_page and user_id must not be empty");
   }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>