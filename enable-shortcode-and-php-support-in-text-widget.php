<?php
/*
 Plugin Name: Enable Shortcode and PHP in Text widget
 Plugin URI: http://w3guy.com/shortcode-php-support-wordpress-text-widget/
 Description: Enable shortcode support and execute PHP in WordPress's Text Widget
 Author: Collins Agbonghama
 Version: 1.2.7
 Author URI: http://w3guy.com
 Text Domain: espw-plugin
 Domain Path: /languages/
 */


require_once dirname(__FILE__) . '/mo-admin-notice.php';

function espw_load_plugin_textdomain() {
	load_plugin_textdomain( 'espw-plugin', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'espw_load_plugin_textdomain' );


// Initialize setting options on activation
register_activation_hook( __FILE__, 'espw_activate_default_values' );
function espw_activate_default_values() {
	$espw_options = array(
		'shortcode' => '',
		'php'       => '',
	);
	update_option( 'espw_option', $espw_options );
}


add_action( 'admin_menu', 'espw_plugin_menu' );

// Adding Submenu to settings
function espw_plugin_menu() {
	add_options_page(
		__( 'Shortcode & PHP in Text Widget', 'espw-plugin' ),
		__( "Shortcode & PHP in Text Widget", "espw-plugin" ),
		'manage_options',
		'espw-short-code-php-widget',
		'espw_plugin_settings'
	);
}


function espw_plugin_settings() {
	echo '<div class="wrap">';
	echo '<h2>';
	_e( 'Enable Shortcode and PHP support in Text widget', 'espw-plugin' );
	echo '</h2>';
	echo '<form action="options.php" method="post">';
	do_settings_sections( 'espw-short-code-php-widget' );
	settings_fields( 'espw_settings_group' );
	submit_button();
	?>
	<br>
	<br>
	<div style="width: 600px; text-align: center; margin: 10px auto; padding: 2px; background-color: #e3e3e3; border: 1px solid #DDDDDD">
		<p>

		<h3>See Also</h3>
		<strong><a target="_blank" href="https://wordpress.org/plugins/ppress/">ProfilePress</a></strong>: A shortcode based WordPress form builder that makes building custom login, registration and password reset forms stupidly simple.
		</p>
	</div>
<?php

}

// plugin field and sections
function espw_plugin_option() {
	add_settings_section(
		'espw_settings_section',
		'Plugin Options',
		null,
		'espw-short-code-php-widget'
	);

	add_settings_field(
		'shortcode',
		'<label for="shortcode">' . __( 'Enable Shortcode Support', 'espw-plugin' ) . '</label>',
		'espw_shortcode_field',
		'espw-short-code-php-widget',
		'espw_settings_section'
	);

	add_settings_field(
		'php',
		'<label for="php">' . __( 'Enable PHP Suport', 'espw-plugin' ) . '</label>',
		'espw_php_field',
		'espw-short-code-php-widget',
		'espw_settings_section'
	);

	// register settings
	register_setting( 'espw_settings_group', 'espw_option' );
}

function espw_shortcode_field() {
	$optionz = get_option( 'espw_option' );
	echo '<input type="checkbox" id="shortcode" name="espw_option[shortcode]" value="1"' . checked( $optionz['shortcode'], 1, false ) . '">';

}

function espw_php_field() {
	$optionz = get_option( 'espw_option' );
	echo '<input type="checkbox" id="php" name="espw_option[php]" value="1"' . checked( $optionz['php'], 1, false ) . '">';

}

// register options
add_action( 'admin_init', 'espw_plugin_option' );

$espw_options = get_option( 'espw_option' );

if ( $espw_options['shortcode'] ) {

	// shortcode support
	add_filter( 'widget_text', 'do_shortcode' );
}

if ( $espw_options['php'] ) {
	// PHP support
	function exam_plug_text_replace( $text ) {
		ob_start();
		eval( '?>'
		      . $text );
		$text = ob_get_contents();
		ob_end_clean();

		return $text;
	}

	add_filter( 'widget_text', 'exam_plug_text_replace' );
}
