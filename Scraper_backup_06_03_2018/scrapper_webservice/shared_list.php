<?php
error_reporting(E_ALL);
include_once('connection.php');
require_once 'phpmailer/PHPMailerAutoload.php';
header('Content-type: application/json');




		function send_notification($message_arr,$firebase_token){
		
			
			$url = 'https://fcm.googleapis.com/fcm/send';
		
 
        $headers = array(
            'Authorization: key=AAAAK9eenP8:APA91bGYHPY9M8vaZKyEduPe40mmdFk5tgiKUoapCi7NXdz9QBm-LhikHM_KjmOfGdB54o1JGpo-ikvPjpKqvHiJBdZi91E8Qr9PTdPlI5qlsQd0DsRVIwYsE4FPM3o3oGg0yCpSVZJJ',
            'Content-Type: application/json'
        );
		
		$notification_arr['body']=$message_arr['message'];
		$notification_arr['title']=$message_arr['title'];
		$notification_arr['click_action']="SPLASH";
		
			$fields = array(
            'to' => $firebase_token,
			'notification' => $notification_arr,
            'data' => $message_arr,
			'content_available'=>true,
			'priority'=>'high'
        );
			// Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		//echo json_encode($fields);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        //echo $result;
		//echo "##################### <br>";
		//print_r ($reg_id_arr);
		
			
		return $fields;
			
		}

//decode json data
$json_data=$_REQUEST["JSON_data"];
$obj=json_decode($json_data);
//var_dump($obj);

//set json data into local variables
$email=$obj->email;

$list_name=$obj->list_name;
$sender_name=$obj->sender_name;
$sender_email=$obj->sender_email;
//$share_list_json_arr=$obj->share_list_json_arr;

$share_obj=$obj->share_list_json_arr;
//var_dump($share_obj);
if( !empty($email) && !empty($list_name) && !empty($share_obj) ){
		
			
			//send mail 
			$sql1 = "select email,firebase_token from users where email='".$email."'";
			if($qur1 = mysqli_query($conn,$sql1)or die(mysqli_error($conn)))
		    {
				
				$key=mysqli_fetch_array($qur1);
				if($key[0]!="")
				{
					
					
					/*
					*	Send notification to user
					*/
					$notification_msg_arr=Array();
					$notification_msg_arr['message']=$sender_name." (".$sender_email.") has shared a new list with you";
					$notification_msg_arr['list_name']=$list_name;
					$notification_msg_arr['sender_email']=$sender_email;
					$notification_msg_arr['sender_name']=$sender_name;
					$notification_msg_arr['title']="New Shared List :".$list_name;
					$notification_msg_arr['product_id_list']=json_encode($share_obj);
					$sent_notification_json=send_notification($notification_msg_arr,$key['firebase_token']);
					
					
					
					
					$message = '<html><head>
					   <title>Shared List</title>
					    <style type="text/css">
                          body{margin:0;padding:0;}table{width: 100%;max-width: 100%;margin:0;padding:5px;margin-bottom: 1rem;border-collapse: collapse;background-color: transparent;font-family:Tahoma, Geneva, sans-serif;font-weight:300;font-size:13px;text-align: left;}table tr{padding: .75rem;vertical-align: top;border-top: 1px solid #eceeef;}table th{background:#F30;color:#FFF;}table th,table td{vertical-align: bottom;border-bottom: 1px solid #eceeef;padding: .75rem;}@media only screen and (max-width: 800px) {#no-more-tables table,#no-more-tables thead,#no-more-tables tbody,#no-more-tables th,#no-more-tables td,#no-more-tables tr {display: block;}#no-more-tables thead tr {position: absolute;top: -9999px;left: -9999px;}#no-more-tables tr { border: 1px solid #ccc; }#no-more-tables td {border: none;border-bottom: 1px solid #eee;position: relative;padding-left: 50%;white-space: normal;text-align:left;}#no-more-tables td:before {position: absolute;top: 6px;left: 6px;width: 45%;padding-right: 10px;white-space: nowrap;text-align:left;font-weight: bold;}#no-more-tables td:before { content: attr(data-title);}}
                        </style>
					   </head>
					   <body> <div id="no-more-tables">';
					$message .= '<h3>'.$sender_name.' ('.$sender_email.') Shared List : '.$list_name.' </h3>';
					$message .= '<table> <thead> <tr><th>PRODUCT NAME</th><th>PRODUCT DESC</th><th>BRAND NAME</th><th>UNIT</th><th>PRICE</th><th>OFFER PRICE</th><th>OFFER TITLE</th><th>OFFER CONDITIONS</th><th>SOURCE</th><th>PRODUCT IMAGE</th><th>QUANTITY</th></tr></thead> ';
					$message .= '<tbody> ';
					$arr_length = count($share_obj); 
                    for($i=0;$i<$arr_length;$i++) 
                    { 
				        $sql = "select product_id,product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source FROM product where product_id=".$share_obj[$i]->product_id;
			            if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		                {			
				         $res=mysqli_fetch_array($qur);
						 $link="";
						/* if($res["source"]=="tottus") 
							 $link="http://54.86.9.69/scraper/product_images/".$res["product_id"].".jpg";
						 else
							 $link="http://54.86.9.69/scraper/product_images/".$res["product_id"].".jpg";*/
						 
						 $message .=	'<tr> <td data-title="PRODUCT NAME" >'.$res[1] .'</td><td data-title="PRODUCT DESC">'.$res[2] .'</td><td data-title="BRAND NAME">'.$res[3] .'</td><td data-title="UNIT">'.$res[4] .'</td><td data-title="PRICE">'.$res[5] .'</td><td data-title="OFFER PRICE">'.$res[6] .'</td><td data-title="OFFER TITLE">'.$res[7] .'</td><td data-title="OFFER CONDITIONS">'.$res[8] .'</td><td data-title="SOURCE">'.$res[9] .'</td><td data-title="PRODUCT IMAGE"> <img src="'.$link .'" width="75"></td><td data-title="QUANTITY"> '.$share_obj[$i]->quantity.'</td> </tr> ';											 
						}
						
					}
					$message .='</tbody> ';
					$message .= '</table> </div>';
					$message .= "</body></html>";
					 
					$mail = new PHPMailer();
					$mail->From      = "admin@codomotive.com";
					$mail->FromName  = "Shoplist App";
					$mail->Subject   = "[Shared List]".$list_name." By ".$sender_name.' ('.$sender_email.') ';
					$mail->Body      = $message;
					$mail->AddAddress( $email );
					/*$email->addBCC('joyeshk@gmail.com');*/
					$mail->IsHTML(true);
					if($mail->Send()) {
						//echo "mail sent";
					}
					$json = array("status" => 0, "msg" => "Shared with user","notification_json"=>$sent_notification_json);
				}
				else
				{
					$message = '<html><head>
					   <title>Shared List</title>
					    <style type="text/css">
                          body{margin:0;padding:0;}table{width: 100%;max-width: 100%;margin:0;padding:5px;margin-bottom: 1rem;border-collapse: collapse;background-color: transparent;font-family:Tahoma, Geneva, sans-serif;font-weight:300;font-size:13px;text-align: left;}table tr{padding: .75rem;vertical-align: top;border-top: 1px solid #eceeef;}table th{background:#F30;color:#FFF;}table th,table td{vertical-align: bottom;border-bottom: 1px solid #eceeef;padding: .75rem;}@media only screen and (max-width: 800px) {#no-more-tables table,#no-more-tables thead,#no-more-tables tbody,#no-more-tables th,#no-more-tables td,#no-more-tables tr {display: block;}#no-more-tables thead tr {position: absolute;top: -9999px;left: -9999px;}#no-more-tables tr { border: 1px solid #ccc; }#no-more-tables td {border: none;border-bottom: 1px solid #eee;position: relative;padding-left: 50%;white-space: normal;text-align:left;}#no-more-tables td:before {position: absolute;top: 6px;left: 6px;width: 45%;padding-right: 10px;white-space: nowrap;text-align:left;font-weight: bold;}#no-more-tables td:before { content: attr(data-title);}}
                        </style>
					   </head>
					   <body> <div id="no-more-tables">';
					$message .= '<h3>'.$sender_name.' ('.$sender_email.') Shared List : '.$list_name.' </h3>';
					$message .= '<table> <thead> <tr><th>PRODUCT NAME</th><th>PRODUCT DESC</th><th>BRAND NAME</th><th>UNIT</th><th>PRICE</th><th>OFFER PRICE</th><th>OFFER TITLE</th><th>OFFER CONDITIONS</th><th>SOURCE</th><th>PRODUCT IMAGE</th><th>QUANTITY</th></tr></thead> ';
					$message .= '<tbody> ';
					$arr_length = count($share_obj); 
                    for($i=0;$i<$arr_length;$i++) 
                    { 
				        $sql = "select product_id,product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,source FROM product where product_id=".$share_obj[$i]->product_id;
						
			            if($qur = mysqli_query($conn,$sql)or die(mysqli_error($conn)))
		                {			
				         $res=mysqli_fetch_array($qur);
						 $link="";
						 /*if($res["source"]=="tottus") 
							 $link="http://54.86.9.69/scraper/product_images/".$res["product_id"].".jpg";
						 else
							 $link="http://54.86.9.69/scraper/product_images/".$res["product_id"].".jpg";*/
						 
						 $message .=	'<tr> <td data-title="PRODUCT NAME" >'.$res[1] .'</td><td data-title="PRODUCT DESC">'.$res[2] .'</td><td data-title="BRAND NAME">'.$res[3] .'</td><td data-title="UNIT">'.$res[4] .'</td><td data-title="PRICE">'.$res[5] .'</td><td data-title="OFFER PRICE">'.$res[6] .'</td><td data-title="OFFER TITLE">'.$res[7] .'</td><td data-title="OFFER CONDITIONS">'.$res[8] .'</td><td data-title="SOURCE">'.$res[9] .'</td><td data-title="PRODUCT IMAGE"> <img src="'.$link .'" width="75"></td><td data-title="QUANTITY"> '.$share_obj[$i]->quantity.'</td> </tr> ';											 
						}
						
					}
					$message .='</tbody> ';
					$message .= '</table> </div>';
					$message .= "</body></html>";
					 
					$mail = new PHPMailer();
					$mail->From      = "admin@codomotive.com";
					$mail->FromName  = "Shoplist App";
					$mail->Subject   = "[Shared List]".$list_name." By ".$sender_name.' ('.$sender_email.') ';
					$mail->Body      = $message;
					$mail->AddAddress( $email );
					/*$email->addBCC('joyeshk@gmail.com');*/
					$mail->IsHTML(true);
					if($mail->Send()) {
						//echo "mail sent";
					}
					$json = array("status" => 0, "msg" => "Shared with user");
				}			
			 }
			 	
		  else{
			 $json = array("status" => 1, "msg" => "'".mysqli_error($conn)."'");
		  }	 				
		
     }
	 else{
	   $json = array("status" => 1, "msg" => "all fields must be filled with values");
	 }


   mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	
?>