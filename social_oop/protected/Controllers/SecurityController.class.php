<?php

//namespace MvcOop;

/**
 *  Security controller for login, logout, registration etc
 */
class SecurityController extends Controller {
  
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
  
  public function registerAction(){
    // Errors for submitted form
    $notices = array();
    // Sucess message
    $success = '';
    //Has user submit a form?
    if(isset($_POST['register'])){
      //Check submitted data
      if(!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['repassword']) || !isset($_POST['realName'])){
        $notices[] = 'Please fill all fields marked with *.';
      }
      else{
        //User has to repeat password
        if($_POST['password'] !== $_POST['repassword']){
          $notices[] = 'Passwords are not match each other.';
        }
        //Unexpected value - just null it
        if($_POST['sex']!=='1' && $_POST['sex']!=='0'){
          $_POST['sex'] = -1;
        }
      }
      if(count($notices) === 0){
        //No notices? Register!
        $data = array(
          'email'    => $_POST['email'],
          'password' => md5($_POST['password']),
          'realName' => $_POST['realName'],
          'sex'      => (isset($_POST['sex'])?$_POST['sex']:'-1'),
          'subscribtion' => (isset($_POST['subscribtion'])?'1':'0'),
          'rating'   => rand(0, 999),
        );
        add_user($data) !== FALSE
                       or die('Error with writing to DB!');
        $success = 'You was registered! <a href="'.$this->core->generate_path('account', 'auth', array()).'">You can log in now</a>';
      }
    }
    return array(
        'notices' => $notices,
        'success' => $success,
    );
  }
  
  public function authAction(){
    // Errors for submitted form
    $notices = array();
    // Success message
    $success = '';
    //Has user submit a form?
    if(isset($_POST['email']) && isset($_POST['password'])){
      $user = find_user($_POST['email'], $_POST['password']);
      if(!$user){
        $notices[] = "Can't find user with such email and password.";
      }
      // Find such user in data file
      if(count($notices) === 0){
        $_SESSION['user'] = $user;
        $this->core->redirect('account', 'index', array());
      }
    }
    return array(
        'notices' => $notices,
        'success' => $success,
    );
  }
    
  public function exitAction(){
    $this->core->logout();
    $this->core->redirect('main', 'index', array());
    return array();
  }

}