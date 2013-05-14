<?php
/**
 * Implements a custom header
 * See http://codex.wordpress.org/Custom_Headers
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 */
function immanis_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
}
add_action( 'customize_register', 'immanis_customize_register' );

/**
 * Binds JavaScript handlers to make Customizer preview reload changes
 * asynchronously.
 */
function immanis_customize_preview_js() {
	wp_enqueue_script( 'immanis-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130226', true );
}
add_action( 'customize_preview_init', 'immanis_customize_preview_js' );

/**
 * Sets up the WordPress core custom header arguments and settings.
 */
function immanis_custom_header_setup() {
	$args = array(
		'height' => 943,
		'width' => 1920,
		'header-text' => false,
		'wp-head-callback' => 'immanis_header_style',
		'admin-preview-callback' => 'immanis_admin_header_image',
	);

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'immanis_custom_header_setup' );

/**
 * Styles the header text displayed on the blog.
 */
function immanis_header_style() {
	$header_image = get_header_image();
	?>
	<style type="text/css" id="immanis-header-css">
	<?php if ( is_single() ) : ?>
		<?php
		$src = wp_get_attachment_image_src ( get_post_thumbnail_id(), 'post-thumbnail' );
		if ( $src != null && !is_attachment() ) :
		?>
		.site-header {
			background: url(<?php echo $src[0]; ?>) no-repeat top center fixed;
			background-size: 100% auto;
		}
		.site-header hgroup {
			background: url(<?php echo $src[0]; ?>) no-repeat top center;
			background-size: 100% auto;
		}
		@media only screen and (max-width: 900px) {
			.site-header hgroup {
				display: block;
				padding-bottom: 40%;
			}
		}
		<?php else : ?>
		.site-header {
			height: auto !important;
		}
		<?php endif; ?>
	<?php elseif ( ! empty( $header_image ) ) : ?>
		.site-header {
			background: url(<?php header_image(); ?>) no-repeat top center fixed;
			background-size: 100% auto;
		}
		.site-header hgroup {
			background: url(<?php header_image(); ?>) no-repeat top center;
			background-size: 100% auto;
		}
		@media only screen and (max-width: 900px) {
			.site-header hgroup {
				display: block;
				padding-bottom: 40%;
			}
		}
	<?php else : ?>
		.site-header {
			height: auto !important;
		}
	<?php endif; ?>
	</style>
	<?php
}

/**
 * Outputs markup to be displayed on the Appearance > Header admin panel.
 * This callback overrides the default markup displayed there.
 */
function immanis_admin_header_image() {
	$header_image = get_header_image();
	?>
	<div id="headimg" style="background: url(<?php echo $header_image; ?>) 0 0 no-repeat; background-size: 100% auto; <?php if ( $header_image ) echo ' padding-bottom: 49%;'; ?> border: none;"></div>
<?php }
