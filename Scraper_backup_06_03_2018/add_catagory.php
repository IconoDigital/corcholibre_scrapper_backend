<?php
  session_start();
//include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");

$product_id = $_POST["product_id"];
//echo $product_id ;
$cat_keyword= $_POST["cat_keyword"];
//echo $cat_keyword;
$quantity_keyword = $_POST["quantity_keyword"];
//echo $unit_keyword;

$insert_sql = "update product SET category_keyword='".$cat_keyword."',quantity_keyword='".$quantity_keyword."' where product_id=".$product_id  ;
$insert_que = mysqli_query($mysqli,$insert_sql) or die_u(mysqli_error($mysqli));
if ($insert_que) {
	echo "updated";
}
else
{
	echo "error";
}
?>