
var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;

function openPage(url)
{
	if (url.indexOf("?") == -1)        // if there is no ? in the url
	{
		url = url + "?";
	}
	var encoded_url = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encoded_url);
	// $("body").scrollTop(0);    // scroll to top of page when changing page
	history.pushState(null, null, url);    // change url when changing page
}


function formatTime(time_in_seconds)
{
	var time = Math.round(time_in_seconds);
	var minutes = Math.floor(time / 60);
	var seconds = time - (minutes * 60);

	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}


function updateTimeProgressBar(audio)
{
	$(".progressTime.current").text(formatTime(audio.currentTime));      // start time increases
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));     // end time decreases

	var barProgress = audio.currentTime / audio.duration * 100;     // progress for the player bar 
	$(".playbackBar .progress").css("width", barProgress + "%"); 
}

function updateVolumeProgressBar(audio)
{
	var volumeBarProgress = audio.volume * 100;     // progress for the volume bar 
	$(".volumeBar .progress").css("width", volumeBarProgress + "%"); 
}

function playFirstSong()
{
	setTrack(tempPlaylist[0], tempPlaylist, true);
}


function Audio()
{
	this.currentlyPlaying;
	this.audio = document.createElement('audio');       // 'audio' is a built-in audio element

	this.audio.addEventListener("ended", function(){
		nextSong();
	});

	this.audio.addEventListener("canplay", function() {
		// 'this' refers to the object that the event was called on  (i.e: the audio object)
		var duration = formatTime(this.duration);
		$(".progressTime.remaining").text(duration);
	});

	this.audio.addEventListener("timeupdate", function(){
		if(this.duration)
		{
			updateTimeProgressBar(this);
		}
	});

	this.audio.addEventListener("volumechange", function(){
		updateVolumeProgressBar(this);
	})

	this.setTrack = function(track) {
		this.currentlyPlaying = track;      // overwrites previously playing track with the new track 
		this.audio.src = track.songLocation;
	}

	this.play = function()
	{
		this.audio.play();
	}

	this.pause = function()
	{
		this.audio.pause();
	}

	this.setTime = function(seconds)
	{
		this.audio.currentTime = seconds;
	}
}