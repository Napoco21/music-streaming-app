<?php 
	ob_start();

	session_start();      // starts user login session 

	$timezone = date_default_timezone_set("America/Vancouver");

	$conn = mysqli_connect("localhost", "root", "", "music_streaming_app");    // for user="root", password=none, database_name="music_streaming_app"

	if(mysqli_connect_errno())
	{
		echo "Failed to connect: ".mysqli_connect_errno();
	}

?>