<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nk_reviews_render_shortcode( $attributes = [] ) {
	$attributes = shortcode_atts(
		[
			'default_filter' => 'mentions_nan',
			'keyword'        => 'nan',
			'allow_show_all' => '1',
		],
		$attributes,
		'nk_reviews'
	);

	$default_filter = sanitize_key( $attributes['default_filter'] );
	$keyword        = sanitize_text_field( $attributes['keyword'] );
	$allow_show_all = filter_var( $attributes['allow_show_all'], FILTER_VALIDATE_BOOLEAN );

	if ( '' === $keyword ) {
		$keyword = 'nan';
	}

	$cache = get_option( NK_REVIEWS_OPTION_CACHE, '[]' );
	if ( ! is_string( $cache ) ) {
		$cache = wp_json_encode( $cache );
	}

	$reviews = json_decode( $cache, true );
	if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $reviews ) ) {
		$reviews = [];
	}

	$last_updated = get_option( NK_REVIEWS_OPTION_LAST_UPDATED );
	$last_updated = $last_updated ? date_i18n( 'Y-m-d H:i:s', (int) $last_updated ) : __( 'Never', 'nk-reviews' );

	$container_id = 'nk-reviews-' . wp_generate_uuid4();
	$overall_count = count( $reviews );
	$show_all_by_default = 'mentions_nan' !== $default_filter;

	ob_start();
	?>
	<div class="nk-reviews" id="<?php echo esc_attr( $container_id ); ?>">
		<div class="nk-reviews__summary">
			<p>
				<strong><?php echo esc_html__( 'Total reviews:', 'nk-reviews' ); ?></strong>
				<span class="nk-reviews__count"><?php echo esc_html( $overall_count ); ?></span>
			</p>
			<p>
				<strong><?php echo esc_html__( 'Last updated:', 'nk-reviews' ); ?></strong>
				<span class="nk-reviews__updated"><?php echo esc_html( $last_updated ); ?></span>
			</p>
		</div>
		<p class="nk-reviews__status"><?php echo esc_html__( 'Showing reviews that mention Nan.', 'nk-reviews' ); ?></p>
		<?php if ( $allow_show_all ) : ?>
			<div class="nk-reviews__filters">
				<button type="button" class="nk-reviews__toggle" aria-pressed="<?php echo $show_all_by_default ? 'true' : 'false'; ?>">
					<?php echo esc_html__( 'Show all reviews', 'nk-reviews' ); ?>
				</button>
			</div>
		<?php endif; ?>
		<div class="nk-reviews__list">
			<?php foreach ( $reviews as $review ) :
				$author = isset( $review['author'] ) ? (string) $review['author'] : '';
				$rating = isset( $review['rating'] ) ? (string) $review['rating'] : '';
				$date   = isset( $review['date'] ) ? (string) $review['date'] : '';
				$text   = isset( $review['text'] ) ? (string) $review['text'] : '';
				$matches = '' === $keyword ? true : false !== stripos( $text, $keyword );
				?>
				<div class="nk-reviews__item" data-matches="<?php echo $matches ? '1' : '0'; ?>">
					<p class="nk-reviews__meta">
						<strong class="nk-reviews__author"><?php echo esc_html( $author ); ?></strong>
						<span class="nk-reviews__rating"><?php echo esc_html( $rating ); ?></span>
						<span class="nk-reviews__date"><?php echo esc_html( $date ); ?></span>
					</p>
					<p class="nk-reviews__text"><?php echo esc_html( $text ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<script>
		(() => {
			const container = document.getElementById(<?php echo wp_json_encode( $container_id ); ?>);
			if (!container) {
				return;
			}
			const toggle = container.querySelector('.nk-reviews__toggle');
			const status = container.querySelector('.nk-reviews__status');
			const items = Array.from(container.querySelectorAll('.nk-reviews__item'));
			let showAll = <?php echo wp_json_encode( $show_all_by_default ); ?>;

			const updateStatus = () => {
				if (!status) {
					return;
				}
				status.textContent = showAll
					? <?php echo wp_json_encode( __( 'Showing all reviews.', 'nk-reviews' ) ); ?>
					: <?php echo wp_json_encode( __( 'Showing reviews that mention Nan.', 'nk-reviews' ) ); ?>;
			};

			const applyFilter = () => {
				items.forEach((item) => {
					const matches = item.getAttribute('data-matches') === '1';
					item.style.display = showAll || matches ? '' : 'none';
				});
				updateStatus();
				if (toggle) {
					toggle.setAttribute('aria-pressed', showAll ? 'true' : 'false');
					toggle.textContent = showAll
						? <?php echo wp_json_encode( __( 'Show only reviews that mention Nan', 'nk-reviews' ) ); ?>
						: <?php echo wp_json_encode( __( 'Show all reviews', 'nk-reviews' ) ); ?>;
				}
			};

			applyFilter();

			if (toggle) {
				toggle.addEventListener('click', () => {
					showAll = !showAll;
					applyFilter();
				});
			}
		})();
	</script>
	<?php

	return ob_get_clean();
}
