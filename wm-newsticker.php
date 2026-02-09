<?php
/**
 * Plugin Name: WM Newsticker
 * Plugin URI: https://github.com/arnoldwender/wm-newsticker
 * Description: A Gutenberg block for animated news tickers with scroll, fade, slide and typing animations.
 * Version: 1.4.6
 * Author: Arnold Wender
 * Author URI: https://www.wendermedia.com
 * License: GPL v2 or later
 * Text Domain: wm-newsticker
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * @package WM_Newsticker
 */

// Security: Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Security: Prevent direct file execution.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define plugin constants.
define( 'WM_NEWSTICKER_VERSION', '1.4.6' );
define( 'WM_NEWSTICKER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WM_NEWSTICKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
final class WM_Newsticker {

	/**
	 * Single instance of the class.
	 *
	 * @var WM_Newsticker
	 */
	private static $instance = null;

	/**
	 * Get single instance of the class.
	 *
	 * @return WM_Newsticker
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor - private to enforce singleton.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route( 'wm-newsticker/v1', '/post-types', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_post_types' ),
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}

	/**
	 * Get available post types for REST API.
	 *
	 * @return WP_REST_Response
	 */
	public function get_post_types() {
		$post_types = get_post_types(
			array(
				'public'       => true,
				'show_in_rest' => true,
			),
			'objects'
		);

		$result = array();
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			$result[] = array(
				'value' => $post_type->name,
				'label' => $post_type->labels->singular_name,
			);
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Register the Gutenberg block using block.json metadata.
	 *
	 * @return void
	 */
	public function register_block() {
		register_block_type( WM_NEWSTICKER_PLUGIN_DIR, array(
			'render_callback' => array( $this, 'render_block' ),
		) );

		wp_set_script_translations(
			'wm-newsticker-editor-script',
			'wm-newsticker',
			WM_NEWSTICKER_PLUGIN_DIR . 'languages'
		);
	}

	/**
	 * Sanitize a color value (hex, rgb, rgba, or CSS variable).
	 *
	 * @param string $color The color to sanitize.
	 * @return string Sanitized color or default.
	 */
	private function sanitize_color( $color ) {
		if ( empty( $color ) ) {
			return '';
		}

		// Valid hex color.
		if ( preg_match( '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color ) ) {
			return $color;
		}

		// RGB/RGBA color.
		if ( preg_match( '/^rgba?\s*\(\s*\d+\s*,\s*\d+\s*,\s*\d+/', $color ) ) {
			return $color;
		}

		// CSS variable (theme colors).
		if ( preg_match( '/^var\s*\(\s*--[a-zA-Z0-9_-]+/', $color ) ) {
			return $color;
		}

		return '#000000';
	}

	/**
	 * Sanitize spacing values (padding, margin, border-radius).
	 *
	 * @param array $spacing The spacing object to sanitize.
	 * @return array Sanitized spacing values.
	 */
	private function sanitize_spacing( $spacing ) {
		$default = array(
			'top'    => '0px',
			'right'  => '0px',
			'bottom' => '0px',
			'left'   => '0px',
		);

		if ( ! is_array( $spacing ) ) {
			return $default;
		}

		$sanitized = array();
		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			$sanitized[ $side ] = isset( $spacing[ $side ] )
				? $this->sanitize_css_value( $spacing[ $side ] )
				: '0px';
		}

		return $sanitized;
	}

	/**
	 * Sanitize CSS value (e.g., "10px", "1rem", "5%").
	 *
	 * @param string $value The value to sanitize.
	 * @return string Sanitized value.
	 */
	private function sanitize_css_value( $value ) {
		if ( empty( $value ) ) {
			return '0px';
		}

		if ( preg_match( '/^-?\d*\.?\d+(px|em|rem|%|vh|vw)?$/', $value ) ) {
			return $value;
		}

		return '0px';
	}

	/**
	 * Sanitize box shadow value.
	 *
	 * @param string $value The box shadow value.
	 * @return string Sanitized value.
	 */
	private function sanitize_box_shadow( $value ) {
		$allowed = array(
			'none',
			'0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)',
			'0 3px 6px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.12)',
			'0 10px 20px rgba(0,0,0,0.15), 0 3px 6px rgba(0,0,0,0.10)',
			'inset 0 2px 4px rgba(0,0,0,0.1)',
		);

		return in_array( $value, $allowed, true ) ? $value : 'none';
	}

	/**
	 * Convert spacing array to CSS string.
	 *
	 * @param array $spacing The spacing values.
	 * @return string CSS value string.
	 */
	private function spacing_to_css( $spacing ) {
		return sprintf(
			'%s %s %s %s',
			$spacing['top'],
			$spacing['right'],
			$spacing['bottom'],
			$spacing['left']
		);
	}

	/**
	 * Build inline style string from array.
	 *
	 * @param array $styles Key-value pairs of CSS properties.
	 * @return string Style string.
	 */
	private function build_style_string( $styles ) {
		$style_parts = array();

		foreach ( $styles as $property => $value ) {
			if ( ! empty( $value ) && 'none' !== $value && '0px 0px 0px 0px' !== $value ) {
				$style_parts[] = $property . ': ' . $value;
			}
		}

		return implode( '; ', $style_parts );
	}

	/**
	 * Sanitize numeric value within range.
	 *
	 * @param mixed $value   The value to sanitize.
	 * @param int   $min     Minimum allowed value.
	 * @param int   $max     Maximum allowed value.
	 * @param int   $default Default value if invalid.
	 * @return int Sanitized integer.
	 */
	private function sanitize_number_range( $value, $min, $max, $default ) {
		$value = absint( $value );

		if ( $value < $min || $value > $max ) {
			return $default;
		}

		return $value;
	}

	/**
	 * Sanitize item array.
	 *
	 * @param array $item The item to sanitize.
	 * @return array Sanitized item.
	 */
	private function sanitize_item( $item ) {
		return array(
			'text'   => isset( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '',
			'link'   => isset( $item['link'] ) ? esc_url_raw( $item['link'] ) : '',
			'newTab' => isset( $item['newTab'] ) ? (bool) $item['newTab'] : false,
		);
	}

	/**
	 * Get dynamic posts for ticker.
	 *
	 * @param array $attributes Block attributes.
	 * @return array Array of items.
	 */
	private function get_dynamic_items( $attributes ) {
		$items = array();

		$post_type   = isset( $attributes['postType'] ) ? sanitize_key( $attributes['postType'] ) : 'post';
		$posts_count = $this->sanitize_number_range(
			isset( $attributes['postsCount'] ) ? $attributes['postsCount'] : 5,
			1, 20, 5
		);

		$valid_orderby = array( 'date', 'title', 'modified', 'rand', 'comment_count' );
		$orderby       = isset( $attributes['orderBy'] ) && in_array( $attributes['orderBy'], $valid_orderby, true )
			? $attributes['orderBy']
			: 'date';

		$order = isset( $attributes['order'] ) && in_array( $attributes['order'], array( 'ASC', 'DESC' ), true )
			? $attributes['order']
			: 'DESC';

		$show_date   = isset( $attributes['showDate'] ) ? (bool) $attributes['showDate'] : false;
		$date_format = isset( $attributes['dateFormat'] ) ? sanitize_text_field( $attributes['dateFormat'] ) : 'relative';

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_count,
			'orderby'        => $orderby,
			'order'          => $order,
			'post_status'    => 'publish',
		);

		if ( ! empty( $attributes['categoryIds'] ) && is_array( $attributes['categoryIds'] ) ) {
			$category_ids = array_map( 'absint', $attributes['categoryIds'] );
			$category_ids = array_filter( $category_ids );
			if ( ! empty( $category_ids ) ) {
				$args['category__in'] = $category_ids;
			}
		}

		if ( ! empty( $attributes['tagIds'] ) && is_array( $attributes['tagIds'] ) ) {
			$tag_ids = array_map( 'absint', $attributes['tagIds'] );
			$tag_ids = array_filter( $tag_ids );
			if ( ! empty( $tag_ids ) ) {
				$args['tag__in'] = $tag_ids;
			}
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$text = get_the_title();

				if ( $show_date ) {
					$date_text = $this->format_post_date( get_the_date( 'U' ), $date_format );
					$text      = $date_text . ' — ' . $text;
				}

				$items[] = array(
					'text'   => $text,
					'link'   => get_permalink(),
					'newTab' => false,
				);
			}
			wp_reset_postdata();
		}

		return $items;
	}

	/**
	 * Format post date.
	 *
	 * @param int    $timestamp Unix timestamp.
	 * @param string $format    Date format type.
	 * @return string Formatted date.
	 */
	private function format_post_date( $timestamp, $format ) {
		if ( 'relative' === $format ) {
			return human_time_diff( $timestamp, time() ) . ' ' . __( 'ago', 'wm-newsticker' );
		}

		return date_i18n( get_option( 'date_format' ), $timestamp );
	}

	/**
	 * Render the block on the frontend.
	 *
	 * @param array $attributes Block attributes.
	 * @return string Rendered HTML.
	 */
	public function render_block( $attributes ) {
		$content_source = isset( $attributes['contentSource'] ) ? sanitize_key( $attributes['contentSource'] ) : 'manual';

		if ( 'posts' === $content_source ) {
			$items = $this->get_dynamic_items( $attributes );
		} else {
			$items = array();
			if ( isset( $attributes['items'] ) && is_array( $attributes['items'] ) ) {
				foreach ( $attributes['items'] as $item ) {
					if ( is_array( $item ) ) {
						$items[] = $this->sanitize_item( $item );
					}
				}
			}
		}

		if ( empty( $items ) ) {
			return '';
		}

		// Sanitize numeric values.
		$speed     = $this->sanitize_number_range(
			isset( $attributes['speed'] ) ? $attributes['speed'] : 50,
			10, 100, 50
		);
		$font_size = $this->sanitize_number_range(
			isset( $attributes['fontSize'] ) ? $attributes['fontSize'] : 14,
			10, 72, 14
		);
		$height    = $this->sanitize_number_range(
			isset( $attributes['height'] ) ? $attributes['height'] : 45,
			20, 200, 45
		);

		// Sanitize booleans.
		$pause_on_hover = isset( $attributes['pauseOnHover'] ) ? (bool) $attributes['pauseOnHover'] : true;
		$is_rtl         = isset( $attributes['isRTL'] ) ? (bool) $attributes['isRTL'] : false;

		// Sanitize controls options.
		$show_controls   = isset( $attributes['showControls'] ) ? (bool) $attributes['showControls'] : false;
		$show_play_pause = isset( $attributes['showPlayPause'] ) ? (bool) $attributes['showPlayPause'] : true;
		$show_prev_next  = isset( $attributes['showPrevNext'] ) ? (bool) $attributes['showPrevNext'] : true;
		$show_progress   = isset( $attributes['showProgress'] ) ? (bool) $attributes['showProgress'] : false;

		$valid_positions   = array( 'left', 'right' );
		$controls_position = isset( $attributes['controlsPosition'] ) && in_array( $attributes['controlsPosition'], $valid_positions, true )
			? $attributes['controlsPosition']
			: 'right';

		// Sanitize animation type and direction.
		$valid_animations = array( 'scroll', 'fade', 'slide', 'typing' );
		$animation_type   = isset( $attributes['animationType'] ) && in_array( $attributes['animationType'], $valid_animations, true )
			? $attributes['animationType']
			: 'scroll';

		$valid_directions = array( 'left', 'right', 'up', 'down' );
		$direction        = isset( $attributes['direction'] ) && in_array( $attributes['direction'], $valid_directions, true )
			? $attributes['direction']
			: 'left';

		// Sanitize colors.
		$bg_color         = $this->sanitize_color( isset( $attributes['backgroundColor'] ) ? $attributes['backgroundColor'] : '#1a1a2e' );
		$text_color       = $this->sanitize_color( isset( $attributes['textColor'] ) ? $attributes['textColor'] : '#ffffff' );
		$label_bg_color   = $this->sanitize_color( isset( $attributes['labelBackgroundColor'] ) ? $attributes['labelBackgroundColor'] : '#e94560' );
		$label_text_color = $this->sanitize_color( isset( $attributes['labelTextColor'] ) ? $attributes['labelTextColor'] : '#ffffff' );

		// Sanitize text fields.
		$label_text = isset( $attributes['labelText'] ) ? sanitize_text_field( $attributes['labelText'] ) : '';
		$separator  = isset( $attributes['separator'] ) ? sanitize_text_field( $attributes['separator'] ) : '•';

		// Limit separator length.
		$separator = mb_substr( $separator, 0, 5 );

		// Sanitize spacing and border attributes.
		$border_radius = $this->sanitize_spacing( isset( $attributes['borderRadius'] ) ? $attributes['borderRadius'] : array() );
		$padding       = $this->sanitize_spacing( isset( $attributes['padding'] ) ? $attributes['padding'] : array() );
		$margin        = $this->sanitize_spacing( isset( $attributes['margin'] ) ? $attributes['margin'] : array() );
		$border_width  = $this->sanitize_css_value( isset( $attributes['borderWidth'] ) ? $attributes['borderWidth'] : '0px' );
		$border_color  = $this->sanitize_color( isset( $attributes['borderColor'] ) ? $attributes['borderColor'] : '#000000' );

		$valid_border_styles = array( 'solid', 'dashed', 'dotted', 'none' );
		$border_style        = isset( $attributes['borderStyle'] ) && in_array( $attributes['borderStyle'], $valid_border_styles, true )
			? $attributes['borderStyle']
			: 'solid';

		$box_shadow = isset( $attributes['boxShadow'] ) ? $this->sanitize_box_shadow( $attributes['boxShadow'] ) : 'none';

		// Generate unique ID.
		$unique_id = 'wm-newsticker-' . wp_unique_id();

		// Calculate animation duration.
		$item_count = count( $items );
		if ( 'scroll' === $animation_type ) {
			$animation_duration = max( 10, $item_count * ( 100 - $speed ) / 5 );
		} else {
			$animation_duration = max( 2, ( 100 - $speed ) / 10 );
		}

		$should_duplicate = ( 'scroll' === $animation_type && $item_count > 2 );

		// Build CSS classes.
		$wrapper_classes   = array( 'wm-newsticker-wrapper' );
		$wrapper_classes[] = 'wm-animation-' . $animation_type;
		$wrapper_classes[] = 'wm-direction-' . $direction;
		if ( $is_rtl ) {
			$wrapper_classes[] = 'wm-rtl';
		}
		if ( $show_controls ) {
			$wrapper_classes[] = 'wm-has-controls';
			$wrapper_classes[] = 'wm-controls-' . $controls_position;
		}

		// Build inline styles.
		$wrapper_styles = array(
			'background-color' => $bg_color,
			'height'           => $height . 'px',
			'border-radius'    => $this->spacing_to_css( $border_radius ),
			'padding'          => $this->spacing_to_css( $padding ),
			'margin'           => $this->spacing_to_css( $margin ),
			'border-width'     => $border_width,
			'border-color'     => $border_color,
			'border-style'     => $border_style,
			'box-shadow'       => $box_shadow,
		);

		$style_string = $this->build_style_string( $wrapper_styles );

		ob_start();
		?>
		<div id="<?php echo esc_attr( $unique_id ); ?>"
			class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
			style="<?php echo esc_attr( $style_string ); ?>"
			role="marquee"
			aria-label="<?php esc_attr_e( 'News ticker', 'wm-newsticker' ); ?>"
			aria-live="off"
			data-pause-on-hover="<?php echo esc_attr( $pause_on_hover ? 'true' : 'false' ); ?>"
			data-animation="<?php echo esc_attr( $animation_type ); ?>"
			data-direction="<?php echo esc_attr( $direction ); ?>"
			data-speed="<?php echo esc_attr( $animation_duration ); ?>"
			data-items="<?php echo esc_attr( $item_count ); ?>"
			data-has-controls="<?php echo esc_attr( $show_controls ? 'true' : 'false' ); ?>">

			<?php if ( ! empty( $label_text ) ) : ?>
			<div class="wm-newsticker-label" style="background-color: <?php echo esc_attr( $label_bg_color ); ?>; color: <?php echo esc_attr( $label_text_color ); ?>; font-size: <?php echo esc_attr( $font_size ); ?>px;">
				<?php echo esc_html( $label_text ); ?>
			</div>
			<?php endif; ?>

			<?php if ( $show_controls && 'left' === $controls_position ) : ?>
				<?php $this->render_controls( $show_play_pause, $show_prev_next, $text_color ); ?>
			<?php endif; ?>

			<div class="wm-newsticker-content">
				<?php if ( $show_progress ) : ?>
					<div class="wm-newsticker-progress">
						<div class="wm-newsticker-progress-bar" style="animation-duration: <?php echo esc_attr( $animation_duration ); ?>s;"></div>
					</div>
				<?php endif; ?>
				<?php if ( 'scroll' === $animation_type ) : ?>
					<div class="wm-newsticker-track<?php echo $should_duplicate ? '' : ' wm-newsticker-no-duplicate'; ?>" style="animation-duration: <?php echo esc_attr( $animation_duration ); ?>s;">
						<?php
						$this->render_items( $items, $separator, $text_color, $font_size, true );

						if ( $should_duplicate ) {
							$this->render_items( $items, $separator, $text_color, $font_size, false );
						}
						?>
					</div>
				<?php else : ?>
					<div class="wm-newsticker-slides" data-current="0">
						<?php foreach ( $items as $index => $item ) : ?>
							<div class="wm-newsticker-slide<?php echo 0 === $index ? ' active' : ''; ?>"
								style="color: <?php echo esc_attr( $text_color ); ?>; font-size: <?php echo esc_attr( $font_size ); ?>px;"
								data-index="<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $item['link'] ) ) : ?>
									<a href="<?php echo esc_url( $item['link'] ); ?>"
										style="color: <?php echo esc_attr( $text_color ); ?>;"
										<?php echo ! empty( $item['newTab'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
										<?php echo esc_html( $item['text'] ); ?>
									</a>
								<?php else : ?>
									<span><?php echo esc_html( $item['text'] ); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_controls && 'right' === $controls_position ) : ?>
				<?php $this->render_controls( $show_play_pause, $show_prev_next, $text_color ); ?>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render ticker controls.
	 *
	 * @param bool   $show_play_pause Whether to show play/pause button.
	 * @param bool   $show_prev_next  Whether to show prev/next buttons.
	 * @param string $text_color      Text color for icons.
	 * @return void
	 */
	private function render_controls( $show_play_pause, $show_prev_next, $text_color ) {
		?>
		<div class="wm-newsticker-controls" style="color: <?php echo esc_attr( $text_color ); ?>;">
			<?php if ( $show_prev_next ) : ?>
				<button type="button" class="wm-control-btn wm-control-prev" aria-label="<?php esc_attr_e( 'Previous', 'wm-newsticker' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<polyline points="15 18 9 12 15 6"></polyline>
					</svg>
				</button>
			<?php endif; ?>
			<?php if ( $show_play_pause ) : ?>
				<button type="button" class="wm-control-btn wm-control-play-pause" aria-label="<?php esc_attr_e( 'Play/Pause', 'wm-newsticker' ); ?>" data-playing="true">
					<svg class="wm-icon-pause" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<rect x="6" y="4" width="4" height="16"></rect>
						<rect x="14" y="4" width="4" height="16"></rect>
					</svg>
					<svg class="wm-icon-play" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;" aria-hidden="true" focusable="false">
						<polygon points="5 3 19 12 5 21 5 3"></polygon>
					</svg>
				</button>
			<?php endif; ?>
			<?php if ( $show_prev_next ) : ?>
				<button type="button" class="wm-control-btn wm-control-next" aria-label="<?php esc_attr_e( 'Next', 'wm-newsticker' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<polyline points="9 18 15 12 9 6"></polyline>
					</svg>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render ticker items.
	 *
	 * @param array  $items     Items to render.
	 * @param string $separator Separator between items.
	 * @param string $text_color Text color.
	 * @param int    $font_size Font size.
	 * @param bool   $is_first  Whether this is the first set of items.
	 * @return void
	 */
	private function render_items( $items, $separator, $text_color, $font_size, $is_first = true ) {
		foreach ( $items as $index => $item ) {
			if ( ! $is_first || $index > 0 ) {
				?>
				<span class="wm-newsticker-separator" style="color: <?php echo esc_attr( $text_color ); ?>; font-size: <?php echo esc_attr( $font_size ); ?>px;">
					<?php echo esc_html( $separator ); ?>
				</span>
				<?php
			}

			if ( ! empty( $item['link'] ) ) {
				$target_attr = ! empty( $item['newTab'] ) ? '_blank' : '_self';
				$rel_attr    = ! empty( $item['newTab'] ) ? 'noopener noreferrer' : '';
				?>
				<a href="<?php echo esc_url( $item['link'] ); ?>"
					class="wm-newsticker-item"
					style="color: <?php echo esc_attr( $text_color ); ?>; font-size: <?php echo esc_attr( $font_size ); ?>px;"
					target="<?php echo esc_attr( $target_attr ); ?>"
					<?php if ( $rel_attr ) : ?>rel="<?php echo esc_attr( $rel_attr ); ?>"<?php endif; ?>>
					<?php echo esc_html( $item['text'] ); ?>
				</a>
				<?php
			} else {
				?>
				<span class="wm-newsticker-item" style="color: <?php echo esc_attr( $text_color ); ?>; font-size: <?php echo esc_attr( $font_size ); ?>px;">
					<?php echo esc_html( $item['text'] ); ?>
				</span>
				<?php
			}
		}
	}
}

// Initialize the plugin.
WM_Newsticker::get_instance();
