<?php
/**
 * Core class for Content Primary Category.
 *
 * @package David_Cramer/Content_Primary_Category
 */

namespace David_Cramer\Content_Primary_Category;

/**
 * Primary_Category Class.
 */
class Content_Primary_Category {

	/**
	 * Get the instance of this class.
	 *
	 * @return self
	 */
	public static function get_instance(): self {

		static $instance;
		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Setup the plugin.
	 *
	 * @return void
	 */
	public function setup(): void {

		$this->add_hooks();
	}

	/**
	 * Add hooks to WordPress
	 *
	 * @return void
	 */
	private function add_hooks(): void {

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );

		// Setup metadata.
		$metadata = new Metadata();
		add_action( 'init', [ $metadata, 'register_post_meta' ] );
		$types = $metadata->get_compatible_types();
		foreach ( $types as $type ) {
			add_filter( "update_{$type}_meta", [ $metadata, 'prep_update_state' ], 10, 4 );
			add_action( "updated_{$type}_meta", [ $metadata, 'updated_added_meta' ], 10, 4 );

			add_filter( "delete_{$type}_meta", [ $metadata, 'prep_update_state' ], 10, 4 );
			add_action( "deleted_{$type}_meta", [ $metadata, 'updated_added_meta' ], 10, 4 );

			add_filter( "add_{$type}_meta", [ $metadata, 'prep_add_state' ], 10, 3 );
			add_action( "added_{$type}_meta", [ $metadata, 'updated_added_meta' ], 10, 4 );
		}

		// Setup rewrites.
		$rewrite = new Rewrite_Rules();
		add_action( 'init', [ $rewrite, 'register_rewrites' ] );

		// Setup Query alterations.
		$query = new Query();
		add_filter( 'query_vars', [ $query, 'add_query_vars' ] );
		add_filter( 'pre_get_posts', [ $query, 'alter_query' ] );
		add_filter( 'get_the_archive_title_prefix', [ $query, 'get_archive_title_prefix' ] );
	}

	/**
	 * Enqueue block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets(): void {

		$asset = include PRIMCAT_PATH . 'build/editor.asset.php';
		wp_enqueue_script( 'content_primary_category', PRIMCAT_URL . '/build/editor.js', $asset['dependencies'], $asset['version'], true );
	}
}
