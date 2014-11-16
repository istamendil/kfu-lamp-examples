<?php

//namespace MvcOop;

/**
 *  Account controller for individual pages etc
 */
class AccountController extends Controller {
  
  public function indexAccount(){}

  public function showAction($userId){
    $userId = (int) $userId;
    $rating = get_user_rating($userId);
    if($rating === NULL){
      $this->core->return404("No user with id ".$userId);
    }
    $params = array(
        'user' => array(
                    'userId' => $userId,
                    'userRating' => $rating,
                  ),
    );
    return $params;
  }

}