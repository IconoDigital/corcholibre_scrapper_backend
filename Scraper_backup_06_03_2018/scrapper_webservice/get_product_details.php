<?php

// Include confi.php
include_once('connection.php');
header('Content-type: application/json');
$json_data=$_POST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);
$product_id_list=$obj->product_id_list;
 
   if( !empty($product_id_list) )
   {
	   $arr_length = count($product_id_list);
       $result =array();	   
       for($i=0;$i<$arr_length;$i++) 
       { 
			$sql = "select * FROM product where product_id=".$product_id_list[$i];
			if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		    {			
					   
				$r = mysqli_fetch_array($qur);
			    $result[] = array('product_id'=>$r[0], 'product_name' => $r[1], 'product_desc' => $r[2], 'brand_name' => $r[3] , 'unit' => $r[4] , 'price' => $r[5] , 'offer_price' => $r[6] , 'offer_title' => $r[7] , 'offer_conditions' => $r[8], 'timestamp' => $r[9], 'updated' => $r[10], 'source' => $r[11], 'category_id' => $r[12], 'valid_until' => $r[13], 'url' => $r[14], 'category_keyword' => $r[15], 'quantity_keyword' => $r[16]);
		         
			  $json = array("status" => 0, "product_list" => $result);
		    }
            else{
	          $json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
            }
						
		}
   }
   else
   {
	    $json = array("status" => 1, "msg" => "all fields must be filled with values");
   }
   
     mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
?>