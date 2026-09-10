<?php
$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
if($path==='/admin'||$path==='/admin/') require __DIR__.'/admin.php';
elseif($path==='/'||$path==='/index.php') require __DIR__.'/index.php';
elseif($path==='/style.css'){header('Content-Type:text/css');readfile(__DIR__.'/style.css');}
else{http_response_code(404);echo'Not Found';}