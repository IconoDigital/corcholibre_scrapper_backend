<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
//$category_id=$obj->category_id;
//$source_id=$obj->source_id;
//$source_id=$_REQUEST['source_id'];
//$source_id='';
if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	// Get user data from database 
	$sql_product = "select product_id,product_name,brand_name,price,offer_price,source_card_price,source,valid_until,source_category_id,offer_title,product_desc from product WHERE offer_price !=0 ORDER BY offer_price ASC";
		//echo  '<br>'. $sql;
		
		if($qur_product = mysqli_query($conn,$sql_product)or die(mysqli_error($conn)))
		{
	      $result =array();
		while($ret = mysqli_fetch_array($qur_product)){
		$image_link="http://52.3.144.26/scraper/product_images/".$ret[0].".jpg";
		//echo $image_link;  
		$sql_source_cat="select category_id from source_category_maping where  source_category_id =".$ret[8]."";  
		$run_source_cat = mysqli_query($conn,$sql_source_cat)or die(mysqli_error($conn));  
		$category_id=mysqli_fetch_array($run_source_cat);  
		  
		  $result[] = array("product_id"=>$ret[0], "product_name" => $ret[1], "brand_name" => $ret[2], 'price' => $ret[3] ,'offer_price' => $ret[4], 'source_card_price' => $ret[5] , 'store' => $ret[6] , 'valid_until' => $ret[7],'offer_title'=>$ret[9],'product_desc'=>$ret[10],'category_id'=>$category_id[0], 'img_url' => $image_link);
		  }
		  $json = array("status" => 0, "product_info" => $result);
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