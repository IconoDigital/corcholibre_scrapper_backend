<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$search_item=$obj->search_item;
$page_no=$obj->page_no;
$results_per_page=$obj->no_of_items_in_a_page;
$offer_clause="";
if(property_exists($obj,"offer")){
	$offer_clause=' AND (offer_price !="" OR offer_title!="" OR offer_conditions!="") ';
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
   
   // get all products data from database page wise
      //$results_per_page=10;
   if(!empty($page_no) && !empty($results_per_page) && !empty($search_item) ) {	  
	$start_from = ($page_no-1) * $results_per_page;

    $sql = "SELECT distinct product_id,product_name,product_desc,brand_name,category_keyword,quantity_keyword FROM product where product_name like '%".$search_item."%' or brand_name like '%".$search_item."%' or category_keyword like '%".$search_item."%' or quantity_keyword like '%".$search_item."%'".$offer_clause;
    $sql = $sql." ORDER BY product_id ASC LIMIT ". $start_from .", ".$results_per_page;
		
		//echo  '<br>'. $sql;
		if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		{
	      $result =array();
		  while($r = mysqli_fetch_array($qur)){
		  $result[] = array('product_id'=>$r[0], 'product_name' => $r[1], 'product_desc' => $r[2], 'brand_name' => $r[3], 'category_keyword' =>$r[4] ,'quantity_keyword' =>$r[5]  );
		  }
		  $json = array("status" => 0, "product_info" => $result);
		}
		//get total row count accoding to search 
		$sql1 = "SELECT count(*) count FROM product where product_name like '%".$search_item."%' or brand_name like '%".$search_item."%' or category_keyword like '%".$search_item."%' or quantity_keyword like '%".$search_item."%'";
        $sql1 = $sql1." ORDER BY product_id ASC ";
		//echo  '<br>'. $sql1;
		$result1 = mysqli_query($conn,$sql1);
		$val = mysqli_fetch_array($result1);
        $row_count = $val["count"];
		$json["total_count"]=$row_count;
   }
   else{
	    $json = array("status" => 1, "msg" => "page no,no_of_items_in_a_page must and search_item must not be empty");
   }
}else{
	$json = array("status" => 1, "msg" => "Request method not accepted");
}

   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>