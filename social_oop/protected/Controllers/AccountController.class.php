<?php

//namespace MvcOop;

/**
 *  Account controller for individual pages etc
 */
class AccountController extends Controller {
  
  public function indexAction(){
    $params = array();
    if(!$this->core->isLogged()){
      $this->core->forward('security', 'auth', array());
    }
    else{
      $info = $this->core->getUserFullData();
      if($info === NULL){
        $this->core->return500("User session is corrupted.");
      }
      $params = array(
        'userInfo' => $info,
      );
    }
    return $params;
  }

  public function showAction($userId){
    $userId = (int) $userId;
    $info = get_user_info($userId);
    if($info === NULL){
      $this->core->return404("No user with id ".$userId);
    }
    $params = array(
      'userInfo' => $info,
    );
    return $params;
  }
}