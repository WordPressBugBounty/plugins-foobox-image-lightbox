<?php

if ( !class_exists( 'FooBox_Free_Settings' ) ) {

	class FooBox_Free_Settings {

		function __construct() {
			add_filter( 'foobox-free-admin_settings', array( $this, 'create_settings' ) );
			// Sidebar content moved into the "Thanks" tab. Sidebar hook removed.
		}

		function create_settings() {
			//region General Tab
			$tabs['general'] = __('General', 'foobox-image-lightbox');

			$sections['attach'] = array(
				'tab' => 'general',
				'name' => __('What do you want to attach FooBox to?', 'foobox-image-lightbox')
			);

			$settings[] = array(
				'id'      => 'enable_galleries',
				'title'   => __( 'WordPress Galleries', 'foobox-image-lightbox' ),
				'desc'    => __( 'Enable FooBox for all WordPress image galleries.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'attach',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'enable_captions',
				'title'   => __( 'WordPress Images With Captions', 'foobox-image-lightbox' ),
				'desc'    => __( 'Enable FooBox for all WordPress images that have captions.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'attach',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'enable_attachments',
				'title'   => __( 'Attachment Images', 'foobox-image-lightbox' ),
				'desc'    => __( 'Enable FooBox for all media images included in posts or pages.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'attach',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'enable_class',
				'title'   => __( 'Specific CSS classes', 'foobox-image-lightbox' ),
				'desc'    => __( 'Enable FooBox on specific container elements that have a specific CSS class name.<br />Use this to target only very specific elements in your site.<br />Example : <code>.container, .gallery</code>', 'foobox-image-lightbox' ),
				'type'    => 'text',
				'section' => 'attach',
				'tab'     => 'general'
			);

			$sections['settings'] = array(
				'tab' => 'settings',
				'name' => __('Display Settings', 'foobox-image-lightbox')
			);

			// New: Editor Defaults section
			$sections['editor_defaults'] = array(
				'tab'  => 'general',
				'name' => __( 'Editor Defaults', 'foobox-image-lightbox' ),
			);

			$settings[] = array(
				'id'      => 'fit_to_screen',
				'title'   => __( 'Fit To Screen', 'foobox-image-lightbox' ),
				'desc'    => __( 'Force smaller images to fit the screen dimensions.', 'foobox-image-lightbox' ),
				'default' => 'off',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'hide_scrollbars',
				'title'   => __( 'Hide Page Scrollbars', 'foobox-image-lightbox' ),
				'desc'    => __( 'Hide the page\'s scrollbars when FooBox is visible.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'show_count',
				'title'   => __( 'Show Counter', 'foobox-image-lightbox' ),
				'desc'    => __( 'Shows a counter under the FooBox modal when viewing a gallery of images.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'count_message',
				'title'   => __( 'Count Message', 'foobox-image-lightbox' ),
				'desc'    => __( 'the message to use as the item counter. The fields <code>%index</code> and <code>%total</code> can be used to substitute the correct values. <br/ >Example : <code>item %index / %total</code> would result in <code>item 1 / 7</code>', 'foobox-image-lightbox' ),
				'default' => 'item %index of %total',
				'type'    => 'text',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'hide_caption',
				'title'   => __( 'Hide Captions', 'foobox-image-lightbox' ),
				'desc'    => __( 'Whether or not to hide captions for images.', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'captions_show_on_hover',
				'title'   => __( 'Show Captions On Hover', 'foobox-image-lightbox' ),
				'desc'    => __( 'Only show the caption when hovering over the image.', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'section' => 'settings',
				'tab'     => 'general'
			);

			$settings[] = array(
				'id'      => 'error_message',
				'title'   => __( 'Error Message', 'foobox-image-lightbox' ),
				'desc'    => __( 'The error message to display when an image has trouble loading.', 'foobox-image-lightbox' ),
				'default' => __( 'Could not load the item', 'foobox-image-lightbox' ),
				'type'    => 'text',
				'section' => 'settings',
				'tab'     => 'general'
			);

			// Setting row: Default Image Link (custom render)
			$settings[] = array(
				'id'      => 'default_image_link',
				'title'   => __( 'Default Image Link', 'foobox-image-lightbox' ),
				'desc'    => __( "Controls WordPress' default when inserting images. FooBox works best when images link to the media file.", 'foobox-image-lightbox' ),
				'type'    => 'editor_default_image_link',
				'section' => 'editor_defaults',
				'tab'     => 'general'
			);

			//endregion

			//region Advanced Tab

			$tabs['advanced'] = __('Advanced', 'foobox-image-lightbox');

			$settings[] = array(
				'id'      => 'close_overlay_click',
				'title'   => __( 'Close On Overlay Click', 'foobox-image-lightbox' ),
				'desc'    => __( 'Should the FooBox lightbox close when the overlay is clicked.', 'foobox-image-lightbox' ),
				'default' => 'on',
				'type'    => 'checkbox',
				'tab'     => 'advanced'
			);

			$settings[] = array(
				'id'      => 'custom_excludes',
				'title'   => __( 'Custom Excludes', 'foobox-image-lightbox' ),
				'desc'    => __( 'The exclude selector is used to exclude certain elements, so that they do not open in FooBox. (Default is <code>.fbx-link, .nofoobox</code>)', 'foobox-image-lightbox' ),
				'type'    => 'text',
				'tab'     => 'advanced',
				'class'   => 'short_input'
			);

			$settings[] = array(
				'id'      => 'image_rel_selector',
				'title'   => __( 'REL Grouping', 'foobox-image-lightbox' ),
				'desc'    => __( 'Images can be grouped by their REL attribute if needed.', 'foobox-image-lightbox' ),
				'default' => '',
				'type'    => 'text',
				'tab'     => 'advanced'
			);

			$settings[] = array(
				'id'      => 'disable_others',
				'title'   => __( 'Disable Other Lightboxes', 'foobox-image-lightbox' ),
				'desc'    => __( 'Certain themes and plugins use a hard-coded lightbox, which make it very difficult to override.<br>By enabling this setting, we inject a small amount of javascript onto the page which attempts to get around this issue.<br>But please note this is not guaranteed, as we cannot account for every lightbox solution out there :)', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'tab'     => 'advanced'
			);

			$settings[] = array(
				'id'      => 'enable_debug',
				'title'   => __( 'Enable Debug Mode', 'foobox-image-lightbox' ),
				'desc'    => __( 'Show an extra debug information tab to help debug any issues.', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'tab'     => 'advanced'
			);

			$settings[] = array(
				'id'      => 'force_hide_trial',
				'title'   => __( 'Force Hide Trial Notice', 'foobox-image-lightbox' ),
				'desc'    => __( 'Force the FooBox trial notice admin banner to never show', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'tab'     => 'advanced'
			);

			$settings[] = array(
				'id'      => 'excludebydefault',
				'title'   => __( 'Exclude FooBox Assets', 'foobox-image-lightbox' ),
				'desc'    => __( 'By default, FooBox includes javascript and stylesheet assets into all your pages. We do this, because we do not know if the page content contains media or not.<br>If you want more control over when FooBox assets are included, you can now exclude FooBox assets by default, by enabling this setting. Then on each page, you can choose to include the assets if required.<br>Or you can leave the setting disabled, and then choose to exclude FooBox assets from particular pages. A new FooBox metabox is now available when editing your pages or posts.', 'foobox-image-lightbox' ),
				'type'    => 'checkbox',
				'section' => __( 'JS &amp; CSS', 'foobox-image-lightbox' ),
				'tab'     => 'advanced'
			);

			//endregion

			//region Debug Tab
			$foobox_free = Foobox_Free::get_instance();

			if ( $foobox_free->options()->is_checked( 'enable_debug', false ) ) {

				$tabs['debug'] = __('Debug', 'foobox-image-lightbox');

				$settings[] = array(
					'id'      => 'debug_output',
					'title'   => __( 'Debug Information', 'foobox-image-lightbox' ),
					'type'    => 'debug_output',
					'tab'     => 'debug'
				);
			}
			//endregion

			//region Upgrade tab
			$tabs['upgrade'] = __('Upgrade', 'foobox-image-lightbox') . '<i class="dashicons dashicons-unlock"></i>';

			$link_text = __('FooBox PRO Pricing', 'foobox-image-lightbox');

			if ( foobox_hide_pricing_menu() ) {
				$link_text = '';
			}

			$link = sprintf( '<p><a href="%s">%s</a></p><br />',  esc_url ( foobox_pricing_url() ), $link_text );

			$settings[] = array(
				'id'    => 'upgrade',
				'title' => $link,
				'type'  => 'upgrade',
				'tab'   => 'upgrade'
			);
			//endregion

			//region Thanks tab
			$tabs['thanks'] = __('Thanks', 'foobox-image-lightbox') . '<i class="dashicons dashicons-heart"></i>';

			$settings[] = array(
				'id'    => 'thanks',
				'title' => __('Thanks for using FooBox!', 'foobox-image-lightbox'),
				'type'  => 'thanks',
				'tab'   => 'thanks'
			);
			//endregion

			return array(
				'tabs' => $tabs,
				'sections' => $sections,
				'settings' => $settings
			);
		}
	}
}
