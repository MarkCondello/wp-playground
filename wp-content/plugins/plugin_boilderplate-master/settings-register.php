<?php
//exit if file is called directly
if(! defined('ABSPATH')){
    exit;
}

// register plugin settings
function blickplugin_register_settings() {
	/*
	register_setting( 
		string   $option_group. Must match settings_fields() name, 
		string   $option_name, 
		callable $sanitize_callback
	);
	*/
	register_setting( 
		'blickplugin_options', 
		'blickplugin_options', 
		'blickplugin_callback_validate_options' 
    ); 
    
    /*
	add_settings_section( 
		string   $id, 
		string   $title, 
		callable $callback function, 
		string   $page where options are displayed. Must match do_settings_sections() name.
	);*/

	add_settings_section( 
		'blickplugin_section_login', 
		'Customize Login Page', 
		'blickplugin_callback_section_login', 
		'blickplugin'
	);
	
	add_settings_section( 
		'blickplugin_section_admin', 
		'Customize Admin Area', 
		'blickplugin_callback_section_admin', 
		'blickplugin'
    );
    
    // callback: login section
    function blickplugin_callback_section_login() {
        echo '<p>These settings enable you to customize the WP Login screen.</p>';
    }

    // callback: admin section
    function blickplugin_callback_section_admin() {
        echo '<p>These settings enable you to customize the WP Admin Area.</p>';
    }

    /*
	add_settings_field(
    	string   $id,
		string   $title,
		callable $callback,
		string   $page,
		string   $section = 'default',
		array    $args = []
	);*/

	add_settings_field(
		'custom_url',
		'Custom URL',
		'blickplugin_callback_field_text',
		'blickplugin',
		'blickplugin_section_login',
		[ 'id' => 'custom_url', 'label' => 'Custom URL for the login logo link' ]
	);

	add_settings_field(
		'custom_title',
		'Custom Title',
		'blickplugin_callback_field_text',
		'blickplugin',
		'blickplugin_section_login',
		[ 'id' => 'custom_title', 'label' => 'Custom title attribute for the logo link' ]
	);

	add_settings_field(
		'custom_style',
		'Custom Style',
		'blickplugin_callback_field_radio',
		'blickplugin',
		'blickplugin_section_login',
		[ 'id' => 'custom_style', 'label' => 'Custom CSS for the Login screen' ]
	);

	add_settings_field(
		'custom_message',
		'Custom Message',
		'blickplugin_callback_field_textarea',
		'blickplugin',
		'blickplugin_section_login',
		[ 'id' => 'custom_message', 'label' => 'Custom text and/or markup' ]
	);

	add_settings_field(
		'custom_footer',
		'Custom Footer',
		'blickplugin_callback_field_text',
		'blickplugin',
		'blickplugin_section_admin',
		[ 'id' => 'custom_footer', 'label' => 'Custom footer text' ]
	);

	add_settings_field(
		'custom_toolbar',
		'Custom Toolbar',
		'blickplugin_callback_field_checkbox',
		'blickplugin',
		'blickplugin_section_admin',
		[ 'id' => 'custom_toolbar', 'label' => 'Remove new post and comment links from the Toolbar' ]
	);

	add_settings_field(
		'custom_scheme',
		'Custom Scheme',
		'blickplugin_callback_field_select',
		'blickplugin',
		'blickplugin_section_admin',
		[ 'id' => 'custom_scheme', 'label' => 'Default color scheme for new users' ]
	);
}
add_action( 'admin_init', 'blickplugin_register_settings' );