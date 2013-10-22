<?php
if (!isset($content_width))
	$content_width = 1060;

function immanis_setup() {
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');

	add_theme_support('structured-post-formats', array(
		'link', 'video'
	));
	add_theme_support('post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'quote', 'status'
	));

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu('primary', __('Navigation Menu', 'immanis'));

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(1920, 943, true);

	// Register custom image size for image post formats.
	add_image_size('immanis-image', 1060, 560, true);

	// This theme uses its own gallery styles.
	add_filter('use_default_gallery_style', '__return_false');
}

add_action('after_setup_theme', 'immanis_setup');

function immanis_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support sites with
	 * threaded comments (when in use).
	 */
	if (is_singular() && comments_open() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');

	wp_enqueue_script('immanis-script', get_template_directory_uri().'/js/functions.js', array('jquery'), '20130416', true);

	wp_enqueue_style('google-lato', 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic');
	wp_enqueue_style('google-pt-serif', 'http://fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic');

	// Loads our main stylesheet.
	wp_enqueue_style('immanis-style', get_stylesheet_uri());

	wp_deregister_script('wp-mediaelement');
	wp_enqueue_script('immanis-mediaelement', get_template_directory_uri().'/js/immanis-mediaelement.js', array('mediaelement'), '20130508', true);
	wp_enqueue_style('wp-mediaelement');

	if (!is_singular() && 'infinite-scroll' == get_theme_mod('immanis_page_navigation'))
		wp_enqueue_script('infinite-scroll', get_template_directory_uri().'/js/jquery.infinitescroll.min.js', array('jquery'), '2.0b.110415', true);
}

add_action('wp_enqueue_scripts', 'immanis_scripts_styles');

/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 */
function immanis_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed())
		return $title;

	// Add the site name.
	$title .= get_bloginfo('name');

	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page()))
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ($paged >= 2 || $page >= 2)
		$title = "$title $sep ".sprintf(__('Page %s', 'immanis'), max($paged, $page));

	return $title;
}

add_filter('wp_title', 'immanis_wp_title', 10, 2);

function immanis_widgets_init() {
	register_sidebar(array(
		'name' => __('Footer Widget Area 1', 'immanis'),
		'id' => 'sidebar-1',
		'description' => __('Appears in the left side of the footer', 'immanis'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => __('Footer Widget Area 2', 'immanis'),
		'id' => 'sidebar-2',
		'description' => __('Appears in the center of the footer', 'immanis'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => __('Footer Widget Area 3', 'immanis'),
		'id' => 'sidebar-3',
		'description' => __('Appears in the right side of the footer', 'immanis'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}

add_action('widgets_init', 'immanis_widgets_init');

if (!function_exists('immanis_paging_nav')):
/**
 * Displays navigation to next/previous set of posts when applicable.
 */
function immanis_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ($wp_query->max_num_pages < 2 && (is_home() || is_archive() || is_search()))
		return;
	?>

	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e('Posts navigation', 'immanis'); ?></h1>
		<div class="nav-links">
	<?php if ('ajax-fetch' == get_theme_mod('immanis_page_navigation')): ?>
			<div class="load-more">
				<?php next_posts_link(__('<span class="img loader"></span><span class="img loader2"></span><span class="text">Load more posts</span>', 'immanis')); ?>
			</div>
			<script type="text/javascript">
				jQuery(function() {
					jQuery('.load-more').on('click', 'a', function(e) {
						e.preventDefault();
						var link = jQuery(this);
						link.addClass('loading').find('.text').text('Loading...');
						jQuery.ajax({
							type: 'GET',
							url: link.attr('href') + '#content',
							dataType: 'html',
							success: function(out) {
								result = jQuery(out).find('#content .hentry');
								nextLink = jQuery(out).find('.load-more a').attr('href');
								var nav = jQuery('.paging-navigation');
								result.each(function() {
									jQuery(this).insertBefore(nav);
								});
								if (undefined != nextLink) {
									link.removeClass('loading').attr('href', nextLink).find('.text').text('Load more posts');
								}
								else {
									nav.remove();
								}
								fixLinks();
								fixVideos();
								fixAudios();
								result.find('.wp-video-shortcode').mediaelementplayer();
								result.find('.wp-audio-shortcode').mediaelementplayer({
									audioHeight: 78,
									startVolume: 0.8,
									features: ['playpause', 'progress', 'tracks']
								});
								result.each(function() {
									var item = jQuery(this);
									if (item.hasClass('format-gallery')) {
										var gallery = new wpshowerGallery('#' + item.attr('id'));
									}
								});
							}
						});
					});
				});
			</script>
	<?php else: ?>
		<?php if (get_next_posts_link()): ?>
			<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">‹ </span>Older posts', 'immanis')); ?></div>
		<?php endif; ?>

		<?php if (get_previous_posts_link()): ?>
			<div class="nav-next"><?php previous_posts_link(__('Newer posts<span class="meta-nav"> ›</span>', 'immanis')); ?></div>
		<?php endif; ?>
	<?php endif; ?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->

	<?php
}
endif;

/**
 * Infinite scroll
 */
function immanis_infinite_scroll_js() {
	if (is_singular() || 'infinite-scroll' != get_theme_mod('immanis_page_navigation'))
		return;
	?>
	<script type="text/javascript">
		jQuery(function() {
			var infinite_scroll = {
				loading: {
					img: "<?php echo get_stylesheet_directory_uri(); ?>/images/loadmore.svg",
					msgText: "",
					finishedMsg: "<?php _e( 'The End', 'immanis' ); ?>"
				},
				'nextSelector': '.paging-navigation .nav-previous a',
				'navSelector': '.paging-navigation',
				'itemSelector': '.hentry',
				'contentSelector': '#content'
			};
			jQuery(infinite_scroll.contentSelector).infinitescroll(infinite_scroll, function(arrayOfNewElems) {
				fixLinks();
				fixVideos();
				fixAudios();
				var items = jQuery(arrayOfNewElems);
				items.find('.wp-video-shortcode').mediaelementplayer();
				items.find('.wp-audio-shortcode').mediaelementplayer({
					audioHeight: 78,
					startVolume: 0.8,
					features: ['playpause', 'progress', 'tracks']
				});
				items.each(function() {
					var item = jQuery(this);
					if (item.hasClass('format-gallery')) {
						var gallery = new wpshowerGallery('#' + item.attr('id'));
					}
				});
			});
		});
	</script>
<?php
}

add_action('wp_footer', 'immanis_infinite_scroll_js', 100);

/**
 * Adds body class for infinite scroll
 */
function immanis_body_class($class) {
	if ('infinite-scroll' == get_theme_mod('immanis_page_navigation'))
		$class[] = 'infinite-scroll';

	return $class;
}

add_filter('body_class', 'immanis_body_class');

/**
 * Displays navigation to next/previous post when applicable.
 */
if (!function_exists('immanis_post_nav')):
function immanis_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
	$next = get_adjacent_post(false, '', false);

	if (!$next && !$previous)
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e('Post navigation', 'immanis'); ?></h1>
		<div class="nav-links">

			<div class="nav-previous"><?php previous_post_link('%link', _x('<span class="meta-nav">‹ Older post</span>%title', 'Previous post link', 'immanis')); ?></div>
			<div class="nav-next"><?php next_post_link('%link', _x('<span class="meta-nav">Newer post ›</span>%title', 'Next post link', 'immanis')); ?></div>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if (!function_exists('immanis_entry_meta')):
function immanis_entry_meta() {
	if (is_sticky() && is_home() && !is_paged())
		echo '<span class="featured-post">'.__('Sticky', 'immanis').'</span> ';

	if (!has_post_format('link') && 'post' == get_post_type())
		immanis_entry_date();

	// Translators: used between list items, there is a space after the comma.
	/*$categories_list = get_the_category_list( __( ', ', 'immanis' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}*/
}
endif;

function immanis_tags() {
	if (!is_single()) return;

	$tag_list = get_the_tag_list('', __(', ', 'immanis'));
	if ($tag_list) {
		echo '<div class="entry-tags">Tags: <span class="tags-links">'.$tag_list.'</span></div>';
	}
}

if (!function_exists('immanis_entry_date')):
function immanis_entry_date($echo = true) {
	$format_prefix = (has_post_format('chat') || has_post_format('status')) ? _x('%1$s on %2$s', '1: post format name. 2: date', 'immanis') : '%2$s';

	$date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url(get_permalink()),
		esc_attr(sprintf(__('Permalink to %s', 'immanis'), the_title_attribute('echo=0'))),
		esc_attr(get_the_date('c')),
		esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()), get_the_date()))
	);

	if ($echo)
		echo $date;

	return $date;
}
endif;

/**
 * Url for "link" posts
 */
function immanis_get_link_url() {
	$has_url = get_the_post_format_url();

	return ($has_url) ? $has_url : apply_filters('the_permalink', get_permalink());
}

/**
 * Switches default core markup for search form to output valid HTML5.
 */
function immanis_searchform_format($format) {
	return 'html5';
}

add_filter('search_form_format', 'immanis_searchform_format');

/**
 * Adds support for a custom header image.
 */
require(get_template_directory().'/inc/custom-header.php');

/**
 * Customizer additions
 */
require(get_template_directory().'/inc/customizer.php');

/**
 * Adds back compat handling for WP versions pre-3.6.
 */
if (version_compare($GLOBALS['wp_version'], '3.6-alpha', '<'))
	require(get_template_directory().'/inc/back-compat.php');

/**
 * Custom html for comments
 */
function immanis_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;

	$tag = ('div' === $args['style']) ? 'div' : 'li';
	?>

	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size']); ?>
				</div><!-- .comment-author -->

				<div class="reply">
					<?php comment_reply_link(array_merge($args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div><!-- .reply -->
			</footer><!-- .comment-meta -->

			<div class="comment-wrapper">
				<?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>

				<div class="comment-metadata">
					<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
						<time datetime="<?php comment_time('c'); ?>">
							<?php printf(_x('%1$s at %2$s', '1: date, 2: time'), get_comment_date(), get_comment_time()); ?>
						</time>
					</a>
					<?php edit_comment_link(__('Edit'), '<span class="edit-link">', '</span>'); ?>
				</div><!-- .comment-metadata -->

				<?php if ('0' == $comment->comment_approved): ?>
				<p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></p>
				<?php endif; ?>
				
				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
			</div>

			<div class="clear"></div>
		</article><!-- .comment-body -->

	<?php
}

/**
 * Adds class to menu items that have submenus
 */
function wpshower_menu_parent_class($items) {
	$parents = array();
	foreach ($items as $item) {
		if ($item->menu_item_parent && $item->menu_item_parent > 0) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ($items as $item) {
		if (in_array($item->ID, $parents)) {
			$item->classes[] = 'submenu-off';
		}
	}

	return $items;
}

add_filter('wp_nav_menu_objects', 'wpshower_menu_parent_class');

/**
 * Removes galleries from post content
 */
remove_shortcode('gallery');
add_shortcode('gallery', array('wpShower', 'catchGallery'));

class wpShower {
	private static $galleries = array();

	public static function catchGallery($attr) {
		self::$galleries[] = $attr;
	}

	public static function getGalleries() {
		$results = self::$galleries;
		self::$galleries = array();
		return $results;
	}

	public static function setCache($key, $value) {
		set_theme_mod('wpshower_cache_'.$key, time().$value);
	}

	public static function getCache($key, $duration) {
		$cache = get_theme_mod('wpshower_cache_'.$key);
		if ($cache === false)
			return false;

		$time = substr($cache, 0, 10);
		if (intval($time) + $duration > time())
			return substr($cache, 10);

		return false;
	}
}

/**
 * Share links
 */
function immanis_share() {
	if (!is_single()) return;
	?>

	<div class="sharing">
		<span class="text">Share</span>

		<div class="button">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-width="170" data-show-faces="false" layout="button_count"></div>
		</div>

		<div class="button">
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>

		<div class="button">
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium"></div>
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
		</div>

		<div class="button">
			<a href="//pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
		</div>
	</div>

	<?php
}

/**
 * Latest posts from Twitter
 */
function twitter_feed($username) {
	$url = 'http://api.twitter.com/1/statuses/user_timeline/'.$username.'.json?count=1';
	$json = file_get_contents($url);
	if ($json == false) return false;

	$result = json_decode($json);
	return $result;
}

function immanis_twitter() {
	$enabled = get_theme_mod('immanis_twitter_enabled');
	if ($enabled === false || $enabled == 0) return;

	$username = get_theme_mod('immanis_twitter_username');
	if ($username === false || $username == '') return;

	$cache_duration = get_theme_mod('immanis_twitter_cache');
	if ($cache_duration !== false && $cache_duration != 0) {
		$cache = wpShower::getCache('immanis_twitter', $cache_duration * 60);
		if ($cache != false) {
			echo $cache;
			return;
		}
	}

	$result = twitter_feed($username);
	if ($result == false) return;

	$html = '<article class="format-twitter">
		<div class="article-wrapper">
			<header class="entry-header">
				<span></span>
			</header><!-- .entry-header -->

			<div class="entry-wrapper">
				<div class="entry-content">
					<a href="http://twitter.com/'.$result[0]->user->screen_name.'">@'.$result[0]->user->screen_name.'</a>
					'.$result[0]->text.'
				</div><!-- .entry-content -->
			</div>

			<footer class="entry-meta">
				<span class="date">'.date('F d, Y', strtotime($result[0]->created_at)).'</span>
				<span>
					<a href="http://twitter.com/'.$result[0]->user->screen_name.'">Follow us on Twitter</a>
				</span>
			</footer><!-- .entry-meta -->

			<div class="clear"></div>
		</div>
	</article><!-- #post -->';

	if ($cache_duration !== false && $cache_duration != 0)
		wpShower::setCache('immanis_twitter', $html);

	echo $html;
}
