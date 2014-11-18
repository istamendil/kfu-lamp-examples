<?php
  if(!empty($_GET['url'])&&isset($_GET['redirect'])){
    header('Location: '.$_GET['url']);
    exit();
  }
  elseif(isset($_GET['loop'])){
    header('Location: '.$_SERVER['REQUEST_URI']);
    exit();
  }
?>
<form action="" method="GET">
  <input type="text" name="url">
  <input type="submit" name="redirect" value="Redirect">
  <input type="submit" name="loop" value="Redirect to itself">
</form>