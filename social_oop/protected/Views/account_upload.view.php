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
  $images = $this->getViewData('uploadPath');
  echo '<h4>File has been uploaded successfully</h4>';
  foreach($images as $image){
    echo '<div><a href="'.$image.'" target="_blank">'.$image.'</a></div>';
  }
}

// Upload form
?>
<h3>Here you can upload new photos to your account.</h3>
<form method="POST" action="<?=$this->generate_path('account', 'upload')?>" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="4000000">
  <input type="file" name="upload">
  <input type="submit" value="Upload new photo">
</form>