<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nk_reviews_register_settings_page() {
	add_options_page(
		__( 'NK Reviews', 'nk-reviews' ),
		__( 'NK Reviews', 'nk-reviews' ),
		'manage_options',
		'nk-reviews',
		'nk_reviews_render_settings_page'
	);
}

function nk_reviews_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$cache         = get_option( NK_REVIEWS_OPTION_CACHE, '[]' );
	$last_updated  = get_option( NK_REVIEWS_OPTION_LAST_UPDATED );
	$last_updated  = $last_updated ? date_i18n( 'Y-m-d H:i:s', (int) $last_updated ) : __( 'Never', 'nk-reviews' );
	$cache_display = is_string( $cache ) ? $cache : wp_json_encode( $cache );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'NK Reviews Cache', 'nk-reviews' ); ?></h1>
		<p><?php echo esc_html__( 'Paste cached reviews JSON below and save to update the frontend display.', 'nk-reviews' ); ?></p>
		<p>
			<strong><?php echo esc_html__( 'Last updated:', 'nk-reviews' ); ?></strong>
			<?php echo esc_html( $last_updated ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'nk_reviews_save_cache', 'nk_reviews_nonce' ); ?>
			<input type="hidden" name="action" value="nk_reviews_save_cache" />
			<textarea
				name="nk_reviews_cache"
				rows="12"
				class="large-text code"
			><?php echo esc_textarea( $cache_display ); ?></textarea>
			<p>
				<button class="button button-primary" type="submit">
					<?php echo esc_html__( 'Save Cache', 'nk-reviews' ); ?>
				</button>
			</p>
		</form>
	</div>
	<?php
}

function nk_reviews_handle_save_cache() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized.', 'nk-reviews' ) );
	}

	check_admin_referer( 'nk_reviews_save_cache', 'nk_reviews_nonce' );

	$raw_cache = isset( $_POST['nk_reviews_cache'] ) ? wp_unslash( $_POST['nk_reviews_cache'] ) : '';
	$raw_cache = is_string( $raw_cache ) ? $raw_cache : '';

	$decoded = json_decode( $raw_cache, true );
	if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $decoded ) ) {
		$decoded = [];
	}

	$sanitized = [];
	foreach ( $decoded as $review ) {
		if ( ! is_array( $review ) ) {
			continue;
		}

		$sanitized[] = [
			'author' => isset( $review['author'] ) ? sanitize_text_field( $review['author'] ) : '',
			'rating' => isset( $review['rating'] ) ? floatval( $review['rating'] ) : 0,
			'date'   => isset( $review['date'] ) ? sanitize_text_field( $review['date'] ) : '',
			'text'   => isset( $review['text'] ) ? sanitize_textarea_field( $review['text'] ) : '',
		];
	}

	update_option( NK_REVIEWS_OPTION_CACHE, wp_json_encode( $sanitized ) );
	update_option( NK_REVIEWS_OPTION_LAST_UPDATED, current_time( 'timestamp' ) );

	wp_safe_redirect( add_query_arg( 'page', 'nk-reviews', admin_url( 'options-general.php' ) ) );
	exit;
}
