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
		self::register_event_post_type();
		self::register_event_taxonomy();
		self::register_closing_post_type();
		self::register_closing_taxonomy();
		self::register_closing_meta();
		self::register_block_patterns();

		add_filter( 'manage_event_posts_columns', array( __CLASS__, 'add_event_year_column' ) );
		add_action( 'manage_event_posts_custom_column', array( __CLASS__, 'render_event_year_column' ), 10, 2 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_closing_meta_box' ) );
		add_action( 'save_post_closing', array( __CLASS__, 'save_closing_meta' ), 10, 2 );
	}

	/**
	 * Register block patterns.
	 */
	private static function register_block_patterns(): void {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}

		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'nk-core',
				array(
					'label' => __( 'NK Core', 'nk-core' ),
				)
			);
		}

		register_block_pattern(
			'nk-core/home-hero',
			array(
				'title'       => __( 'Home Hero', 'nk-core' ),
				'description' => __( 'Homepage hero with headline, subhead, and apply button.', 'nk-core' ),
				'categories'  => array( 'nk-core' ),
				'content'     => '<!-- wp:group {"tagName":"section","layout":{"type":"constrained"}} -->
<section class="wp-block-group">
<!-- wp:heading {"level":1} -->
<h1>Guiding you home with confidence.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Local mortgage expertise with options tailored to your goals.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/apply">Apply now</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</section>
<!-- /wp:group -->',
			)
		);

		register_block_pattern(
			'nk-core/reviews-preview',
			array(
				'title'       => __( 'Reviews Preview', 'nk-core' ),
				'description' => __( 'Heading and reviews shortcode block.', 'nk-core' ),
				'categories'  => array( 'nk-core' ),
				'content'     => '<!-- wp:group {"tagName":"section","layout":{"type":"constrained"}} -->
<section class="wp-block-group">
<!-- wp:heading {"level":2} -->
<h2>Borrowers are saying great things.</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[nk_reviews]
<!-- /wp:shortcode -->
</section>
<!-- /wp:group -->',
			)
		);

		register_block_pattern(
			'nk-core/cta-strip',
			array(
				'title'       => __( 'CTA Strip', 'nk-core' ),
				'description' => __( 'Apply, call, and email call-to-action strip.', 'nk-core' ),
				'categories'  => array( 'nk-core' ),
				'content'     => '<!-- wp:group {"tagName":"section","layout":{"type":"constrained"}} -->
<section class="wp-block-group">
<!-- wp:heading {"level":2} -->
<h2>Ready to get started?</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="/apply">Apply</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="tel:+15555555555">Call</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="mailto:hello@nankirkpatrick.com">Email</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</section>
<!-- /wp:group -->',
			)
		);
	}

	/**
	 * Register the Event custom post type.
	 */
	private static function register_event_post_type(): void {
		$labels = array(
			'name'                  => __( 'Events', 'nk-core' ),
			'singular_name'         => __( 'Event', 'nk-core' ),
			'menu_name'             => __( 'Events', 'nk-core' ),
			'name_admin_bar'        => __( 'Event', 'nk-core' ),
			'add_new'               => __( 'Add New', 'nk-core' ),
			'add_new_item'          => __( 'Add New Event', 'nk-core' ),
			'new_item'              => __( 'New Event', 'nk-core' ),
			'edit_item'             => __( 'Edit Event', 'nk-core' ),
			'view_item'             => __( 'View Event', 'nk-core' ),
			'all_items'             => __( 'All Events', 'nk-core' ),
			'search_items'          => __( 'Search Events', 'nk-core' ),
			'parent_item_colon'     => __( 'Parent Events:', 'nk-core' ),
			'not_found'             => __( 'No events found.', 'nk-core' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'nk-core' ),
			'featured_image'        => __( 'Event Image', 'nk-core' ),
			'set_featured_image'    => __( 'Set event image', 'nk-core' ),
			'remove_featured_image' => __( 'Remove event image', 'nk-core' ),
			'use_featured_image'    => __( 'Use as event image', 'nk-core' ),
			'archives'              => __( 'Event Archives', 'nk-core' ),
			'insert_into_item'      => __( 'Insert into event', 'nk-core' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'nk-core' ),
			'filter_items_list'     => __( 'Filter events list', 'nk-core' ),
			'items_list_navigation' => __( 'Events list navigation', 'nk-core' ),
			'items_list'            => __( 'Events list', 'nk-core' ),
		);

		register_post_type(
			'event',
			array(
				'labels'             => $labels,
				'public'             => true,
				'has_archive'        => 'events',
				'menu_position'      => 20,
				'menu_icon'          => 'dashicons-calendar-alt',
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'rewrite'            => array(
					'slug'       => 'events',
					'with_front' => false,
				),
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'show_in_admin_bar'  => true,
				'exclude_from_search'=> false,
			)
		);
	}

	/**
	 * Register the Event Year taxonomy.
	 */
	private static function register_event_taxonomy(): void {
		$labels = array(
			'name'              => __( 'Event Year', 'nk-core' ),
			'singular_name'     => __( 'Event Year', 'nk-core' ),
			'search_items'      => __( 'Search Event Years', 'nk-core' ),
			'all_items'         => __( 'All Event Years', 'nk-core' ),
			'parent_item'       => __( 'Parent Event Year', 'nk-core' ),
			'parent_item_colon' => __( 'Parent Event Year:', 'nk-core' ),
			'edit_item'         => __( 'Edit Event Year', 'nk-core' ),
			'update_item'       => __( 'Update Event Year', 'nk-core' ),
			'add_new_item'      => __( 'Add New Event Year', 'nk-core' ),
			'new_item_name'     => __( 'New Event Year Name', 'nk-core' ),
			'menu_name'         => __( 'Event Year', 'nk-core' ),
		);

		register_taxonomy(
			'event_year',
			array( 'event' ),
			array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => 'events',
					'with_front' => false,
				),
			)
		);
	}

	/**
	 * Register the Closing custom post type.
	 */
	private static function register_closing_post_type(): void {
		$labels = array(
			'name'                  => __( 'Closings', 'nk-core' ),
			'singular_name'         => __( 'Closing', 'nk-core' ),
			'menu_name'             => __( 'Closings', 'nk-core' ),
			'name_admin_bar'        => __( 'Closing', 'nk-core' ),
			'add_new'               => __( 'Add New', 'nk-core' ),
			'add_new_item'          => __( 'Add New Closing', 'nk-core' ),
			'new_item'              => __( 'New Closing', 'nk-core' ),
			'edit_item'             => __( 'Edit Closing', 'nk-core' ),
			'view_item'             => __( 'View Closing', 'nk-core' ),
			'all_items'             => __( 'All Closings', 'nk-core' ),
			'search_items'          => __( 'Search Closings', 'nk-core' ),
			'parent_item_colon'     => __( 'Parent Closings:', 'nk-core' ),
			'not_found'             => __( 'No closings found.', 'nk-core' ),
			'not_found_in_trash'    => __( 'No closings found in Trash.', 'nk-core' ),
			'featured_image'        => __( 'Closing Image', 'nk-core' ),
			'set_featured_image'    => __( 'Set closing image', 'nk-core' ),
			'remove_featured_image' => __( 'Remove closing image', 'nk-core' ),
			'use_featured_image'    => __( 'Use as closing image', 'nk-core' ),
			'archives'              => __( 'Closing Archives', 'nk-core' ),
			'insert_into_item'      => __( 'Insert into closing', 'nk-core' ),
			'uploaded_to_this_item' => __( 'Uploaded to this closing', 'nk-core' ),
			'filter_items_list'     => __( 'Filter closings list', 'nk-core' ),
			'items_list_navigation' => __( 'Closings list navigation', 'nk-core' ),
			'items_list'            => __( 'Closings list', 'nk-core' ),
		);

		register_post_type(
			'closing',
			array(
				'labels'             => $labels,
				'public'             => true,
				'has_archive'        => 'closings',
				'menu_position'      => 21,
				'menu_icon'          => 'dashicons-yes-alt',
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'       => true,
				'show_in_menu'       => true,
				'rewrite'            => array(
					'slug'       => 'closings',
					'with_front' => false,
				),
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'show_in_admin_bar'  => true,
				'exclude_from_search'=> false,
			)
		);
	}

	/**
	 * Register the Closing Year taxonomy.
	 */
	private static function register_closing_taxonomy(): void {
		$labels = array(
			'name'              => __( 'Closing Year', 'nk-core' ),
			'singular_name'     => __( 'Closing Year', 'nk-core' ),
			'search_items'      => __( 'Search Closing Years', 'nk-core' ),
			'all_items'         => __( 'All Closing Years', 'nk-core' ),
			'parent_item'       => __( 'Parent Closing Year', 'nk-core' ),
			'parent_item_colon' => __( 'Parent Closing Year:', 'nk-core' ),
			'edit_item'         => __( 'Edit Closing Year', 'nk-core' ),
			'update_item'       => __( 'Update Closing Year', 'nk-core' ),
			'add_new_item'      => __( 'Add New Closing Year', 'nk-core' ),
			'new_item_name'     => __( 'New Closing Year Name', 'nk-core' ),
			'menu_name'         => __( 'Closing Year', 'nk-core' ),
		);

		register_taxonomy(
			'closing_year',
			array( 'closing' ),
			array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => false,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => 'closings',
					'with_front' => false,
				),
			)
		);
	}

	/**
	 * Register Closing metadata.
	 */
	private static function register_closing_meta(): void {
		$default_disclaimer = 'Nan Kirkpatrick acted as the mortgage loan originator, not the listing agent. Information shown is for illustrative purposes only; results vary.';
		$meta_fields = array(
			'closing_city'          => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'closing_month'         => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'closing_year_value'    => array(
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
			),
			'closing_property_type' => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'closing_price_range'   => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'closing_disclaimer'    => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_textarea_field',
				'default'           => $default_disclaimer,
			),
		);

		foreach ( $meta_fields as $meta_key => $meta_args ) {
			register_post_meta(
				'closing',
				$meta_key,
				array_merge(
					array(
						'single'       => true,
						'show_in_rest' => true,
					),
					$meta_args
				)
			);
		}
	}

	/**
	 * Register the Closing meta box.
	 */
	public static function register_closing_meta_box(): void {
		add_meta_box(
			'closing-details',
			__( 'Closing Details', 'nk-core' ),
			array( __CLASS__, 'render_closing_meta_box' ),
			'closing',
			'normal',
			'default'
		);
	}

	/**
	 * Render Closing meta box fields.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function render_closing_meta_box( WP_Post $post ): void {
		$default_disclaimer = 'Nan Kirkpatrick acted as the mortgage loan originator, not the listing agent. Information shown is for illustrative purposes only; results vary.';
		wp_nonce_field( 'save_closing_meta', 'closing_meta_nonce' );
		$fields = array(
			'closing_city'          => array( 'label' => __( 'City', 'nk-core' ) ),
			'closing_month'         => array( 'label' => __( 'Month', 'nk-core' ) ),
			'closing_year_value'    => array( 'label' => __( 'Year (numeric)', 'nk-core' ) ),
			'closing_property_type' => array( 'label' => __( 'Property Type', 'nk-core' ) ),
			'closing_price_range'   => array( 'label' => __( 'Price Range (optional)', 'nk-core' ) ),
		);

		echo '<table class="form-table"><tbody>';
		foreach ( $fields as $meta_key => $field ) {
			$value = get_post_meta( $post->ID, $meta_key, true );
			echo '<tr>';
			echo '<th scope="row"><label for="' . esc_attr( $meta_key ) . '">' . esc_html( $field['label'] ) . '</label></th>';
			echo '<td><input type="text" class="regular-text" id="' . esc_attr( $meta_key ) . '" name="' . esc_attr( $meta_key ) . '" value="' . esc_attr( $value ) . '"/></td>';
			echo '</tr>';
		}

		$disclaimer_value = get_post_meta( $post->ID, 'closing_disclaimer', true );
		if ( '' === $disclaimer_value ) {
			$disclaimer_value = $default_disclaimer;
		}

		echo '<tr>';
		echo '<th scope="row"><label for="closing_disclaimer">' . esc_html__( 'Disclaimer', 'nk-core' ) . '</label></th>';
		echo '<td><textarea class="large-text" rows="3" id="closing_disclaimer" name="closing_disclaimer">' . esc_textarea( $disclaimer_value ) . '</textarea></td>';
		echo '</tr>';
		echo '</tbody></table>';
	}

	/**
	 * Save Closing meta fields.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 */
	public static function save_closing_meta( int $post_id, WP_Post $post ): void {
		if ( ! isset( $_POST['closing_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['closing_meta_nonce'] ), 'save_closing_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'closing_city'          => 'sanitize_text_field',
			'closing_month'         => 'sanitize_text_field',
			'closing_year_value'    => 'absint',
			'closing_property_type' => 'sanitize_text_field',
			'closing_price_range'   => 'sanitize_text_field',
			'closing_disclaimer'    => 'sanitize_textarea_field',
		);

		foreach ( $fields as $meta_key => $sanitize_callback ) {
			if ( isset( $_POST[ $meta_key ] ) ) {
				$value = call_user_func( $sanitize_callback, wp_unslash( $_POST[ $meta_key ] ) );
				update_post_meta( $post_id, $meta_key, $value );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		}
	}

	/**
	 * Add the Event Year column to the Events list table.
	 *
	 * @param array<string, string> $columns Columns for the list table.
	 * @return array<string, string>
	 */
	public static function add_event_year_column( array $columns ): array {
		$columns['event_year'] = __( 'Event Year', 'nk-core' );
		return $columns;
	}

	/**
	 * Render the Event Year column for the Events list table.
	 *
	 * @param string $column Column name.
	 * @param int    $post_id Post ID.
	 */
	public static function render_event_year_column( string $column, int $post_id ): void {
		if ( 'event_year' !== $column ) {
			return;
		}

		$terms = get_the_terms( $post_id, 'event_year' );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			echo '&mdash;';
			return;
		}

		$names = wp_list_pluck( $terms, 'name' );
		echo esc_html( implode( ', ', $names ) );
	}
}
