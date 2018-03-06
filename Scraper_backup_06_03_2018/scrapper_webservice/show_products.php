<?php

// Include confi.php
include_once('connection.php');
//header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$page_no=$obj->page_no;
$results_per_page=$obj->no_of_items_in_a_page;
$offer_clause="";
if(property_exists($obj,"offer")){
	$offer_clause=' WHERE (offer_price !="" OR offer_title!="" OR offer_conditions!="") ';
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
   
   // get all products data from database page wise
      //$results_per_page=10;
   if(!empty($page_no) && !empty($results_per_page)){	  
	$start_from = ($page_no-1) * $results_per_page;
    $sql = "SELECT * FROM product ".$offer_clause." ORDER BY product_id ASC LIMIT ". $start_from .", ".$results_per_page;
		//$sql = "select user_id,first_name,last_name,email,fb_token,birth_year,gender,country from users where user_id=".$user_id.";";
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($r = mysqli_fetch_array($qur)){
		  $result[] = array('product_id'=>$r[0], 'product_name' => $r[1], 'product_desc' => $r[2], 'brand_name' => $r[3] , 'unit' => $r[4] , 'price' => $r[5] , 'offer_price' => $r[6] , 'offer_title' => $r[7] , 'offer_conditions' => $r[8], 'timestamp' => $r[9], 'updated' => $r[10], 'source' => $r[11], 'category_id' => $r[12], 'valid_until' => $r[13], 'url' => $r[14]);
		  }
		  $json = array("status" => 0, "product_info" => $result);
		}
		//get total row count from product table
		$sql1 = "SELECT count(*) count FROM product ";//ORDER BY product_id ASC LIMIT ". $start_from .", ".$results_per_page;
		//echo  '<br>'. $sql1;
		$result1 = mysqli_query($conn,$sql1);
		$val = mysqli_fetch_array($result1);
        $row_count = $val["count"];
		$json["total_count"]=$row_count;
   }
   else{
	    $json = array("status" => 1, "msg" => "page no and no_of_items_in_a_page must not be empty");
   }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>