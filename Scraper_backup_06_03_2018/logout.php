<?php 
session_start();
if($_SESSION["user_type"]=='affiliate')
	$loc="https://unidefend.com";
else
	$loc="login.php";
		$_SESSION["user_id"]='';
		$_SESSION["user_type"]='';
		$_SESSION["user_name"]='';
		$_SESSION["email"]='';
		unset($_SESSION["user_id"]);
		unset($_SESSION["user_type"]);
		unset($_SESSION["user_name"]);
		unset($_SESSION["email"]);
		
		header("Location:".$loc);
?>