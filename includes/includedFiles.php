<!-- checks whether the request has been sent by ajax or if the user inputted the url manually -->
<?php 

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))    // if it is an ajax request
{
	include("includes/config.php"); 
	include("includes/classes/Artist.php"); 
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
}
else
{
	include("includes/header.php");
	include("includes/footer.php");

	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>";
	exit();   // prevents from loading the rest of the page
}

?>