<?php

//namespace MvcOop;

/**
 *  Social controller for social functionality
 */
class SocialController extends Controller {

  public function chatsAction() {
    if (!$this->core->isLogged()) {
      $this->core->forward('security', 'auth', array());
    }
    
    $chats = get_user_chats($this->core->getUserData('id'));
    return array(
        'chats' => $chats,
    );
  }
  
  public function chatAction($userId){
    if (!$this->core->isLogged()) {
      $this->core->forward('security', 'auth', array());
    }
    
    $notice = '';
    $success = '';
    $messages = array();
    $lastMessageId = 0;
    // From has been sent
    if(isset($_POST['text'])){
      if(strlen($_POST['text'])<1){
        $notice = 'Message must be at least 1 character long.';
      }
      elseif(strlen($_POST['text']) > 500){
        $notice = 'Message must be less then 500 characters.';
      }
      else{
        add_message($this->core->getUserData('id'), $userId, $_POST['text']);
      }
    }
    else{
      $messages = get_chat_messages($this->core->getUserData('id'), $userId); // Simple request - all messages
    }
    
    return array(
        'messages' => $messages,
        'notices'  => $notice,
        'success'  => $success,
        'userId'   => $userId
    );
  }

}
