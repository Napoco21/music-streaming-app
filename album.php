<?php include("includes/header.php"); 

if(isset($_GET['id']))
{
	$albumId = $_GET['id'];
}
else
{
	header("Location: index.php");    
}

$album = new Album($conn, $albumId);

$artist = $album->getArtist();

?>

<div class="entityInfo">
	<div class="leftSection">
		<img src="<?php echo $album->getArtworkLocation(); ?>">
	</div>

	<div class="rightSection">
		<h2>
			<?php echo $album->getTitle(); ?>
		</h2>
		<p> By <?php echo $artist->getName() ?>  </p> 
		<p> <?php echo $album->getNumberOfSongs() ?> songs </p> 
	</div>
</div>

<div class="trackListContainer">
	<ul class="tracklist">
		
		<?php 
			$songIdArray = $album->getSongIds();

			$iterator = 1;

			foreach($songIdArray as $songId)
			{
				$albumSong = new Song($conn, $songId);
				$albumArtist = $albumSong->getArtist();

				echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
						<span class='trackNumber'>$iterator</span>
					</div>

					<div class='trackInfo'>
						<span class='trackName'>" . $albumSong->getTitle() . "</span>
						<span class='artistName'>" . $albumArtist->getName() . "</span>
					</div>

					<div class='trackOptions'>
						<img class='optionsButton' src='assets/images/icons/more.png'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $albumSong->getDuration() . "</span
					</div>

				</li>";

				$iterator++;
			}
		?>

		<script>
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>';     // contains ids of songs on album page
			tempPlaylist = JSON.parse(tempSongIds);
		</script>

	</ul>
</div>


<?php include("includes/footer.php"); ?>
