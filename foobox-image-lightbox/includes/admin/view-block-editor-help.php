<?php
/**
 * Shared Block Editor help for the FooBox Free and Pro getting-started screens.
 *
 * @package FooBox
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$foobox_block_editor_is_pro = ! empty( $foobox_block_editor_is_pro );
$foobox_block_editor_docs_url = 'https://fooplugins.com/documentation/foobox/';
$foobox_block_editor_settings_url = foobox_settings_url() . '#general';
$foobox_block_editor_new_page_url = admin_url( 'post-new.php?post_type=page' );
?>
<style>
	.foobox-block-editor-help {
		max-width: 1040px;
	}

	.foobox-block-editor-help .foobox-help-intro {
		font-size: 15px;
		max-width: 760px;
	}

	.foobox-block-editor-help .foobox-help-actions {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
		margin: 20px 0 24px;
	}

	.foobox-block-editor-help .foobox-help-workflows {
		display: grid;
		gap: 16px;
		grid-template-columns: repeat( 2, minmax( 0, 1fr ) );
		margin: 20px 0 28px;
	}

	.foobox-block-editor-help .foobox-help-card {
		box-sizing: border-box;
		margin: 0;
		max-width: none;
		padding: 20px;
	}

	.foobox-block-editor-help .foobox-help-card h3 {
		align-items: center;
		display: flex;
		gap: 8px;
		margin-top: 0;
	}

	.foobox-block-editor-help .foobox-help-card h3 > .dashicons {
		color: currentColor;
		font-family: dashicons;
		font-size: 20px;
		height: 20px;
		line-height: 1;
		padding: 0;
		width: 20px;
	}

	.foobox-block-editor-help .foobox-help-card ol,
	.foobox-block-editor-help .foobox-help-troubleshooting {
		margin-bottom: 0;
		padding-left: 20px;
	}

	.foobox-block-editor-help .foobox-help-badge {
		background: #f0f0f1;
		border-radius: 2px;
		color: #50575e;
		font-size: 11px;
		font-weight: 600;
		line-height: 1.8;
		padding: 0 6px;
		text-transform: uppercase;
	}

	.foobox-block-editor-help .foobox-help-table {
		margin: 12px 0 24px;
	}

	.foobox-block-editor-help .notice {
		margin: 16px 0 24px;
	}

	@media screen and ( max-width: 782px ) {
		.foobox-block-editor-help .foobox-help-workflows {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="foobox-block-editor-help">
	<h2><?php esc_html_e( 'Create lightboxes in the Block Editor', 'foobox-image-lightbox' ); ?></h2>
	<p class="foobox-help-intro">
		<?php esc_html_e( 'FooBox adds native controls to WordPress Image, Gallery, Button, and Group blocks. You can build common lightbox layouts without writing HTML or adding custom CSS selectors.', 'foobox-image-lightbox' ); ?>
	</p>

	<ol>
		<li><?php esc_html_e( 'Add or select a supported block in the editor.', 'foobox-image-lightbox' ); ?></li>
		<li><?php esc_html_e( 'Open the block inspector sidebar. In WordPress 7, choose the Content tab.', 'foobox-image-lightbox' ); ?></li>
		<li><?php esc_html_e( 'Expand the FooBox panel and enable FooBox for the selected block.', 'foobox-image-lightbox' ); ?></li>
		<li><?php esc_html_e( 'Complete the block-specific options, then preview the page and open the lightbox.', 'foobox-image-lightbox' ); ?></li>
	</ol>

	<div class="foobox-help-actions">
		<a class="button button-primary" href="<?php echo esc_url( $foobox_block_editor_new_page_url ); ?>">
			<?php esc_html_e( 'Create a page', 'foobox-image-lightbox' ); ?>
		</a>
		<a class="button" href="<?php echo esc_url( $foobox_block_editor_settings_url ); ?>">
			<?php esc_html_e( 'Review compatibility settings', 'foobox-image-lightbox' ); ?>
		</a>
		<a class="button" href="<?php echo esc_url( $foobox_block_editor_docs_url ); ?>" target="_blank" rel="noopener noreferrer">
			<?php esc_html_e( 'View full documentation', 'foobox-image-lightbox' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( ' (opens in a new tab)', 'foobox-image-lightbox' ); ?></span>
		</a>
	</div>

	<?php if ( ! $foobox_block_editor_is_pro ) : ?>
		<div class="notice notice-info inline">
			<p>
				<?php esc_html_e( 'FooBox Free supports image lightboxes, galleries, image buttons, styles, themes, captions, grouping, sizing, and compatibility tools. Opening effects, hidden HTML content, iframes, videos, and video posters require FooBox Pro. Pro fields remain visible so your content is ready if you upgrade.', 'foobox-image-lightbox' ); ?>
			</p>
		</div>
	<?php endif; ?>

	<div class="foobox-help-workflows">
		<div class="card foobox-help-card">
			<h3>
				<span class="dashicons dashicons-format-image" aria-hidden="true"></span>
				<?php esc_html_e( 'Open an image', 'foobox-image-lightbox' ); ?>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<span class="foobox-help-badge"><?php esc_html_e( 'Free', 'foobox-image-lightbox' ); ?></span>
				<?php endif; ?>
			</h3>
			<ol>
				<li><?php esc_html_e( 'Add or select an Image block.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Enable FooBox in the FooBox panel.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Use “Link image to media file” if it appears.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Set optional captions, dimensions, panning, style, theme, and gallery group.', 'foobox-image-lightbox' ); ?></li>
			</ol>
		</div>

		<div class="card foobox-help-card">
			<h3>
				<span class="dashicons dashicons-format-gallery" aria-hidden="true"></span>
				<?php esc_html_e( 'Create a gallery lightbox', 'foobox-image-lightbox' ); ?>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<span class="foobox-help-badge"><?php esc_html_e( 'Free', 'foobox-image-lightbox' ); ?></span>
				<?php endif; ?>
			</h3>
			<ol>
				<li><?php esc_html_e( 'Add a Gallery block and choose your images.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Use List View to select the outer Gallery block, not a child Image.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Enable FooBox and link the gallery images to their media files.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Choose a group, style, and theme. FooBox copies the gallery options to its images.', 'foobox-image-lightbox' ); ?></li>
			</ol>
		</div>

		<div class="card foobox-help-card">
			<h3>
				<span class="dashicons dashicons-button" aria-hidden="true"></span>
				<?php esc_html_e( 'Open an image from a Button', 'foobox-image-lightbox' ); ?>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<span class="foobox-help-badge"><?php esc_html_e( 'Free', 'foobox-image-lightbox' ); ?></span>
				<?php endif; ?>
			</h3>
			<ol>
				<li><?php esc_html_e( 'Add or select a Button block and enable FooBox.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Choose Image or Auto-detect from URL as the content type.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Enter the target URL or use “Select from media library”.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Add optional dimensions, captions, appearance, and grouping.', 'foobox-image-lightbox' ); ?></li>
			</ol>
		</div>

		<div class="card foobox-help-card">
			<h3>
				<span class="dashicons dashicons-layout" aria-hidden="true"></span>
				<?php esc_html_e( 'Open hidden block content', 'foobox-image-lightbox' ); ?>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<span class="foobox-help-badge"><?php esc_html_e( 'Pro', 'foobox-image-lightbox' ); ?></span>
				<?php endif; ?>
			</h3>
			<ol>
				<li><?php esc_html_e( 'Build the content visually inside a Group block.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Enable “Use as hidden FooBox content” and generate an HTML id.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Add a Button and choose HTML content as its content type.', 'foobox-image-lightbox' ); ?></li>
				<li><?php esc_html_e( 'Set the Button target to the Group id with a leading #.', 'foobox-image-lightbox' ); ?></li>
			</ol>
		</div>
	</div>

	<h2><?php esc_html_e( 'Controls by block', 'foobox-image-lightbox' ); ?></h2>
	<table class="widefat striped foobox-help-table">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Block', 'foobox-image-lightbox' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Main controls', 'foobox-image-lightbox' ); ?></th>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<th scope="col"><?php esc_html_e( 'Availability', 'foobox-image-lightbox' ); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Image', 'foobox-image-lightbox' ); ?></th>
				<td><?php esc_html_e( 'Media linking, captions, dimensions, maximum size, panning, style, theme, group, and opening effect.', 'foobox-image-lightbox' ); ?></td>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<td><?php esc_html_e( 'Free; opening effects require Pro', 'foobox-image-lightbox' ); ?></td>
				<?php endif; ?>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Gallery', 'foobox-image-lightbox' ); ?></th>
				<td><?php esc_html_e( 'Media linking, gallery group, style, theme, and opening effect.', 'foobox-image-lightbox' ); ?></td>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<td><?php esc_html_e( 'Free; opening effects require Pro', 'foobox-image-lightbox' ); ?></td>
				<?php endif; ?>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Button', 'foobox-image-lightbox' ); ?></th>
				<td><?php esc_html_e( 'Content type, media picker, URL or target, dimensions, captions, appearance, grouping, and video poster.', 'foobox-image-lightbox' ); ?></td>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<td><?php esc_html_e( 'Images are Free; HTML, iframe, video, posters, and effects require Pro', 'foobox-image-lightbox' ); ?></td>
				<?php endif; ?>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Group', 'foobox-image-lightbox' ); ?></th>
				<td><?php esc_html_e( 'HTML id, generated id, frontend visibility, width, and height.', 'foobox-image-lightbox' ); ?></td>
				<?php if ( ! $foobox_block_editor_is_pro ) : ?>
					<td><?php esc_html_e( 'Pro', 'foobox-image-lightbox' ); ?></td>
				<?php endif; ?>
			</tr>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Common problems', 'foobox-image-lightbox' ); ?></h2>
	<ul class="foobox-help-troubleshooting">
		<li><strong><?php esc_html_e( 'The FooBox panel is missing:', 'foobox-image-lightbox' ); ?></strong> <?php esc_html_e( 'Select an Image, Gallery, Button, or Group block and open the block inspector. In WordPress 7, use the Content tab.', 'foobox-image-lightbox' ); ?></li>
		<li><strong><?php esc_html_e( 'An image does not open:', 'foobox-image-lightbox' ); ?></strong> <?php esc_html_e( 'Link it to the media file, or review the Default Image Link and Auto Link Existing Images compatibility settings.', 'foobox-image-lightbox' ); ?></li>
		<li><strong><?php esc_html_e( 'Gallery controls look wrong:', 'foobox-image-lightbox' ); ?></strong> <?php esc_html_e( 'Select the outer Gallery block from List View instead of an individual Image block.', 'foobox-image-lightbox' ); ?></li>
		<li><strong><?php esc_html_e( 'Hidden content does not open:', 'foobox-image-lightbox' ); ?></strong> <?php esc_html_e( 'Confirm FooBox Pro is active and the Button target exactly matches the Group id, including the leading #.', 'foobox-image-lightbox' ); ?></li>
		<li><strong><?php esc_html_e( 'WordPress opens a second lightbox:', 'foobox-image-lightbox' ); ?></strong> <?php esc_html_e( 'Enable the WordPress Lightbox compatibility setting, reopen the post, and save it again.', 'foobox-image-lightbox' ); ?></li>
	</ul>
</div>
