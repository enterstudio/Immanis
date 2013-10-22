<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title('|', true, 'right'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<script type="text/javascript">
		WebFontConfig = {
			google: { families: ['Lato:400'] },
			fontactive: function() {
				jQuery(function() {
					var nav = jQuery('#site-navigation');
					var lis = nav.find('> div > ul > li');
					lis.each(function() {
						var li = jQuery(this);
						var submenu = li.find('> ul');
						if (submenu.length == 1) {
							submenu.css('left', Math.round((li.width() - submenu.width()) / 2))
								.css('display', 'none')
								.css('visibility', 'visible');
						}
					});
				});
			}
		};
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	</script>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header" role="banner">
			<div id="navbar" class="navbar">
				<h1 class="site-title">
					<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a>.
				</h1>

				<div id="header-search">
					<h3 class="mobile-search"></h3>
					<h3 class="menu-toggle">m</h3>
					<?php get_search_form(); ?>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e('Skip to content', 'immanis'); ?>"><?php _e('Skip to content', 'immanis'); ?></a>
				</div>

				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu')); ?>
				</nav><!-- #site-navigation -->

				<div class="clear"></div>
			</div><!-- #navbar -->

			<hgroup></hgroup>
		</header><!-- #masthead -->

		<div id="main" class="site-main">
