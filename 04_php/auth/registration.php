<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<style>
	  .form-line{
	    clear:both;
	  }
	  .form-line input, .form-line select{
	    display:block;
	    float:left;
	  }
	  .form-line label{
	    display: block;
	    float:left;
	    width:200px;
	  }
	</style>
</head>
<body>
  <?php
  //Errors for submitted form
  $notices = array();
  //Has user submit a form?
  if(isset($_POST['register'])){
    //Check submitted data
    if(!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['repassword']) || !isset($_POST['realName'])){
      $notices[] = 'Please fill all fields marked with *.';
    }
    else{
      //User has to repeat password
      if($_POST['password'] !== $_POST['repassword']){
        $notices[] = 'Passwords are not match each other.';
      }
      //Unexpected value - just null it
      if($_POST['sex']!=='1' && $_POST['sex']!=='0'){
        $_POST['sex'] = -1;
      }
    }
    if(count($notices)){
      //Output form notices
      echo '<h4>Form is filled wrong.</h4><ul>';
      foreach($notices as $notice){
        echo '<li>'.$notice.'</li>';
      }
      echo '</ul><br>';
    }
    else{
      //No notices? Register!
      $data = array(
        'email'    => $_POST['email'],
        'password' => md5($_POST['password']),
        'realName' => $_POST['realName'],
        'sex'      => (isset($_POST['sex'])?$_POST['sex']:'-1'),
        'subscribtion' => (isset($_POST['subscribtion'])?'1':'0'),
      );
      file_put_contents('data/users', implode(' ', $data)."\n", FILE_APPEND) !== FALSE
                     or die('Error with writing to DB!');
      echo '<h3>You was registered!</h3>';
    }
  }
  ?>
  <?php
  if( (isset($_POST['register']) && count($notices)) || !isset($_POST['register']) ){
    ?>
    <form action="" method="POST">
      <div class="form-line">
        <label for="email">E-mail *</label>
        <input type="email" name="email" id="email" placeholder="itis@example.com" required<?=(isset($_POST['email'])?' value="'.$_POST['email'].'"':'')?>>
      </div>
      <div class="form-line">
        <label for="password">Password *</label>
        <input type="password" name="password" id="password" required<?=(isset($_POST['password'])?' value="'.$_POST['password'].'"':'')?>>
      </div>
      <div class="form-line">
        <label for="repassword">Password repeat*</label>
        <input type="password" name="repassword" id="repassword" required<?=(isset($_POST['repassword'])?' value="'.$_POST['repassword'].'"':'')?>>
      </div>
      <div class="form-line">
        <label for="realName">Real name *</label>
        <input type="text" name="realName" id="realName" placeholder="Ivan" required<?=(isset($_POST['realName'])?' value="'.$_POST['realName'].'"':'')?>>
      </div>
      <div class="form-line">
        <label for="sex">Sex</label>
        <select name="sex" id="sex">
          <option value="-1">Please select</option>
          <option value="1"<?=(isset($_POST['sex'])?($_POST['sex']==="1"?' selected':''):'')?>>Male</option>
          <option value="0"<?=(isset($_POST['sex'])?($_POST['sex']==="0"?' selected':''):'')?>>Female</option>
        </select>
      </div>
      <div class="form-line">
        <label for="subscription">News subscription</label>
        <input type="checkbox" name="subscription" id="subscription"<?=(isset($_POST['subscription'])?' checked':'')?>>
      </div>
      <div class="form-line">
        <input type="submit" name="register" value="Register">
      </div>
    </form>
    <?php
  }
  ?>
</body>
</html>
