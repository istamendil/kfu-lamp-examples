<?php

require 'config.php';

$ch = curl_init($url);
curl_exec($ch);
curl_close($ch);

