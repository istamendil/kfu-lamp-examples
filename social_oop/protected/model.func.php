<?php

function get_user_info($userId){
  $user = NULL;
  // Open file onyl for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/users', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        if($id === $userId){
          // We found our user
          $data = explode(' ', trim($line));
          $user = array(
              'id'           => $id,
              'email'        => $data[0],
              'realName'     => $data[2],
              'sex'          => $data[3],
              'subscribtion' => $data[4],
              'rating'       => $data[5],
          );
          break;
        }
        $id++;
      }
  } else {
      $core = Core::getInstance();
      $core->return500("Can't open user DB.");
  } 
  fclose($handle);
  
  return $user;
}
function add_user($data){
  return file_put_contents(SOCIAL_SYSTEM_PATH.'/data/users', implode(' ', $data)."\n", FILE_APPEND);
}
function find_user($email, $password){
  $user = NULL;
  // Open file onyl for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/users', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        if(strpos($line, $email.' ') === 0){ //line has to start with email. Space must be after it or we can mull itis@ex.com and itis@ex.com.ua
          // We found our user
          $data = explode(' ', $line);
          // Check password
          if(md5($password) === $data[1]){
            $user = array(
                'id'           => $id,
                'email'        => $data[0],
                'realName'     => $data[2],
                'sex'          => $data[3],
                'subscribtion' => $data[4],
                'rating'       => $data[5],
            );
          }
          break;
        }
        $id++;
      }
  } else {
      $core = Core::getInstance();
      $core->return500("Can't open user DB.");
  } 
  fclose($handle);
  
  return $user;
}