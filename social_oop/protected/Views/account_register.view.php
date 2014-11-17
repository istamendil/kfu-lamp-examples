<?php

// Output form notices
if(count($this->getViewData('notices'))){
  echo '<h4>Form is filled wrong.</h4><ul>';
  foreach($this->getViewData('notices') as $notice){
    echo '<li>'.$notice.'</li>';
  }
  echo '</ul><br>';
}
elseif($this->getViewData('success')){
  echo '<h4>'.$this->getViewData('success').'</h4>';
}

// Registration form
if( count($this->getViewData('notices')) || !$this->getViewData('success')){
  ?>
  <form action="" method="POST">
    <div class="form-line">
      <label for="email">E-mail *</label>
      <input type="email" name="email" id="email" placeholder="itis@example.com" required<?=(isset($_POST['email'])?' value="'.$_POST['email'].'"':'')?>>
    </div>
    <div class="form-line">
      <label for="password">Password *</label>
      <input type="password" name="password" id="password" required<?=(isset($_POST['password'])?' value="'.$_POST['password'].'"':'')?>>
    </div>
    <div class="form-line">
      <label for="repassword">Password repeat*</label>
      <input type="password" name="repassword" id="repassword" required<?=(isset($_POST['repassword'])?' value="'.$_POST['repassword'].'"':'')?>>
    </div>
    <div class="form-line">
      <label for="realName">Real name *</label>
      <input type="text" name="realName" id="realName" placeholder="Ivan" required<?=(isset($_POST['realName'])?' value="'.$_POST['realName'].'"':'')?>>
    </div>
    <div class="form-line">
      <label for="sex">Sex</label>
      <select name="sex" id="sex">
        <option value="-1">Please select</option>
        <option value="1"<?=(isset($_POST['sex'])?($_POST['sex']==="1"?' selected':''):'')?>>Male</option>
        <option value="0"<?=(isset($_POST['sex'])?($_POST['sex']==="0"?' selected':''):'')?>>Female</option>
      </select>
    </div>
    <div class="form-line">
      <label for="subscription">News subscription</label>
      <input type="checkbox" name="subscription" id="subscription"<?=(isset($_POST['subscription'])?' checked':'')?>>
    </div>
    <div class="form-line">
      <input type="submit" name="register" value="Register">
    </div>
  </form>
  <?php
}