<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nk_reviews_activate() {
	nk_reviews_ensure_cron_scheduled();
}

function nk_reviews_deactivate() {
	$timestamp = wp_next_scheduled( NK_REVIEWS_CRON_HOOK );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, NK_REVIEWS_CRON_HOOK );
	}
}

function nk_reviews_ensure_cron_scheduled() {
	if ( ! wp_next_scheduled( NK_REVIEWS_CRON_HOOK ) ) {
		wp_schedule_event( time(), 'daily', NK_REVIEWS_CRON_HOOK );
	}
}

function nk_reviews_handle_cron_sync() {
	nk_reviews_sync_reviews( 'cron' );
}

function nk_reviews_handle_sync_now() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized.', 'nk-reviews' ) );
	}

	check_admin_referer( 'nk_reviews_sync_now', 'nk_reviews_sync_nonce' );

	$success = nk_reviews_sync_reviews( 'manual' );

	$args = [
		'page' => 'nk-reviews',
	];

	if ( $success ) {
		$args['nk_reviews_synced'] = 1;
	} else {
		$args['nk_reviews_error'] = 1;
	}

	wp_safe_redirect( add_query_arg( $args, admin_url( 'options-general.php' ) ) );
	exit;
}

function nk_reviews_sync_reviews( $context ) {
	$result = nk_reviews_fetch_google_reviews();
	if ( is_wp_error( $result ) ) {
		update_option( NK_REVIEWS_OPTION_LAST_ERROR, $result->get_error_message() );
		return false;
	}

	$payload = wp_json_encode( $result );
	update_option( NK_REVIEWS_OPTION_CACHE, $payload );
	update_option( NK_REVIEWS_OPTION_LAST_UPDATED, current_time( 'timestamp' ) );
	delete_option( NK_REVIEWS_OPTION_LAST_ERROR );

	return true;
}

function nk_reviews_fetch_google_reviews() {
	$account_id  = get_option( NK_REVIEWS_OPTION_GOOGLE_ACCOUNT_ID, '' );
	$location_id = get_option( NK_REVIEWS_OPTION_GOOGLE_LOCATION_ID, '' );

	if ( empty( $account_id ) || empty( $location_id ) ) {
		return new WP_Error(
			'nk_reviews_missing_location',
			__( 'Google Business Profile account or location ID is missing.', 'nk-reviews' )
		);
	}

	$access_token = nk_reviews_get_google_access_token();
	if ( is_wp_error( $access_token ) ) {
		return $access_token;
	}

	$url = sprintf(
		'https://mybusiness.googleapis.com/v4/accounts/%s/locations/%s/reviews',
		rawurlencode( $account_id ),
		rawurlencode( $location_id )
	);

	$response = wp_remote_get(
		$url,
		[
			'timeout' => 20,
			'headers' => [
				'Authorization' => 'Bearer ' . $access_token,
			],
		]
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error(
			'nk_reviews_http_error',
			sprintf(
				/* translators: %s: error message */
				__( 'Review sync failed: %s', 'nk-reviews' ),
				$response->get_error_message()
			)
		);
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( 200 !== $code || JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
		return new WP_Error(
			'nk_reviews_bad_response',
			__( 'Review sync failed due to an unexpected response from Google.', 'nk-reviews' )
		);
	}

	$reviews = [];
	$items   = isset( $data['reviews'] ) && is_array( $data['reviews'] ) ? $data['reviews'] : [];

	foreach ( $items as $review ) {
		if ( ! is_array( $review ) ) {
			continue;
		}

		$reviewer = isset( $review['reviewer'] ) && is_array( $review['reviewer'] ) ? $review['reviewer'] : [];
		$author   = isset( $reviewer['displayName'] ) ? sanitize_text_field( $reviewer['displayName'] ) : '';
		$rating   = nk_reviews_normalize_rating( isset( $review['starRating'] ) ? $review['starRating'] : '' );
		$text     = isset( $review['comment'] ) ? sanitize_textarea_field( $review['comment'] ) : '';
		$date     = '';
		$created  = isset( $review['createTime'] ) ? $review['createTime'] : '';
		$updated  = isset( $review['updateTime'] ) ? $review['updateTime'] : '';
		$stamp    = strtotime( $created ? $created : $updated );

		if ( $stamp ) {
			$date = date_i18n( 'Y-m-d', $stamp );
		}

		$reviews[] = [
			'author' => $author,
			'rating' => $rating,
			'date'   => $date,
			'text'   => $text,
		];
	}

	return $reviews;
}

function nk_reviews_get_google_access_token() {
	$client_id     = get_option( NK_REVIEWS_OPTION_GOOGLE_CLIENT_ID, '' );
	$client_secret = get_option( NK_REVIEWS_OPTION_GOOGLE_CLIENT_SECRET, '' );
	$refresh_token = get_option( NK_REVIEWS_OPTION_GOOGLE_REFRESH_TOKEN, '' );

	if ( empty( $client_id ) || empty( $client_secret ) || empty( $refresh_token ) ) {
		return new WP_Error(
			'nk_reviews_missing_credentials',
			__( 'Google OAuth credentials are missing.', 'nk-reviews' )
		);
	}

	$response = wp_remote_post(
		'https://oauth2.googleapis.com/token',
		[
			'timeout' => 20,
			'body'    => [
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
				'refresh_token' => $refresh_token,
				'grant_type'    => 'refresh_token',
			],
		]
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error(
			'nk_reviews_auth_error',
			sprintf(
				/* translators: %s: error message */
				__( 'Token request failed: %s', 'nk-reviews' ),
				$response->get_error_message()
			)
		);
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( 200 !== $code || JSON_ERROR_NONE !== json_last_error() || empty( $data['access_token'] ) ) {
		return new WP_Error(
			'nk_reviews_auth_invalid',
			__( 'Token request failed due to an unexpected response from Google.', 'nk-reviews' )
		);
	}

	return sanitize_text_field( $data['access_token'] );
}

function nk_reviews_normalize_rating( $raw_rating ) {
	if ( is_numeric( $raw_rating ) ) {
		return (float) $raw_rating;
	}

	$rating = strtoupper( (string) $raw_rating );

	switch ( $rating ) {
		case 'ONE':
			return 1;
		case 'TWO':
			return 2;
		case 'THREE':
			return 3;
		case 'FOUR':
			return 4;
		case 'FIVE':
			return 5;
		default:
			return 0;
	}
}
