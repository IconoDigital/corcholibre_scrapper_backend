<?php

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
  header('Content-Type: text/html; charset=utf-8');
$conn = mysqli_connect($servername, $username, $password)or die("Unable to connect to MySQL"); // Create connection
$selected = mysqli_select_db($conn,"scraper_project") or die("Could not select scraper_project");	//select a database to work with
mysqli_set_charset($conn,'utf8' );
  
  //The name of the directory that we need to create.
  $directoryName = 'product_images_plazavea';
 
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
       foreach($html->find('.product-name') as $element) 
       {
		   foreach($element->find('div') as $ele) 
          {
			 $product_name=mysqli_real_escape_string($conn,$ele->innertext);	
		     $product_n=$ele->innertext;
		      $scrap[]= "Product Name:".$product_name.'<br>';
		  }
       }
	    $img_file='product_images_plazavea/'.$product_n.".jpg";
		if(file_exists($img_file))
		{
			unlink($img_file);
		}
        grab_image($image_src,$img_file);
	   //brand_name
	    foreach($html->find('.brandName') as $element) 
        {
			 foreach($element->find('a') as $ele) 
           {
			  $brand_name=mysqli_real_escape_string($conn,$ele->innertext);
              //$brand_name=$ele->innertext;
			   $scrap[]= "Brand Name:".$brand_name.'<br>';		   		    
           }
			
	    }
	  
	   //find offer price
	  
	     foreach($html->find('.product-information .descricao-preco .price-multiplier strong') as $element) 
       {	
			$offer_price=$element->innertext;		
			 $scrap[]= "Offer Price:".$offer_price.'<br>';
	   }
	   $p='S/. 0.00';
	   if($offer_price=="")
	   {
		     foreach($html->find('.skuBestPrice') as $element) 
       {	
			$offer_price=$element->innertext;		
			 $scrap[]= "Offer Price:".$offer_price.'<br>';
	   }
	   }
	    //find original_price;
	    foreach($html->find('.skuListPrice') as $element) 
       {		
	      
           $price=$element->innertext;	
//		   echo $price.'<br>';
            if($price==$p)
			{
             $price=$offer_price;	
			 $offer_price='';
			}			 
		    $scrap[]= 'original_price: '.$price.'<br>';
	   }
	   
	   if($price=="")
	   {
		foreach($html->find('.skuBestPrice') as $element) 
       {	
			$price=$element->innertext;		
			 $scrap[]= "Price:".$price.'<br>';
	   }
	   }
	   
	    foreach($html->find('.product-conditions .product-conditions-inner .product-conditions-tooltip .product-conditions-window .product-conditions-header') as $element) 
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
		   
		   foreach($html->find(".product-conditions-content") as $ele) 
           {	   
			  $conditions=$ele->innertext;	
				$conditions=str_replace("/<font/>","",$conditions);
				$conditions=str_replace("/<\/font/>","",$conditions);
				$conditions=str_replace("<br>","",$conditions);
				$conditions=preg_replace("/<a.*<\/a>/","",$conditions);
			   $scrap[]= "conditions:".$conditions.'<br>';
			  
			  $matches = array();
                   preg_match('/\d\d\/\d\d\/\d\d\d\d/', $conditions, $matches);
				   if($matches)
				   {
					 $dat=trim($matches[0]);
					 $date = $dat;
                     $date = str_replace('/', '-', $date);
                      $scrap[]= "<br>ends:".$dateformat= date('Y-m-d', strtotime($date));
				     
				   }
		   }
			
	   //} 
	   /*
	   
	     foreach($html->find('.active-offer') as $element) 
       {		   
		    foreach($element->find('span') as $ele) 
                {
					 //foreach($element->find('.red') as $el) 
                   //{
					   if($i==0)
					   {
						   $offer_title=trim($ele->innertext);
					       echo $offer_title.'<br>';
					   }
				  else
				  {
					  $offer_conditions=mysqli_real_escape_string($conn,$ele->innertext);				        
					  echo $offer_conditions;
					  echo '<br>';
				   $matches = array();
                   preg_match('/\d\d\/\d\d\/\d\d\d\d/', $offer_conditions, $matches);
				   if($matches)
				   {
					 $dat=trim($matches[0]);
					 $date = $dat;
                     $date = str_replace('/', '-', $date);
                     echo $dateformat= date('Y-m-d', strtotime($date));
				     
				   }
				  }
				   $i++;
				}
	   }*/
  $source="plazavea";
  $query="insert into product(product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source,valid_until,url) values("; //str_to_date('".$date."','%d-%m-%Y')
  
  $query=$query."'".$product_name."','','".$brand_name."','".$unit."','".$price."','".$offer_price."','".$offer_title."','".$conditions."','".$source."','".$dateformat."','-') ON DUPLICATE KEY UPDATE 
   `product_desc`='".''."',brand_name='".$brand_name."',unit='".$unit."',price='".$price."',offer_price='".$offer_price."',offer_title='".$offer_title."',offer_conditions='".$conditions."',source='".$source."',valid_until='".$dateformat."'";
   $scrap[]=  "<br>Query:".$query;
   
  echo "<p><b>Details:</b></p>";
foreach($scrap as $d)
{
	echo $d;
	echo "<br>";
}  

echo "<h2>Result</h2>";

  mysqli_query($conn,$query)or die(mysqli_error($conn));
	
 echo "<h3>Product has been saved to database.</h3>";
 
echo '<a href="product_plazavea.php"><input type="button" value="Add another"/></a>';
	  
  //execute the SQL query and return records
	  //close the connection
mysqli_close($conn);
	   }
	   else
	   {
		   echo "There is some problem with parsing this link.";
	   }
	   }
	   else
	   {
		   ?>
		   <!DOCTYPE HTML>  
<html>
<head>
   
</head>
<body>  
<h1>Plazavea</h1> 
<form action="product_plazavea.php" accept-charset="utf-8" method="post">
<br>
<br>
<br>
 &nbsp &nbsp Website LINK: <input type="text" name="name" > &nbsp <input type="submit" name="submit" value="Submit"> 
 </form>
</body>
</html>
		   <?php
	   }
?>