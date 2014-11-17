<!DOCTYPE html>
<html>
  <head>
    <title>E-Meet: Cute Animals (EM:CA)</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/social_oop/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/social_oop/assets/js/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="/social_oop/assets/social.css">

    <meta name="description" content="Социальная сеть для любителей ми-ми-ми-животных">
    <meta name="keywords" content="ми-ми-ми, животные, социальная сеть">

    <script src="/social_oop/assets/js/jquery-2.1.1.min.js"></script>
    <script src="/social_oop/assets/js/main.js"></script>
    <script src="/social_oop/assets/js/magnific-popup/jquery.magnific-popup.js"></script> 
  </head>
  <body>
    <!-- Navigation -->
    <ul id="main-navigation">
      <li><a href="<?=$this->generate_path('main', 'index', array())?>" class="mainpage" title="Main page"></a></li>
      <?php
      if($this->isLogged()){
        ?>
          <li><a href="<?=$this->generate_path('account', 'index', array())?>" class="profile" title="Profile"></a></li>
          <li><a href="<?=$this->generate_path('account', 'exit', array())?>" class="exit" title="Exit"></a></li>
        <?php
      }
      else{
        ?>
          <li><a href="<?=$this->generate_path('account', 'auth', array())?>" class="login" title="Login"></a></li>
        <?php
      }
      ?>
    </ul>
    
    <?php
    if($this->isLogged()){
      ?>
      <!-- Likemeter -->
      <div id="cutemeter">
        <?=$this->getUserData('rating')?>
      </div>
      <?php
    }
    ?>

    <!-- Main block -->
    <div id="main-box">