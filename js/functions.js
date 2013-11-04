function fixLinks() {
	jQuery('.entry-content a').each(function() {
		var item = jQuery(this);
		var img = item.find('img');
		if (img.length === 1) {
			item.addClass('image_link');
		}
	});
}

function getRatio(item, type) {
	var video = item.find(type);
	if (video.length === 1) {
		var width = video.attr('width');
		var height = video.attr('height');
		if (typeof width !== 'undefined' && width !== false && typeof height !== 'undefined' && height !== false) {
			var ratio = parseInt(height) / parseInt(width) * 100;
			return ratio.toFixed(2);
		}
	}
	return 0;
}

function fixAudios() {
	jQuery('.wp-audio-shortcode').each(function() {
		var item = jQuery(this);
		item.parent().next().hide();
		item.next().show();
	});
}

function wpshowerGallery(selector) {
	this.thumbs = 1;
	this.selector = selector;
	this.total_thumbs = jQuery(this.selector + ' td').length;
	this.thumb_width = 1110;

	this.margin = 0;
	this.td = 0;
	this.active = false;
	this.current = 0;
	this.all_thumbs = 0;

	this.setCurrent = function(steps) {
		this.current += steps;
		if (this.current < 0) this.current = this.total_thumbs - 1;
		else if (this.current >= this.total_thumbs) this.current -= this.total_thumbs;
		jQuery(this.selector + ' .bullets span').removeClass('current');
		jQuery(this.selector + ' .bullets span:eq(' + this.current + ')').addClass('current');
	}

	this.animateMargin = function(margin) {
		var obj = this;
		jQuery(this.selector + ' table').animate({
			'marginLeft': margin + '%'
		}, function() {
			obj.active = false;
		});
	}

	this.caption = function() {
		var txt = jQuery(this.selector + ' td:eq(' + this.td + ')').attr('data-caption');
		jQuery('.gallery-caption').fadeOut(200, function() {
			jQuery(this).text(txt).fadeIn(200);
		});
	}

	this.prevMove = function() {
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 0.3
		});

		jQuery(this.selector + ' td:eq(' + (this.all_thumbs - 1) + ')').insertBefore(
			jQuery(this.selector + ' td:eq(0)')
		);
		this.margin -= 100;
		jQuery(this.selector + ' table').css('marginLeft', this.margin + '%');

		this.caption();
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 1
		});

		this.margin += 100;
		this.animateMargin(this.margin);
	}

	this.nextMove = function() {
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 0.3
		});

		jQuery(this.selector + ' td:eq(0)').insertAfter(
			jQuery(this.selector + ' td:eq(' + (this.all_thumbs - 1) + ')')
		);
		this.margin += 100;
		jQuery(this.selector + ' table').css('marginLeft', this.margin + '%');

		this.caption();
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 1
		});

		this.margin -= 100;
		this.animateMargin(this.margin);
	}

	this.nextMoveBy = function(num) {
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 0.3
		});

		for (var i = 0; i < num; i++) {
			jQuery(this.selector + ' td:eq(0)').insertAfter(
				jQuery(this.selector + ' td:eq(' + (this.all_thumbs - 1) + ')')
			);
			this.margin += 100;
			jQuery(this.selector + ' table').css('marginLeft', this.margin + '%');
		}

		this.caption();
		jQuery(this.selector + ' td:eq(' + this.td + ')').animate({
			'opacity': 1
		});

		this.margin -= 100 * num;
		this.animateMargin(this.margin);
	}

	this.urlClick = function(e, item) {
		if (this.active == true) {
			e.preventDefault();
			return;
		}

		var index = item.index();
		if (index == this.td) return;

		this.active = true;
		e.preventDefault();
		if (index < this.td) {
			this.setCurrent(-1);
			this.prevMove();
		}
		else {
			this.setCurrent(1);
			this.nextMove();
		}
	}

	this.init = function() {
		if (this.total_thumbs == 0) return;

		// additional items to the left
		var additional_items = Math.ceil(((jQuery(window).width() - this.thumb_width * this.thumbs) / 2) / this.thumb_width) + 1;
		var additional_loops = Math.ceil(additional_items / this.total_thumbs);
		for (var i = 0; i < additional_loops; i++) {
			for (var j = 0; j < this.total_thumbs; j++) {
				jQuery(this.selector + ' td:eq(' + (this.total_thumbs - 1) + ')').clone().insertBefore(
					jQuery(this.selector + ' td:eq(0)')
				);
				this.margin -= 100;
				this.td++;
			}
		}

		// additional items to the right - if needed
		if (this.total_thumbs < this.thumbs + additional_items) {
			additional_loops = Math.ceil((this.thumbs + additional_items - this.total_thumbs) / this.total_thumbs);
			for (i = 0; i < additional_loops; i++) {
				for (j = 0; j < this.total_thumbs; j++) {
					jQuery(this.selector + ' td:eq(' + j + ')').clone().insertAfter(
						jQuery(this.selector + ' td:eq(' + (this.total_thumbs + this.td + j - 1) + ')')
					);
				}
			}
		}

		// bullets added for each photo
		for (i = 0; i < this.total_thumbs; i++) {
			jQuery(this.selector + ' .bullets').append('<span data-num="' + i + '">&#8226;</span>');
		}

		// initial position
		this.all_thumbs = jQuery(this.selector + ' td').length;
		jQuery(this.selector + ' table').css({
			'marginLeft': this.margin + '%',
			'width': 100 * this.all_thumbs + '%'
		});

		jQuery(this.selector + ' td').filter(":lt(" + this.td + "), :gt(" + (this.td + this.thumbs - 1) + ")").each(function() {
			jQuery(this).css('opacity', '0.3');
		});

		this.caption();

		// events
		var obj = this;
		jQuery(this.selector + ' .gallery-prev').click(function(e) {
			e.preventDefault();
			if (!obj.active) {
				obj.active = true;
				obj.setCurrent(-1);
				obj.prevMove();
			}
		});

		jQuery(this.selector + ' .gallery-next').click(function(e) {
			e.preventDefault();
			if (!obj.active) {
				obj.active = true;
				obj.setCurrent(1);
				obj.nextMove();
			}
		});

		jQuery(this.selector + ' .bullets span').click(function() {
			if (!obj.active) {
				obj.active = true;

				var num = parseInt(jQuery(this).attr('data-num'));
				if (num == obj.current) {
					obj.active = false;
					return;
				}
				if (num < obj.current) {
					num += obj.total_thumbs;
				}
				num -= obj.current;
				obj.setCurrent(num);
				obj.nextMoveBy(num);
			}
		});

		jQuery(this.selector + ' td a').click(function(e) {
			obj.urlClick(e, jQuery(this).parent());
		});
	}

	this.init();
	this.setCurrent(0);
}

(function($) {
	/**
	 * Enables menu toggle for small screens.
	 */
	var nav = $('#navbar'),
		button = nav.find('.menu-toggle'),
		search = nav.find('.mobile-search');
	menu = nav.find('.nav-menu');

	if (!menu || !menu.children().length) {
		button.hide();
	}

	button.on('click', function() {
		nav.removeClass('toggled-search');
		nav.toggleClass('toggled-on');
	});

	search.on('click', function() {
		nav.removeClass('toggled-on');
		nav.toggleClass('toggled-search');
	});

	fixLinks();
	jQuery('.entry-video').wpShowerResponsiveVideos();
	fixAudios();

	/**
	 * Parallax
	 */
	var parallax_width = 900;
	var scroll_inited = false;

	function initScroll() {
		if (!scroll_inited && $(window).width() >= parallax_width) {
			scroll_inited = true;
			$(document).scroll(function() {
				scrolly();
			});
		}
	}

	function scrolly() {
		var position = $(window).scrollTop() * -0.2;
		$('.site-header').css({backgroundPosition: '0 ' + position + 'px'});
	};

	initScroll();

	$(window).resize(function() {
		initScroll();
	});

	/**
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	$(window).on('hashchange', function() {
		var element = $(location.hash);

		if (element) {
			if (!/^(?:a|select|input|button)$/i.test(element.tagName))
				element.attr('tabindex', -1);

			element.focus();
		}
	});
})(jQuery);
