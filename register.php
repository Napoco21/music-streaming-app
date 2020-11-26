<?php 
	include("includes/config.php");
	include("includes/classes/Account.php");
	include("includes/classes/Constants.php");

    $account = new Account($conn);                   // defined before register handler

	include("includes/handlers/register-handler.php"); 
	include("includes/handlers/login-handler.php"); 



	function displayInput($input)     // keeps the field input value on page in case the login is unsuccessful
	{
		if(isset($_POST[$input]))
		{
			echo $_POST[$input];
		}
	}


?>


<!DOCTYPE html>
<html>   
<head>
	<title>Login page</title>
</head>
<body>
	<div id = "input">
		<form id="loginForm" action="register.php" method="POST">
			<h2>
				Login to your account
			</h2> 
			<p>
				<?php echo $account->getError(Constants::$loginFailed); ?>
				<label for="loginUsername"> Username </label>
				<input id="loginUsername" name="loginUsername" type="text" required>
			</p>
			<p>
				<label for="loginPassword"> Password </label>
				<input id="loginPassword" name="loginPassword" type="password" required>
			</p>
 
			<button type="submit" name="loginButton">Log in</button>
			
		</form>


		<form id="registerForm" action="register.php" method="POST">
			<h2>
				Create an account
			</h2> 
			<p>
				<?php echo $account->getError(Constants::$firstname_length_error); ?>
				<label for="firstName"> First Name </label>
				<input id="firstName" name="firstName" type="text" value="<?php displayInput('firstName') ?>"  required>
			</p>
			<p>
				<?php echo $account->getError(Constants::$lastname_length_error); ?>
				<label for="lastName"> Last name </label>
				<input id="lastName" name="lastName" type="text" value="<?php displayInput('lastName') ?>" required>
			</p>
			<p>
				<?php echo $account->getError(Constants::$username_length_error); ?>
				<?php echo $account->getError(Constants::$usernameAlreadyExists); ?>
				<label for="registerUsername"> Username </label>
				<input id="registerUsername" name="registerUsername" type="text" value="<?php displayInput('registerUsername') ?>" required>
			</p>
			<p>
				<?php echo $account->getError(Constants::$email_match_error); ?>
				<?php echo $account->getError(Constants::$email_invalid_error); ?>
				<?php echo $account->getError(Constants::$emailAlreadyExists); ?>
				<label for="email"> Email </label>
				<input id="email" name="email" type="email" value="<?php displayInput('email') ?>" required>
			</p>
			<p>
				<label for="email_confirm"> Confirm email </label>
				<input id="email_confirm" name="email_confirm" type="email" value="<?php displayInput('email_confirm') ?>" required>
			</p>
			<p>
				<?php echo $account->getError(Constants::$password_match_error); ?>
				<?php echo $account->getError(Constants::$password_length_error); ?>
				<label for="registerPassword"> Password </label>
				<input id="registerPassword" name="registerPassword" type="password" required>
			</p>
			<p>
				<label for="password_confirm"> Confirm password </label>
				<input id="password_confirm" name="password_confirm" type="password" required>
			</p>
 
			<button type="submit" name="registerButton">Register</button>
			
		</form>






	</div>
</body>
</html>