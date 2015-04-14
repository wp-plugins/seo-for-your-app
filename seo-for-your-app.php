<?php
/*
Plugin Name: SEO For Your App
Plugin URI: 
Description: Adds SEO Deeplinking Capability for your App to your Site
Version: 0.1
Author: Nebelhorn Medien GmbH
Author URI: http://www.nebelhorn.com
License: GPLv2 or later
Min WP Version: 3.0
License: GPL2
*/


if ( !class_exists( 'NH_SEOAPPScripts' ) ) {
	
	class NH_SEOAPPScripts {

		function SEOAPPScripts() {
		
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ) );
		
		}
		
	
		function init() {
			load_plugin_textdomain( 'nh_sfya', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );
		}
	
		function admin_init() {
			register_setting( 'seo-for-your-app', 'sfya_package_id', 'trim' );
			register_setting( 'seo-for-your-app', 'sfya_scheme', 'trim' );
		}
	
		function admin_menu() {
			add_options_page(  'SEO For Your App', 'SEO For Your App', 'manage_options', __FILE__, array( &$this, 'sfya_options_panel' ) );
		}
	
		function wp_head() {
		if (is_single()) {
			$postid = get_the_ID();
			$packageID = get_option( 'sfya_package_id', '' );
			$scheme = get_option( 'sfya_scheme', '' );
			if ( $packageID != '' ) {
				if ( $scheme != '' ) {
					echo "<link rel=\"alternate\" href=\"android-app://$packageID/$scheme/blappsta/?article=$postid\" />\n";
				}
			}
			}
		}
	
	function sfya_options_panel() { ?>
		<div id="fb-root"></div>
			<div id="sfya-wrap">
				<div class="wrap">
				<?php screen_icon(); ?>
					<h2><?php _e('SEO For Your App - Options', 'nh_sfya') ?></h2>
					<hr />
					<div class="sfya-wrap" style="width: auto;float: left;margin-right: 2rem;">
						<form name="dofollow" action="options.php" method="post">
						<?php settings_fields( 'seo-for-your-app' ); ?>
						<h3 class="sfya-labels" for="sfya_insert_header"><?php _e('Package ID:', 'nh_sfya') ; ?></h3>
                            <input rows="5" cols="57" id="insert_header" name="sfya_package_id" value="<?php echo esc_html( get_option( 'sfya_package_id' ) ); ?>" /><br />
						<?php _e('Please enter the package ID, e.g. com.blappsta.previewapp', 'nh_sfya') ?>  
						<h3 class="sfya-labels" for="sfya_insert_header">Scheme:</h3>
                            <input rows="5" cols="57" id="insert_header" name="sfya_scheme" value="<?php echo esc_html( get_option( 'sfya_scheme' ) ); ?>" /><br />
						<?php _e('Please enter the scheme, e.g. blappsta', 'nh_sfya'); ?>  
						<?php submit_button(); ?>
						

						</form>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
		
		
		
	}



	
$seo_app_scripts = new NH_SEOAPPScripts();

}


