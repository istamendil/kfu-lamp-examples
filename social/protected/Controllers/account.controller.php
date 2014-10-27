<?php

function controller_account_index(){
  
}

function controller_account_show($userId){
  $params = array(
      'userId' => $userId,
      'userRating' => get_user_rating($userId),
  );
  return $params;
}