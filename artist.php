<?php 
include("includes/includedFiles.php");

if(isset($_GET['id']))
{
	$artistId = $_GET['id'];
}
else
{
	header("Location: index.php");    
}

$artist = new Artist($conn, $artistId);

 ?>

 <div class="entityInfo borderButton">
	 <div class="centerSection">
	 	<div class="artistInfo">
	 		<h1 class="artistName"><?php echo $artist->getName(); ?></h1>
	 		<div class="headerButtons">
	 			<button class="button green" onclick="playFirstSong()">Play</button>
	 		</div>
	 	</div>
	 </div> 
 </div>


 <div class="tracklistContainer borderButton">
 	<h2>Songs</h2>
	<ul class="tracklist">
		
		<?php 
			$songIdArray = $artist->getSongIds();

			$iterator = 1;

			foreach($songIdArray as $songId)
			{
				if ($iterator > 5)      
				{
					break;            // only produce html for first 5 songs
				}
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

<div class="gridViewContainer">
	<h2>Albums</h2>
	<?php 
		$albumQuery = mysqli_query($conn, "SELECT * FROM albums WHERE artist='$artistId'");

		while($row = mysqli_fetch_array($albumQuery))
		{
			echo "<div class='gridViewItem'>
				<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
					<img src='" . $row['artworkLocation'] . "'>

					<div class='gridViewInfo'>" . $row['title'] . "</div>
				</span>
			</div>";
		}
	 ?>
</div>