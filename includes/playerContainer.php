<?php 

$songQuery = mysqli_query($conn, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

while($row = mysqli_fetch_array($songQuery))
{
	array_push($resultArray, $row['id']);     // resultArray stores all the song ids
	// when we want to play a song, we use the song id and create a new song object using the Song class and play the song
} 

$jsonArray = json_encode($resultArray);

?>

<script>


	$(document).ready(function(){
		var newPlaylist = <?php echo $jsonArray; ?>;
		audioElement = new Audio();
		setTrack(newPlaylist[0], newPlaylist, false);   // play is initially set to false so that the song does not get played  unless the user plays it
		updateVolumeProgressBar(audioElement.audio);

		$("#playerContainer").on("mousedown touchstart mousemove touchmove", function(e){         // 4 events concatenated 
			e.preventDefault();
		});	 

		$(".playbackBar .progressBar").mousedown(function()
		{
			mouseDown = true;
		});

		$(".playbackBar .progressBar").mousemove(function(e)
		{ 
			if(mouseDown) 
			{
				// ser time of the song depending on the position of the mouse
				timeFromOffset(e, this);   // 'this' refers to object in jquery (playbackbar progressbar)
			}
		});

		$(".playbackBar .progressBar").mouseup(function(e)
		{
			timeFromOffset(e, this); 
		});


		$(".volumeBar .progressBar").mousedown(function()
		{
			mouseDown = true;
		});

		$(".volumeBar .progressBar").mousemove(function(e)
		{ 
			if(mouseDown)
			{
				var percentage = e.offsetX / $(this).width();
				if(percentage >= 0 && percentage <= 1)
				{
					audioElement.audio.volume = percentage;
				}	
			}
		});
 
		$(".volumeBar .progressBar").mouseup(function(e)
		{
			var percentage = e.offsetX / $(this).width();
			if(percentage >= 0 && percentage <= 1)
			{
				audioElement.audio.volume = percentage;
			}	
		});


		$(document).mouseup(function(){
			mouseDown = false;
		});
 
 
	});

	function timeFromOffset(mouse, progressBar)
	{
		var percentage = mouse.offsetX / $(progressBar).width() * 100;
		var seconds = audioElement.audio.duration * (percentage / 100);
		audioElement.setTime(seconds);
	}

	function nextSong() {
		if (repeat)
		{
			audioElement.setTime(0);
			playSong();
			return;
		}
		if(currentIndex == currentPlaylist.length - 1)     // if index is last index
		{
			currentIndex = 0;                             // go back to the start
		}
		else
		{
			currentIndex++;
		}
		var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
		setTrack(trackToPlay, currentPlaylist, true);
	}

	function prevSong(){
		if(audioElement.audio.currentTime >= 3 || currentIndex == 0)
		{
			audioElement.setTime(0);     // will just go back to start of song
		}
		else
		{
			currentIndex = currentIndex - 1;
			setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
		}
	}

	function setRepeat()
	{
		if(repeat)
		{
			repeat = false;
		}
		else
		{
			repeat = true;
		}
		var iconName = repeat ? "repeat-active.png" : "repeat.png";
		$(".controlButton.repeat img").attr("src", "assets/images/icons/" + iconName);
	}

	function setMute()
	{
		if(audioElement.audio.muted)
		{
			audioElement.audio.muted = false;
		}
		else
		{
			audioElement.audio.muted = true;
		}
		var iconName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
		$(".controlButton.volume img").attr("src", "assets/images/icons/" + iconName);
	}

	function setShuffle()
	{
		if(shuffle)
		{
			shuffle = false;
		}
		else
		{
			shuffle = true;
		}
		var iconName = shuffle ? "shuffle-active.png" : "shuffle.png";
		$(".controlButton.shuffle img").attr("src", "assets/images/icons/" + iconName);

		if(shuffle)   
		{
			// randomize the playlist
			shuffleArray(shufflePlaylist);
			currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);   // record the index so we have it when new playlist gets created from shuffling so we don't play the same song twice
		}
		else
		{
			// shuffle is turned off so use regular playlist
			currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
		}
	}

	function shuffleArray(arr)
	{
		var j, x, i;
		for(i = arr.length; i; i--)
		{
			j = Math.floor(Math.random() * i);
			x = arr[i - 1];
			arr[i - 1] = arr[j];
			arr[j] = x;
		}
	}

	function setTrack(trackId, newPlaylist, play)   // newPlaylist is the playlist that gets created when switching to another album
	{
		if (newPlaylist != currentPlaylist)
		{
			currentPlaylist = newPlaylist;
			shufflePlaylist = currentPlaylist.slice();   // returns a copy of currentPlaylist
			shuffleArray(shufflePlaylist);
		}

		if (shuffle)
		{
			currentIndex = shufflePlaylist.indexOf(trackId);
		}
		else
		{
			currentIndex = currentPlaylist.indexOf(trackId);
		}

		pauseSong();

		$.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

			var track = JSON.parse(data);

			$(".trackName span").text(track.title);

			$.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
				var artist = JSON.parse(data);
				$(".artistName span").text(artist.name);
				$(".artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
			});

			$.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
				var album = JSON.parse(data);
				$(".albumLink img").attr("src", album.artworkLocation);
				$(".albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
				$(".trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
			});

			audioElement.setTrack(track);
 
			if (play)
			{
				playSong();
			}
		});

	}

	function playSong()
	{
		// update number of plays only when song is being played from the beginning

		if(audioElement.audio.currentTime == 0)
		{
			$.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
		}

		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
		audioElement.play();
	}

	function pauseSong()
	{
		$(".controlButton.play").show();
		$(".controlButton.pause").hide();
		audioElement.pause();
	}
</script>




<div id="playerContainer">
	<div id="player">
		<div id="player_left_side">
			<div class="content">
				<span class="albumLink">
					<img role="link" tabindex="0" src="" class="albumArtwork">
				</span>

				<div class="trackInfo">
					<span class="trackName">
						<span role="link" tabindex="0"></span>
					</span>
					<span class="artistName">
						<span role="link" tabindex="0"></span>
					</span>
				</div>

			</div>
		</div>


		<div id="player_center">
			<div class="content playerControls">
				<div class="buttons">

					<button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle">
					</button>

					<button class="controlButton previous" title="Previous button" onclick="prevSong()">
						<img src="assets/images/icons/previous.png" alt="Previous">
					</button>

					<button class="controlButton play" title="Play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>

					<button class="controlButton pause" title="Pause button" style="display:none;" onclick="pauseSong()">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>

					<button class="controlButton next" title="Next button" onclick="nextSong()">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>

					<button class="controlButton repeat" title="Repat button" onclick="setRepeat()">
						<img src="assets/images/icons/repeat.png" alt="Repeat">
					</button>

				</div>
				<div class="playbackBar">
					<span class="progressTime current">0.00</span>
					<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
					</div>
					<span class="progressTime remaining">0.00</span>
				</div>
			</div>
		</div>


		<div id="player_right_side">
			<div class="volumeBar">
				<button class="controlButton volume" title="volume button" onclick="setMute()">
					<img src="assets/images/icons/volume.png" alt="Volume">
				</button>

				<div class="progressBar">
						<div class="progressBarBg">
							<div class="progress"></div>
						</div>
				</div>	
			</div>		
		</div>				
	</div>
</div>	