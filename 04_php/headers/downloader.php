<?php
  if(!empty($_GET)){
    if(isset($_GET['normal'])){
      header('Content-type: image/png');
    }
    elseif(isset($_GET['download_noname'])){
      header('Content-type: image/png');
      header('Content-Disposition: attachment');
    }
    elseif(isset($_GET['download_scecialname'])){
      header('Content-type: image/png');
      header('Content-Disposition: attachment; filename="web.png"');
    }
    readfile('anchor.png');
    exit();
  }
?>
<form action="" method="GET">
  <input type="submit" name="download_noname" value="Download noname"><br>
  <input type="submit" name="download_scecialname" value="Download special name"><br>
  <input type="submit" name="normal" value="Normal output"><br>
  <input type="submit" name="raw" value="Raw output"><br>
</form>
