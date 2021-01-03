<?php 

include("../../config.php");

if(isset($_POST['songId']))
{
	$songId = $_POST['songId'];
	$query = mysqli_query($conn, "UPDATE songs SET numOfplays = numOfplays + 1  WHERE id='$songId'");
}


?>