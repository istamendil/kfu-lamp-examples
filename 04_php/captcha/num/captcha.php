<?php
 session_start();
 $img=imagecreatefromjpeg('kp_image.jpg');
 $colors=array('255-00-00','00-155-00','200-50-00','50-200-00','50-200-50');
 $cused=array();
 $string='';
 for($i=0;$i<6;$i++){
   //getting color
   $color=$colors[mt_rand(0,count($colors)-1)];
   $color=explode('-',$color);
   $cused[$i]=imagecolorallocate($img,$color[0],$color[1],$color[2]);
   //making string
   $char=mt_rand(1,9);
   $string.=$char;
   //painting string
   imagettftext($img,18,mt_rand(-15,15),15+$i*17,mt_rand(30,40),$cused[$i],'./comic.ttf',$char);
 }
 $_SESSION['captcha_keystring_num']=$string;
 header('Content-type:image/gif');
 imagepng($img);
 imagedestroy($img);