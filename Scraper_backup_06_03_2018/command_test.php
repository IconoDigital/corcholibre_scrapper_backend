<?php 
//exec("/usr/local/bin/phantomjs fetch_page.js http://www.plazavea.com.pe/lenteja-paisana-fina-seleccion-bolsa-500gr/p",$o,$e);
exec("/usr/local/bin/phantomjs hello.js",$o,$e);
print_r($o);
echo $e;

?>