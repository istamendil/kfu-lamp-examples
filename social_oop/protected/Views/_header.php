<!DOCTYPE html>
<html>
  <head>
    <title>E-Meet: Cute Animals (EM:CA)</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?=$this->get('site_path')?>/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$this->get('site_path')?>/assets/js/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="<?=$this->get('site_path')?>/assets/social.css">

    <meta name="description" content="Социальная сеть для любителей ми-ми-ми-животных">
    <meta name="keywords" content="ми-ми-ми, животные, социальная сеть">

    <script src="<?=$this->get('site_path')?>/assets/js/jquery-2.1.1.min.js"></script>
    <script src="<?=$this->get('site_path')?>/assets/js/main.js"></script>
    <script src="<?=$this->get('site_path')?>/assets/js/magnific-popup/jquery.magnific-popup.js"></script> 
  </head>
  <body>
    <!-- Navigation -->
    <ul id="main-navigation">
      <li><a href="<?=$this->generate_path('main', 'index')?>" class="mainpage" title="Homepage"></a></li>
      <?php
      if($this->isLogged()){
        ?>
          <li><a href="<?=$this->generate_path('account', 'index')?>" class="profile" title="My Profile"></a></li>
          <li><a href="<?=$this->generate_path('social', 'chats')?>" class="chats" title="Your chats"></a></li>
          <li><a href="<?=$this->generate_path('account', 'upload')?>" class="add-picture" title="Upload a picture"></a></li>
          <li><a href="<?=$this->generate_path('security', 'exit')?>" class="exit" title="Exit"></a></li>
        <?php
      }
      else{
        ?>
          <li><a href="<?=$this->generate_path('security', 'register')?>" class="register" title="Register"></a></li>
          <li><a href="<?=$this->generate_path('security', 'auth')?>" class="login" title="Login"></a></li>
        <?php
      }
      ?>
    </ul>
    
    <?php
    if($this->isLogged()){
      ?>
      <!-- Likemeter -->
      <div id="cutemeter">
        <?php
          $rating = $this->getUserData('rating');
          if($rating > 999){
            $rating = (int) ($rating/1000);
            $rating .= 'K';
          }
          echo $rating;
        ?>
      </div>
      <?php
    }
    ?>

    <!-- Main block -->
    <div id="main-box">