<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Sign Up Form with live validation</title>
  <link rel="stylesheet" href="<?=base_url("css/signup.css");?>">
	<script src="<?=base_url("js/jquery.js");?>"></script>
	<script src="<?=base_url("js/index.js");?>"></script>
</head>

<body>

<?=form_open("login/processSignUp");?>
  <h2>FUTO Farms<br/>Sign Up</h2>
  <h3 style="text-align:center;"><?=$message?></h3>
		<p>
			<label for="Email" class="floatLabel">Email</label>
			<input id="Email" value="<?=$email?>" name="email" type="text">
		</p>
		<p>
			<label for="password" class="floatLabel">Password</label>
			<input id="password" name="password" type="password">
		</p>
		<p>
			<label for="confirm_password" class="floatLabel">Confirm Password</label>
			<input id="confirm_password" name="confirm_password" type="password">
		</p>
    <p>
			<label for="Username" class="floatLabel">User Name</label>
			<input id="Username" name="username" value="<?=$username?>" type="text">
		</p>
    <p>
			<label for="Email" class="floatLabel">Phone Number</label>
			<input id="Email" value="<?=$phone_number?>" name="phone_number" type="text">
		</p>
    <p>
			<label for="Email" class="floatLabel">First Name</label>
			<input id="Email" name="first_name" value="<?=$first_name?>" type="text">
		</p>
    <p>
			<label for="Email" class="floatLabel">Last Name</label>
			<input id="Email" name="last_name" value="<?=$last_name?>" type="text">
		</p>
    <p>
			<label for="Email" class="floatLabel">Middle Name</label>
			<input id="Email" name="middle_name" value="<?=$middle_name?>" type="text">
		</p>
		<p>
			<input type="submit" value="Create My Account" id="submit">
		</p>
	<?=form_close();?>
</body>
</html>
