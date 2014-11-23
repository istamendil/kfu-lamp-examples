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
      $this->core->forward('account', 'show', array('id' => $this->core->getUserData('id')));
    }
    return $params;
  }

  public function showAction($userId) {
    $userId = (int) $userId;
    $info = get_user_info($userId);
    if ($info === NULL) {
      $this->core->return404("No user with id " . $userId);
    }
    
    //get user pictures
    $pictures = get_user_pictures($userId);
    if(count($pictures) < $this->core->get('minimum_user_pictures_amount')){
      $dummyPicture = array(
              'userId'     => $userId,
              'path'       => $this->core->get('default_user_picture'),
              'small_path' => $this->core->get('default_user_picture'),
              'name'       => "User didn't upload enough pictures.",
              'rating'     => 0,
              );
      $dummyArray = array_fill(
              count($pictures),
              $this->core->get('minimum_user_pictures_amount')-count($pictures),
              $dummyPicture
              );
      $pictures = array_merge($pictures, $dummyArray);
    }
    
    $params = array(
        'userInfo' => $info,
        'pictures' => $pictures,
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
    $uploadedPaths = '';
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
          $error = add_picture($this->core->getUserData('id'), $newName.'.'.$ext);
          if($error !== TRUE){
            $notices[] = $error;
          }
          else{
            $success = TRUE;
            $uploadedPaths[] = 'http://'.$_SERVER['SERVER_NAME'].$this->core->get('upload_path').'/'.$newName.'.'.$ext;
            
            
            // Make thumbnails
            $width  = 160;
            $height = 160;
            list($width_orig, $height_orig) = getimagesize($this->core->get('upload_dir').'/'.$newName.'.'.$ext);
            $ratio_orig = $width_orig/$height_orig;
            if ($width/$height > $ratio_orig) {
               $width = $height*$ratio_orig;
            } else {
               $height = $width/$ratio_orig;
            }
            $image_mini = imagecreatetruecolor($width, $height);
            switch($ext){
              case 'bmp': $image_original = imagecreatefromwbmp($this->core->get('upload_dir').'/'.$newName.'.'.$ext); break;
              case 'gif': $image_original = imagecreatefromgif ($this->core->get('upload_dir').'/'.$newName.'.'.$ext); break;
              case 'jpg': $image_original = imagecreatefromjpeg($this->core->get('upload_dir').'/'.$newName.'.'.$ext); break;
              case 'png': $image_original = imagecreatefrompng ($this->core->get('upload_dir').'/'.$newName.'.'.$ext); break;
            }
            imagecopyresampled($image_mini, $image_original, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            switch($ext){
              case 'bmp': imagewbmp($image_mini, $this->core->get('upload_dir').'/mini_'.$newName.'.'.$ext); break;
              case 'gif': imagegif ($image_mini, $this->core->get('upload_dir').'/mini_'.$newName.'.'.$ext); break;
              case 'jpg': imagejpeg($image_mini, $this->core->get('upload_dir').'/mini_'.$newName.'.'.$ext); break;
              case 'png': imagepng ($image_mini, $this->core->get('upload_dir').'/mini_'.$newName.'.'.$ext); break;
            }
            $uploadedPaths[] = 'http://'.$_SERVER['SERVER_NAME'].$this->core->get('upload_path').'/mini_'.$newName.'.'.$ext;
          }
        }
      }
    }
    return array(
        'notices'    => $notices,
        'success'    => $success,
        'uploadPath' => $uploadedPaths,
    );
  }

}
