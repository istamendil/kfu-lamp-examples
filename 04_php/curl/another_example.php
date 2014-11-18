<?php

require 'config.php';

//send some get data
$url .= '?some_get=some_get_value';

//init cURL request
$ch = curl_init($url);

//options
//return response - don't output it!
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//return headers too
curl_setopt($ch, CURLOPT_HEADER, true);
//look as nowdays browser
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:33.0) Gecko/20100101 Firefox/33.0');

//sva e and restore cookies
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);

//send some post data
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'some_post' => 'some_post_value',
));

//custom headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-LAMP: True'
));

//Follow redirects
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

//make request
var_dump(curl_exec($ch));
//get errors
var_dump(curl_error($ch));

//clean resources
curl_close($ch);