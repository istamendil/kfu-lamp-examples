<?php

function makeDigitalString($number){

    $numbers=array(
                    array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
                    array('одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать',
                                                                    'семнадцать', 'восемнадцать', 'девятнадцать'),
                    array('', 'десять', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто')
                );
    $number=(int)$number;
    $result='';
    //invalid number range
    if($number<1||$number>99){
        trigger_error('Can\'t format to digits '.$number.' course of out of range.', E_USER_NOTICE);
    }
    //second type of numbers
    elseif($number>10&&$number<20){
        $result=$numbers[1][($number%10)-1];
    }
    //normal number
    else{
        $subtens=$number%10;
        $tens=($number-$subtens)/10;
        $result=$numbers[2][$tens];
        $result.=$numbers[0][$subtens];
    }
    return $result;
}
function showCaptcha($config){
    //vars
    $background='';
    $backgrounds=array();
    $colors=array();
    foreach($config['colors'] as $color){
        $colors[]=explode('-', $color);
    }
    $fonts=array();
    $font='';
    $string='';

    //getting random background
    if(!file_exists($config['backgroundsPath'])){
        trigger_error('Path with background images doesn\'t exist: '.$config['backgroundsPath'], E_USER_ERROR);
    }
    $backgroundsDir = dir($config['backgroundsPath']);
    while ($entry = $backgroundsDir->read()) {
        if ($entry=='.'||$entry=='..') {
            continue;
        }
        $entry=$config['backgroundsPath'].'/'.$entry;
        if(pathinfo($entry, PATHINFO_EXTENSION)!='jpg'&&pathinfo($entry, PATHINFO_EXTENSION)!='jpeg') continue;
        $backgrounds[]=$entry;
    }
    if(empty($backgrounds)){
        trigger_error('Can\'t find any jpeg-image on '.$config['backgroundsPath'], E_USER_ERROR);
    }
    $background=$backgrounds[mt_rand(0, count($backgrounds)-1)];
    $img=imagecreatefromjpeg($background);

    //getting random font
    if(!file_exists($config['fontsPath'])){
        trigger_error('Path with fonts doesn\'t exist: '.$config['fontsPath'], E_USER_ERROR);
    }
    $fontsDir = dir($config['fontsPath']);
    while ($entry = $fontsDir->read()) {
        if ($entry=='.'||$entry=='..') {
            continue;
        }
        $entry=$config['fontsPath'].'/'.$entry;
        if(pathinfo($entry, PATHINFO_EXTENSION)!='ttf') continue;
        $fonts[]=$entry;
    }
    if(empty($fonts)){
        trigger_error('Can\'t find any ttf-font on '.$config['fontsPath'], E_USER_ERROR);
    }
    $font=$fonts[mt_rand(0, count($fonts)-1)];

    //first number
    $color=$colors[mt_rand(0, count($colors)-1)];
    $colorUsed=imagecolorallocate($img, $color[0], $color[1], $color[2]);
    $firstNumber=mt_rand(1, 90);
    $string=makedigitalstring($firstNumber);
    imagettftext($img,   $config['fontSize']+(mt_rand(-1, +1)), mt_rand(-6,+2), 10+mt_rand(0, 20), 30+mt_rand(-5, 5), $colorUsed, $font, $string);

    //plus
    $color='00-00-00';
    $colorUsed=imagecolorallocate($img, (float)$color[0], (float)$color[1], (float)$color[2]);
    if(mt_rand(0,1)){
       $string='плюс';
    }
    else{
        $string='+';
    }
    imagettftext  ($img,   $config['fontSize']+(mt_rand(-1, +2)), mt_rand(-2,+2), 70+mt_rand(-10, 30), 50+mt_rand(0, 20), $colorUsed, $font, $string);

    //second number
    $color=$colors[mt_rand(0, count($colors)-1)];
    $colorUsed=imagecolorallocate($img, $color[0], $color[1], $color[2]);
    $secondNumber=mt_rand(1, 9);
    $string=makedigitalstring($secondNumber);
    imagettftext  ($img,   $config['fontSize']+(mt_rand(-1, +3)), mt_rand(-3,+1), 40+mt_rand(-30, 100), 90+mt_rand(-10, 10), $colorUsed, $font, $string);

    $_SESSION['captcha_keystring_summ']=$firstNumber+$secondNumber;
    header('Content-type:image/png');
    ImagePNG($img);
    ImageDestroy($img);
    return $firstNumber+$secondNumber;
}

session_start();

$config=array(
    'backgroundsPath'=>'./backgrounds',
    'colors'=>array('255-00-00','00-155-00','200-50-00','50-200-00','50-200-50'),
    'fontsPath'=>'./fonts',
    'fontSize'=>18
);
showCaptcha($config);