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
          $user = decrypt_user_row($line);
          $user['id'] = $id;
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
function encrypt_user_row($data){
  foreach($data as $name => $val){
    $val = str_replace(' ', '', $val);
    $data[$name] = $val;
  }
  
  $line  = '';
  $line .= $data['email']                 .' ';
  $line .= $data['password']              .' ';
  $line .= $data['realName']              .' ';
  $line .= $data['sex']                   .' ';
  $line .= ($data['subscription']?'1':'0').' ';
  $line .= $data['rating']                .' ';
  return $line;
}
function decrypt_user_row($row){
  $row = trim($row);
  $data = explode(' ', $row);
  foreach($data as $name => $val){
    $val = trim($val);
    $data[$name] = $val;
  }
  return array(
            'email'        => $data[0],
            'password'     => $data[1],
            'realName'     => $data[2],
            'sex'          => $data[3],
            'subscribtion' => (boolean)$data[4],
            'rating'       => (int)$data[5],
      );
}
function add_user($data){
  if(find_user($data['email'])){
    return 'There is user with such email already.';
  }
  else{
    if(!file_put_contents(SOCIAL_SYSTEM_PATH.'/data/users', encrypt_user_row($data)."\n", FILE_APPEND)){
      return "Can't write to DB.";
    }
    else{
      return TRUE;
    }
  }
}
function find_user($email, $password = ''){
  $user = NULL;
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/users', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        if(strpos($line, $email.' ') === 0){ //line has to start with email. Space must be after it or we can mull itis@ex.com and itis@ex.com.ua
          // We found our user
          $data = decrypt_user_row($line);
          $data['id'] = $id;
          // Check password or just get user data
          if(strlen($password) === 0 || md5($password) === $data['password']){
            $user = $data;
          }
          break;
        }
        $id++;
      }
  } else {
    trigger_error("Can't open user DB.", E_USER_ERROR);
  } 
  fclose($handle);
  
  return $user;
}
function get_all_users(){
  $users = array();
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/users', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        // One more user
        $data = decrypt_user_row($line);
        $data['id'] = $id;
        $users[] = $data;
        $id++;
      }
  } else {
    trigger_error("Can't open user DB.", E_USER_ERROR);
  } 
  fclose($handle);
  
  return $users;
}

function encrypt_picture_row($data){
  foreach($data as $name => $val){
    $val = str_replace(' ', '', $val);
    $data[$name] = $val;
  }
  
  $line  = '';
  $line .= $data['userId'].' ';
  $line .= $data['path']  .' ';
  $line .= $data['name']  .' ';
  $line .= $data['rating'].' ';
  return $line;
}
function decrypt_picture_row($row){
  $row = trim($row);
  $data = explode(' ', $row);
  foreach($data as $name => $val){
    $val = trim($val);
    $data[$name] = $val;
  }
  return array(
            'userId'     => (int)$data[0],
            'path'       => $data[1],
            'small_path' => 'mini_'.$data[1],
            'name'       => $data[2],
            'rating'     => (int)$data[3],
      );
}
function add_picture($userId, $photoPath, $photoRealName = 'Photo'){
  $data = array(
      'userId'    => $userId,
      'path'      => $photoPath,
      'name'      => $photoRealName,
      'rating'    => 1
  );
  if(!file_put_contents(SOCIAL_SYSTEM_PATH.'/data/pictures', encrypt_picture_row($data)."\n", FILE_APPEND)){
    return "Can't write to DB.";
  }
  else{
    return TRUE;
  }
}
function get_user_pictures($userId){
  $userId = (int) $userId;
  $pictures = array();
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/pictures', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        if(strpos($line, $userId.' ') === 0){ //line has to start with email. Space must be after it or we can mull itis@ex.com and itis@ex.com.ua
          // We found one picture
          $data = decrypt_picture_row($line);
          $data['id'] = $id;
          $pictures[] = $data;
        }
        $id++;
      }
  } else {
    trigger_error("Can't open pictures DB.", E_USER_ERROR);
  } 
  fclose($handle);
  usort($pictures, 'sort_pictures_by_rating');
  return $pictures;
}
function sort_pictures_by_rating($x, $y){
  if($x['rating'] > $y['rating']){
    return -1;
  }
  elseif($x['rating'] < $y['rating']){
    return 1;
  }
  else{
    return 0;
  }
}