<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nk_reviews_render_shortcode() {
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
		<div class="nk-reviews__filters">
			<label>
				<input type="checkbox" class="nk-reviews__filter" />
				<?php echo esc_html__( 'Mentions Nan', 'nk-reviews' ); ?>
			</label>
		</div>
		<div class="nk-reviews__list">
			<?php foreach ( $reviews as $review ) :
				$author = isset( $review['author'] ) ? (string) $review['author'] : '';
				$rating = isset( $review['rating'] ) ? (string) $review['rating'] : '';
				$date   = isset( $review['date'] ) ? (string) $review['date'] : '';
				$text   = isset( $review['text'] ) ? (string) $review['text'] : '';
				$filter_text = strtolower( $text );
				?>
				<div class="nk-reviews__item" data-review-text="<?php echo esc_attr( $filter_text ); ?>">
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
			const toggle = container.querySelector('.nk-reviews__filter');
			const items = Array.from(container.querySelectorAll('.nk-reviews__item'));
			const applyFilter = () => {
				const shouldFilter = toggle && toggle.checked;
				items.forEach((item) => {
					const text = (item.getAttribute('data-review-text') || '').toLowerCase();
					const matches = text.includes('nan');
					item.style.display = !shouldFilter || matches ? '' : 'none';
				});
			};
			if (toggle) {
				toggle.addEventListener('change', applyFilter);
			}
		})();
	</script>
	<?php

	return ob_get_clean();
}
