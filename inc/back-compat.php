<?php
/**
 * Prevent switching to Immanis on old versions of WordPress. Switches
 * to the previously activated theme or the default theme.
 *
 * @param string $theme_name
 * @param WP_Theme $theme
 * @return void
 */
function immanis_switch_theme($theme_name, $theme) {
	if ('immanis' != $theme->template)
		switch_theme($theme->template, $theme->stylesheet);
	elseif ('immanis' != WP_DEFAULT_THEME)
		switch_theme(WP_DEFAULT_THEME);

	unset($_GET['activated']);
	add_action('admin_notices', 'immanis_upgrade_notice');
}

add_action('after_switch_theme', 'immanis_switch_theme', 10, 2);

/**
 * Prints an update nag after an unsuccessful attempt to switch to
 * Immanis on WordPress versions prior to 3.6.
 *
 * @return void
 */
function immanis_upgrade_notice() {
	$message = sprintf(__('Immanis requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'immanis'), $GLOBALS['wp_version']);
	printf('<div class="error"><p>%s</p></div>', $message);
}
