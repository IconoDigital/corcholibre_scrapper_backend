<?php 
error_reporting(0);
Session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");


  $message="";
  $error=0;

if(isset($_POST["name"]) && $_POST["name"]!="")
{
	include_once('simple_html_dom.php');
	$link=$_POST["name"];
	$html = file_get_html($link); // Create DOM from URL or file

  $servername = "localhost";
  $username = "root";
  $password = "news5cs3";
  $i=0;
  $j=0;
  $product_name='';
  $product_desc='';
  $unit='';
  $brand_name='';
  $price='';
  $offer_price='';
  $offer_title='';
  $offer_condition='';
  $valid_until='0000-00-00';
  //The name of the directory that we need to create.
   $directoryName = 'product_images';
 
  //Check if the directory already exists.
   if(!is_dir($directoryName)){
    //Directory does not exist, so lets create it.
    mkdir($directoryName, 0777);
   }
   
  function grab_image($url,$saveto){
    $ch = curl_init($url);
	$fp = fopen($saveto,'x');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	
  }
 
 //find image url
$j=0;
 foreach($html->find(".image-zoom") as $a)
{
	foreach($a->find("img") as $img){
	$image_src=$img->src;
	
	$scrap[]= 'image-src: '.$image_src.'<br>';
	}
	$j++;
	}
	/*foreach($a->find("a") as $image)
	{
	foreach($image->find("img") as $img){
	}	  
		 /* foreach($html->find('img') as $ele) 
           {  if($j==0)
			   {
			   $image_src=$ele->src;
			   $image_src="http:".$image_src;
			   $scrap[]= 'image-src: '.$image_src.'<br>';
		      }
		    $j++;
		   }*/
			 
             
		
		 foreach($html->find('.brand') as $ele) 
       {
			 $brand_name=mysqli_real_escape_string($mysqli,$ele->innertext);	
		     $brand_name=$ele->innertext;
		      $scrap[]= "Brand Name:".$brand_name.'<br>';
       
		foreach($html->find('.productName') as $elem) 
        {
			
		    $product_name=mysqli_real_escape_string($mysqli,$elem->innertext);
				$scrap[]= 'product_name: '.$product_name.'<br>';
		}
	   
	   }	    
	 $img_file='product_images/'.$product_name.".jpg";
		
       //find price
	   $n=0;
	     foreach($html->find('.skuListPrice') as $elmnt) 
       {		   
			if($n==0){
			$price=$elmnt->innertext;}
			else{
				$unit=$elmnt->innertext;
					$n++;
			}
				$scrap[]= 'active price: '.$price.'<br>';
				$scrap[]= 'unit: '.$unit.'<br>';
	   }
	
	  //find offer price
	   $n=0;
	     foreach($html->find('.skuBestPrice') as $elmt) 
       {		   
			if($n==0){
			$offer_price=$elmt->innertext;}
			$scrap[]= 'active price: '.$offer_price.'<br>';
			//$scrap[]= 'unit: '.$unit.'<br>';
	   }
	
	/*convert price and offer price to float*/
	$price=str_replace(",",".",$price);
	preg_match('/[+-]?\d+([,.]\d+)?/', $price, $price);
	$price=$price[0];
	echo "price: ".$price."<br>";
	
	$offer_price=str_replace(",",".",$offer_price);
	preg_match('/[+-]?\d+([,.]\d+)?/', $offer_price, $offer_price);
	$offer_price=$offer_price[0];
	echo "offer_price: ".$offer_price."<br>";
 
 
 $source="vivanda";
  $query="insert into product(product_name,brand_name,unit,price,product_desc,offer_price,offer_title,offer_conditions,valid_until,source,url) values(";
  
  $query=$query."'".$product_name."','".$brand_name."','".$unit."','".$price."','".$product_desc."','".$offer_price."','".$offer_title."','".$offer_condition."','".$valid_until."','".$source."','-') 
	ON DUPLICATE KEY UPDATE 
   `product_name`='".$product_name."',brand_name='".$brand_name."',unit='".$unit."',price='".$price."',source='".$source."'";
  $scrap[]=  '<br>'. $query;
  echo $query;
    $message.= "<p><b>Details:</b></p>";
foreach($scrap as $d)
{
	$message.= $d;
	$message.= "<br>";
}  

  
  mysqli_query($mysqli,$query)or die_u(mysqli_error($mysqli));
  if(mysqli_insert_id($mysqli)!=0)
			{
			$img_file='/var/www/html/scraper/product_images/'.mysqli_insert_id($mysqli).".jpg";
			}
			else
			{
				$sql6="SELECT product_id FROM product WHERE product_name='".mysqli_real_escape_string($mysqli,$product_name)."'
				 && brand_name='".mysqli_real_escape_string($mysqli,$brand_name)."' 
				&& source='vivanda'
				";
				$rs5=mysqli_query($mysqli,$sql6) or die(mysqli_error($mysqli));
				$res13=mysqli_fetch_array($rs5);
				$img_file='/var/www/html/scraper/product_images/'.$res13["product_id"].".jpg";
			}
			echo $img_file;
			
			if(file_exists($img_file))
			{
				if(unlink($img_file))
					grab_image($image_src,$img_file);
			}
			else
			grab_image($image_src,$img_file);
  
if($error==0)
	{
		$fancy_message=1;
		$fancy_message_title="Success!";
		$fancy_message_text="Product has been successfully added to database.";
	}

		//close the connection
mysqli_close($mysqli);


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
    <!---<link rel="shortcut icon" href="img/favicon.ico">--->

    <title>Add Vivanda Offer</title>

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
                              Add Vivanda
							  
							  <!--<div  class="pull-right">
							     <a href="add_products.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD </a>
							  </div>-->
                          </header>
                          <div class="panel-body">
						  <?php if($message!="") echo $message;?>
						<form action="" accept-charset="utf-8" method="post">
						<br>
						<br>
						<br>
						 &nbsp &nbsp Website LINK: <input type="text" name="name" > &nbsp <input type="submit" name="submit" value="Submit"> 
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


  </body>

</html>