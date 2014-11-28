<?php
$user = $this->getUserFullData();
$messages = $this->getViewData('messages');
$this->include_view('social_chat_messages');
echo '<div class="clearfix"></div>';