<?php
/*
 * Plugin Name: Content Primary Category
 * Plugin URI: https://cramer.co.za
 * Description:
 * Version: 0.0.1
 * Author: David Cramer
 * Author URI: https://cramer.co.za
 * Text Domain: content-primary-category
 * License: GPL2+
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Constants.
define( 'PRIMCAT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRIMCAT_URL', plugin_dir_url( __FILE__ ) );
const PRIMCAT_CORE = __FILE__;

if ( ! version_compare( PHP_VERSION, '8.0', '>=' ) ) {
	if ( is_admin() ) {
		add_action( 'admin_notices', 'primary_category_php_ver' );
	}
} else {
	// Includes Primary_Category and starts instance.
	include_once PRIMCAT_PATH . 'bootstrap.php';
}

function primary_category_php_ver() {

	$message = __( 'Primary Category requires PHP version 8.0 or later. We strongly recommend PHP 8.0 or later for security and performance reasons.', 'primary-category' );
	echo sprintf( '<div id="primary_category_error" class="error notice notice-error"><p>%s</p></div>', esc_html( $message ) );
}

register_activation_hook( __FILE__, 'flush_rewrite_rules' );

$args = [
	'post_type'      => 'post',
	'posts_per_page' => 10,
	'meta_query'     => [
		[
			'key'     => '_cpc_{term_slug}',
			'compare' => 'EXISTS',
		],
	],
];


