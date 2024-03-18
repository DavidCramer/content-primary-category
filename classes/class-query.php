<?php
/**
 * Query class for Content Primary Category.
 *
 * @package David_Cramer/Content_Primary_Category
 */

namespace David_Cramer\Content_Primary_Category;

use WP_Query;

/**
 * Query Class.
 */
class Query {

	/**
	 * Holds the query var.
	 *
	 * @var string
	 */
	const QUERY_VAR = 'primary_category';

	/**
	 * Add rewrite query var.
	 *
	 * @param array $query_vars Array of allowed query vars.
	 *
	 * @return array
	 */
	public function add_query_vars( array $query_vars ): array {

		$query_vars[] = self::QUERY_VAR;

		return $query_vars;
	}

	/**
	 * Alter the WP_Query, by adding the necessary meta key.
	 *
	 * @param WP_Query $query The wp_query object.
	 *
	 * @return WP_Query
	 */
	public function alter_query( WP_Query $query ): WP_Query {

		$category = $query->get( self::QUERY_VAR, false );
		if ( $query->is_main_query() && $category ) {
			$category = $query->get( 'category_name' );
			$query->set( 'meta_key', '_cpc_' . $category );
		}

		return $query;
	}

	/**
	 * Add 'Primary' to the archive page title.
	 *
	 * @param string $prefix The prefix for the archive page.
	 *
	 * @return string
	 */
	public function get_archive_title_prefix( string $prefix ): string {

		$is_primary_category = get_query_var( self::QUERY_VAR );
		if ( ! empty( $is_primary_category ) ) {
			// Translators: Placeholder is the category.
			$prefix = sprintf( __( 'Primary %s', 'content-primary-category' ), $prefix );
		}

		return $prefix;
	}
}
