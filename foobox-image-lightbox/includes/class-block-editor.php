<?php
/**
 * FooBox block editor integrations.
 *
 * @package FooBox
 */

if ( ! class_exists( 'FooBox_Block_Editor' ) ) {

	class FooBox_Block_Editor {

		const SCRIPT_HANDLE = 'foobox-block-editor';
		const STYLE_HANDLE  = 'foobox-block-editor';
		const CORE_LIGHTBOX_SETTING = 'override_core_lightbox';

		/**
		 * Register shared block hooks used outside the editor.
		 */
		public static function init() {
			add_filter( 'render_block_data', array( __CLASS__, 'filter_render_block_data' ), 9, 3 );
			add_filter( 'render_block_core/image', array( __CLASS__, 'render_image_block' ), 20, 3 );
			add_filter( 'render_block_core/gallery', array( __CLASS__, 'render_gallery_block' ), 20, 3 );
		}

		/**
		 * Enqueue block editor controls used to configure FooBox from core blocks.
		 */
		public static function enqueue_assets() {
			$script_path = FOOBOX_BASE_PATH . 'assets/js/foobox-block-editor.js';
			$style_path  = FOOBOX_BASE_PATH . 'assets/css/foobox-block-editor.css';

			wp_enqueue_script(
				self::SCRIPT_HANDLE,
				FOOBOX_BASE_URL . 'assets/js/foobox-block-editor.js',
				array(
					'wp-block-editor',
					'wp-blocks',
					'wp-components',
					'wp-compose',
					'wp-data',
					'wp-element',
					'wp-hooks',
					'wp-i18n',
				),
				file_exists( $script_path ) ? filemtime( $script_path ) : FOOBOX_BASE_VERSION,
				true
			);

			wp_enqueue_style(
				self::STYLE_HANDLE,
				FOOBOX_BASE_URL . 'assets/css/foobox-block-editor.css',
				array( 'wp-components' ),
				file_exists( $style_path ) ? filemtime( $style_path ) : FOOBOX_BASE_VERSION
			);

			wp_localize_script(
				self::SCRIPT_HANDLE,
				'FOOBOX_BLOCK_EDITOR',
				array(
					'overrideCoreLightbox' => self::core_lightbox_override_enabled(),
					'inspectorGroup'       => version_compare( get_bloginfo( 'version' ), '7.0', '>=' ) ? 'content' : '',
					'styles'               => array(
						array(
							'label' => __( 'Default', 'foobox-image-lightbox' ),
							'value' => '',
						),
						array(
							'label' => __( 'Rounded', 'foobox-image-lightbox' ),
							'value' => 'fbx-rounded',
						),
						array(
							'label' => __( 'Flat', 'foobox-image-lightbox' ),
							'value' => 'fbx-flat',
						),
						array(
							'label' => __( 'Metro', 'foobox-image-lightbox' ),
							'value' => 'fbx-metro',
						),
						array(
							'label' => __( 'Glass', 'foobox-image-lightbox' ),
							'value' => 'fbx-glass',
						),
					),
					'themes'               => array(
						array(
							'label' => __( 'Default', 'foobox-image-lightbox' ),
							'value' => '',
						),
						array(
							'label' => __( 'Light', 'foobox-image-lightbox' ),
							'value' => 'fbx-light',
						),
						array(
							'label' => __( 'Dark', 'foobox-image-lightbox' ),
							'value' => 'fbx-dark',
						),
					),
					'effects'              => array(
						array(
							'label' => __( 'Default', 'foobox-image-lightbox' ),
							'value' => '',
						),
						array(
							'label' => __( 'None', 'foobox-image-lightbox' ),
							'value' => 'none',
						),
						array(
							'label' => __( 'Zoom in', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-1',
						),
						array(
							'label' => __( 'Slide from right', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-2',
						),
						array(
							'label' => __( 'Slide from bottom', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-3',
						),
						array(
							'label' => __( 'Newspaper', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-4',
						),
						array(
							'label' => __( 'Fall', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-5',
						),
						array(
							'label' => __( 'Slide fall', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-6',
						),
						array(
							'label' => __( 'Flip horizontal', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-7',
						),
						array(
							'label' => __( 'Flip vertical', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-8',
						),
						array(
							'label' => __( 'Fold down', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-9',
						),
						array(
							'label' => __( 'Super scaled', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-10',
						),
						array(
							'label' => __( 'Swing from bottom', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-11',
						),
						array(
							'label' => __( 'Swing from left', 'foobox-image-lightbox' ),
							'value' => 'fbx-effect-12',
						),
					),
				)
			);
		}

		/**
		 * Convert core Image/Gallery lightbox intent into FooBox block attributes before rendering.
		 *
		 * @param array $parsed_block Parsed block data.
		 * @return array
		 */
		public static function filter_render_block_data( $parsed_block, $source_block = null, $parent_block = null ) {
			$block_name = isset( $parsed_block['blockName'] ) ? $parsed_block['blockName'] : '';

			if ( ! self::core_lightbox_override_enabled() || '' === $block_name ) {
				return $parsed_block;
			}

			if ( 'core/image' === $block_name && self::image_has_core_lightbox_intent( $parsed_block ) ) {
				$parsed_block['attrs'] = self::prepare_image_attrs_for_foobox( isset( $parsed_block['attrs'] ) ? $parsed_block['attrs'] : array() );
				return $parsed_block;
			}

			if ( 'core/gallery' === $block_name && self::gallery_has_core_lightbox_intent( $parsed_block ) ) {
				$group = self::get_gallery_group( $parsed_block );

				$parsed_block['attrs']                  = isset( $parsed_block['attrs'] ) ? $parsed_block['attrs'] : array();
				$parsed_block['attrs']['linkTo']        = 'media';
				$parsed_block['attrs']['fooboxEnabled'] = true;
				$parsed_block['attrs']['fooboxGroup']   = $group;

				if ( ! empty( $parsed_block['innerBlocks'] ) && is_array( $parsed_block['innerBlocks'] ) ) {
					foreach ( $parsed_block['innerBlocks'] as $index => $inner_block ) {
						if ( isset( $inner_block['blockName'] ) && 'core/image' === $inner_block['blockName'] ) {
							$inner_attrs = isset( $inner_block['attrs'] ) ? $inner_block['attrs'] : array();
							$parsed_block['innerBlocks'][ $index ]['attrs'] = self::prepare_image_attrs_for_foobox( $inner_attrs, $group );
						}
					}
				}
			}

			return $parsed_block;
		}

		/**
		 * Add FooBox markup to rendered Image blocks that were converted from core lightbox intent.
		 *
		 * @param string $block_content Rendered block content.
		 * @param array  $block Parsed block.
		 * @return string
		 */
		public static function render_image_block( $block_content, $block, $block_instance = null ) {
			$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();

			if ( empty( $block_content ) || empty( $attrs['fooboxEnabled'] ) ) {
				return $block_content;
			}

			$block_content = self::add_foobox_root_attributes( $block_content, $attrs );

			if ( self::has_anchor( $block_content ) ) {
				return self::add_foobox_link_attributes( $block_content, $attrs );
			}

			return self::wrap_first_image_with_foobox_link( $block_content, $attrs );
		}

		/**
		 * Add per-gallery FooBox options to the Gallery wrapper that owns the frontend instance.
		 *
		 * @param string $block_content Rendered block content.
		 * @param array  $block Parsed block.
		 * @return string
		 */
		public static function render_gallery_block( $block_content, $block, $block_instance = null ) {
			$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();

			if ( empty( $block_content ) || empty( $attrs['fooboxEnabled'] ) ) {
				return $block_content;
			}

			return self::add_foobox_root_attributes( $block_content, $attrs );
		}

		/**
		 * Determine whether the override setting is enabled.
		 *
		 * @return bool
		 */
		public static function core_lightbox_override_enabled() {
			return (bool) apply_filters(
				'foobox_override_core_lightbox',
				self::setting_enabled( self::CORE_LIGHTBOX_SETTING, false )
			);
		}

		/**
		 * Read a checkbox setting from the active Free/Pro option array.
		 *
		 * @param string $key Setting key.
		 * @param bool   $default Default for new installs without an option array.
		 * @return bool
		 */
		private static function setting_enabled( $key, $default = false ) {
			$options = get_option( self::settings_option_name(), false );

			if ( false === $options ) {
				return (bool) $default;
			}

			if ( ! is_array( $options ) ) {
				return (bool) $default;
			}

			return isset( $options[ $key ] ) && 'on' === $options[ $key ];
		}

		/**
		 * Get the active settings option name.
		 *
		 * @return string
		 */
		private static function settings_option_name() {
			if ( function_exists( 'foobox_fs' ) && foobox_fs()->is__premium_only() && foobox_fs()->can_use_premium_code() ) {
				return 'foobox';
			}

			return 'foobox-free';
		}

		/**
		 * Check whether a core Image block should be converted to FooBox.
		 *
		 * @param array $parsed_block Parsed block.
		 * @return bool
		 */
		private static function image_has_core_lightbox_intent( $parsed_block ) {
			$attrs            = isset( $parsed_block['attrs'] ) && is_array( $parsed_block['attrs'] ) ? $parsed_block['attrs'] : array();
			$link_destination = isset( $attrs['linkDestination'] ) ? $attrs['linkDestination'] : 'none';

			if ( 'none' !== $link_destination ) {
				return false;
			}

			if ( isset( $attrs['lightbox'] ) && is_array( $attrs['lightbox'] ) && array_key_exists( 'enabled', $attrs['lightbox'] ) ) {
				return true === $attrs['lightbox']['enabled'];
			}

			if ( function_exists( 'block_core_image_get_lightbox_settings' ) ) {
				$lightbox_settings = block_core_image_get_lightbox_settings( $parsed_block );
				return isset( $lightbox_settings['enabled'] ) && true === $lightbox_settings['enabled'];
			}

			return false;
		}

		/**
		 * Check whether a core Gallery block should be converted to FooBox.
		 *
		 * @param array $parsed_block Parsed block.
		 * @return bool
		 */
		private static function gallery_has_core_lightbox_intent( $parsed_block ) {
			$attrs = isset( $parsed_block['attrs'] ) && is_array( $parsed_block['attrs'] ) ? $parsed_block['attrs'] : array();

			return isset( $attrs['linkTo'] ) && 'lightbox' === $attrs['linkTo'];
		}

		/**
		 * Prepare Image block attributes so FooBox handles the lightbox.
		 *
		 * @param array  $attrs Image block attributes.
		 * @param string $group Optional FooBox group.
		 * @return array
		 */
		private static function prepare_image_attrs_for_foobox( $attrs, $group = '' ) {
			$attrs['fooboxEnabled']  = true;
			$attrs['linkDestination'] = 'media';
			$attrs['lightbox']       = array( 'enabled' => false );

			if ( '' !== $group ) {
				$attrs['fooboxGroup'] = $group;
			}

			return $attrs;
		}

		/**
		 * Build a stable request-local group for converted galleries.
		 *
		 * @param array $parsed_block Parsed Gallery block.
		 * @return string
		 */
		private static function get_gallery_group( $parsed_block ) {
			$attrs = isset( $parsed_block['attrs'] ) && is_array( $parsed_block['attrs'] ) ? $parsed_block['attrs'] : array();

			if ( ! empty( $attrs['fooboxGroup'] ) ) {
				return sanitize_html_class( $attrs['fooboxGroup'] );
			}

			return 'foobox-gallery-' . wp_unique_id();
		}

		/**
		 * Add FooBox attributes to a block wrapper.
		 *
		 * @param string $html Block HTML.
		 * @param array  $attrs Block attributes.
		 * @return string
		 */
		private static function add_foobox_root_attributes( $html, $attrs ) {
			if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
				return $html;
			}

			$processor = new WP_HTML_Tag_Processor( $html );

			if ( ! $processor->next_tag( 'figure' ) ) {
				return $html;
			}

			$processor->add_class( 'foobox' );
			$processor->set_attribute( 'data-foobox-trigger', 'image' );
			self::set_processor_attribute( $processor, 'data-style', isset( $attrs['fooboxStyle'] ) ? $attrs['fooboxStyle'] : '' );
			self::set_processor_attribute( $processor, 'data-theme', isset( $attrs['fooboxTheme'] ) ? $attrs['fooboxTheme'] : '' );
			self::set_processor_attribute( $processor, 'data-effect', isset( $attrs['fooboxEffect'] ) ? $attrs['fooboxEffect'] : '' );
			self::set_processor_attribute( $processor, 'data-foobox-group', isset( $attrs['fooboxGroup'] ) ? $attrs['fooboxGroup'] : '' );

			if ( ! empty( $attrs['fooboxOverflow'] ) ) {
				$processor->set_attribute( 'data-overflow', 'true' );
			}

			$processor->remove_class( 'wp-lightbox-container' );
			$processor->remove_attribute( 'data-wp-interactive' );
			$processor->remove_attribute( 'data-wp-context' );
			$processor->remove_attribute( 'data-wp-key' );

			return $processor->get_updated_html();
		}

		/**
		 * Check whether rendered HTML contains an anchor.
		 *
		 * @param string $html HTML.
		 * @return bool
		 */
		private static function has_anchor( $html ) {
			if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
				return false;
			}

			$processor = new WP_HTML_Tag_Processor( $html );
			return $processor->next_tag( 'a' );
		}

		/**
		 * Add FooBox attributes to the first link in an Image block.
		 *
		 * @param string $html Block HTML.
		 * @param array  $attrs Block attributes.
		 * @return string
		 */
		private static function add_foobox_link_attributes( $html, $attrs ) {
			if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
				return $html;
			}

			$processor = new WP_HTML_Tag_Processor( $html );

			if ( ! $processor->next_tag( 'a' ) ) {
				return $html;
			}

			self::set_link_processor_attributes( $processor, $attrs );

			return $processor->get_updated_html();
		}

		/**
		 * Wrap the first image in a FooBox media link.
		 *
		 * @param string $html Block HTML.
		 * @param array  $attrs Block attributes.
		 * @return string
		 */
		private static function wrap_first_image_with_foobox_link( $html, $attrs ) {
			$href = self::get_image_href( $html, $attrs );

			if ( '' === $href || ! preg_match( '/<img\b[^>]*>/i', $html, $match ) ) {
				return $html;
			}

			$link = '<a' . self::build_link_attribute_string( $attrs, $href ) . '>' . $match[0] . '</a>';

			return preg_replace( '/<img\b[^>]*>/i', $link, $html, 1 );
		}

		/**
		 * Get the media URL for an Image block.
		 *
		 * @param string $html Block HTML.
		 * @param array  $attrs Block attributes.
		 * @return string
		 */
		private static function get_image_href( $html, $attrs ) {
			if ( ! empty( $attrs['id'] ) ) {
				$url = wp_get_attachment_url( absint( $attrs['id'] ) );
				if ( $url ) {
					return $url;
				}
			}

			if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
				return '';
			}

			$processor = new WP_HTML_Tag_Processor( $html );

			if ( $processor->next_tag( 'img' ) ) {
				$src = $processor->get_attribute( 'src' );
				return is_string( $src ) ? $src : '';
			}

			return '';
		}

		/**
		 * Add FooBox link attributes to a tag processor positioned on an anchor.
		 *
		 * @param WP_HTML_Tag_Processor $processor Processor.
		 * @param array                 $attrs Block attributes.
		 */
		private static function set_link_processor_attributes( $processor, $attrs ) {
			self::set_processor_attribute( $processor, 'data-width', isset( $attrs['fooboxWidth'] ) ? $attrs['fooboxWidth'] : '' );
			self::set_processor_attribute( $processor, 'data-height', isset( $attrs['fooboxHeight'] ) ? $attrs['fooboxHeight'] : '' );
			self::set_processor_attribute( $processor, 'data-max-width', isset( $attrs['fooboxMaxWidth'] ) ? $attrs['fooboxMaxWidth'] : '' );
			self::set_processor_attribute( $processor, 'data-max-height', isset( $attrs['fooboxMaxHeight'] ) ? $attrs['fooboxMaxHeight'] : '' );
			self::set_processor_attribute( $processor, 'data-foobox-group', isset( $attrs['fooboxGroup'] ) ? $attrs['fooboxGroup'] : '' );
			self::set_processor_attribute( $processor, 'data-caption-title', isset( $attrs['fooboxCaptionTitle'] ) ? $attrs['fooboxCaptionTitle'] : '' );
			self::set_processor_attribute( $processor, 'data-caption-desc', isset( $attrs['fooboxCaptionDesc'] ) ? $attrs['fooboxCaptionDesc'] : '' );

			if ( ! empty( $attrs['fooboxOverflow'] ) ) {
				$processor->set_attribute( 'data-overflow', 'true' );
			}
		}

		/**
		 * Build escaped attributes for a new FooBox anchor.
		 *
		 * @param array  $attrs Block attributes.
		 * @param string $href Link href.
		 * @return string
		 */
		private static function build_link_attribute_string( $attrs, $href ) {
			$link_attrs = array(
				'href' => $href,
			);

			$attribute_map = array(
				'fooboxWidth'        => 'data-width',
				'fooboxHeight'       => 'data-height',
				'fooboxMaxWidth'     => 'data-max-width',
				'fooboxMaxHeight'    => 'data-max-height',
				'fooboxGroup'        => 'data-foobox-group',
				'fooboxCaptionTitle' => 'data-caption-title',
				'fooboxCaptionDesc'  => 'data-caption-desc',
			);

			foreach ( $attribute_map as $attr_key => $html_attr ) {
				if ( isset( $attrs[ $attr_key ] ) && '' !== trim( (string) $attrs[ $attr_key ] ) ) {
					$link_attrs[ $html_attr ] = $attrs[ $attr_key ];
				}
			}

			if ( ! empty( $attrs['fooboxOverflow'] ) ) {
				$link_attrs['data-overflow'] = 'true';
			}

			$output = '';
			foreach ( $link_attrs as $name => $value ) {
				$output .= ' ' . $name . '="' . esc_attr( $value ) . '"';
			}

			return $output;
		}

		/**
		 * Set a processor attribute when a value is not empty.
		 *
		 * @param WP_HTML_Tag_Processor $processor Processor.
		 * @param string                $name Attribute name.
		 * @param mixed                 $value Attribute value.
		 */
		private static function set_processor_attribute( $processor, $name, $value ) {
			if ( '' !== trim( (string) $value ) ) {
				$processor->set_attribute( $name, $value );
			}
		}
	}
}
