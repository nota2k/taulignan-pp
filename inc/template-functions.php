<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package taulignan
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function taulignan_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'taulignan_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function taulignan_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'taulignan_pingback_header' );

// functions.php du thème enfant
function sejour_acf_shortcode()
{
	if (! function_exists('get_field')) return '';
	ob_start();
	// Ton rendu ACF (date, programme, prix...) — réutilise le code sécurisé que tu as
	// Exemple simple :
	$date = get_field('date');
	if ($date) {
		$d = DateTime::createFromFormat('Ymd', $date) ?: date_create($date);
		echo '<div class="sejour-date">' . esc_html($d->format('j M Y')) . '</div>';
	}
	return ob_get_clean();
}
add_shortcode('sejour_acf', 'sejour_acf_shortcode');
