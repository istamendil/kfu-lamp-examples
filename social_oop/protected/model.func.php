<?php

function get_user_rating($userId){
  $ratings = array(
      5 => 143,
  );
  return empty($ratings[$userId]) ? 0 : (int)$ratings[$userId];
}

