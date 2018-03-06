<?php
include_once('simple_html_dom.php');
if(isset($_POST["name"]) && $_POST["name"]!="")
{
$link=$_POST["name"];
$html = file_get_html($link); // Create DOM from URL or file

  $servername = "localhost";
  $username = "root";
  $password = "news5cs3";
  $i=0;
  $j=0;
  $product_name='';
  $product_description='';
  $unit='';
  $brand_name='';
  $price='';
  $offer_price='';
  $offer_title='';
  $offer_conditions='';
  $date='';
  $dateformat='';
   header('Content-Type: text/html; charset=utf-8');
$conn = mysqli_connect($servername, $username, $password)or die("Unable to connect to MySQL"); // Create connection
$selected = mysqli_select_db($conn,"scraper_project") or die("Could not select scraper_project");	//select a database to work with
mysqli_set_charset($conn,'utf8' );
  
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
		    foreach($html->find('img') as $ele) 
           {  if($j==0)
			   {
			   $image_src=$ele->src;
			   $image_src="http:".$image_src;
			   $scrap[]= 'image-src: '.$image_src.'<br>';
		      }
		    $j++;
		   }
		   //$url='http://www.tottus.com.pe/static/15a//img/img-com/icons/add-note.png';
	    //final product_name and brand_name
        foreach($html->find('.title') as $element) 
       {
		    foreach($element->find('h5') as $ele) 
           {
               $arr=explode('<span>',trim($ele->innertext));
			   $product_name=trim($arr[0]," &nbsp;");
			   $scrap[]= 'product_name: '.$product_name.'<br>';
			   
			     foreach($ele->find('span') as $el) 
                {
					$brand_name=$el->innertext;
					$scrap[]= 'brand_name: '.$brand_name.'<br>';
				}
           }
		   $img_file='product_images/'.$product_name.".jpg";
		   if (!file_exists($img_file)) {
             grab_image($image_src,$img_file);
		   }
       }
	   //find description;
	    foreach($html->find('.statement') as $element) 
       {		
           $product_description=$element->innertext;	   
		   $scrap[]= 'product_description: '.$product_description.'<br>';
	   }
	   //find price
	    $n=0;
	     foreach($html->find('.active-price') as $element) 
       {		   
		    foreach($element->find('span') as $el) 
                {
					if($n==0)
					$price=trim($el->innertext);
				    else
						$unit=trim($el->innertext);
					$n++;
				}
				$scrap[]= 'price: '.$price.'<br>';
				$scrap[]= 'unit: '.$unit.'<br>';
	   }
	   
	   //find red_letters and price_offer
	     foreach($html->find('.active-offer') as $element) 
       {		   
		    foreach($element->find('span') as $ele) 
                {
					 //foreach($element->find('.red') as $el) 
                   //{
					   if($i==0)
					   {
						   $offer_title=trim($ele->innertext);
					       $scrap[]= 'offer_title: '.$offer_title.'<br>';
					   }
				  else
				  {
					  $offer_conditions=mysqli_real_escape_string($conn,$ele->innertext);				        
					  $scrap[]= 'offer_conditions: '.$offer_conditions;
				   $matches = array();
                   preg_match('/\d\d\/\d\d\/\d\d\d\d/', $offer_conditions, $matches);
				   if($matches)
				   {
					 $dat=trim($matches[0]);
					 $date = $dat;
                     $date = str_replace('/', '-', $date);
                     $scrap[]= 'date: '.$dateformat= date('Y-m-d', strtotime($date));
				     
				   }
				  }
				   $i++;
				}
	   }
  $source="tottus";
  $query="insert into product(product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source,valid_until,url) values(";
  
  $query=$query."'".$product_name."','".$product_description."','".$brand_name."','".$unit."','".$price."','".$offer_price."','".$offer_title."','".$offer_conditions."','".$source."','".$dateformat."','-') ON DUPLICATE KEY UPDATE 
   `product_desc`='".$product_description."',brand_name='".$brand_name."',unit='".$unit."',price='".$price."',offer_price='".$offer_price."',offer_title='".$offer_title."',offer_conditions='".$offer_conditions."',source='".$source."',valid_until='".$dateformat."'";
  $scrap[]=  '<br>'. $query;
  
    echo "<p><b>Details:</b></p>";
foreach($scrap as $d)
{
	echo $d;
	echo "<br>";
}  

echo "<h2>Result</h2>";
  
  mysqli_query($conn,$query)or die(mysqli_error($conn));
  
  echo "<h3>Product has been saved to database.</h3>";
 
echo '<a href="product_tottus.php"><input type="button" value="Add another"/></a>';
	  
   ///execute the SQL query and return records
  //close the connection
mysqli_close($conn);
	   }
	   else
	   {
		   ?>
		   
<!DOCTYPE HTML>  
<html>
<head>
   
</head>
<body> 
<h1>Tottus</h1> 
<form action="product_tottus.php " accept-charset="utf-8" method="post">
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

