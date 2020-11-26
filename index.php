<?php
include("includes/config.php");

session_destroy();

if(isset($_SESSION['userLoggedIn']))
{
	$userLoggedIn = $_SESSION['userLoggedIn'];
}
else
{
	header("Location: register.php");
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>index page</title>
</head>
<body>
	What?
</body>
</html>
