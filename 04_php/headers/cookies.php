<?php
  if (!empty($_GET)) {
    if (isset($_GET['clear'])) {
      $past = time() - 3600;
      foreach ($_COOKIE as $key => $value){
        setcookie($key, '', $past, '/');
      }
    }
    elseif (isset($_GET['session'])) {
      session_start();
    }
    elseif (isset($_GET['test'])) {
      setcookie('some_cookie', date('r'), time()+3600, '/');
    }
    header('Location: '.$_SERVER['PHP_SELF']);
  }

  if (empty($_COOKIE)){
    echo 'No cookies';
  }
  else{
    echo 'All cookies:<br>';
    foreach ($_COOKIE as $name => $val) {
      echo $name . ' = ' . $val . '<br>';
    }
  }
?>
<form action="" method="GET">
  <input type="submit" name="clear" value="Clear cookies"><br>
  <input type="submit" name="session" value="Start session"><br>
  <input type="submit" name="test" value="Set TEST cookie"><br>
</form>