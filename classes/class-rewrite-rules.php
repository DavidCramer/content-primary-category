<?php
/**
 * Rewrite class for Content Primary Category.
 *
 * @package David_Cramer/Content_Primary_Category
 */

namespace David_Cramer\Content_Primary_Category;

/**
 * Rewrite_Rules Class.
 */
class Rewrite_Rules {

	/**
	 * Options key to track rewrite flush.
	 *
	 * @var string
	 */
	const FLUSH_KEY = '_cpc_flushed';

	/**
	 * Register category rewrite rules.
	 *
	 * @return void
	 */
	public function register_rewrites(): void {

		$this->add_rewrite_tag();
		$this->add_permastruct();
		$this->maybe_flush();
	}

	/**
	 * Add rewrite tag.
	 *
	 * @return void
	 */
	private function add_rewrite_tag(): void {

		global $wp_rewrite;

		$position = array_search( '%category%', $wp_rewrite->rewritecode, true );
		if ( empty( $position ) ) {
			return; // Categories may be removed or disabled.
		}

		$rewrite = [
			'rewrite' => $wp_rewrite->rewritereplace[ $position ],
			'query'   => $wp_rewrite->queryreplace[ $position ],
		];

		add_rewrite_tag( '%primary-category%', $rewrite['rewrite'], 'primary_category=1&' . $rewrite['query'] );
	}

	/**
	 * Add permastructure.
	 *
	 * @return void
	 */
	private function add_permastruct(): void {

		$name = 'primary-category';
		$base = get_option( 'category_base' );
		if ( ! empty( $base ) ) {
			$name = 'primary-' . $base;
		}
		$struct = $name . '/%primary-category%';
		$args   = [
			'hierarchical' => true,
			'slug'         => 'category',
			'with_front'   => true,
		];
		add_permastruct( 'primary-category', $struct, $args );
	}

	/**
	 * Maybe flush rewrite rules.
	 *
	 * @return void
	 */
	private function maybe_flush(): void {

		if ( is_admin() ) {
			$has_flushed = get_option( self::FLUSH_KEY, false );
			if ( ! $has_flushed ) {
				flush_rewrite_rules();
				update_option( self::FLUSH_KEY, true );
			}
		}
	}

	/**
	 * Clear flush data.
	 *
	 * @return void
	 */
	public static function clear_flush(): void {

		delete_option( self::FLUSH_KEY );
	}
}
