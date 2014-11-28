<?php

//namespace MvcOop;

/**
 *  Main controller
 */
class MainController extends Controller {
  
  public function indexAction(){
    return array(
        'users' => get_all_users('name')
        );
  }

}