<?php
$p=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
if($p==='/admin'||$p==='/admin/')require __DIR__.'/admin.php';else require __DIR__.'/index.php';