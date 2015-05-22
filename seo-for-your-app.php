<?php
/*
Plugin Name: App
Plugin URI: 
Description: Adds SEO Deeplinking Capability for your App to your Site
Version: 0.3.2
Author: Nebelhorn Medien GmbH
Author URI: http://www.nebelhorn.com
License: GPLv2 or later
Min WP Version: 3.0
License: GPL2
*/

if ( !class_exists( 'NH_SEOAPPScripts' ) ) {
class NH_SEOAPPScripts
{
    
    private $options;
	private $plugin_options_key = 'seo-for-your-app';

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'sfya_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'sfya_page_init' ) );
		
		add_action( 'wp_head', array( $this, 'sfya_wp_head' ) );
    }
	
	/**
	 * Translation
	 */
	 function sfya_init() {
			//load_plugin_textdomain( 'nh_sfya', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );
	}

    /**
     * Add options page
     */
    public function sfya_add_plugin_page()
    {
       
        add_options_page(
            'SEO For Your App Setting', 
            'SEO For Your App', 
            'manage_options', 
            $this->plugin_options_key, 
            array( $this, 'sfya_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function sfya_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sfya_option' );
        ?>
        <div class="wrap">
            <h2><?php _e('SEO For Your App - Settings', 'nh_sfya') ?></h2>           
            <form method="post" action="options.php">
            <?php
            
                // This prints out all hidden setting fields
                settings_fields( 'sfya_option_group' );   
                do_settings_sections( $this->plugin_options_key );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function sfya_page_init()
    {        
        register_setting(
            'sfya_option_group', // Option group
            'sfya_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'sfya_section_id', // ID
            __('My App Properties', 'nh_sfya'), // Title
            array( $this, 'sfya_print_section_info' ), // Callback
            $this->plugin_options_key // Page
        );  

        add_settings_field(
            'package_id', // ID
            __('Package ID', 'nh_sfya'), // Title 
            array( $this, 'sfya_package_id_callback' ), // Callback
            $this->plugin_options_key, // Page
            'sfya_section_id' // Section           
        );      

        add_settings_field(
            'scheme', 
            __('Scheme', 'nh_sfya'), 
            array( $this, 'sfya_scheme_callback' ), 
            $this->plugin_options_key, 
            'sfya_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
    		$new_input = array();
        if( isset( $input['package_id'] ) )
            $new_input['package_id'] = sanitize_text_field( $input['package_id'] );

        if( isset( $input['scheme'] ) )
            $new_input['scheme'] = sanitize_text_field( $input['scheme'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
       // print 'Enter your app  below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfya_package_id_callback()
    {
        printf(
            '<input size="30" type="text" id="package_id" name="sfya_option[package_id]" value="%s" required="required" />',
            isset( $this->options['package_id'] ) ? esc_attr( $this->options['package_id']) : ''
        );
		echo '<br />'. __('Please enter the package ID, e.g. com.blappsta.previewapp', 'nh_sfya');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfya_scheme_callback()
    {
        printf(
            '<input size="30" type="text" id="scheme" name="sfya_option[scheme]" value="%s"  required="required" />',
            isset( $this->options['scheme'] ) ? esc_attr( $this->options['scheme']) : ''
        );
		echo '<br />'. __('Please enter the scheme, e.g. blappsta', 'nh_sfya');
    }
	
	
	
	/**
	 * ADD Deeplink  to header
	 */
	  function sfya_wp_head() {

		if (is_single() || is_home() || is_front_page()) {
			$postid = get_the_ID();
			$sfya_option = get_option('sfya_option');

			if(!empty($sfya_option) && is_array($sfya_option) && !empty($sfya_option['package_id']) && !empty($sfya_option['scheme'])) {
				echo '<link rel="alternate" href="android-app://'.$sfya_option['package_id'].'/'.$sfya_option['scheme'].'/blappsta';
				if (is_single() && !is_home() && !is_front_page()) echo '?article='.$postid;
				echo "\" />\n";
				return;
			}
			
			
			}
		}
}
}

   $seo_app_scripts = new NH_SEOAPPScripts();