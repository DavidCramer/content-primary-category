<?php
/**
 * Metadata class for Content Primary Category.
 *
 * @package David_Cramer/Content_Primary_Category
 */

namespace David_Cramer\Content_Primary_Category;

/**
 * Metadata Class.
 */
class Metadata {

	/**
	 * Holds the meta key.
	 *
	 * @var string
	 */
	const META_KEY = '_content_primary_category';

	/**
	 * Holds the meta updating states
	 *
	 * @var array
	 */
	private array $update_states = [];

	/**
	 * Get compatible post types.
	 *
	 * @return array
	 */
	public function get_compatible_types(): array {

		static $types;
		if ( ! $types ) {
			// Cache the value as not to run apply_filters more than once.
			$types = apply_filters( 'content_primary_category_post_types', [ 'post' ] );
		}

		return $types;
	}

	/**
	 * Register post meta.
	 *
	 * @return void
	 */
	public function register_post_meta(): void {

		$post_types = $this->get_compatible_types();
		$args       = array(
			'type'          => 'integer',
			'single'        => true,
			'show_in_rest'  => true,
			'default'       => 0,
			'auth_callback' => static function () {

				return current_user_can( 'edit_posts' );
			},
		);
		foreach ( $post_types as $post_type ) {
			register_post_meta(
				$post_type,
				self::META_KEY,
				$args
			);
		}
	}

	/**
	 * Check if meta can be applied.
	 *
	 * @param int|null $object_id The object id.
	 * @param string   $meta_key  The meta key.
	 *
	 * @return bool
	 */
	public function can_apply_meta( ?int $object_id, string $meta_key ): bool {

		return $object_id && self::META_KEY === $meta_key;
	}

	/**
	 * Prepare an add to metadata.
	 *
	 * @param int    $object_id  The post ID.
	 * @param string $meta_key   The meta key being updated.
	 * @param mixed  $meta_value The value being set.
	 *
	 * @return void
	 */
	public function prep_add_state( int $object_id, string $meta_key, mixed $meta_value ): void {

		$this->prep_update_state( 0, $object_id, $meta_key, $meta_value );
	}

	/**
	 * Prepare an update state for post meta.
	 *
	 * @param int|array $meta_id    The Meta ID/s.
	 * @param int       $object_id  The post ID.
	 * @param string    $meta_key   The meta key being updated.
	 * @param mixed     $meta_value The value being set.
	 *
	 * @return void
	 */
	public function prep_update_state( int|array $meta_id, int $object_id, string $meta_key, mixed $meta_value ): void {

		if ( ! $this->can_apply_meta( $object_id, $meta_key ) ) {
			return;
		}

		$prev_value = (int) get_post_meta( $object_id, self::META_KEY, true );

		$this->update_states[ $object_id ] = [
			'new' => $this->get_term_key( $meta_value ),
			'old' => $this->get_term_key( $prev_value ),
		];
	}

	/**
	 * Get a term slug key for use as meta key.
	 *
	 * @param int|string $term_id The term ID, or 0 if none.
	 *
	 * @return string|null
	 */
	private function get_term_key( int|string $term_id ): string|null {

		$key = null;
		if ( ! empty( $term_id ) ) {
			$term = get_term( $term_id );
			if ( ! is_wp_error( $term ) ) {
				$key = '_cpc_' . $term->slug;
			}
		}

		return $key;
	}

	/**
	 * Update primary category meta.
	 *
	 * @param int|array $meta_id    ID/s of updated metadata entry.
	 * @param int       $object_id  ID of the object metadata is for.
	 * @param string    $meta_key   Metadata key.
	 * @param mixed     $meta_value Metadata value.
	 *
	 * @return void
	 */
	public function updated_added_meta( int|array $meta_id, int $object_id, string $meta_key, mixed $meta_value ): void {

		if ( ! $this->can_apply_meta( $object_id, $meta_key ) || empty( $this->update_states[ $object_id ] ) ) {
			return;
		}

		$keys = $this->update_states[ $object_id ];

		if ( ! empty( $keys['old'] ) ) {
			delete_post_meta( $object_id, $keys['old'] );
		}
		if ( ! empty( $keys['new'] ) ) {
			add_post_meta( $object_id, $keys['new'], $meta_value, true );
		}
	}
}
