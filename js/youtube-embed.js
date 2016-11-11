(function($, window){
	/**
	 * Create a script element to load in the YouTube iFrame API and insert it
	 * into the document.
	 */
	loadYoutube = function() {
		var tag = document.createElement('script');

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	};

	/**
	 * Callback function expected by the YouTube Iframe API. Without a function
	 * with this name available in the global space, our use of the YouTube API
	 * does not work.
	 *
	 * Loop through each of the inline YouTube Videos, gather the video information,
	 * and set up objects representing the videos.
	 */
	window.onYouTubeIframeAPIReady = function() {
		$('.inline-youtube-video').each(function(){
			var video_id = $(this).data('video-id'),
				video_height = $(this).data('video-height'),
				video_width = $(this).data('video-width');

			player = new YT.Player( 'youtube-video-' + video_id, {
				height: video_height,
				width: video_width,
				videoId: video_id,
				playerVars: {
					modestbranding: 1,
					showinfo: 0,
					controls: 0,
					rel: 0
				},
				events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerEnded
				}
			});

			$('.stop-' + video_id).on('click', function() {
				player.stopVideo();
				closeVideo('youtube-video-' + video_id);
			})
		});
	};

	/**
	 * Callback function expected by the YouTube iFrame API based on our specification
	 * in the events data above. We use this to attach a click event to any text with
	 * a class of `start-{video_id}`. This allows initial interaction with the video to
	 * begin inside the document.
	 *
	 * @param event
	 */
	window.onPlayerReady = function(event) {
		var video_id = event.target.h.id;

		$('.start-' + video_id).on('click', function() {
			$('#' + video_id).closest('.column').addClass('playing');
			event.target.playVideo();
		});
	};

	/**
	 * Return to initial state when the video has ended.
	 */
	window.onPlayerEnded = function(event) {
		if ( 0 == event.data ) {
			closeVideo(event.target.h.id);
		}
	}

	/**
	 * Remove the `playing` class to send the video back from whence it came.
	 */
	closeVideo = function(video_id) {
		$('#' + video_id).closest('.column').removeClass('playing');
	}

	/**
	 * Fire any actions that we need to happen once the document is ready.
	 */
	$(document).ready(function() {
		loadYoutube();
	});

})(jQuery, window);
