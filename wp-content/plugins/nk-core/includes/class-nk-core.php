<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class NK_Core {
	/**
	 * Initialize the plugin.
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	/**
	 * Register core hooks.
	 */
	public static function register(): void {
		// Intentionally left minimal for scaffold.
	}
}
