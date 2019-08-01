<!doctype html>
<html>
<head>
  <title>FUTO FARMS ADMIN PAGE</title>
  <link rel="stylesheet" href="<?=base_url("css/style.css")?>">
  <link rel="stylesheet" href="<?=base_url("css/w3.css")?>">
</head>
<body>
  <div class="login-page">
    <div class="form">
      <?=form_open("login/process", array("class"=>"login-form"));?>
        <input name="username" type="text" placeholder="username"/>
        <input name="password" type="password" placeholder="password"/>
        <button>Login</button>
        <div>
          <a class="w3-button w3-margin w3-green" href="<?=site_url("login/signUp");?>">SignUp</a>
        </div>
        <p class="message"><?=$message?></p>
      <?=form_close();?>
    </div>
  </div>
</body>
</html>
