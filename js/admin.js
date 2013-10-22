(function($) {
	function immanisPostType() {
		$('#immanis_big_video_box').hide();
		$('#immanis_big_audio_box').hide();
		if ($('#post-format-video:checked').length > 0) {
			$('#immanis_big_video_box').show();
		}
		else if ($('#post-format-audio:checked').length > 0) {
			$('#immanis_big_audio_box').show();
		}
	}

	$(function() {
		immanisPostType();
		$('input[name="post_format"]').on('change', immanisPostType);
	});
})(jQuery);
