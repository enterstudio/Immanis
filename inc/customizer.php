<?php
/**
 * Theme Customizer
 */

function immanis_customizer($wp_customize) {
	// Social networks
	$wp_customize->remove_control('blogdescription');

	$wp_customize->add_section('immanis_social', array(
		'title' => __('Social networks', 'immanis'),
		'priority' => 95,
		'capability' => 'edit_theme_options',
		'description' => __('Allows you to customize social network links.', 'immanis')
	));
	$array = array(
		'twitter' => 'Twitter',
		'facebook' => 'Facebook',
		'instagram' => 'Instagram',
		'pinterest' => 'Pinterest',
		'dribbble' => 'Dribbble',
		'google' => 'Google+',
		'vimeo' => 'Vimeo',
		'flickr' => 'Flickr',
		'rss' => 'RSS'
	);
	$i = 0;
	foreach ($array as $key => $value) {
		$i++;
		$wp_customize->add_setting('immanis_social['.$key.']', array(
			'default' => '',
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage'
		));
		$wp_customize->add_control('immanis_social['.$key.']', array(
			'label' => __($value, 'immanis'),
			'section' => 'immanis_social',
			'type' => 'text',
			'priority' => $i
		));
	}

	// Page navigation
	$wp_customize->add_section('page_navigation', array(
		'title' => __('Page Navigation', 'immanis'),
		'priority' => 100,
	));
	$wp_customize->add_setting('immanis_page_navigation', array(
		'default' => 'standard',
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('immanis_page_navigation', array(
		'section' => 'page_navigation',
		'type' => 'select',
		'choices' => array(
			'standard' => __('Standard', 'immanis'),
			'ajax-fetch' => __('Load More Button', 'immanis'),
			'infinite-scroll' => __('Infinite Scroll', 'immanis'),
		),
	));

	// Twitter posts
	$wp_customize->add_section('twitter_latest', array(
		'title' => __('Latest post from Twitter', 'immanis'),
		'priority' => 900,
	));
	$wp_customize->add_setting('immanis_twitter_enabled', array(
		'default' => 1,
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_setting('immanis_twitter_username', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_setting('immanis_twitter_cache', array(
		'default' => 30,
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('immanis_twitter_enabled', array(
		'label' => __('Enabled', 'immanis'),
		'section' => 'twitter_latest',
		'type' => 'checkbox',
		'priority' => 0
	));
	$wp_customize->add_control('immanis_twitter_username', array(
		'label' => __('Username', 'immanis'),
		'section' => 'twitter_latest',
		'type' => 'text',
		'priority' => 1
	));
	$wp_customize->add_control('immanis_twitter_cache', array(
		'label' => __('Cache', 'immanis'),
		'section' => 'twitter_latest',
		'type' => 'select',
		'choices' => array(0 => 'Disabled', 5 => '5 min.', 10 => '10 min.', 30 => '30 min.', 60 => '1 hour', 120 => '2 hours', 180 => '3 hours', 360 => '6 hours', 720 => '12 hours', 1440 => '24 hours'),
		'priority' => 2
	));
}

add_action('customize_register', 'immanis_customizer');
