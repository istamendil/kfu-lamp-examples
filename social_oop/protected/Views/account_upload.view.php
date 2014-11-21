<?php

// Output form notices
if(count($this->getViewData('notices'))){
  echo '<ul>';
  foreach($this->getViewData('notices') as $notice){
    echo '<li>'.$notice.'</li>';
  }
  echo '</ul><br>';
}

if($this->getViewData('success')){
  echo '<h3>File has been uploaded successfully</h3>';
  echo '<div><a href="'.$this->getViewData('uploadPath').'" target="_blank">'.$this->getViewData('uploadPath').'</a></div>';
}

// Upload form
?>
<form method="POST" action="<?=$this->generate_path('account', 'upload')?>" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
  <input type="file" name="upload">
  <input type="submit" value="Upload new photo">
</form>