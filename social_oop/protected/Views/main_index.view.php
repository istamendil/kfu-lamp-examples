<h2>Main page of EM:CA</h2>
<a href="<?=$this->generate_path('account', 'index')?>"><h3>Current user page example</h3></a>
<a href="<?=$this->generate_path('account', 'upload')?>"><h3>File upload</h3></a>
<a href="<?=$this->generate_path('account', 'show', array('id'=>0))?>"><h3>User page example</h3></a>
<a href="<?=$this->generate_path('account', 'show', array('id'=>153))?>"><h3>Non-existed user page example</h3></a>
<a href="<?=$this->generate_path('fhfdhd', 'shffdgdow', array('id'=>1))?>"><h3>Non-existed controller page example</h3></a>
<hr><br>
<h2>Registered users:</h2>
<?php
foreach($this->getViewData('users') as $user){
  echo '<a href="'.$this->generate_path('account', 'show', array('id'=>$user['id'])).'"><h3>'.$user['realName'].'</h3></a>';
}
?>