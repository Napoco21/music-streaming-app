
<?php
include("includes/config.php"); 
include("includes/classes/Artist.php"); 
include("includes/classes/Album.php");
include("includes/classes/Song.php");
// session_destroy();

if(isset($_SESSION['userLoggedIn']))
{
	$userLoggedIn = $_SESSION['userLoggedIn'];
	echo "<script>userLoggedIn = '$userLoggedIn';</script>";
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="assets/js/script.js"></script>
</head>
<body>



	<div id="mainContainer">

		<div id="topContainer">

			<?php include("includes/navBarContainer.php"); ?>

			<div id="mainViewContainer">
				<div id="mainContent">            <!-- div is closed in footer.php, and content in betwween is in index.php -->