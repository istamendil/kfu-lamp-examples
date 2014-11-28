<?php
$user = $this->getUserFullData();
$messages = $this->getViewData('messages');
if(!count($messages)){
  echo '<h3>No messages yet</h3>';
}
else{
  echo '<div class="chat-messages">';
  $this->include_view('social_chat_messages');
  echo '<div class="clearfix"></div>';
  echo '</div>';
}
?>
<form action="" method="POST" class="send-message">
  <div class="notice"><?=($this->getViewData('notice')?$this->getViewData('notice'):'')?></div>
  <textarea name="text" class="form-control" maxlength="500"></textarea><br>
  <input type="submit" value="Send message" class="btn btn-info">
</form>
<script>
  $(document).ready(function(){
    // New form handling
    $('form.send-message').submit(function(e){
      e.preventDefault();
      var form = $(this);
      $.post(
              document.location.href,
              form.serialize(),
              function(response){
                var noticeBox = form.find('.notice');
                noticeBox.empty();
                if(response.notice){
                  noticeBox.text(response.notice);
                }
                else{
                  form.find('textarea').empty();
                }
              },
              'json'
              );
    });
    
  });
</script>