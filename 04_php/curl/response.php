<?php

echo '<pre>';

setcookie('test', date('r'), time()+3600, '/');

echo '<h3>REQUEST HEADERS</h3>';
var_dump(getallheaders());

echo '<h3>GET</h3>';
var_dump($_GET);

echo '<h3>POST</h3>';
var_dump($_POST);

echo '<h3>COOKIE</h3>';
var_dump($_COOKIE);

echo '<h3>SERVER</h3>';
var_dump($_SERVER);