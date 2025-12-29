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

		add_filter( 'manage_event_posts_columns', array( __CLASS__, 'add_event_year_column' ) );
		add_action( 'manage_event_posts_custom_column', array( __CLASS__, 'render_event_year_column' ), 10, 2 );
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
