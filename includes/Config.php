<?php
/**
 * Filtered configuration, normalised at the edge.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness;

defined( 'ABSPATH' ) || exit;

/**
 * Reads the plugin's two filtered settings and hands back a usable value.
 *
 * apply_filters() returns whatever the last callback in the chain returned, and
 * class-wp-hook.php is not strict typed, so a third party that returns the
 * wrong shape, or forgets to return at all and yields null, decides what this
 * plugin gets. Every call site went straight from the filter into a foreach or
 * into date arithmetic, so that value had to be normalised in eight places or
 * in one. This is the one.
 *
 * @since 1.0.1
 */
final class Config {

	/**
	 * Post types tracked when nothing says otherwise.
	 *
	 * @var array<int, string>
	 */
	public const DEFAULT_POST_TYPES = [ 'post', 'page' ];

	/**
	 * Review interval in days when nothing says otherwise.
	 *
	 * @var int
	 */
	public const DEFAULT_INTERVAL = 180;

	/**
	 * The post types this plugin tracks.
	 *
	 * A bare string is accepted as a single post type, matching how core treats
	 * most post_type arguments. Anything else unusable yields an empty array,
	 * which switches the plugin off rather than taking the site down.
	 *
	 * @return array<int, string> Post type slugs, possibly empty.
	 */
	public static function tracked_post_types(): array {
		/**
		 * Filters the post types tracked for content freshness.
		 *
		 * @since 1.0.0
		 *
		 * @param array<int, string> $post_types Post type slugs. Default post and page.
		 */
		$post_types = apply_filters( 'wpcity_cf_post_types', self::DEFAULT_POST_TYPES );

		if ( is_string( $post_types ) ) {
			$post_types = [ $post_types ];
		}

		if ( ! is_array( $post_types ) ) {
			return [];
		}

		$clean = [];

		foreach ( $post_types as $post_type ) {
			if ( is_string( $post_type ) && '' !== $post_type ) {
				$clean[] = $post_type;
			}
		}

		return array_values( array_unique( $clean ) );
	}

	/**
	 * The default review interval, in days.
	 *
	 * Falls back to the shipped default for anything that is not a positive
	 * number. Zero would put every deadline on the baseline date and report the
	 * whole site as overdue, which is worse than ignoring the filter.
	 *
	 * @return int A positive number of days.
	 */
	public static function default_interval(): int {
		/**
		 * Filters the site-wide default review interval.
		 *
		 * @since 1.0.0
		 *
		 * @param int $days Interval in days. Default 180.
		 */
		$days = apply_filters( 'wpcity_cf_default_interval', self::DEFAULT_INTERVAL );

		if ( ! is_numeric( $days ) || (int) $days < 1 ) {
			return self::DEFAULT_INTERVAL;
		}

		return (int) $days;
	}
}
