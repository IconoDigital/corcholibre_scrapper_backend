<?php 
session_start();
//include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");

$id = $_POST["delete_id"];
$get_file = "delete from product where product_id='".$id."'";
$run_file = mysqli_query($mysqli,$get_file) or die($mysqli);
?>