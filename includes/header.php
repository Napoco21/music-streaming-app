
<?php
include("includes/config.php");

// session_destroy();

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
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
	<div id="mainContainer">

		<div id="topContainer">

			<?php include("includes/navBarContainer.php"); ?>

			<div id="mainViewContainer">
				<div id="mainContent">            <!-- div is closed in footer.php, and content in betwween is in index.php -->