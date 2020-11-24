<?php

# sanitizers
function sanitizeUsername($input)
{
	$input = strip_tags($input);   # prevent users from sending tags to server
	$input = str_replace(" ", "", $input);
	return $input;
}
function sanitizeString($input)
{
	$input = strip_tags($input);
	$input = str_replace(" ", "", $input);
	$input = ucfirst($input);
	return $input;
}
function sanitizePassword($input)
{
	$input = strip_tags($input);
	return $input;
}





if(asset($_POST['registerButton']))      //  action follows if the register button is clicked on
{
	
	$username = sanitizeUsername($_POST['registerUsername']);
	$firstname = sanitizeString($_POST['firstName']);
	$lastname = sanitizeString($_POST['lastName']);
	$email = sanitizeString($_POST['email']);
	$email_confirm = sanitizeString($_POST['email_confirm']);
	$password = sanitizePassword($_POST['registerPassword']);
	$password_confirm = sanitizePassword($_POST['password_confirm']);

	$noError = $account->register($username, $firstname, $lastname, $email, $email_confirm, $password, $password_confirm);
	if ($NoError)
	{
		header("Location: index.php");   // redirect to index page if registration is succesful
	}

}



?>