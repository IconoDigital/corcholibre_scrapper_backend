<?php
//LIbrary File
//All out of MVC scope functions lies here
 $error=0;
 $error_msg;
include("DatabaseConnection.php");

date_default_timezone_set('Europe/London');

function get_column_from_table_where_value($column,$table,$where,$value)
{
	global $mysqli;
	$sql="SELECT $column FROM $table WHERE $where='".$value."'";
	$rs=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
	$res=mysqli_fetch_array($rs);
	return $res[$column];
}

function generate_square_image($target_path){
list($width, $height, $type, $attr) = getimagesize($target_path);
$extension=image_type_to_extension($type);
if($width!=$height)
{
if($extension==".jpg" || $extension==".jpeg")
$img=imagecreatefromjpeg($target_path);
if($extension==".png")
$img=imagecreatefrompng($target_path);
if($width>$height)
{
	$thumb = imagecreatetruecolor($height, $height);
	$half_diff=ceil(($width-$height)/2);
	$c_start_x=$half_diff;
	$c_start_y=0;
	imagecopyresized (  $thumb ,  $img , 0 , 0 , $c_start_x , $c_start_y ,  $height ,  $height ,  $height ,  $height );
	if($extension==".jpg" || $extension==".jpeg")
	imagejpeg($thumb,$target_path);
	if($extension==".png")
	imagepng($thumb,$target_path);
}
else
{
	$thumb = imagecreatetruecolor($width, $width);
	$half_diff=ceil(($height-$width)/2);
	$c_start_y=$half_diff;
	$c_start_x=0;
	imagecopyresized (  $thumb ,  $img , 0 , 0 , $c_start_x , $c_start_y ,  $width ,  $width ,  $width ,  $width );
	if($extension==".jpg" || $extension==".jpeg")
	imagejpeg($thumb,$target_path);
	if($extension==".png")
	imagepng($thumb,$target_path);
}
}
}

function die_u($message)
{
	global $error;
	global $error_msg;
	$error=1;
	$error_msg=$message;
}

function name_split($name)
//return the prefix, first name, last name etc in array
/*	First Last Jr.
	Array
	(
	[0] => First Last Jr.
	[1] =>
	[2] => First
	[3] => Last
	[4] => Jr.
	)*/
{
	$name_results = array();
	preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $name, $results);
	return($name_results);
}

function get_table_row_count_l($table)
{
$sql="SELECT count(*) as cnt FROM `".$table."`;";
$rs=mysql_query($sql) or die(mysql_error());
$res=mysql_fetch_array($rs);
return ($res['cnt']);
}

function get_users_name($user_id)
//retrieves users name from user id
{
	$sql="SELECT name from user_details WHERE user_id='".$user_id."'";
	$rs=mysql_query($sql) or ShowErrorPopup(mysql_error().'@ get_users_name library');
	$res=mysql_fetch_array($rs);
	$name=$res['name'];
	return ($name);
}

function get_users_email($user_id)
{

	$sql="SELECT e_mail from user_details WHERE user_id='".$user_id."'";

	$rs=mysql_query($sql) or ShowErrorPopup(mysql_error().'@ get_users_name library');

	$res=mysql_fetch_array($rs);

	$email=$res['e_mail'];

	return ($email);

}

function random_string_l($length = 10)
//generates a random string 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function random_upper_case_string_l($length = 10)
//generates a random string 
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function random_number_l($length = 10)
//generates a random string 
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function send_mail_l($to,$from,$subject,$msg,$reply_to)
{
	
	$headers = "From: ".$from."\r\nReply-To: ".$reply_to."";

$headers = 'From: '.$from.'' . "\r\n" .
    'Reply-To: '.$reply_to.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	if(mail($to,$subject,$msg,$headers))
	return 1;
	else
	return 0;
}


function get_date_time_from_timestamp_l($timestamp)
{//converts a timestamp into readable date time
	return date('F j, Y, g:i a', strtotime($timestamp));
}
function get_date_from_timestamp_l($timestamp){return date('F j, Y', strtotime($timestamp));}


function is_dir_empty_l($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}

function get_dir_size_l($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function remove_dir_l($dir) { 
/*with files in it*/
  foreach(glob($dir . '/*') as $file) { 
    if(is_dir($file)) rrmdir($file); else unlink($file); 
  } rmdir($dir); 
}

function get_category_id_l($name)//@param name of the category
// Retrieves category id of a specific category
{
	$sql="Select category_id from product_category where name='".$name."'";
	$result=mysql_query($sql) or ShowErrorPopUP(DB_ERROR.mysql_error());
	$id=mysql_fetch_array($result);
	return($id['category_id']);
}

function get_category_name_l($id)//@param id of the category
// Retrieves category name of a specific category
{
	$sql="Select name from product_category where category_id='".$id."'";
	$result=mysql_query($sql) or ShowErrorPopUP(DB_ERROR.mysql_error());
	$name=mysql_fetch_array($result);
	return($name['name']);
}



function ShowError($msg)
{
	die($msg."  @ShowError");
}
function ShowErrorPopup($msg)
{
	echo '<script type="text/javascript">alert("'.$msg.'");</script>';
	die();
}


function get_categories_l()
{
	$sql="SELECT name FROM product_category WHERE status=1";
	$res=mysql_query($sql) or ShowErrorPopup(DBERROR.'@get_categories_l');
	return($res);
}

function get_count($sql_part,$sql)//@param part of the sql to be concated or count 
//Retrieves row counts of a session stored sql or retrieves row count of a concated sql

{
	
	if($sql_part!='count')	
	echo $sql=$sql.$sql_part;
	$ret_val=mysql_num_rows(mysql_query($sql)) or ShowErrorPopup(mysql_error().'@get_count');
	return($ret_val);
}

function visitor_country()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryCode != null)
    {
        $result = $ip_data->geoplugin_countryCode;
    }

    return $result;
	
}


function get_canvas_price($canvas_type,$size,$material=null){
	//echo ("type ".$canvas_type.' size '.$size);
	$price_sql="select price from canvas_pricing where canvas_type='".$canvas_type."' and size='".$size."'";
	//echo $price_sql;
	$rs = mysql_query($price_sql);
	$price=1500;
	if(mysql_numrows($rs)<1){
		return(1500);
	}
	else{
		$row=mysql_fetch_array($rs);
		if($canvas_type=="Single Canvas"){
			$price=explode('|',$row['price']);
			if($material=="canvas"){
				
				return($price[0]);
			}
			if($material=="kappa"||$material=="kapa"){	//ya typo compensation 'kapa', lel
				return($price[1]);
			}
			if($material=="paper"){
				return($price[2]);
			}
		}
		return($row['price']);
	}
}
function get_frame_price($type,$size){
	//echo ("type ".$type.' size '.$size);
	$frm_sql="select price from frame_pricing where frame_type='".$type."' and canvas_size='".$size."'";
	$rs=mysql_query($frm_sql);
	$row=mysql_fetch_array($rs);
	return($row['price']);
}

function get_lamination_price($material,$size){
	if($material=="kapa"){
		$material="kappa";
	}
	else{
		$material="paper";
	}
	$lam_sql="select price from lamination_prices where material='".$material."' and size='".$size."'";
	$rs=mysql_query($lam_sql);
	$row=mysql_fetch_array($rs);
	//echo $lam_sql;
	//echo $row['price'];
	return($row['price']);
}

function get_total_cart_price($user_id){
	$order_ids=get_all_order_ids($user_id);
	$cart_price=0;
	foreach($order_ids as $sale_id){
		$price_sql="select * from invoice where sale_id='".$sale_id."'";
	$rs=mysql_query($price_sql);
	$row=mysql_fetch_array($rs);
	$price=($row['printing_price']+($row['frame_price']*$row['frame_check'])+($row['lamination_price']*$row['lamination_check']))*$row['quantity'];
	$cart_price+=$price;
		
	}
	return($cart_price);
}
function get_all_order_ids($user_id){
	$ordrid_sql="select c.order_id,o.created from cart c, orders o where c.user_id='".$user_id."' and c.order_id=o.sale_id ORDER BY o.created DESC";
	$rs=mysql_query($ordrid_sql);
	$ordrid_arr=array();
	while($row=mysql_fetch_array($rs)){
		$ordrid_arr[]=$row['order_id'];
	}
	return($ordrid_arr);
}
function get_all_print_price($user_id){
	$order_ids=get_all_order_ids($user_id);
	$cnv_price=0;
	foreach($order_ids as $sale_id){
		$print_sql="select printing_price from invoice where sale_id='".$sale_id."'";
		$rs=mysql_query($print_sql);
		$row=mysql_fetch_array($rs);
		$cnv_price+=$row['printing_price'];
		
	}
	return($cnv_price);
}
function get_all_lamination_price($user_id){
	$order_ids=get_all_order_ids($user_id);
	$lam_price=0;
	foreach($order_ids as $sale_id){
		$print_sql="select lamination_price from invoice where sale_id='".$sale_id."'";
		$rs=mysql_query($print_sql);
		$row=mysql_fetch_array($rs);
		$lam_price+=$row['lamination_price'];
	}
	return($lam_price);
}
function get_all_frame_price($user_id){
	$order_ids=get_all_order_ids($user_id);
	$frm_price=0;
	foreach($order_ids as $sale_id){
		$print_sql="select frame_price from invoice where sale_id='".$sale_id."'";
		$rs=mysql_query($print_sql);
		$row=mysql_fetch_array($rs);
		$frm_price+=$row['frame_price'];
	}
	return($frm_price);
}
function get_order_price($sale_id){
	$price_sql="select * from invoice where sale_id='".$sale_id."'";
	$rs=mysql_query($price_sql);
	$row=mysql_fetch_array($rs);
	$price=($row['printing_price']+($row['frame_price']*$row['frame_check'])+($row['lamination_price']*$row['lamination_check']))*$row['quantity'];
	return($price);
}

?>