<?php

//namespace MvcOop;

/**
 *  Account controller for individual pages etc
 */
class AccountController extends Controller {

  public function indexAction() {
    $params = array();
    if (!$this->core->isLogged()) {
      $this->core->forward('security', 'auth', array());
    } else {
      $info = $this->core->getUserFullData();
      if ($info === NULL) {
        $this->core->return500("User session is corrupted.");
      }
      $params = array(
          'userInfo' => $info,
      );
    }
    return $params;
  }

  public function showAction($userId) {
    $userId = (int) $userId;
    $info = get_user_info($userId);
    if ($info === NULL) {
      $this->core->return404("No user with id " . $userId);
    }
    $params = array(
        'userInfo' => $info,
    );
    return $params;
  }

  public function uploadAction() {
    // Logged in?
    if (!$this->core->isLogged()) {
      $this->core->forward('security', 'auth', array());
    }
    
    $notices = array();
    $success = FALSE;
    $uploadedPath = '';
    if (!empty($_FILES['upload'])) {
      switch ($_FILES['upload']['error']) {
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_NO_FILE:
          $notices[] = 'No file sent.';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          $notices[] = 'Exceeded filesize limit.';
        default:
          $notices[] = 'Unknown errors.';
      }
      if ($_FILES['upload']['size'] > 1000000) {
        $notices[] = 'Exceeded filesize limit.';
      }

      // Check MIME Type by yourself.
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search(
              $finfo->file($_FILES['upload']['tmp_name']), array(
          'jpg' => 'image/jpeg',
          'png' => 'image/png',
          'gif' => 'image/gif',
              ), true
              )) {
        $notices[] = 'Invalid file format.';
      }

      // You should name it uniquely.
      if(count($notices) === 0){
        $newName = md5($_FILES['upload']['tmp_name'].time());
        if (!move_uploaded_file($_FILES['upload']['tmp_name'], $this->core->get('upload_dir').'/'.$newName.'.'.$ext)) {
          $notices[] = 'Failed to move uploaded file.';
        }
        else{
          $success = TRUE;
          $uploadedPath = 'http://'.$_SERVER['SERVER_NAME'].$this->core->get('upload_path').'/'.$newName.'.'.$ext;
        }
      }
    }
    return array(
        'notices'    => $notices,
        'success'    => $success,
        'uploadPath' => $uploadedPath,
    );
  }

}
