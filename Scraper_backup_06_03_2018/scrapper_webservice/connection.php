<?php 
  $servername = "localhost";
  $username = "root";
  $password = "news5cs3";
  
  header('Content-Type: text/html; charset=utf-8');
  $conn = mysqli_connect($servername, $username, $password)or die("Unable to connect to MySQL"); // Create connection
  $selected = mysqli_select_db($conn,"scraper_project") or die("Could not select scraper_project");	//select a database to work with
  mysqli_set_charset($conn,'utf8' );
  
?>