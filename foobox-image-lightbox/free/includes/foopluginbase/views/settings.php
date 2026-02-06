<?php
/*
Default settings page used by Foo_Plugin_Base
*/
global $wp_version, $wp_settings_sections, $wp_settings_fields;

//need to make sure are included correctly
if ( !isset($this) || !is_subclass_of( $this, 'Foo_Plugin_Base_v2_1' ) ) {
	throw new Exception("This settings view has not been included correctly!");
}

$summary = $this->apply_filters( 'settings_page_summary', '' );
$tabs = $this->_settings->get_tabs();
$plugin_info = $this->get_plugin_info();
$plugin_slug = $plugin_info['slug'];

?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php
	//only show the settings messages if less than WP3.5
	if ( version_compare( $wp_version, '3.5' ) < 0 ) {
		settings_errors();
	}

	if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$plugin_slug]) ) {
		return;
	}

	if ( !empty($summary) ) {
		echo esc_html( $summary );
	}
	?>
	<div id="<?php echo esc_attr($plugin_slug); ?>-settings-wrapper">
		<div id="<?php echo esc_attr($plugin_slug); ?>-settings-main">
			<form action="options.php" method="post">
				<?php settings_fields( $plugin_slug ); ?>
				<?php
				if ( !empty($tabs) ) {
					//we have tabs - woot!
					?>
					<div class="foo-nav-tabs">
						<?php
						//loop through the tabs to render the actual tabs at the top
						$first = true;
						foreach ( $tabs as $tab ) {
							$class = $first ? "foo-nav-tab foo-nav-tab-active" : "foo-nav-tab";
							echo "<a href='#" . esc_attr($tab['id']) . "' class='" . esc_attr($class) . "'>" . wp_kses_post($tab['title']) . "</a>";
							if ( $first ) {
								$first = false;
							}
						}
						?>
					</div>
					<?php
					//now loop through the tabs to render the content containers
					$first = true;
					foreach ( $tabs as $tab ) {
						echo '<div class="foo-nav-container" id="' . esc_attr($tab['id']) . '_tab"' . ($first ? '' : ' style="display:none"') . '>';

						foreach ( (array) $wp_settings_sections[$plugin_slug] as $section ) {
							if ( in_array( $section['id'], $tab['sections'] ) ) {
								echo "<h3>" . esc_html($section['title']) . "</h3>\n";
								call_user_func( $section['callback'], $section );
								if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$plugin_slug]) || !isset($wp_settings_fields[$plugin_slug][$section['id']]) ) {
									continue;
								}
								echo '<table class="form-table">';
								do_settings_fields( $plugin_slug, $section['id'] );
								echo '</table>';
							}
						}

						echo "</div>";
						if ( $first ) {
							$first = false;
						}
					}
					?>
				<?php
				} else {
					//no tabs so just render the sections
					do_settings_sections( $plugin_slug );
				}
				?>
				<p class="submit">
					<input name="submit" class="button-primary" type="submit"
						   value="<?php esc_attr_e( 'Save Changes', $plugin_slug ); ?>"/>
					<input name="<?php echo esc_attr($plugin_slug); ?>[reset-defaults]"
						   onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to restore all settings back to their default values?', $plugin_slug ); ?>');"
						   class="button-secondary" type="submit"
						   value="<?php esc_attr_e( 'Restore Defaults', $plugin_slug ); ?>"/>
					<?php do_action( $plugin_slug . '-settings_buttons' ) ?>
				</p>
			</form>
		</div>
		<div id="<?php echo esc_attr($plugin_slug); ?>-settings-sidebar">
			<?php
            do_action($plugin_slug . '-settings-sidebar' );
            ?>
		</div>
	</div>
</div>