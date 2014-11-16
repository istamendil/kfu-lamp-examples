<?php

function get_user_rating($userId){
  $ratings = array(
      5 => 143,
  );
  return empty($ratings[$userId]) ? NULL : (int)$ratings[$userId];
}

