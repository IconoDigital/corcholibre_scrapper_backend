<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
//$category_id=$obj->category_id;
//$source_id=$obj->source_id;
$category_id=2;
//$source_id='3,4';
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	if(isset($category_id) && !empty($category_id)){
		if(empty($source_id)){
		$sql_source_cat="select source_category_id from source_category_maping where  category_id=".$category_id;
		}
		else{$sql_source_cat="select source_category_id from source_category_maping where  category_id=".$category_id." AND source_id IN(".$source_id.")";}
		
		$run_source_cat = mysqli_query($conn,$sql_source_cat)or die(mysqli_error($conn));
		$result_src_cat =array();
		//$result_source_cat=array();
		while($result_source_cat = mysqli_fetch_array($run_source_cat)){
		//echo $result_src_cat;
		$result_src_cat[] = $result_source_cat['source_category_id'];
		}
		print_r ($result_src_cat);
		$values=implode(",",$result_src_cat);
		echo $values;
		$sql_product = "select product_id,product_name,brand_name,price,offer_price,source_card_price,source,valid_until from product where source_category_id IN(".$values.")";	
		echo $sql_product;
		
		//$result_source_cat = mysqli_fetch_array($run_source_cat);
		
		
		//echo  '<br>'. $sql;
		if($qur_product = mysqli_query($conn,$sql_product)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($ret = mysqli_fetch_array($qur_product)){
		  $result[] = array("product_id"=>$ret[0], "product_name" => $ret[1], "brand_name" => $ret[2], 'price' => $ret[3] ,'offer_price' => $ret[4], 'source_card_price' => $ret[5] , 'store' => $ret[6] , 'valid_until' => $ret[7]);
		  }
		  $json = array("status" => 0, "product_info" => $result);
		}
    }
	 else{
		$sql_product = "select product_id,product_name,brand_name,price,offer_price,source_card_price,source,valid_until from product";
		//echo  '<br>'. $sql;
		if($qur_product = mysqli_query($conn,$sql_product)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($ret = mysqli_fetch_array($qur_product)){
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