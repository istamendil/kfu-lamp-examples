<?php
$user = $this->getUserFullData();
$messages = $this->getViewData('messages');
foreach($messages as $message){
  ?>
  <div class="message <?=($message['from'] == $user['id'] ? 'from' : 'to')?>">
    <div class="user"><?=$message['from_name']?></div>
    <div class="time"><?=date('d.m.Y H:i', $message['time'])?></div>
    <div class="text"><?=$message['text']?></div>
  </div>
  <?php
}