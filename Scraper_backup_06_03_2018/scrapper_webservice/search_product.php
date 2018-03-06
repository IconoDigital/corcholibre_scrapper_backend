<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
//$search_item=$obj->search_item;
//$search_item='wong';
$search_item=$_REQUEST['search_item'];
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	if(isset($search_item) && !empty($search_item)){
		$sql_search = "SELECT product_id,product_name,brand_name,price,offer_price,source_card_price,source,valid_until FROM product where product_name like '%".$search_item."%' or source like '%".$search_item."%' or brand_name like '%".$search_item."%' ORDER BY product_id ASC";
		
		
		//echo  '<br>'. $sql;
		if($qur_search = mysqli_query($conn,$sql_search)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($ret = mysqli_fetch_array($qur_search)){
		  $result[] = array("product_id"=>$ret[0], "product_name" => $ret[1], "brand_name" => $ret[2], 'price' => $ret[3] ,'offer_price' => $ret[4], 'source_card_price' => $ret[5] , 'store' => $ret[6] , 'valid_until' => $ret[7]);
		  }
		  $json = array("status" => 0, "product_info" => $result);
		}
	}
	 
	}
else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>