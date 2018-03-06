<?php 
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");
$message="";
$error=0;
/*
 * Initialize to get the list of category to be 
 * later use to generate dynamic category list by source
 */
$cat_Sql="SELECT category_id,category_name FROM category_master";
$cat_Rs=mysqli_query($mysqli,$cat_Sql) or die(mysqli_error($mysqli));


 
 $src_Sql="SELECT s.source_id,s.source_name from  source_master s";
 $src_Rs=mysqli_query($mysqli,$src_Sql) or die(mysqli_error($mysqli));
/**********/

if(isset($_REQUEST["name"]) && $_REQUEST["name"]!='')
{

if(!isset($_SESSION["pagination"]) || $_SESSION["pagination"]==-1)
{
	$_SESSION["pagination"]=1;
}
else
{
	$_SESSION["pagination"]++;
}

$sql="INSERT INTO father_urls (url,source) VALUES ('".$_REQUEST["name"]."','tottus') ON DUPLICATE KEY UPDATE source='tottus'";
mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$reload=0;
include_once('simple_html_dom.php');
$e=0;

if(file_exists('tottus_list.html'))
{
	unlink("tottus_list.html");
}

$name=str_replace("http://www.tottus.com.pe/tottus/browse/","http://www.tottus.com.pe/tottus/productListFragment/",$_REQUEST["name"]);
$exe="/usr/local/bin/phantomjs fetch_tottus_list.js ".$name."?No=".(($_SESSION["pagination"]-1)*15)."";
exec($exe,$o,$e);


if($e==0)
{
$links=array();
$link='tottus_list.html';
$html = file_get_html($link); // Create DOM from URL or file
$counter=0;
foreach($html->find(".caption-top-wrapper a") as $a)
{
	if($a->href!="")
	{
	$links[]=$a->href;
	$counter++;
	}
}

if($counter==0)
{
	$_SESSION["pagination"]=-1;
	unset($_SESSION["pagination"]);
	die_u("No more new product links to add.");
	$reload=0;
}
else
{
	$sql_source_cat="SELECT source_category_id from source_category_maping WHERE category_id='".$_REQUEST['main-cateogry']."' AND source_id='".$_REQUEST['main-source']."'";
	$sql_sorce_cat_run=mysqli_query($mysqli,$sql_source_cat) or die(mysqli_error($mysqli));
	$sql_res=mysqli_fetch_assoc($sql_sorce_cat_run);
	//echo $sql_source_cat;
	$sql="INSERT INTO `product_list` (`link`,`source`,`source_category_id`) VALUES ";
	$c=0;
	foreach($links as $l)
	{
		if($c==0)
		{   	
			$sql.="('".$l."','tottus','".$sql_res['source_category_id']."')";
		}
		else
		{
			$sql.=",('".$l."','tottus','".$sql_res['source_category_id']."')";
		}
		$c++;
	}
	$sql.=" ON DUPLICATE KEY UPDATE `source`='tottus',`source_category_id`=".$sql_res['source_category_id'];
	mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
	$reload=1;
	//echo $sql;
}

}
else
{
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

    <title>Add Tottus Product List</title>

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
                              Add Tottus Product List
							  
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
                            <?php while($res=mysqli_fetch_assoc($cat_Rs)) {  	
							print '<option value="'.$res['category_id'].'">'.$res['category_name'].'</option>';
							}?>                       
                            </select>
							&nbsp &nbsp Choose Store: 
                            <select class="main-cat-custom" name="main-source">
                            <?php while($res2=mysqli_fetch_assoc($src_Rs)) {
							print '<option value="'.$res2['source_id'].'">'.$res2['source_name'].'</option>';
							}?>                       
                            </select>
                            <br>
							<br>
							<br>
							 &nbsp &nbsp Tottus Father Link: <input type="text" name="name" > &nbsp <input type="submit" name="submit" value="Submit"> 
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
          } );
		  

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