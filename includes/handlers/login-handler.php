<?php 




if(isset($_POST['loginButton']))    //  action follows if the login button is clicked on
{
	$username = $_POST['loginUsername'];
	$password = $_POST['loginPassword'];

	// login function
	$result = $account->login($username, $password);

	if($result)
	{
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}
}





 ?>