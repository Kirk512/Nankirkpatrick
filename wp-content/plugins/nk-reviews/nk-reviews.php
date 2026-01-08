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
define( 'NK_REVIEWS_OPTION_LAST_ERROR', 'nk_reviews_last_error' );
define( 'NK_REVIEWS_OPTION_GOOGLE_CLIENT_ID', 'nk_reviews_google_client_id' );
define( 'NK_REVIEWS_OPTION_GOOGLE_CLIENT_SECRET', 'nk_reviews_google_client_secret' );
define( 'NK_REVIEWS_OPTION_GOOGLE_REFRESH_TOKEN', 'nk_reviews_google_refresh_token' );
define( 'NK_REVIEWS_OPTION_GOOGLE_ACCOUNT_ID', 'nk_reviews_google_account_id' );
define( 'NK_REVIEWS_OPTION_GOOGLE_LOCATION_ID', 'nk_reviews_google_location_id' );
define( 'NK_REVIEWS_CRON_HOOK', 'nk_reviews_daily_sync' );

require_once NK_REVIEWS_PATH . 'includes/admin-settings.php';
require_once NK_REVIEWS_PATH . 'includes/shortcode.php';
require_once NK_REVIEWS_PATH . 'includes/sync.php';

add_action( 'admin_menu', 'nk_reviews_register_settings_page' );
add_action( 'admin_post_nk_reviews_save_cache', 'nk_reviews_handle_save_cache' );
add_action( 'admin_post_nk_reviews_save_settings', 'nk_reviews_handle_save_settings' );
add_action( 'admin_post_nk_reviews_sync_now', 'nk_reviews_handle_sync_now' );
add_shortcode( 'nk_reviews', 'nk_reviews_render_shortcode' );

add_filter( 'cron_schedules', 'nk_reviews_add_cron_schedules' );
add_action( NK_REVIEWS_CRON_HOOK, 'nk_reviews_handle_cron_sync' );
add_action( 'admin_init', 'nk_reviews_maybe_repair_cron_schedule' );

register_activation_hook( __FILE__, 'nk_reviews_activate' );
register_deactivation_hook( __FILE__, 'nk_reviews_deactivate' );
