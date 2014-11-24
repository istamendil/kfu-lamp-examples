<?php
session_start();

// Simple captchas
if (isset($_REQUEST['code'])) {
  
  if (!empty($_SESSION['captcha_keystring_summ']) && $_SESSION['captcha_keystring_summ'] == $_REQUEST['code']) {
    $correct = TRUE;
  }
  elseif (!empty($_SESSION['captcha_keystring_num']) && $_SESSION['captcha_keystring_num'] == $_REQUEST['code']) {
    $correct = TRUE;
  }
  else {
    $correct = FALSE;
  }
  
  if ($correct) {
    echo '<h3>Вы ввели корректный код!</h3>';
  } else {
    echo '<h3>Введённый код некорректен!</h3>';
  }
}

// Google reCaptcha
require_once('recaptcha/recaptchalib.php');
// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "6LfzNf4SAAAAAFectgka7_PHA1QF4RtQftlGb3Su";
$privatekey = "6LfzNf4SAAAAAE60vytLdB6ROslicdOfy0IX4gWt";
$error = null;
if (isset($_REQUEST["recaptcha_response_field"])) {
  $resp = recaptcha_check_answer ($privatekey,
                                  $_SERVER["REMOTE_ADDR"],
                                  $_REQUEST["recaptcha_challenge_field"],
                                  $_REQUEST["recaptcha_response_field"]);
  if ($resp->is_valid) {
    echo '<h3>Вы ввели корректный код reCaptcha!</h3>';
  } else {
    echo '<h3>Введённый код reCaptcha некорректен!</h3>';
    $error = $resp->error;
  }
}
?>

<div style="width:400px;margin:50px auto;border:1px dashed #000;text-align:center;padding:20px">
  <h3>Введите в верхнее поле код с верхней картинки или сумму чисел с картинки посередине, или введите код reCaptcha в поле снизу.</h3><br>

  <form action="" method="POST">
    <img src="num/captcha.php" style="width:180px;height:90px;margin:0px auto;"><br><br>
    <img src="summ/captcha.php" style="width:180px;height:90px;margin:0px auto;"><br><br>
    <input type="text" name="code"><br><br>
    
    <?= recaptcha_get_html($publickey, $error); ?><br><br>
    <input type="submit" name="check" value="Check Me!">
  </form>
</div>