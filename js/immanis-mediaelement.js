(function ($) {
	// add mime-type aliases to MediaElement plugin support
	mejs.plugins.silverlight[0].types.push('video/x-ms-wmv');
	mejs.plugins.silverlight[0].types.push('audio/x-ms-wma');

	$(function () {
		$('.wp-video-shortcode').mediaelementplayer();
		$('.wp-audio-shortcode').mediaelementplayer({
			audioHeight: 78,
			startVolume: 0.8,
			features: ['playpause','progress','tracks']
		});
	});

}(jQuery));
