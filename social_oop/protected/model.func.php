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
    $val = str_replace('"', '""""', $val);
    $val = '"'.$val.'"';
    $data[$name] = $val;
  }
  
  $line  = '';
  $line .= $data['email']                 .',';
  $line .= $data['password']              .',';
  $line .= $data['name']                  .',';
  $line .= $data['sex']                   .',';
  $line .= ($data['subscription']?'1':'0').',';
  $line .= $data['rating']                .',';
  return $line;
}
function decrypt_user_row($row){
  $data = str_getcsv($row);
  $data = array_map('trim', $data);
  return array(
            'email'        => $data[0],
            'password'     => $data[1],
            'name'         => $data[2],
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
        if(strpos($line, '"'.$email.'"') === 0){ //line has to start with email. Space must be after it or we can mull itis@ex.com and itis@ex.com.ua
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
function get_all_users($sort = NULL){
  $users = array();
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/users', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        // One more user
        $data = decrypt_user_row($line);
        $data['id'] = $id;
        $users[$id] = $data;
        $id++;
      }
  } else {
    trigger_error("Can't open user DB.", E_USER_ERROR);
  } 
  fclose($handle);
  
  if($sort){
    uasort($users, 'sort_users_by_'.$sort);
  }
  
  return $users;
}
function sort_users_by_name($x, $y){
  if($x['name'] > $y['name']){
    return 1;
  }
  elseif($x['name'] < $y['name']){
    return -1;
  }
  else{
    return 0;
  }
}
function sort_users_by_rating($x, $y){
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

function encrypt_picture_row($data){
  foreach($data as $name => $val){
    $val = str_replace('"', '""""', $val);
    $val = '"'.$val.'"';
    $data[$name] = $val;
  }
  
  $line  = '';
  $line .= $data['userId'].',';
  $line .= $data['path']  .',';
  $line .= $data['name']  .',';
  $line .= $data['rating'].',';
  return $line;
}
function decrypt_picture_row($row){
  $row = trim($row);
  $data = str_getcsv($row);
  $data = array_map('trim', $data);
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
        if(strpos($line, '"'.$userId.'"') === 0){ //line has to start with email. Space must be after it or we can mull 1 and 12
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
  uasort($pictures, 'sort_pictures_by_rating');
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
function encrypt_message_row($data){
  foreach($data as $name => $val){
    $val = htmlspecialchars($val);
    $val = nl2br($val);
    $val = str_replace(array("\n", "\r"), '', $val);
    $val = '"'.$val.'"';
    $data[$name] = $val;
  }
  
  $line  = '';
  $line .= $data['from']     .',';
  $line .= $data['to']       .',';
  $line .= $data['time']     .',';
  $line .= $data['text']     .',';
  return $line;
}
function decrypt_message_row($row){
  $row = trim($row);
  $data = str_getcsv($row);
  $data = array_map('trim', $data);
  return array(
            'from'       => (int)$data[0],
            'to'         => (int)$data[1],
            'time'       => $data[2],
            'text'       => $data[3],
      );
}
function get_user_chats($userId){
  $userId = (int) $userId;
  // Get users list with empty messages array. Delete current user from the list.
  $users = get_all_users('name');
  foreach($users as $id=>$user){
    if($id == $userId){
      unset($users[$id]);
    }
    else{
      $users[$id]['messages'] = 0;
      $users[$id]['last_message'] = '&ndash;';
    }
  }
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/messages', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        $data = decrypt_message_row($line);
        if($data['from'] === $userId || $data['to'] === $userId){
          $curId = ($data['from'] === $userId ? $data['to'] : $data['from']);
          $users[$curId]['messages']++;
          if(strlen($data['text']) >= 50){
            $users[$curId]['last_message'] = substr($data['text'], 47).'...';
          }
          else{
            $users[$curId]['last_message'] = $data['text'];
          }
        }
        $id++;
      }
  } else {
    trigger_error("Can't open pictures DB.", E_USER_ERROR);
  } 
  fclose($handle);
  return $users;
}
function get_chat_messages($from, $to, $minId = 0){
  $from = (int) $from;
  $to = (int) $to;
  // Get users
  $from = get_user_info($from);
  $to = get_user_info($to);
  if(!$from || !$to){
    trigger_error("Can't find user.", E_USER_ERROR);
  }
  $messages = array();
  // Open file only for reading
  $handle = fopen(SOCIAL_SYSTEM_PATH.'/data/messages', "r");
  if ($handle) {
      $id = 0;
      while (($line = fgets($handle)) !== false) {
        $data = decrypt_message_row($line);
        $data['id'] = $id;
        // Get only new messages
        if($id < $minId){
          continue;
        }
        // Add to messages only if this is message from fialog with $from and $to
        if($data['from'] == $from['id'] && $data['to'] == $to['id']){
          $data['from_name'] = $from['name'];
          $data['to_name']   = $to['name'];
          $messages[$id] = $data;
        }
        elseif($data['from'] == $to['id'] && $data['to'] == $from['id']){
          $data['to_name']   = $to['name'];
          $data['from_name'] = $from['name'];
          $messages[$id] = $data;
        }
        $id++;
      }
  } else {
    trigger_error("Can't open pictures DB.", E_USER_ERROR);
  } 
  fclose($handle);
  return $messages;
}

function add_message($from, $to, $message){
  $data = array(
            'from'       => (int)$from,
            'to'         => (int)$to,
            'time'       => time(),
            'text'       => $message,
      );
  if(!file_put_contents(SOCIAL_SYSTEM_PATH.'/data/messages', encrypt_message_row($data)."\n", FILE_APPEND)){
    return "Can't write to DB.";
  }
  else{
    return TRUE;
  }
}