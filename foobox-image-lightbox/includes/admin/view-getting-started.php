<?php
$title = apply_filters( 'foobox_getting_started_title', __( 'Welcome to FooBox!', 'foobox-image-lightbox' ) );
$tagline = apply_filters( 'foobox_getting_started_tagline', __( 'Thank you for choosing FooBox as your lightbox for your WordPress website!', 'foobox-image-lightbox' ) );
?>
<style>

	.foobox-badge-foobot {
		position: absolute;
		top: 0;
		right: 0;
		background:url(<?php echo esc_url( foobox_asset_url( 'img/foobot.png') ); ?>) no-repeat;
		width:109px;
		height:200px;
	}

	.about-wrap.foobox-getting-started .about-text {
		margin: 0.5em 200px 0.5em 0;
	}

	.foobox-getting-started p.foobox-links {
		margin: 0.5em 0 !important;
	}

	p.foobox-links a {
		font-size: 0.8em;
	}

	.feature-section h4 {
		text-align: center;
	}

	.feature-section h4 a {
		background: #0085ba;
		border-color: #0073aa #006799 #006799;
		-webkit-box-shadow: 0 1px 0 #006799;
		box-shadow: 0 1px 0 #006799;
		color: #fff;
		text-decoration: none;
		text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799;
		padding: 5px 20px;
		border-radius: 3px;
	}

	@media only screen and (max-width: 500px) {
		.foobox-badge-foobot,
		.feature-section img {
			display: none;
		}
	}

	.about-wrap div.updated.fs-notice {
		display: block !important;
		width: 85%;
	}

</style>
<div class="wrap about-wrap foobox-getting-started">
	<h1><?php echo esc_html( $title ); ?></h1>
	<div class="about-text">
		<?php echo esc_html( $tagline ); ?>
	</div>
	<p class="foobox-links">
		<a href="https://fooplugins.com/foobox/" target="_blank"><?php esc_html_e( 'FooBox Homepage', 'foobox-image-lightbox' ); ?></a>
		|
		<a href="https://fooplugins.com/documentation/foobox/" target="_blank"><?php esc_html_e( 'FooBox Documentation', 'foobox-image-lightbox' ); ?></a>
	</p>
	<div class="foobox-badge-foobot"></div>
	<?php foobox_action_admin_menu_render_getting_started(); ?>
</div>
