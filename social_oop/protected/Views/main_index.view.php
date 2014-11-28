<h2>Main page of EM:CA</h2>
<a href="<?=$this->generate_path('account', 'show', array('id'=>153))?>"><h3>Non-existed user page example</h3></a>
<a href="<?=$this->generate_path('fhfdhd', 'shffdgdow', array('id'=>1))?>"><h3>Non-existed controller page example</h3></a>
<hr><br>
<h2>Registered users:</h2>
<?php
foreach($this->getViewData('users') as $user){
  echo '<a href="'.$this->generate_path('account', 'show', array('id'=>$user['id'])).'"><h3>'.$user['name'].'</h3></a>';
}
?>