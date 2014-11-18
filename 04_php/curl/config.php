<?php

$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['REQUEST_URI']);
$url  = 'http://'.$host.$path.'/response.php';

echo '<pre>';
