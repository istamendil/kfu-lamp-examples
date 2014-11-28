<?php
$chats = $this->getViewData('chats');
if(!count($chats)){
  echo '<h3>No users here =(</h3>';
}
else{
  echo '<table class="table table-hover table-condensed chats-table">';
  echo '<tr>'
          . '<th>User</th>'
          . '<th>Messages count</th>'
          . '<th>Last message</th>'
          . '<th></th>'
          . '</tr>';
  foreach($chats as $id=>$chat){
    echo '<tr>'
            . '<td>'.$chat['name'].'</td>'
            . '<td>'.$chat['messages'].'</td>'
            . '<td>'.str_replace('<br />', ' ', $chat['last_message']).'</td>'
            . '<td><a href="'.$this->generate_path('social', 'chat', array('id'=>$id)).'" class="open-chat"><img src="'.$this->get('site_path').'/assets/flaticons/mail.png" alt="Open chat"> Open chat</a></td>'
            . '</tr>';
  }
  echo '</table>';
}
