<?php

/*
Plugin Name: FooBox Image Lightbox
Plugin URI: https://fooplugins.com/foobox/
Description: The best responsive lightbox for WordPress.
Version: 2.7.41
Author: FooPlugins
Author URI: https://fooplugins.com
License: GPL2
Text Domain: foobox-image-lightbox
Domain Path: /languages
*/
if ( function_exists( 'foobox_fs' ) ) {
    foobox_fs()->set_basename( false, __FILE__ );
} else {
    if ( !class_exists( 'FooBox' ) ) {
        define( 'FOOBOX_BASE_FILE', __FILE__ );
        define( 'FOOBOX_BASE_SLUG', 'foobox-image-lightbox' );
        define( 'FOOBOX_BASE_PAGE_SLUG_OPTIN', 'foobox-image-lightbox-optin' );
        define( 'FOOBOX_BASE_PAGE_SLUG_SETTINGS', 'foobox-settings' );
        define( 'FOOBOX_BASE_ACTIVATION_REDIRECT_TRANSIENT_KEY', '_foobox_activation_redirect' );
        define( 'FOOBOX_BASE_PATH', plugin_dir_path( __FILE__ ) );
        define( 'FOOBOX_BASE_URL', plugin_dir_url( __FILE__ ) );
        define( 'FOOBOX_BASE_VERSION', '2.7.41' );
        // Create a helper function for easy SDK access.
        function foobox_fs() {
            global $foobox_fs;
            if ( !isset( $foobox_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $foobox_fs = fs_dynamic_init( array(
                    'id'             => '374',
                    'slug'           => 'foobox-image-lightbox',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_7a17ec700c89fe71a25605589e0b9',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                        'slug'       => FOOBOX_BASE_SLUG,
                        'first-path' => 'admin.php?page=' . FOOBOX_BASE_SLUG,
                        'contact'    => false,
                    ),
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $foobox_fs;
        }

        // Init Freemius.
        foobox_fs();
        class FooBox {
            private static $instance;

            public static function get_instance() {
                if ( !isset( self::$instance ) && !self::$instance instanceof FooBox ) {
                    self::$instance = new FooBox();
                }
                return self::$instance;
            }

            /**
             * Initialize the plugin!!!
             */
            private function __construct() {
                //include all the things!
                $this->includes();
                if ( is_admin() ) {
                    new FooBox_Admin_Menu();
                    add_action( 'admin_init', array($this, 'check_for_activation_redirect') );
                    add_action( FOOBOX_ACTION_ADMIN_MENU_RENDER_GETTING_STARTED, array($this, 'render_page_getting_started') );
                    foobox_fs()->add_filter( 'support_forum_submenu', array($this, 'override_support_menu_text') );
                    foobox_fs()->add_filter( 'support_forum_url', array($this, 'override_support_forum_url') );
                    foobox_fs()->add_filter( 'connect_url', array($this, 'override_connect_url') );
                    add_action( 'admin_menu', array($this, 'remove_admin_menu_items_on_mobile'), WP_FS__LOWEST_PRIORITY + 1 );
                    foobox_fs()->add_action( 'after_premium_version_activation', array('FooBox', 'activate') );
                    foobox_fs()->add_filter( 'pricing/show_annual_in_monthly', '__return_false' );
                    add_action( 'admin_page_access_denied', array($this, 'check_for_access_denies_after_account_deletion') );
                    // Show notice on FooBox admin pages if default image link type is not "file"
                    add_action( 'admin_notices', array($this, 'maybe_show_link_type_notice') );
                    // AJAX handler to set default image link type to file
                    add_action( 'wp_ajax_foobox_set_default_image_link_type', array($this, 'ajax_set_default_image_link_type') );
                    // AJAX handler to dismiss the notice and persist preference
                    add_action( 'wp_ajax_foobox_dismiss_default_link_notice', array($this, 'ajax_dismiss_default_link_notice') );
                }
                //register activation hook for free
                register_activation_hook( __FILE__, array('FooBox', 'activate') );
                require_once FOOBOX_BASE_PATH . 'free/foobox-free.php';
            }

            public function override_connect_url( $url ) {
                if ( is_object( foobox_fs()->get_site() ) ) {
                    return 'admin.php?page=' . FOOBOX_BASE_PAGE_SLUG_OPTIN;
                }
                return $url;
            }

            public function override_support_menu_text( $text ) {
                return __( 'Support', 'foobox-image-lightbox' );
            }

            public function override_support_forum_url( $url ) {
                return $url;
            }

            public function check_for_access_denies_after_account_deletion() {
                global $plugin_page;
                if ( FOOBOX_BASE_PAGE_SLUG_OPTIN === $plugin_page ) {
                    if ( !is_object( foobox_fs()->get_site() ) ) {
                        fs_redirect( 'admin.php?page=' . FOOBOX_BASE_SLUG );
                    }
                }
            }

            /**
             * Include all the files needed
             */
            public function includes() {
                require_once FOOBOX_BASE_PATH . 'includes/functions.php';
                require_once FOOBOX_BASE_PATH . 'includes/actions.php';
                require_once FOOBOX_BASE_PATH . 'includes/filters.php';
                require_once FOOBOX_BASE_PATH . 'includes/admin/menu.php';
                require_once FOOBOX_BASE_PATH . 'compatibility/includes.php';
            }

            /**
             * Fired when the plugin is activated.
             *
             * @since    1.1.0
             *
             * @param    boolean $network_wide       True if WPMU superadmin uses
             *                                       "Network Activate" action, false if
             *                                       WPMU is disabled or plugin is
             *                                       activated on an individual blog.
             */
            public static function activate( $network_wide ) {
                if ( function_exists( 'is_multisite' ) && is_multisite() ) {
                    //do nothing for multisite!
                } else {
                    //Make sure we redirect to the welcome page
                    set_transient( FOOBOX_BASE_ACTIVATION_REDIRECT_TRANSIENT_KEY, true, 30 );
                }
            }

            /**
             * On admin_init check that the plugin was activated and redirect to the getting started page
             */
            public function check_for_activation_redirect() {
                // Bail if no activation redirect
                if ( !get_transient( FOOBOX_BASE_ACTIVATION_REDIRECT_TRANSIENT_KEY ) ) {
                    return;
                }
                // Delete the redirect transient
                delete_transient( FOOBOX_BASE_ACTIVATION_REDIRECT_TRANSIENT_KEY );
                // Bail if activating from network, or bulk
                if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
                    return;
                }
                $url = admin_url( 'admin.php?page=' . FOOBOX_BASE_SLUG );
                wp_safe_redirect( $url );
                exit;
            }

            public function render_page_getting_started() {
                require_once FOOBOX_BASE_PATH . 'includes/admin/view-getting-started.php';
            }

            public function remove_admin_menu_items_on_mobile() {
                if ( foobox_hide_pricing_menu() ) {
                    //we only want to hide menu items if we are on mobile!
                    remove_submenu_page( 'foobox-image-lightbox', 'foobox-image-lightbox-pricing' );
                    remove_submenu_page( 'foobox-image-lightbox', 'foobox-image-lightbox-account' );
                    remove_submenu_page( 'foobox-image-lightbox', 'foobox-image-lightbox-contact' );
                }
            }

            /**
             * Show an admin notice on FooBox pages if the default image link type is not set to 'file'.
             * Provides a button to update the option via AJAX.
             */
            public function maybe_show_link_type_notice() {
                if ( !current_user_can( 'manage_options' ) ) {
                    return;
                }
                if ( !function_exists( 'get_current_screen' ) ) {
                    return;
                }
                $screen = get_current_screen();
                if ( !$screen ) {
                    return;
                }
                $allowed_screens = array('toplevel_page_' . FOOBOX_BASE_SLUG, 'foobox_page_' . FOOBOX_BASE_PAGE_SLUG_SETTINGS);
                if ( !in_array( $screen->id, $allowed_screens, true ) ) {
                    return;
                }
                // Respect a stored preference to hide this notice entirely
                $hide_notice = get_option( 'foobox_hide_default_link_notice', '0' );
                if ( '1' === $hide_notice ) {
                    return;
                }
                $current = get_option( 'image_default_link_type', 'none' );
                if ( 'file' === $current ) {
                    return;
                }
                $nonce = wp_create_nonce( 'foobox_set_default_link_type' );
                $dismiss = wp_create_nonce( 'foobox_dismiss_default_link_notice' );
                $ajaxurl = admin_url( 'admin-ajax.php' );
                $title = esc_html__( 'Make images open in FooBox by default!', 'foobox-image-lightbox' );
                $message = esc_html__( "By default, WordPress does not create a link to the media file when you add an image or gallery in the block editor. This means those images will not open in FooBox, and you have to manually link each image to the media file. We strongly recommend you change it so that images will always be linked, which will open in FooBox.", 'foobox-image-lightbox' );
                $button = esc_html__( 'Always Link Images to Media File', 'foobox-image-lightbox' );
                echo '<div id="foobox-link-type-notice" class="notice notice-warning is-dismissible" data-dismiss-nonce="' . esc_attr( $dismiss ) . '">';
                echo '<p><strong>' . esc_html( $title ) . '</strong></p>';
                echo '<p>' . esc_html( $message ) . '</p>';
                echo '<p><button type="button" class="button button-primary" id="foobox-link-type-fix" data-ajaxurl="' . esc_url( $ajaxurl ) . '" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html( $button ) . '</button> <span class="spinner" style="float:none; margin:3px 0 0 8px;"></span></p>';
                echo '</div>';
                // Inline JS to handle the AJAX call.
                ?>
                <script>
                (function(){
                    var btn = document.getElementById('foobox-link-type-fix');
                    if (!btn) return;
                    var notice = document.getElementById('foobox-link-type-notice');
                    var spinner = notice ? notice.querySelector('.spinner') : null;
                    function setDisabled(disabled){
                        if (disabled){ btn.setAttribute('disabled','disabled'); if (spinner) spinner.classList.add('is-active'); }
                        else { btn.removeAttribute('disabled'); if (spinner) spinner.classList.remove('is-active'); }
                    }
                    btn.addEventListener('click', function(){
                        setDisabled(true);
                        var ajaxurl = btn.getAttribute('data-ajaxurl');
                        var nonce = btn.getAttribute('data-nonce');
                        var params = new URLSearchParams();
                        params.append('action','foobox_set_default_image_link_type');
                        params.append('nonce', nonce);
                        // value kept for forward compatibility
                        params.append('value','file');
                        fetch(ajaxurl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                            body: params.toString()
                        }).then(function(res){ return res.json(); }).then(function(json){
                            if (json && json.success) {
                                if (notice){
                                    notice.className = 'notice notice-success is-dismissible';
                                    notice.innerHTML = '<p><?php 
                echo esc_js( __( 'Default updated. New images will link to the media file.', 'foobox-image-lightbox' ) );
                ?></p>';
                                }
                            } else {
                                setDisabled(false);
                                var msg = (json && json.data && json.data.message) ? json.data.message : '<?php 
                echo esc_js( __( 'Something went wrong. Please try again.', 'foobox-image-lightbox' ) );
                ?>';
                                alert(msg);
                            }
                        }).catch(function(){
                            setDisabled(false);
                            alert('<?php 
                echo esc_js( __( 'Network error. Please try again.', 'foobox-image-lightbox' ) );
                ?>');
                        });
                    });

                    // Persist dismissals
                    var ajaxurlDismiss = '<?php 
                echo esc_js( $ajaxurl );
                ?>';
                    var dismissNonce = notice ? notice.getAttribute('data-dismiss-nonce') : '';
                    if (notice) {
                        notice.addEventListener('click', function(e){
                            if (e.target && e.target.classList && e.target.classList.contains('notice-dismiss')) {
                                var params = new URLSearchParams();
                                params.append('action','foobox_dismiss_default_link_notice');
                                params.append('nonce', dismissNonce);
                                fetch(ajaxurlDismiss, {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                                    body: params.toString()
                                });
                            }
                        });
                    }
                })();
                </script>
                <?php 
            }

            /**
             * AJAX: Set the default image link type to 'file'.
             */
            public function ajax_set_default_image_link_type() {
                if ( !current_user_can( 'manage_options' ) ) {
                    wp_send_json_error( array(
                        'message' => __( 'Insufficient permissions.', 'foobox-image-lightbox' ),
                    ), 403 );
                }
                check_ajax_referer( 'foobox_set_default_link_type', 'nonce' );
                // Update the option; allow overriding the value param for future extensibility.
                $value = ( isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : 'file' );
                if ( 'delete' === $value ) {
                    delete_option( 'image_default_link_type' );
                    $current = get_option( 'image_default_link_type', 'none' );
                    wp_send_json_success( array(
                        'current' => $current,
                    ) );
                }
                if ( !in_array( $value, array('file', 'post', 'none'), true ) ) {
                    $value = 'file';
                }
                update_option( 'image_default_link_type', $value );
                wp_send_json_success( array(
                    'current' => $value,
                ) );
            }

            /**
             * AJAX: Dismiss the default link type notice and persist the preference.
             */
            public function ajax_dismiss_default_link_notice() {
                if ( !current_user_can( 'manage_options' ) ) {
                    wp_send_json_error( array(
                        'message' => __( 'Insufficient permissions.', 'foobox-image-lightbox' ),
                    ), 403 );
                }
                check_ajax_referer( 'foobox_dismiss_default_link_notice', 'nonce' );
                update_option( 'foobox_hide_default_link_notice', '1' );
                wp_send_json_success();
            }

        }

    } else {
    }
    FooBox::get_instance();
}