<?php 
session_start();
include("includes/DatabaseConnection.php");
include("includes/Library.php");
include("includes/config.php");
include("permission.php");
header("Location:View_Products.php");
?>
<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Codomotive">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title><?php 
	if(!isset($_GET["view"]))
		echo "Dashboard";
	else
		echo $_GET["view"];
	?></title>

   <?php

     include("header.php");?>
      <!--header end-->
      <!--sidebar start-->
	<?php 
	include("admin-sidebar.php");

	?>
	 <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
             <?php 
			 if(!isset($_GET["view"]) || $_GET["view"]=='')
			 {
			 if(isset($_SESSION['user_type'])  && $_SESSION["user_type"]=="broker")
				 include("admin-content.php");
			 else
				 if(isset($_SESSION["user_type"])&& ($_SESSION["user_type"]=='agent' || $_SESSION["user_type"]=='broker_agent'))
				 {
					 include("agent-content.php");
				 }
			 }
			 else
			 {
				 include("page-".$_GET["view"].".php");
			 }
			 ?>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
  </section>

    <?php 
	include("footer.php");
	?>


  </body>

</html>
