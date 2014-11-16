<!DOCTYPE html>
<html>
  <head>
    <title>E-Meet: Cute Animals (EM:CA)</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/social/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/social/assets/js/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="/social/assets/social.css">

    <meta name="description" content="Социальная сеть для любителей ми-ми-ми-животных">
    <meta name="keywords" content="ми-ми-ми, животные, социальная сеть">

    <script src="/social/assets/js/jquery-2.1.1.min.js"></script>
    <script src="/social/assets/js/main.js"></script>
    <script src="/social/assets/js/magnific-popup/jquery.magnific-popup.js"></script> 
  </head>
  <body>
    <?php
    if($this->getViewData('user')){
      ?>
      <!-- Navigation -->
      <ul id="main-navigation">
        <li><a href="/" class="profile current" title="Profile"></a></li>
        <li><a href="/pets" class="pets" title="My pets"></a></li>
        <li><a href="/settings" class="settings" title="Settings"></a></li>
      </ul>
      <?php
    }
    ?>
    
    <?php
    if($this->getViewData('user')){
      ?>
      <!-- Likemeter -->
      <div id="cutemeter">
        <?=$this->getUserData('userRating')?>
      </div>
      <?php
    }
    ?>

    <!-- Main block -->
    <div id="main-box">