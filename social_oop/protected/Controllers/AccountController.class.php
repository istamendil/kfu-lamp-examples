<?php

//namespace MvcOop;

/**
 *  Account controller for individual pages etc
 */
class AccountController extends Controller {
  
  public function indexAccount(){}

  public function showAction($userId){
    $params = array(
        'userId' => $userId,
        'userRating' => get_user_rating($userId),
    );
    return $params;
  }

}