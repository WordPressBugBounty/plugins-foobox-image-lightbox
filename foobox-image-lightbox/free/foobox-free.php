<?php
/*
FooBox Free Image Lightbox
*/

define( 'FOOBOXFREE_SLUG', 'foobox-free' );
define( 'FOOBOXFREE_PATH', plugin_dir_path( __FILE__ ));
define( 'FOOBOXFREE_URL', plugin_dir_url( __FILE__ ));
define( 'FOOBOXFREE_FILE', __FILE__ );

if (!class_exists('Foobox_Free')) {

	// Includes
	require_once FOOBOXFREE_PATH . "includes/class-settings.php";
	require_once FOOBOXFREE_PATH . "includes/class-script-generator.php";
	require_once FOOBOXFREE_PATH . "includes/class-foogallery-foobox-free-extension.php";
	require_once FOOBOXFREE_PATH . "includes/foopluginbase/bootstrapper.php";
	require_once FOOBOXFREE_PATH . 'includes/class-exclude.php';

	class Foobox_Free extends Foo_Plugin_Base_v2_1 {

		const JS                   = 'foobox.free.min.js';
		const CSS                  = 'foobox.free.min.css';
		const FOOBOX_URL           = 'https://fooplugins.com/plugins/foobox/?utm_source=fooboxfreeplugin&utm_medium=fooboxfreeprolink&utm_campaign=foobox_free_pro_tab';
		const BECOME_AFFILIATE_URL = 'https://fooplugins.com/affiliate-program/';

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Foobox_Free ) ) {
				self::$instance = new Foobox_Free();
			}
			return self::$instance;
		}

		/**
		 * Initialize the plugin by setting localization, filters, and administration functions.
		 */
		private function __construct() {
			//init FooPluginBase
			$this->init( FOOBOXFREE_FILE, FOOBOXFREE_SLUG, FOOBOX_BASE_VERSION, 'FooBox FREE' );

			if (is_admin()) {
				//enqueue FooBox assets in the admin if necessary
				add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'), 20);

				add_action('foobox-free-settings_custom_type_render', array($this, 'custom_admin_settings_render'));
				new FooBox_Free_Settings();

				add_action( FOOBOX_ACTION_ADMIN_MENU_RENDER_GETTING_STARTED, array( $this, 'render_page_getting_started' ) );
				add_action( FOOBOX_ACTION_ADMIN_MENU_RENDER_SETTINGS, array( $this, 'render_page_settings' ) );

				add_filter( 'foobox-free-has_settings_page', '__return_false' );

				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

				add_filter( 'fs_show_trial_foobox-image-lightbox', array( $this, 'force_trial_hide' ) );
				add_action( 'admin_init', array( $this, 'force_hide_trial_notice' ), 99 );

			} else {

				// Render JS to the front-end pages
				add_action('wp_enqueue_scripts', array($this, 'frontend_print_scripts'), 20);

				// Render CSS to the front-end pages
				add_action('wp_enqueue_scripts', array($this, 'frontend_print_styles'));

				if ( $this->is_option_checked('disable_others') ) {
					add_action('wp_footer', array($this, 'disable_other_lightboxes'), 200);
				}
			}

			new FooBox_Free_Exclude();
		}

		function force_trial_hide( $show_trial ) {
			if ( $this->options()->is_checked( 'force_hide_trial', false ) ) {
				$show_trial = false;
			}

		    return $show_trial;
        }

        function force_hide_trial_notice() {
	        if ( $this->options()->is_checked( 'force_hide_trial', false ) ) {
		        $freemius_sdk = foobox_fs();
		        $plugin_id    = $freemius_sdk->get_slug();
		        $admin_notice_manager = FS_Admin_Notice_Manager::instance( $plugin_id );
		        $admin_notice_manager->remove_sticky( 'trial_promotion' );
	        }
        }

		function enqueue_block_editor_assets() {
			$this->frontend_print_scripts();
			$this->frontend_print_styles();
		}

		function custom_admin_settings_render($args = array()) {
			$type = '';

			extract($args);

			if ($type == 'debug_output') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_debug_info();
			} else if ($type == 'upgrade') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_upgrade_notice();
			} else if ($type == 'thanks') {
				echo '</td></tr><tr valign="top"><td colspan="2">';
				$this->render_thanks_notice();
			} else if ( $type === 'editor_default_image_link' ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}
				$current = get_option( 'image_default_link_type', 'none' );
				$nonce   = wp_create_nonce( 'foobox_set_default_link_type' );
				$ajaxurl = admin_url( 'admin-ajax.php' );
				$label_map = array(
					'file' => __( 'Media File', 'foobox-image-lightbox' ),
					'post' => __( 'Attachment Page', 'foobox-image-lightbox' ),
					'none' => __( 'None', 'foobox-image-lightbox' ),
				);
				$label = isset( $label_map[ $current ] ) ? $label_map[ $current ] : __( 'None', 'foobox-image-lightbox' );
				?>
				<div id="foobox-default-image-link-wrap">
					<p><strong><?php echo esc_html__( 'Current:', 'foobox-image-lightbox' ); ?></strong> <span id="foobox-default-image-link-status"><?php echo esc_html( $label ); ?></span></p>
					<p>
						<button type="button" class="button button-primary" id="foobox-default-image-link-action"
							data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>"
							data-nonce="<?php echo esc_attr( $nonce ); ?>"
							data-mode="<?php echo ( 'file' === $current ) ? 'reset' : 'set'; ?>">
							<?php echo ( 'file' === $current ) ? esc_html__( 'Reset to default', 'foobox-image-lightbox' ) : esc_html__( 'Set to Media File', 'foobox-image-lightbox' ); ?>
						</button>
						<span class="spinner" style="float:none; margin:3px 0 0 8px;"></span>
					</p>
				</div>
				<script>
				jQuery(function($){
					var $wrap = $('#foobox-default-image-link-wrap');
					var $btn = $('#foobox-default-image-link-action');
					var $status = $('#foobox-default-image-link-status');
					var $spinner = $wrap.find('.spinner');
					function setDisabled(disabled){
						if(disabled){ $btn.prop('disabled', true); $spinner.addClass('is-active'); }
						else { $btn.prop('disabled', false); $spinner.removeClass('is-active'); }
					}
					function mapLabel(val){
						if(val === 'file') return '<?php echo esc_js( __( 'Media File', 'foobox-image-lightbox' ) ); ?>';
						if(val === 'post') return '<?php echo esc_js( __( 'Attachment Page', 'foobox-image-lightbox' ) ); ?>';
						return '<?php echo esc_js( __( 'None', 'foobox-image-lightbox' ) ); ?>';
					}
					$btn.on('click', function(){
						setDisabled(true);
						var mode = $btn.data('mode');
						var ajaxurl = $btn.data('ajaxurl');
						var nonce = $btn.data('nonce');
						var value = (mode === 'set') ? 'file' : 'delete';
						$.post(ajaxurl, { action: 'foobox_set_default_image_link_type', nonce: nonce, value: value })
						 .done(function(resp){
							if (resp && resp.success){
								var cur = resp.data && resp.data.current ? resp.data.current : 'none';
								$status.text(mapLabel(cur));
								if (cur === 'file'){
									$btn.text('<?php echo esc_js( __( 'Reset to default', 'foobox-image-lightbox' ) ); ?>').data('mode','reset');
								} else {
									$btn.text('<?php echo esc_js( __( 'Set to Media File', 'foobox-image-lightbox' ) ); ?>').data('mode','set');
								}
							} else {
								alert((resp && resp.data && resp.data.message) ? resp.data.message : '<?php echo esc_js( __( 'Something went wrong. Please try again.', 'foobox-image-lightbox' ) ); ?>');
							}
						 })
						 .fail(function(){
							alert('<?php echo esc_js( __( 'Network error. Please try again.', 'foobox-image-lightbox' ) ); ?>');
						 })
						 .always(function(){ setDisabled(false); });
					});
				});
				</script>
				<?php
			}
		}

		function generate_javascript($debug = false) {
			return FooBox_Free_Script_Generator::generate_javascript($this, $debug);
		}

		function render_for_archive() {
			if (is_admin()) return true;

			return !is_singular();
		}

		function render_debug_info() {

			echo '<strong>Javascript:<br /><pre style="width:600px; overflow:scroll;">';

			echo esc_html($this->generate_javascript(true));

			echo '</pre><br />Settings:<br /><pre style="width:600px; overflow:scroll;">';

			echo esc_html( print_r(get_option($this->plugin_slug), true) );

			echo '</pre>';
		}

		function render_upgrade_notice() {
			require_once FOOBOXFREE_PATH . "includes/upgrade.php";
		}

		function build_install_url( $slug ) {
			$action      = 'install-plugin';
			return wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug
					),
					admin_url( 'update.php' )
				),
				$action . '_' . $slug
			);
        }

		function render_thanks_notice() {
			$plugins = array();

			if ( !class_exists( 'FooPlugins\FooConvert\Init' ) ) {
				$plugins[] = array(
					'id'      => 'fooconvert',
					'title'   => __( 'FooConvert', 'foobox-image-lightbox' ),
					'desc'    => __( 'Convert your website into a lead generation machine with Popups, Bars and Flyouts. Professionally designed templates. Triggered by events (exit intent, time, or manually). Analytics included!', 'foobox-image-lightbox' ),
					'install_url' => self::build_install_url( 'fooconvert' ),
					'image' => array(
						'url' => FOOBOX_BASE_URL . 'assets/img/fooconvert.svg',
						'height' => 60,
						'width' => 250,
					),
					'link' => 'https://fooplugins.com/fooconvert',
					'coupon' => 'FOOBOX20'
				);
			}

			if ( !class_exists( 'FooPlugins\FooBar\Init' ) ) {
				$plugins[] = array(
					'id'      => 'foobar',
					'title'   => __( 'FooBar', 'foobox-image-lightbox' ),
					'desc'    => __( 'Create unlimited notification bars & announcements that catch your visitor\'s attention. Countdowns, Signup forms, and more!', 'foobox-image-lightbox' ),
					'install_url' => self::build_install_url( 'foobar-notifications-lite' ),
					'image' => array(
						'url' => FOOBOX_BASE_URL . 'assets/img/foobar.svg',
						'height' => 50,
						'width' => 180,
					),
					'link' => 'https://fooplugins.com/foobar-wordpress-notification-bars/',
					'coupon' => 'FOOBOX20'
				);
			}

			if ( !class_exists( 'FooGallery_Plugin' ) ) {
				$plugins[] = array(
					'id'      => 'foogallery',
					'title'   => __( 'FooGallery', 'foobox-image-lightbox' ),
					'desc'    => __( 'Easily add a responsive photo gallery to your website in minutes. Masonry, justified, carousel, grid layouts. Hover effects, lightbox, albums. Even sell your images online!', 'foobox-image-lightbox' ),
					'install_url' => self::build_install_url( 'foogallery' ),
					'image' => array(
						'url' => FOOBOX_BASE_URL . 'assets/img/foogallery.svg',
						'height' => 50,
						'width' => 250,
					),
					'link' => 'https://fooplugins.com/foogallery-wordpress-gallery-plugin/',
					'coupon' => 'FOOBOX20'
				);
			}

        ?>
<style>
    /* Thanks tab layout */
	#thanks_tab .form-table tr:first-child {
		display: none;
	}

    .foobox-thanks-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background: #fff;
        border: 1px solid #ccd0d4;
        border-left-width: 4px;
        border-left-color: #ff69b4; /* pink accent */
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.04);
        padding: 16px;
        margin-bottom: 16px;
    }
    .foobox-thanks-bar .bar-content { display: flex; align-items: center; gap: 12px; }
    .foobox-thanks-bar .bar-content .dashicons-heart { color: #ff69b4; font-size: 24px; }
    .foobox-thanks-bar h2 { margin: 0; }
    .foobox-thanks-bar p { margin: 2px 0 0; }
    .foobox-thanks-bar .bar-actions { display: flex; flex-wrap: wrap; gap: 8px; }

    /* Plugin cards grid */
    .foobox-thanks-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        margin-top: 8px;
    }
    .foobox-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-left-width: 4px;
        border-left-color: #007cba; /* blue accent */
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.04);
        padding: 16px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
    }
    .foobox-card h3 { margin: 8px 0 4px; }
    .foobox-card img { object-fit: contain; margin-bottom: 4px; }
    .foobox-card .card-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-top: 4px; }
    .foobox-card .card-coupon { margin-top: 4px; }

    @media (max-width: 600px) {
        .foobox-thanks-bar { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="foobox-thanks-bar">
    <div class="bar-content">
        <i class="dashicons dashicons-heart" aria-hidden="true"></i>
        <div class="bar-text">
            <h2><?php echo esc_html__( 'Thanks for using FooBox!', 'foobox-image-lightbox' ); ?></h2>
            <p><?php echo esc_html__( 'If you love FooBox, please consider giving it a 5 star rating on WordPress.org. Your positive ratings help spread the word and help us grow.', 'foobox-image-lightbox' ); ?></p>
        </div>
    </div>
    <div class="bar-actions">
        <a class="button button-primary button-large" target="_blank" href="https://wordpress.org/support/plugin/foobox-image-lightbox/reviews/#new-post"><?php echo esc_html__( 'Rate FooBox on WordPress.org', 'foobox-image-lightbox' ); ?></a>
    </div>
</div>

<?php if ( count( $plugins ) > 0 ) { ?>
<h3><?php echo esc_html__( 'As a thank you for using FooBox, here are some discounts from FooPlugins!', 'foobox-image-lightbox' ); ?></h3>
<div class="foobox-thanks-grid">
    <?php foreach ( $plugins as $plugin ) { ?>
        <div class="foobox-card foobox-card-plugin">
			<?php if ( ! empty( $plugin['image'] ) ) { ?>
				<img src="<?php echo esc_url( $plugin['image']['url'] ); ?>" alt="<?php echo esc_attr( $plugin['title'] ); ?>" height="<?php echo esc_attr( $plugin['image']['height'] ); ?>" width="<?php echo esc_attr( $plugin['image']['width'] ); ?>" />
			<?php } ?>
            <p><?php echo esc_html( $plugin['desc'] ); ?></p>
            <div class="card-actions">
                <a class="button" target="_blank" href="<?php echo esc_url( $plugin['install_url'] ); ?>"><?php echo esc_html( __( 'Install Free', 'foobox-image-lightbox' ) ); ?></a>
                <a class="button button-primary" target="_blank" href="<?php echo esc_url( $plugin['link'] ); ?>"><?php echo esc_html__( 'Visit Plugin', 'foobox-image-lightbox' ); ?></a>
            </div>
			<div class="card-coupon">
				<?php echo esc_html__( 'Coupon Code:', 'foobox-image-lightbox' ); ?> <strong><?php echo esc_html( $plugin['coupon'] ); ?></strong>
			</div>
        </div>
    <?php } ?>
</div>
<?php } ?>
<?php
        }

		function admin_enqueue() {
			$screen_id = foo_current_screen_id();

			if ( 'toplevel_page_' . FOOBOX_BASE_SLUG === $screen_id ||
				 'foobox_page_' . FOOBOX_BASE_PAGE_SLUG_SETTINGS === $screen_id ) {
				$this->frontend_print_scripts();
				$this->frontend_print_styles();
			}
		}

		function frontend_print_styles() {
			if ( !apply_filters('foobox_enqueue_styles', true) ) return;

			//enqueue foobox CSS
            $this->register_and_enqueue_css(self::CSS);
		}

		function frontend_print_scripts() {
			if (!apply_filters('foobox_enqueue_scripts', true)) return;

			$this->register_and_enqueue_js(
				$file = self::JS,
				$d = array('jquery'),
				$v = false,
				$f = false);

			$foobox_js = $this->generate_javascript();

			wp_add_inline_script(
				'foobox-free-min',
				$foobox_js,
				'before'
			);
		}

		/**
		 * PLEASE NOTE : This is only here to avoid the problem of hard-coded lightboxes.
		 * This is not meant to be malicious code to override all lightboxes in favour of FooBox.
		 * But sometimes theme authors hard code galleries to use their built-in lightbox of choice, which is not the desired solution for everyone.
		 * This can be turned off in the FooBox settings page
		 */
		function disable_other_lightboxes() {
			if ( !apply_filters('foobox_enqueue_scripts', true ) ) return;

			?>
			<script type="text/javascript">
				jQuery.fn.prettyPhoto   = function () { return this; };
				jQuery.fn.fancybox      = function () { return this; };
				jQuery.fn.fancyZoom     = function () { return this; };
				jQuery.fn.colorbox      = function () { return this; };
				jQuery.fn.magnificPopup = function () { return this; };
			</script>
		<?php
		}

		function render_page_getting_started() {
			require_once FOOBOXFREE_PATH . 'includes/view-getting-started.php';
		}

		function render_page_settings() {
			if ( isset( $_GET['settings-updated'] ) ) {
				if ( false === get_option( FOOBOXFREE_SLUG ) ) { ?>
					<div id="message" class="updated">
						<p>
							<strong><?php esc_html_e( 'FooBox settings restored to defaults.', 'foobox-image-lightbox' ); ?></strong>
						</p>
					</div>
				<?php } else { ?>
					<div id="message" class="updated">
						<p><strong><?php esc_html_e( 'FooBox settings updated.', 'foobox-image-lightbox' ); ?></strong></p>
					</div>
				<?php }
			}

			$instance = Foobox_Free::get_instance();
			$instance->admin_settings_render_page();
		}

		function is_option_checked($key) {
			$options = $this->options()->get_all();

			if ($options) {
				return array_key_exists($key, $options);
			}

			return false;
		}
	}
}

Foobox_Free::get_instance();
