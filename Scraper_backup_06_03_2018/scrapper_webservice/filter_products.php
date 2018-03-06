<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($_POST["JSON_data"]);
$col_name=$obj->col_name;
$filter=$obj->filter;
$page_no=$obj->page_no;
$results_per_page=$obj->no_of_items_in_a_page;
$offer_clause="";
$sort_clause=" ORDER BY product_id ASC ";
if(property_exists($obj,"offer")){
	$offer_clause='  (offer_price !="" OR offer_title!="" OR offer_conditions!="") ';
}
$price_diff="";
if(property_exists($obj,"sort_by")){	
	if(property_exists($obj,"order"))
	{
		if($obj->sort_by=="max_savings")
		{
		   $price_diff=' ,(price-offer_price) price_diff ';
		   $sort_clause='  order by price_diff '.$obj->order.' ';
		}
		else
		{
		  $sort_clause='  order by '.$obj->sort_by.' '.$obj->order.' ';
		}
	}
	else
	$sort_clause='  order by '.$obj->sort_by.' ';
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
   
   // get all products data from database page wise
      //$results_per_page=10;
   if(!empty($page_no) && !empty($results_per_page)  ){	  
	$start_from = ($page_no-1) * $results_per_page;
	$sql="";
	if(!empty($col_name) && !empty($filter)){
		if($offer_clause!="")
		 $sql = "SELECT *".$price_diff." FROM product where ".$col_name." like '%".$filter."%' AND ".$offer_clause;
		 else
		 		 $sql = "SELECT * FROM product where ".$col_name." like '%".$filter."%' ";
	}
	else{
		if($offer_clause!="")
		 $sql = "SELECT *".$price_diff." FROM product where  ".$offer_clause;
		 else
		 		 $sql = "SELECT * FROM product  ";
	}
	
    //$sql = "SELECT * FROM product where ".$col_name." like '%".$filter."%'".$offer_clause;
    $sql = $sql."  ".$sort_clause." LIMIT ". $start_from .", ".$results_per_page;
		
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql))
		{
	      $result =array();
		  while($r = mysqli_fetch_assoc($qur)){
		  $result[] = $r;
		  //$result[] = array('product_id'=>$r[0], 'product_name' => $r[1], 'product_desc' => $r[2], 'brand_name' => $r[3] , 'unit' => $r[4] , 'price' => $r[5] , 'offer_price' => $r[6] , 'offer_title' => $r[7] , 'offer_conditions' => $r[8], 'timestamp' => $r[9], 'updated' => $r[10], 'source' => $r[11], 'category_id' => $r[12], 'valid_until' => $r[13], 'url' => $r[14]);
		  }
		  $json = array("status" => 0, "product_info" => $result);
		  //get total row count accoding to search 
			$sql1 = "SELECT count(*) count FROM product where ".$col_name." like '%".$filter."%'";
			//$sql1 = $sql1.$sort_clause;
			$result1 = mysqli_query($conn,$sql1);
			$val = mysqli_fetch_array($result1);
			$row_count = $val["count"];
			$json["total_count"]=$row_count;
			$json['sql']=$sql;
		}
		else{
			$json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
		}
		
   }
   else{
	    $json = array("status" => 1, "msg" => "page no and no_of_items_in_a_page must not be empty","post_data"=>$_POST['JSON_data']);
   }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>