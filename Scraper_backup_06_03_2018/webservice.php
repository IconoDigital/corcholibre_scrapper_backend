<?php 
$post_json_string=$_POST["JSON_data"];
$obj=json_decode($post_json_string);
$first_name=$obj->first_name;
echo $first_name;
?>