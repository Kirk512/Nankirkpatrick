<?php
/**
 * NK Theme functions and definitions.
 *
 * @package NK_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define theme constants.
 */
define( 'NK_THEME_VERSION', '1.0.0' );
define( 'NK_THEME_DIR', get_stylesheet_directory() );
define( 'NK_THEME_URI', get_stylesheet_directory_uri() );

/**
 * Enqueue parent and child theme styles.
 */
add_action( 'wp_enqueue_scripts', function () {
	// Parent theme styles (Twenty Twenty-Four).
	wp_enqueue_style(
		'twentytwentyfour-style',
		get_template_directory_uri() . '/style.css',
		[],
		wp_get_theme( 'twentytwentyfour' )->get( 'Version' )
	);

	// Child theme styles.
	wp_enqueue_style(
		'nk-theme-style',
		get_stylesheet_uri(),
		[ 'twentytwentyfour-style' ],
		NK_THEME_VERSION
	);

	// Google Fonts: Inter + Playfair Display.
	wp_enqueue_style(
		'nk-theme-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap',
		[],
		null
	);

	// Theme scripts.
	wp_enqueue_script(
		'nk-theme-scripts',
		NK_THEME_URI . '/assets/js/theme.js',
		[],
		NK_THEME_VERSION,
		true
	);
} );

/**
 * Enqueue editor styles.
 */
add_action( 'after_setup_theme', function () {
	add_editor_style( 'style.css' );
} );

/**
 * Register navigation menus.
 */
add_action( 'after_setup_theme', function () {
	register_nav_menus( [
		'primary'    => __( 'Primary Navigation', 'nk-theme' ),
		'footer'     => __( 'Footer Navigation', 'nk-theme' ),
		'legal'      => __( 'Legal Links (Footer)', 'nk-theme' ),
	] );
} );

/**
 * Add theme support.
 */
add_action( 'after_setup_theme', function () {
	// Block editor features.
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );

	// Custom logo support.
	add_theme_support( 'custom-logo', [
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	] );

	// HTML5 support.
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	] );
} );

/**
 * Register block pattern categories.
 */
add_action( 'init', function () {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category( 'nk-theme', [
		'label' => __( 'NK Theme', 'nk-theme' ),
	] );

	register_block_pattern_category( 'nk-pages', [
		'label' => __( 'NK Page Layouts', 'nk-theme' ),
	] );
} );

/**
 * Register block patterns.
 */
add_action( 'init', function () {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	// Page Hero pattern.
	register_block_pattern( 'nk-theme/page-hero', [
		'title'       => __( 'Page Hero', 'nk-theme' ),
		'description' => __( 'Full-width hero section with heading and optional subtext.', 'nk-theme' ),
		'categories'  => [ 'nk-theme' ],
		'content'     => '<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:heading {"level":1,"textAlign":"center"} -->
<h1 class="wp-block-heading has-text-align-center">Page Title</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Brief description or tagline for this page.</p>
<!-- /wp:paragraph -->
</section>
<!-- /wp:group -->',
	] );

	// About section pattern.
	register_block_pattern( 'nk-theme/about-section', [
		'title'       => __( 'About Section with Image', 'nk-theme' ),
		'description' => __( 'Two-column layout with image and text content.', 'nk-theme' ),
		'categories'  => [ 'nk-theme' ],
		'content'     => '<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns alignwide">
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-large is-style-rounded"><img alt="Professional headshot" /></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%">
<!-- wp:heading -->
<h2>About Nan Kirkpatrick</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>With years of experience helping families achieve their homeownership dreams, Nan Kirkpatrick brings a personal touch to the mortgage process. As your dedicated loan originator, Nan works closely with you to find the right financing solution for your unique situation.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Whether you\'re a first-time homebuyer, looking to refinance, or exploring investment properties, Nan provides expert guidance every step of the way.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link" href="https://www.applywithnan.com">Start Your Application</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/contact">Get in Touch</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->',
	] );

	// Services/offerings pattern.
	register_block_pattern( 'nk-theme/services-grid', [
		'title'       => __( 'Services Grid', 'nk-theme' ),
		'description' => __( 'Three-column grid of mortgage services.', 'nk-theme' ),
		'categories'  => [ 'nk-theme' ],
		'content'     => '<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"secondary","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-secondary-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Mortgage Solutions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Financing options tailored to your goals.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns alignwide">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"border":{"radius":"8px"}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
<!-- wp:heading {"level":3} -->
<h3>Conventional Loans</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Traditional financing with competitive rates and flexible terms for qualified borrowers.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"border":{"radius":"8px"}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
<!-- wp:heading {"level":3} -->
<h3>FHA Loans</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Government-backed loans with lower down payment requirements for first-time buyers.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"border":{"radius":"8px"}},"backgroundColor":"white"} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
<!-- wp:heading {"level":3} -->
<h3>VA Loans</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Exclusive benefits for veterans and active-duty service members with no down payment.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->',
	] );

	// Contact section pattern.
	register_block_pattern( 'nk-theme/contact-section', [
		'title'       => __( 'Contact Section', 'nk-theme' ),
		'description' => __( 'Contact information with call-to-action.', 'nk-theme' ),
		'categories'  => [ 'nk-theme' ],
		'content'     => '<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading -->
<h2>Get in Touch</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ready to start your home financing journey? Reach out today for a personalized consultation.</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-group">
<!-- wp:paragraph -->
<p><strong>Phone:</strong> <a href="tel:+15123357800">(512) 335-7800</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Email:</strong> <a href="mailto:nan@abundancehomemtg.com">nan@abundancehomemtg.com</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>NMLS #:</strong> 212026</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"8px"}},"backgroundColor":"secondary"} -->
<div class="wp-block-group has-secondary-background-color has-background" style="border-radius:8px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
<!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="wp-block-heading has-text-align-center">Ready to Apply?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Start your secure online application today.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link" href="https://www.applywithnan.com">Apply Online</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</section>
<!-- /wp:group -->',
	] );

	// Testimonial/quote pattern.
	register_block_pattern( 'nk-theme/testimonial', [
		'title'       => __( 'Testimonial Quote', 'nk-theme' ),
		'description' => __( 'Featured testimonial with large quote styling.', 'nk-theme' ),
		'categories'  => [ 'nk-theme' ],
		'content'     => '<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained","contentSize":"800px"}} -->
<section class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:quote {"align":"center","className":"is-style-large"} -->
<blockquote class="wp-block-quote has-text-align-center is-style-large">
<p>"Nan made the entire mortgage process smooth and stress-free. Her expertise and genuine care for her clients is unmatched."</p>
<cite>— Happy Homeowner</cite>
</blockquote>
<!-- /wp:quote -->
</section>
<!-- /wp:group -->',
	] );
} );

/**
 * Add NMLS identifier to customizer for easy editing.
 */
add_action( 'customize_register', function ( $wp_customize ) {
	// Add NK Theme section.
	$wp_customize->add_section( 'nk_theme_compliance', [
		'title'       => __( 'Compliance & NMLS', 'nk-theme' ),
		'priority'    => 30,
		'description' => __( 'Required compliance information displayed site-wide.', 'nk-theme' ),
	] );

	// Loan Officer NMLS.
	$wp_customize->add_setting( 'nk_lo_nmls', [
		'default'           => '212026',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'nk_lo_nmls', [
		'label'   => __( 'Loan Officer NMLS #', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'text',
	] );

	// Loan Officer Name.
	$wp_customize->add_setting( 'nk_lo_name', [
		'default'           => 'Nan Kirkpatrick',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'nk_lo_name', [
		'label'   => __( 'Loan Officer Name', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'text',
	] );

	// Company NMLS.
	$wp_customize->add_setting( 'nk_company_nmls', [
		'default'           => '218131',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'nk_company_nmls', [
		'label'   => __( 'Company NMLS #', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'text',
	] );

	// Company Name.
	$wp_customize->add_setting( 'nk_company_name', [
		'default'           => 'Abundance Home Mortgage',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'nk_company_name', [
		'label'   => __( 'Company Name', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'text',
	] );

	// Phone number.
	$wp_customize->add_setting( 'nk_phone', [
		'default'           => '(512) 335-7800',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'nk_phone', [
		'label'   => __( 'Phone Number', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'text',
	] );

	// Email.
	$wp_customize->add_setting( 'nk_email', [
		'default'           => 'nan@abundancehomemtg.com',
		'sanitize_callback' => 'sanitize_email',
	] );
	$wp_customize->add_control( 'nk_email', [
		'label'   => __( 'Email Address', 'nk-theme' ),
		'section' => 'nk_theme_compliance',
		'type'    => 'email',
	] );
} );

/**
 * Helper function to get theme mod with fallback.
 *
 * @param string $key Theme mod key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function nk_get_option( string $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Generate NMLS display string.
 *
 * @return string
 */
function nk_get_nmls_display(): string {
	$lo_name      = nk_get_option( 'nk_lo_name', 'Nan Kirkpatrick' );
	$lo_nmls      = nk_get_option( 'nk_lo_nmls', '212026' );
	$company_nmls = nk_get_option( 'nk_company_nmls', '218131' );

	return sprintf(
		'%s NMLS# %s | Company NMLS# %s',
		esc_html( $lo_name ),
		esc_html( $lo_nmls ),
		esc_html( $company_nmls )
	);
}

/**
 * Add NMLS shortcode for flexible placement.
 */
add_shortcode( 'nk_nmls', function ( $atts ) {
	$atts = shortcode_atts( [
		'format' => 'full', // full, lo_only, company_only
	], $atts, 'nk_nmls' );

	$lo_name      = nk_get_option( 'nk_lo_name', 'Nan Kirkpatrick' );
	$lo_nmls      = nk_get_option( 'nk_lo_nmls', '212026' );
	$company_name = nk_get_option( 'nk_company_name', 'Abundance Home Mortgage' );
	$company_nmls = nk_get_option( 'nk_company_nmls', '218131' );

	switch ( $atts['format'] ) {
		case 'lo_only':
			return sprintf(
				'<span class="nk-nmls nk-nmls--lo">%s NMLS# %s</span>',
				esc_html( $lo_name ),
				esc_html( $lo_nmls )
			);
		case 'company_only':
			return sprintf(
				'<span class="nk-nmls nk-nmls--company">%s NMLS# %s</span>',
				esc_html( $company_name ),
				esc_html( $company_nmls )
			);
		default:
			return sprintf(
				'<span class="nk-nmls nk-nmls--full">%s NMLS# %s<br>%s NMLS# %s</span>',
				esc_html( $lo_name ),
				esc_html( $lo_nmls ),
				esc_html( $company_name ),
				esc_html( $company_nmls )
			);
	}
} );

/**
 * Add Equal Housing Lender shortcode.
 */
add_shortcode( 'nk_equal_housing', function () {
	$logo_url = NK_THEME_URI . '/assets/images/equal-housing-lender.svg';

	return sprintf(
		'<span class="nk-equal-housing">
			<img src="%s" alt="Equal Housing Lender" class="nk-equal-housing-logo" width="40" height="40">
			<span class="nk-equal-housing-text">Equal Housing Lender</span>
		</span>',
		esc_url( $logo_url )
	);
} );
