<?php 
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");
include("includes/DatabaseConnection.php");
//$sql="SELECT * FROM product_list WHERE TIMEDIFF(NOW(),last_parsed)>'2:00:00'";
if(date('d')%2==0)
$sql="SELECT * FROM product_list ORDER BY id DESC";
else
$sql="SELECT * FROM product_list ORDER BY id ASC";
$rs=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$coun=1;

$file_name="scrap_".time().".html";

while($res=mysqli_fetch_array($rs))
{
	echo $coun.":\r\n";
	if($res["source"]=="plazavea")
	{
		/*if(file_exists('/var/www/html/scraper/scrap.html'))
		{
			unlink("/var/www/html/scraper/scrap.html");
		}*/
		
		$link=url_path_encode($res["link"]);
		
		echo $link."\r\n\r\n";
		
		exec("/usr/local/bin/phantomjs /var/www/html/scraper/fetch_page_2.js ".$link." ".$file_name."",$o,$e);

		if($e==0)
		{
			include_once('simple_html_dom.php');

			$link='/var/www/html/scraper/'.$file_name;
			$html = file_get_html($link); // Create DOM from URL or file

			$servername = "localhost";
			$username = "root";
			$password = "news5cs3";

			$i=0;
			$j=0;
			$product_name='';
			$unit='';
			$brand_name='';
			$price=0;
			$offer_price=0;
			$offer_title='';
			$offer_conditions='';
			$conditions="";
			$date='';
			$dateformat='0000-00-00';


			//The name of the directory that we need to create.
			$directoryName = 'product_images';

			//Check if the directory already exists.
			if(!is_dir($directoryName)){
				//Directory does not exist, so lets create it.
				mkdir($directoryName, 0777);
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
		     $product_n=$ele->innertext;
		      $scrap[]= "Product Name:".$product_name.'<br>';
			}
			
			
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
			if(!isset($conditions))
			{
				$price=$offer_price;
				$offer_price=0;
			}
			
			/*convert price and offer price to float*/
			$price=str_replace(",",".",$price);
			preg_match('/[+-]?\d+([,.]\d+)?/', $price, $price);
			$price=$price[0];
			$offer_price=str_replace(",",".",$offer_price);
			preg_match('/[+-]?\d+([,.]\d+)?/', $offer_price, $offer_price);
			$offer_price=$offer_price[0];
			
			if($price==$offer_price)
				$offer_price=0;
			$source="plazavea";
			$query="insert into product(product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source,valid_until,url) values("; //str_to_date('".$date."','%d-%m-%Y')

			$query=$query."'".mysqli_real_escape_string($mysqli,$product_name)."','','".mysqli_real_escape_string($mysqli,$brand_name)."','".mysqli_real_escape_string($mysqli,$unit)."','".mysqli_real_escape_string($mysqli,$price)."','".mysqli_real_escape_string($mysqli,$offer_price)."','".mysqli_real_escape_string($mysqli,$offer_title)."','".mysqli_real_escape_string($mysqli,$conditions)."','".mysqli_real_escape_string($mysqli,$source)."','".$dateformat."','-') ON DUPLICATE KEY UPDATE 
`product_desc`='".''."',brand_name='".mysqli_real_escape_string($mysqli,$brand_name)."',unit='".mysqli_real_escape_string($mysqli,$unit)."',price='".mysqli_real_escape_string($mysqli,$price)."',offer_price='".mysqli_real_escape_string($mysqli,$offer_price)."',offer_title='".mysqli_real_escape_string($mysqli,$offer_title)."',offer_conditions='".mysqli_real_escape_string($mysqli,$conditions)."',source='".mysqli_real_escape_string($mysqli,$source)."',valid_until='".$dateformat."',url='+'";
			$scrap[]=  "<br>Query:".$query;

			if($product_name=='')
				continue;
			mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
			
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
			
			
		}
		
	}
	if($res["source"]=="tottus")
	{
		$link=url_path_encode($res["link"]);
		$links=explode('salePricesPE__listPricesPE.tottusPe',$link);
		$link="http://www.tottus.com.pe".$links[0]."salePricesPE__listPricesPE.tottusPe";
		
		echo $link."\r\n\r\n";
		
		//echo "/usr/local/bin/phantomjs /var/www/html/scraper/fetch_page_2.js ".$link." ".$file_name."";
		exec("/usr/local/bin/phantomjs /var/www/html/scraper/fetch_page_2.js ".$link." ".$file_name."",$o,$e);
		$e=0;
		if($e==0)
		{
			include_once('simple_html_dom.php');
			
			$html = file_get_html('/var/www/html/scraper/'.$file_name); 
			//$html = file_get_html($link); 
			$servername = "localhost";
			$username = "root";
			$password = "news5cs3";
			$i=0;
			$j=0;
			$product_name='';
			$product_description='';
			$unit='';
			$brand_name='';
			$price=0;
			$offer_price=0;
			$offer_title='';
			$offer_conditions='';
			$date='';
			$dateformat='0000-00-00';


			//The name of the directory that we need to create.
			$directoryName = 'product_images';

			//Check if the directory already exists.
			if(!is_dir($directoryName)){
				//Directory does not exist, so lets create it.
				mkdir($directoryName, 0777);
			}

			
			//find image url
			foreach($html->find('.caption-img img') as $ele) 
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
			foreach($html->find('.caption-description .title') as $element) 
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
				$scrap[]= 'active price: '.$price.'<br>';
				$scrap[]= 'unit: '.$unit.'<br>';
			}
			
			foreach($html->find('.nule-price') as $element) {
				$offer_price=$price;
				$price=$element->innertext;
				$scrap[]= 'price: '.$price.'<br>';
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
						$offer_conditions=mysqli_real_escape_string($mysqli,$ele->innertext);				        
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
			
			/*convert price and offer price to float*/
			$price=str_replace(",",".",$price);
			preg_match('/[+-]?\d+([,.]\d+)?/', $price, $price);
			$price=$price[0];
			$offer_price=str_replace(",",".",$offer_price);
			preg_match('/[+-]?\d+([,.]\d+)?/', $offer_price, $offer_price);
			$offer_price=$offer_price[0];
			
			$source="tottus";
			$query="insert into product(product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source,valid_until,url) values(";

			$query=$query."'".mysqli_real_escape_string($mysqli,$product_name)."','".mysqli_real_escape_string($mysqli,$product_description)."','".mysqli_real_escape_string($mysqli,$brand_name)."','".mysqli_real_escape_string($mysqli,$unit)."','".mysqli_real_escape_string($mysqli,$price)."','".mysqli_real_escape_string($mysqli,$offer_price)."','".mysqli_real_escape_string($mysqli,$offer_title)."','".mysqli_real_escape_string($mysqli,$offer_conditions)."','".mysqli_real_escape_string($mysqli,$source)."','".$dateformat."','-') ON DUPLICATE KEY UPDATE 
`product_desc`='".mysqli_real_escape_string($mysqli,$product_description)."',brand_name='".mysqli_real_escape_string($mysqli,$brand_name)."',unit='".mysqli_real_escape_string($mysqli,$unit)."',price='".mysqli_real_escape_string($mysqli,$price)."',offer_price='".mysqli_real_escape_string($mysqli,$offer_price)."',offer_title='".mysqli_real_escape_string($mysqli,$offer_title)."',offer_conditions='".mysqli_real_escape_string($mysqli,$offer_conditions)."',source='".mysqli_real_escape_string($mysqli,$source)."',valid_until='".$dateformat."',url='+'";
			/*echo '<br>'. $query;*/
			if($product_name=='')
				continue;
			mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
			
			if(mysqli_insert_id($mysqli)!=0)
			{
			$img_file='/var/www/html/scraper/product_images/'.mysqli_insert_id($mysqli).".jpg";
			}
			else
			{
				$sql6="SELECT product_id FROM product WHERE product_name='".mysqli_real_escape_string($mysqli,$product_name)."'
				 && brand_name='".mysqli_real_escape_string($mysqli,$brand_name)."' 
				 && product_desc='".mysqli_real_escape_string($mysqli,$product_description)."' 
				 && unit='".mysqli_real_escape_string($mysqli,$unit)."' 
				 && source='tottus'
				";
				$rs5=mysqli_query($mysqli,$sql6) or die(mysqli_error($mysqli));
				$res13=mysqli_fetch_array($rs5);
				$img_file='/var/www/html/scraper/product_images/'.$res13["product_id"].".jpg";
			}
			if(file_exists($img_file))
			{
				if(unlink($img_file))
					grab_image($image_src,$img_file);
			}
			else
			grab_image($image_src,$img_file);
			
			}

	}
	$coun++;
}

if(file_exists('/var/www/html/scraper/'.$file_name))
{
	unlink('/var/www/html/scraper/'.$file_name);
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

function url_path_encode($url) {
    $path = parse_url($url, PHP_URL_PATH);
    if (strpos($path,'%') !== false) return $url; //avoid double encoding
    else {
        $encoded_path = array_map('urlencode', explode('/', $path));
        return str_replace($path, implode('/', $encoded_path), $url);
    }   
}

?>