<?php

// Output form notices
if(count($this->getViewData('notices'))){
  echo '<ul>';
  foreach($this->getViewData('notices') as $notice){
    echo '<li>'.$notice.'</li>';
  }
  echo '</ul><br>';
}

// Auth form
?>
<form action="" method="POST">
  <div class="form-line">
    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" placeholder="itis@example.com" required<?=(isset($_POST['email'])?' value="'.$_POST['email'].'"':'')?>>
  </div>
  <div class="form-line">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
  </div>
  <div class="form-line">
    <input type="submit" name="auth" value="Auth">
  </div>
</form>
<?php