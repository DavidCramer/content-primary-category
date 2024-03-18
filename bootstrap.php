<?php
/**
 * Content Primary Category Bootstrap.
 *
 * @package   David_Cramer/Content_Primary_Category
 * @author    David Cramer
 * @license   GPL-2.0+
 * @copyright 2024/03/13 David Cramer
 */

namespace David_Cramer\Content_Primary_Category;

require_once PRIMCAT_PATH . 'autoload.php';

/**
 * Activate the plugin core.
 *
 * @return void
 */
function activate_content_primary_category(): void {

	// Init class by getting the instance.
	if ( class_exists( Content_Primary_Category::class ) ) {
		$instance = Content_Primary_Category::get_instance();
		$instance->setup();
	}
}

add_action( 'plugins_loaded', 'David_Cramer\\Content_Primary_Category\\activate_content_primary_category' );
register_deactivation_hook( PRIMCAT_CORE, 'David_Cramer\\Content_Primary_Category\\Rewrite_Rules::clear_flush' );
