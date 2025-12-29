<?php
/**
 * Plugin Name: NK Reviews
 * Description: Cached reviews display for Nan Kirkpatrick.
 * Version: 0.1.0
 * Author: Nan Kirkpatrick
 * License: GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NK_REVIEWS_PATH', plugin_dir_path( __FILE__ ) );

define( 'NK_REVIEWS_OPTION_CACHE', 'nk_reviews_cache' );

define( 'NK_REVIEWS_OPTION_LAST_UPDATED', 'nk_reviews_last_updated' );

require_once NK_REVIEWS_PATH . 'includes/admin-settings.php';
require_once NK_REVIEWS_PATH . 'includes/shortcode.php';

add_action( 'admin_menu', 'nk_reviews_register_settings_page' );
add_action( 'admin_post_nk_reviews_save_cache', 'nk_reviews_handle_save_cache' );
add_shortcode( 'nk_reviews', 'nk_reviews_render_shortcode' );
