<?php 
/*
 * This screapper script file is created and mainained by
 @   Bhuvaneshwar Sharma
 @@  Sr. Developer (arvindsonu.sharma2@gmail.com)
 @@@ In this file we are going to scrap product from different websites
 */
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");
/*
 * Initialize to get the list of category to be 
 * later use to generate dynamic category list by source
 */
if(isset($_POST['cat'])) {
  $catSql="SELECT * FROM source_master WHERE category_id=".$_POST['cat'];
  $catRs=mysqli_query($mysqli,$catSql) or die(mysqli_error($mysqli));
  $getCatArr = array();
  $res=mysqli_fetch_array($catRs);
  return json_encode(array('source'=>$res['source_name']));
}
$catSql="SELECT * FROM category_master ORDER BY category_id ASC";
$catRs=mysqli_query($mysqli,$catSql) or die(mysqli_error($mysqli));
$getCatArr = array();
while($res=mysqli_fetch_array($catRs)) {
  $getCatArr[$res['category_id']][] = $res['category_name'];  
}
/*
 * This section is for the form post submitted data
 * we use global $_REQUEST variable to get the values of submmmited form data
 * alse we are using some meaning full variable to scrap product links from different website
 */
$message="";
$error=0;
if(isset($_REQUEST["name"]) && $_REQUEST["name"]!='') {
  // to get all the pagining data from different websites	
  if(!isset($_SESSION["pagination"]) || $_SESSION["pagination"]==-1) {
    $_SESSION["pagination"]=1;
  }
  else {
    $_SESSION["pagination"]++;
  }
  // when the form is submitted then we are going to insert main fater link into our site database table
  $sql="INSERT INTO father_urls (url,source) VALUES ('".$_REQUEST["name"]."','wong') ON DUPLICATE KEY UPDATE source='wong'";
  mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  $reload=0;
  include_once('simple_html_dom.php');
  $e=0;
  // first we need to delete already scrapped HTML file
  if(file_exists('wong_list.html')) {
    unlink("wong_list.html");
  }
  exec("/usr/local/bin/phantomjs fetch_wong_list.js ".$_REQUEST["name"]."#".$_SESSION["pagination"]."",$o,$e);
  if($e==0) {
    $links=array();
	$link='wong_list.html';
	$html = file_get_html($link); // Create DOM from URL or file
	$counter=0;
	foreach($html->find(".b4-cnt-slider ul") as $ul) {
      foreach($ul->find("li") as $li) {
	    foreach($li->find(".g-img-prod") as $a) {
		  $links[]=$a->href;
	    }
      }
      $counter++;
    }
    if($counter==0) {
	  $_SESSION["pagination"]=-1;
	  unset($_SESSION["pagination"]);
	  die_u("No more new product links to add.");
	  $reload=0;
    }
    else {
	  $sql="INSERT INTO `product_list` (`link`,`source`) VALUES ";
	  $c=0;
	  foreach($links as $l) {
	    if($c==0) {
		  $sql.="('".$l."','wong')";
		}
		else {
		  $sql.=",('".$l."','wong')";
		}
		$c++;
	  }
	  $sql.=" ON DUPLICATE KEY UPDATE `source`='wong'";
	  mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
	  $reload=1;
    }
  }
  else {
    die_u("There is some problem with parsing this link.");
  }
}
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

    <title>Add Product List</title>

   <?php include("header.php");?>
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
			   <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Add Product List
							  
							  <!--<div  class="pull-right">
							     <a href="add_products.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD </a>
							  </div>-->
                          </header>
                          <div class="panel-body">
						  <?php if(isset($c) && $c!="") echo $c." links added to the database. Iteration:#".$_SESSION["pagination"];?>
						  <form action="" accept-charset="utf-8" method="post">
							<br>
							<br>
							<br>
							 &nbsp &nbsp Choose Category: 
                            <select class="main-cat-custom" name="main-cateogry">
                            <?php foreach($getCatArr as $sourceKey => $sourceVal) {
							  print '<option value="'.$sourceKey.'">'.$sourceVal[0].'</option>';
							}?>                       
                            </select>
                            <br>
							<br>
							<br>
							 &nbsp &nbsp Choose Source: 
                            <select name="main-source" class="main-source-custom" style="display:none">
                              <?php 
							  foreach($getCatArr as $sourceKey => $sourceVal) {
							    print '<option value="'.$sourceKey.'">'.$sourceKey.'</option>';
							  }
							  ?>                       
                            </select>
                            <br>
							<br>
							<br>
							 &nbsp &nbsp Wong Father Link: <input type="text" name="name" > &nbsp <input type="submit" name="submit" value="Submit"> 
							 </form>
                          </div>
                      </section>
                  </div>
              </div>
             
			   <!-- page end-->
          </section>
      </section>
      <!--main content end-->
  </section>


  
    <?php 
	include("footer.php");
	?>
<script type="text/javascript" charset="utf-8">
          $(document).ready(function() {
              $('#example').dataTable( {
                  "aaSorting": [[ 4, "desc" ]]
              } );
          
		    $('.main-cat-custom').change(function(e) {
			  var getCat = $(this).val();
			  $.post( 'add_list.php','cat='+getCat, function( data ) { 
				alert(data);	    
			
			  });
			});
		  });
		    

      </script>
	  
	  <?php 
	  if(isset($reload) && $reload==1)
	  {
		  ?>
		  <script>
		  location.reload();
		  </script>
		  <?php 
	  }
	  ?>

  </body>

</html>