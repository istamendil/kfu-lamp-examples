<?php

function dump($var){
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
}

function dumpe($var = NULL){
  dump($var);
  exit();
}