<?php
/*
Plugin Name: Last Updated Date
Plugin URI: https://wordpress.org/plugins/accelerated-mobile-pages/
Description: This play the last update of posts, pages
Version: 1.0.0
Author: Sophy Prak
Author URI: https://themecountry.com/
License: GPL2
*/

function last_updated_date_filter_publish_dates( $the_date, $d, $post ) {

	$options = get_option ('last_updated_date_settings');



	if ( ! is_admin() && (isset($options['last_updated_date_enable']) && $options['last_updated_date_enable'] === 'on') ) {

		if ( is_int( $post) ) {

			return $the_date;

		} else {

			if ( '' == $d ) {
				$the_date = mysql2date( get_option( 'date_format' ), $post->post_modified );
			} else {
				$the_date = mysql2date( $d, $post->post_modified );
			}


		}
	
	}

	return $the_date;


}

add_action( 'get_the_date', 'last_updated_date_filter_publish_dates', 10, 3 );

/**
 * For Admin
 */
if ( is_admin() ) :

function last_updated_date_init_setup() {

	add_action( 'admin_init', 'last_updated_date_register_setting' );

}

add_action( 'init', 'last_updated_date_init_setup', 99 );


function last_updated_date_admin_menu() {
 
    add_options_page( 'Last Updated Date Setting', 'Last Updated Date', 'manage_options', 'last-updated-date-options', 'last_updated_date_options' );
 
}

add_action('admin_menu', 'last_updated_date_admin_menu');

function last_updated_date_options() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	?>
		<div class="wrap last-dated-date-settings">
		    
           
          	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		    <form method="post" action="options.php">
		        <?php
		            settings_fields("last_updated_date_setting_group");
		            do_settings_sections("lud-options");      
		            submit_button(); 
		        ?>          
		    </form>
		</div>
	<?php

}

function last_updated_date_register_setting() {

	register_setting(
        'last_updated_date_setting_group',
        'last_updated_date_settings'
    );

    add_settings_section(
        'last_updated_date_section', // ID
        null,
        null, // Callback
        'lud-options' // Page
    ); 

    add_settings_field(
            'last_updated_date_enable', // ID
            __('Enable Last Updated Date', 'last-updated-date'), // Title 
            'last_updated_date_enable_callback', // Callback
            'lud-options', // Page
            'last_updated_date_section' // Section           
        ); 

}

function last_updated_date_enable_callback() {

	$options = get_option ('last_updated_date_settings');

	printf(
            '<input type="checkbox" id="ads_amp_ad_client_code" name="last_updated_date_settings[last_updated_date_enable]" %s />',
            isset( $options['last_updated_date_enable'] ) ? esc_attr('checked="checked"') : ''
        );

}

endif;