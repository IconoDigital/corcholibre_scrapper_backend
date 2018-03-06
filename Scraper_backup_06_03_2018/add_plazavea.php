<?php 
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");


  $message="";
  $error=0;
$cat_Sql="SELECT category_id,category_name FROM category_master";
	$cat_Rs=mysqli_query($mysqli,$cat_Sql) or die(mysqli_error($mysqli));
	//echo $cat_Rs;
	$src_Sql="SELECT source_id,source_name from  source_master";
	$src_Rs=mysqli_query($mysqli,$src_Sql) or die(mysqli_error($mysqli)); 
  
  
  if(isset($_POST["name"]) && $_POST["name"]!='')
{
include_once('simple_html_dom.php');
if(file_exists('scrape.html'))
{
	unlink("scrape.html");
}
exec("/usr/local/bin/phantomjs fetch_page.js ".$_POST["name"]."",$o,$e);

if($e==0)
{

$link='scrap.html';
$html = file_get_html($link); // Create DOM from URL or file

  $servername = "localhost";
  $username = "root";
  $password = "news5cs3";

  $i=0;
  $j=0;
  $product_name='';
  $unit='';
  $brand_name='';
  $price='';
  $offer_price='';
  $offer_title='';
  $offer_conditions='';
  $date='';
  $dateformat='0000-00-00';
  $offer_date='0000-00-00';
  $source_card_price=0;
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
		   foreach($html->find('.image-zoom') as $ele) 
           {  
			   $image_src=$ele->href;
			   $scrap[]= "Image Link:".$image_src.'<br>';
		   } 
		   
		   //$url='http://www.tottus.com.pe/static/15a//img/img-com/icons/add-note.png';
	    //final description and brand_name
       foreach($html->find('.productName') as $ele) 
       {
			 $product_name=mysqli_real_escape_string($mysqli,$ele->innertext);	
		     $product_name=$ele->innertext;
		      $scrap[]= "Product Name:".$product_name.'<br>';
       }
	    $img_file='product_images/'.$product_name.".jpg";
		
	   //brand_name
	    foreach($html->find('.brandName') as $element) 
        {
			 foreach($element->find('a') as $ele) 
           {
			  $brand_name=mysqli_real_escape_string($mysqli,$ele->innertext);
              //$brand_name=$ele->innertext;
			   $scrap[]= "Brand Name:".$brand_name.'<br>';		   		    
           }
			
	    }
		
		
		//find date
		/*foreach($html->find('.b12-cnd-fecha') as $element) 
       {	
			$offer_date=$element->getElementsByClassName('b12-cnd-fecha');		
			//$offer_date=date('Y-m-d', strtotime($date); 
			$scrap[]= "Offer Date:".$offer_date.'<br>';
	   }
		//find source card price
		foreach($html->getElementsByClassName('toh') as $element) 
       {	
			$source_card_price=$element->innertext;		
			 $scrap[]= "Source Card Price:".$source_card_price.'<br>';
	   }*/
		

		
	   //find offer price
	  
	    /*foreach($html->find('.product-information .descricao-preco .price-multiplier strong') as $element) 
       {	
			$offer_price=$element->innertext;		
			 $scrap[]= "Offer Price:".$offer_price.'<br>';
	   }*/
	   $p='S/. 0.00';
	   
		foreach($html->find('.skuBestPrice') as $element) 
        {	
			$offer_price=$element->innertext;		
			$scrap[]= "Offer_Price:".$offer_price.'<br>';
	    }
	   
	    //find original_price;
	    foreach($html->find('.skuListPrice') as $element) 
		{		
			$price=$element->innertext;	
            $scrap[]= 'original_price: '.$price.'<br>';
		}
	   
	 
		foreach($html->find('.Price') as $element) 
       {	
			$source_card_price=$element->innertext;		
			 $scrap[]= "source card Price:".$source_card_price.'<br>';
	   }
	   
	   
	    foreach($html->find('.b12-tctit') as $element) 
       {	
			$offer_title=$element->innertext;		
			 $scrap[]= "Offer title:".$offer_title.'<br>';
	   }
	   
	   //find red_letters and price_offer
	      //foreach($html->find('div class=product-conditions') as $element) 
      // {
	  
	    
		  foreach($html->find(".productDescription") as $ele) 
           {	   
			  $disclaimer=$ele->innertext;		
			   $scrap[]= "disclaimer:".$disclaimer.'<br>';
			  break;
		   }
		   
		   foreach($html->find(".b12-cnd-contenido") as $ele) 
           {	   
			  $conditions=$ele->innertext;
				$conditions=str_replace("/<p/>","",$conditions);
				$conditions=str_replace("/<\/p/>","",$conditions);
			  
				$conditions=str_replace("/<font/>","",$conditions);
				$conditions=str_replace("/<\/font/>","",$conditions);
				$conditions=str_replace("<br>","",$conditions);
				$conditions=preg_replace("/<a.*<\/a>/","",$conditions);
			}
									
			foreach($html->find(".b12-cnd-fecha") as $ele) 
           {	   
			  $conditions.=$ele->innertext;
			  $conditions=str_replace("/<p/>","",$conditions);
				$conditions=str_replace("/<\/p/>","",$conditions);
			  
				$conditions=str_replace("/<font/>","",$conditions);
				$conditions=str_replace("/<\/font/>","",$conditions);
				$conditions=str_replace("<br>","",$conditions);
				$conditions=preg_replace("/<a.*<\/a>/","",$conditions);
			   $scrap[]= "conditions:".$conditions.'<br>';
			   
			   
			
			 $matches = array();
                   preg_match('/\d\d\-\d\d\-\d\d\d\d/', $conditions, $matches);
				   if($matches)
				   {
					 $dat=trim($matches[0]);
					 $date = $dat;
                     $date = str_replace('/', '-', $date);
                      $scrap[]= "<br>ends:".$offer_date= date('Y-m-d', strtotime($date));
				     
				   }
			}
			
		   if(!isset($conditions))
		   {
			   //$price=$offer_price;
			   //$offer_price=0;
			   $conditions="";
		   }
		   
		/*convert price and offer price to float*/
	
	/*if($source_card_price == ''){$source_card_price=0;}
	else{$source_card_price=str_replace(",",".",$source_card_price);
	preg_match('/[+-]?\d+([,.]\d+)?/', $source_card_price, $source_card_price);
	$source_card_price=$source_card_price[0];}*/
	
	$offer_price=str_replace(",",".",$offer_price);
	preg_match('/[+-]?\d+([,.]\d+)?/', $offer_price, $offer_price);
	$offer_price=$offer_price[0];
	
	
	if($price == ''){$price=$offer_price;$offer_price=0;}
	else{
	$price=str_replace(",",".",$price);
	preg_match('/[+-]?\d+([,.]\d+)?/', $price, $price);
	$price=$price[0];
	}
	//echo "price: ".$price."<br>";
	//echo "offer_price: ".$offer_price;
	//echo "source_card_price:".$source_card_price;
	$sql_source_cat="SELECT source_category_id from source_category_maping WHERE category_id='".$_REQUEST['main-cateogry']."' AND source_id='".$_REQUEST['main-source']."'";
	$sql_sorce_cat_run=mysqli_query($mysqli,$sql_source_cat) or die(mysqli_error($mysqli));
	$sql_res=mysqli_fetch_assoc($sql_sorce_cat_run);		
	   
	$source="plazavea";
	$query="insert into product(product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source,source_category_id,valid_until,source_card_price,url) values("; //str_to_date('".$date."','%d-%m-%Y')
  
  $query .="'".$product_name."','','".$brand_name."','".$unit."','".$price."','".$offer_price."','".$offer_title."','".$conditions."','".$source."','".$sql_res['source_category_id']."','".$offer_date."','".$source_card_price."','-') 
  ON DUPLICATE KEY UPDATE brand_name='".$brand_name."',unit='".$unit."',price='".$price."',offer_price='".$offer_price."',offer_title='".$offer_title."',offer_conditions='".$conditions."',source='".$source."',source_category_id='".$sql_res['source_category_id']."',valid_until='".$offer_date."'";
   $scrap[]=  "<br>Query:".$query;
   
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
				$sql4="SELECT product_id FROM product WHERE product_name='".mysqli_real_escape_string($mysqli,$product_name)."'
				 && brand_name='".mysqli_real_escape_string($mysqli,$brand_name)."' 
				 && unit='".mysqli_real_escape_string($mysqli,$unit)."' 
				 && source='plazavea'
				";
				$rs2=mysqli_query($mysqli,$sql4) or die(mysqli_error($mysqli));
				$res12=mysqli_fetch_array($rs2);
				$img_file='/var/www/html/scraper/product_images/'.$res12["product_id"].".jpg";
			}
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

    <title>Add Plazavea Offer</title>

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
                              Add Plazavea
							  
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
							 &nbsp &nbsp Choose Category: 
                            <select class="main-cat-custom" name="main-cateogry">
                            <?php while($res_cat=mysqli_fetch_array($cat_Rs)) {  	
							print '<option value="'.$res_cat['category_id'].'">'.$res_cat['category_name'].'</option>';
							}?>                       
                            </select>
							&nbsp &nbsp Choose Store: 
                            <select class="main-cat-custom" name="main-source">
                            <?php while($res_src=mysqli_fetch_array($src_Rs)) {
							print '<option value="'.$res_src['source_id'].'">'.$res_src['source_name'].'</option>';
							}?>                       
                            </select>
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
<script type="text/javascript" charset="utf-8">
          $(document).ready(function() {
              $('#example').dataTable( {
                  "aaSorting": [[ 4, "desc" ]]
              } );
          } );
		  

      </script>

  </body>

</html>