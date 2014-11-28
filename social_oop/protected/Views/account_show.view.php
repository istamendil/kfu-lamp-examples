<?php
$userInfo = $this->getViewData('userInfo');
$pictures = $this->getViewData('pictures');
?>
<h2><?=$userInfo['name']?> (<?=$userInfo['rating']?>)</h2>
<div class="photo-box">
  <div class="preview-column col1">
    <img src="<?=$this->get('upload_path').'/'.$pictures[4]['small_path']?>" alt="<?=$pictures[4]['name'].' &ndash; '.$pictures[4]['rating']?>">
    <img src="<?=$this->get('upload_path').'/'.$pictures[6]['small_path']?>" alt="<?=$pictures[4]['name'].' &ndash; '.$pictures[6]['rating']?>">
  </div>
  <div class="preview-column col2">
    <img src="<?=$this->get('upload_path').'/'.$pictures[0]['small_path']?>" alt="<?=$pictures[0]['name'].' &ndash; '.$pictures[0]['rating']?>">
    <img src="<?=$this->get('upload_path').'/'.$pictures[2]['small_path']?>" alt="<?=$pictures[2]['name'].' &ndash; '.$pictures[2]['rating']?>">
  </div>
  <div class="avatar col3">
    <img src="/social/assets/img/rabbit_480x480_1.jpg" alt="Pic">
  </div>
  <div class="preview-column col4">
    <img src="<?=$this->get('upload_path').'/'.$pictures[1]['small_path']?>" alt="<?=$pictures[1]['name'].' &ndash; '.$pictures[1]['rating']?>">
    <img src="<?=$this->get('upload_path').'/'.$pictures[3]['small_path']?>" alt="<?=$pictures[3]['name'].' &ndash; '.$pictures[3]['rating']?>">
  </div>
  <div class="preview-column col5">
    <img src="<?=$this->get('upload_path').'/'.$pictures[5]['small_path']?>" alt="<?=$pictures[5]['name'].' &ndash; '.$pictures[5]['rating']?>">
    <img src="<?=$this->get('upload_path').'/'.$pictures[6]['small_path']?>" alt="<?=$pictures[6]['name'].' &ndash; '.$pictures[6]['rating']?>">
  </div>
</div>
<div id="news-best-box">
  <div class="moving-button left"></div>
  <div class="news-container">
    <?php
      for($i=7; $i<count($pictures); $i++){
        ?>
        <a class="news" href="<?=$this->get('upload_path').'/'.$pictures[$i]['path']?>">
          <img src="<?=$this->get('upload_path').'/'.$pictures[$i]['small_path']?>" alt="<?=$pictures[$i]['name'].' &ndash; '.$pictures[$i]['rating']?>">
        </a>
        <?php
      }
    ?>
  </div>
  <div class="moving-button right"></div>
</div>
<div class="news-main-box">Rest news</div>

<script>
  

// Run all animations and listeners etc here
$(document).ready(function(){
  bestNewsSlider = new GeometrySlider();
  bestNewsSlider.init("#news-best-box .news-container", 100);
  
  //slider popups
  $("#news-best-box .news-container").magnificPopup({
    delegate: 'a',
    type: 'image'
  });

});
</script>