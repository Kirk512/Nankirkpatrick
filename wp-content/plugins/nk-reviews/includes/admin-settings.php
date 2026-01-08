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
	$last_error    = get_option( NK_REVIEWS_OPTION_LAST_ERROR, '' );

	$client_id     = get_option( NK_REVIEWS_OPTION_GOOGLE_CLIENT_ID, '' );
	$account_id    = get_option( NK_REVIEWS_OPTION_GOOGLE_ACCOUNT_ID, '' );
	$location_id   = get_option( NK_REVIEWS_OPTION_GOOGLE_LOCATION_ID, '' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'NK Reviews Cache', 'nk-reviews' ); ?></h1>
		<p><?php echo esc_html__( 'Frontend display always uses the cached reviews below.', 'nk-reviews' ); ?></p>
		<?php if ( isset( $_GET['nk_reviews_synced'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html__( 'Reviews synced successfully.', 'nk-reviews' ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( isset( $_GET['nk_reviews_secrets_notice'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<?php
					$secrets_notice = sanitize_text_field( wp_unslash( $_GET['nk_reviews_secrets_notice'] ) );
					if ( 'updated' === $secrets_notice ) {
						echo esc_html__( 'OAuth secrets updated.', 'nk-reviews' );
					} elseif ( 'unchanged' === $secrets_notice ) {
						echo esc_html__( 'OAuth secrets left unchanged.', 'nk-reviews' );
					} else {
						echo esc_html__( 'OAuth secrets updated where provided; empty fields were left unchanged.', 'nk-reviews' );
					}
					?>
				</p>
			</div>
		<?php endif; ?>
		<?php if ( isset( $_GET['nk_reviews_error'] ) || ! empty( $last_error ) ) : ?>
			<div class="notice notice-error">
				<p>
					<?php echo esc_html__( 'Review sync failed. Cached reviews were kept.', 'nk-reviews' ); ?>
					<?php if ( ! empty( $last_error ) ) : ?>
						<?php echo esc_html( ' ' . $last_error ); ?>
					<?php endif; ?>
				</p>
			</div>
		<?php endif; ?>
		<p>
			<strong><?php echo esc_html__( 'Last updated:', 'nk-reviews' ); ?></strong>
			<?php echo esc_html( $last_updated ); ?>
		</p>
		<h2><?php echo esc_html__( 'Google Business Profile Settings', 'nk-reviews' ); ?></h2>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'nk_reviews_save_settings', 'nk_reviews_settings_nonce' ); ?>
			<input type="hidden" name="action" value="nk_reviews_save_settings" />
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="nk-reviews-account-id"><?php echo esc_html__( 'Account ID', 'nk-reviews' ); ?></label>
						</th>
						<td>
							<input
								type="text"
								name="nk_reviews_account_id"
								id="nk-reviews-account-id"
								class="regular-text"
								value="<?php echo esc_attr( $account_id ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="nk-reviews-location-id"><?php echo esc_html__( 'Location ID', 'nk-reviews' ); ?></label>
						</th>
						<td>
							<input
								type="text"
								name="nk_reviews_location_id"
								id="nk-reviews-location-id"
								class="regular-text"
								value="<?php echo esc_attr( $location_id ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="nk-reviews-client-id"><?php echo esc_html__( 'OAuth Client ID', 'nk-reviews' ); ?></label>
						</th>
						<td>
							<input
								type="text"
								name="nk_reviews_client_id"
								id="nk-reviews-client-id"
								class="regular-text"
								value="<?php echo esc_attr( $client_id ); ?>"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="nk-reviews-client-secret"><?php echo esc_html__( 'OAuth Client Secret', 'nk-reviews' ); ?></label>
						</th>
						<td>
							<input
								type="password"
								name="nk_reviews_client_secret"
								id="nk-reviews-client-secret"
								class="regular-text"
								value=""
							/>
							<p class="description"><?php echo esc_html__( 'Leave blank to keep existing value.', 'nk-reviews' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="nk-reviews-refresh-token"><?php echo esc_html__( 'OAuth Refresh Token', 'nk-reviews' ); ?></label>
						</th>
						<td>
							<input
								type="password"
								name="nk_reviews_refresh_token"
								id="nk-reviews-refresh-token"
								class="regular-text"
								value=""
							/>
							<p class="description"><?php echo esc_html__( 'Leave blank to keep existing value.', 'nk-reviews' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<button class="button button-primary" type="submit">
					<?php echo esc_html__( 'Save Settings', 'nk-reviews' ); ?>
				</button>
			</p>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'nk_reviews_sync_now', 'nk_reviews_sync_nonce' ); ?>
			<input type="hidden" name="action" value="nk_reviews_sync_now" />
			<p>
				<button class="button" type="submit">
					<?php echo esc_html__( 'Sync Now', 'nk-reviews' ); ?>
				</button>
			</p>
		</form>
		<h2><?php echo esc_html__( 'Manual Cache Override', 'nk-reviews' ); ?></h2>
		<p><?php echo esc_html__( 'Paste cached reviews JSON below and save to update the frontend display.', 'nk-reviews' ); ?></p>
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

function nk_reviews_handle_save_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized.', 'nk-reviews' ) );
	}

	check_admin_referer( 'nk_reviews_save_settings', 'nk_reviews_settings_nonce' );

	$account_id    = isset( $_POST['nk_reviews_account_id'] ) ? sanitize_text_field( wp_unslash( $_POST['nk_reviews_account_id'] ) ) : '';
	$location_id   = isset( $_POST['nk_reviews_location_id'] ) ? sanitize_text_field( wp_unslash( $_POST['nk_reviews_location_id'] ) ) : '';
	$client_id     = isset( $_POST['nk_reviews_client_id'] ) ? sanitize_text_field( wp_unslash( $_POST['nk_reviews_client_id'] ) ) : '';
	$client_secret = isset( $_POST['nk_reviews_client_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['nk_reviews_client_secret'] ) ) : '';
	$refresh_token = isset( $_POST['nk_reviews_refresh_token'] ) ? sanitize_text_field( wp_unslash( $_POST['nk_reviews_refresh_token'] ) ) : '';

	update_option( NK_REVIEWS_OPTION_GOOGLE_ACCOUNT_ID, $account_id );
	update_option( NK_REVIEWS_OPTION_GOOGLE_LOCATION_ID, $location_id );
	update_option( NK_REVIEWS_OPTION_GOOGLE_CLIENT_ID, $client_id );

	$updated_secret  = false;
	$updated_refresh = false;

	if ( '' !== $client_secret ) {
		nk_reviews_update_option_noautoload( NK_REVIEWS_OPTION_GOOGLE_CLIENT_SECRET, $client_secret );
		$updated_secret = true;
	}

	if ( '' !== $refresh_token ) {
		nk_reviews_update_option_noautoload( NK_REVIEWS_OPTION_GOOGLE_REFRESH_TOKEN, $refresh_token );
		$updated_refresh = true;
	}

	if ( $updated_secret && $updated_refresh ) {
		$secrets_notice = 'updated';
	} elseif ( ! $updated_secret && ! $updated_refresh ) {
		$secrets_notice = 'unchanged';
	} else {
		$secrets_notice = 'partial';
	}

	wp_safe_redirect(
		add_query_arg(
			[
				'page'                     => 'nk-reviews',
				'nk_reviews_secrets_notice' => $secrets_notice,
			],
			admin_url( 'options-general.php' )
		)
	);
	exit;
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

	nk_reviews_update_option_noautoload( NK_REVIEWS_OPTION_CACHE, wp_json_encode( $sanitized ) );
	nk_reviews_update_option_noautoload( NK_REVIEWS_OPTION_LAST_UPDATED, current_time( 'timestamp' ) );

	wp_safe_redirect( add_query_arg( 'page', 'nk-reviews', admin_url( 'options-general.php' ) ) );
	exit;
}
