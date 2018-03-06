<?php


// Database Connection

	 ini_set('display_errors', 1);
   //ini_set('memory_limit', '-1');
   error_reporting(E_ALL);


	 $host_name='localhost';

	 $user_name='root';

	 $pass_word='news5cs3';

	 $database_name='scraper_project';

	 $mysqli = mysqli_connect($host_name, $user_name, $pass_word, $database_name);
	 mysqli_set_charset($mysqli,'utf8' );
/*   
	 $link = mysql_connect($host_name, $user_name, $pass_word);

	 mysql_select_db($database_name,$link); */

	

	



?>