<?php 
//crontest
$date = date('Y/m/d h:i:s a', time());
file_put_contents("/var/www/html/scraper/last_cron.txt",$date);
?>