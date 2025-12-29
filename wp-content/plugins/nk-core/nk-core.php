<?php
/**
 * Plugin Name: NK Core
 * Plugin URI: https://nankirkpatrick.com/
 * Description: Core functionality for the NK site. Placeholder for CPTs, shared shortcodes, and blocks.
 * Version: 0.1.0
 * Author: NK Team
 * Author URI: https://nankirkpatrick.com/
 * Text Domain: nk-core
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

require_once __DIR__ . '/includes/class-nk-core.php';

NK_Core::init();
