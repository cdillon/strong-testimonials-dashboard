<?php
/**
 * Plugin Name: Strong Testimonials Dashboard
 * Version 0.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function strongdashboard_print_r( $option ) {
	echo '<div>';
	echo '<pre>' . print_r( $option, true ) . '</pre>';
	echo '</div>';
}

// force one-column dashboard
function strongdashboard_screen_layout_columns($columns) {
	$columns['dashboard'] = 1;
	return $columns;
}
add_filter('screen_layout_columns', 'strongdashboard_screen_layout_columns');


function strongdashboard_screen_layout_dashboard() { return 1; }
add_filter('get_user_option_screen_layout_dashboard', 'strongdashboard_screen_layout_dashboard');


function strongdashboard_scripts() {
	wp_enqueue_style( 'strongdashboard-style', plugins_url( 'style.css', __FILE__ ), false );
}
add_action( 'admin_enqueue_scripts', 'strongdashboard_scripts' );


/**
 * Add dashboard widgets.
 */
function strongdashboard_add_dashboard_widgets() {
	if ( ! current_user_can( 'manage_options' ) )
		return;

	// -----------------------
	// primary location [core]
	// -----------------------

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_9',
		'Base Forms',
		'strongdashboard_wpmtst_option_9_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_10',
		'Custom Forms',
		'strongdashboard_wpmtst_option_10_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_5',
		'Strong Testimonials Views',
		'strongdashboard_wpmtst_option_5_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_8',
		'Strong Testimonials Templates',
		'strongdashboard_wpmtst_option_8_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_2',
		'Strong Testimonials Fields',
		'strongdashboard_wpmtst_option_2_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_7',
		'Strong Testimonials Default View',
		'strongdashboard_wpmtst_option_7_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_6',
		'Strong Testimonials View Options',
		'strongdashboard_wpmtst_option_6_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_4',
		'Strong Testimonials Form Options',
		'strongdashboard_wpmtst_option_4_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_1',
		'Strong Testimonials Options',
		'strongdashboard_wpmtst_option_1_function'
	);

	//wp_add_dashboard_widget(
	//	'strongdashboard_icpo_1',
	//	'ICPO Options',
	//	'strongdashboard_icpo_1_function'
	//);
	//
	//wp_add_dashboard_widget(
	//	'strongdashboard_scpo_1',
	//	'SCPO Options',
	//	'strongdashboard_scpo_1_function'
	//);
	//
	//wp_add_dashboard_widget(
	//	'strongdashboard_cpto_1',
	//	'CPTO Options',
	//	'strongdashboard_cpto_1_function'
	//);

	// -------------------------
	// secondary location [side]
	// -------------------------
	//add_meta_box( '', 'Paths', 'strongdashboard_showdirs_function', 'dashboard', 'side', 'high' );

}
add_action( 'wp_dashboard_setup', 'strongdashboard_add_dashboard_widgets', 20 );

/**
 * ==============================
 * WIDGETS
 * ==============================
 */

function strongdashboard_wpmtst_option_1_function() {
	$options = get_option( 'wpmtst_options' );
	if ( $options ) {
		strongdashboard_print_r( $options );
		if ( class_exists( 'Kint' ) ) {
			d( $options );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_2_function() {
	$fields = get_option( 'wpmtst_fields' );
	if ( $fields ) {
		strongdashboard_print_r( $fields );
		if ( class_exists( 'Kint' ) ) {
			d( $fields );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_4_function() {
	$form_options = get_option( 'wpmtst_form_options' );
	if ( $form_options ) {
		strongdashboard_print_r( $form_options );
		if ( class_exists( 'Kint' ) ) {
			d( $form_options );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_5_function() {
	if ( function_exists( 'wpmtst_get_views' ) ) {
		$views = wpmtst_get_views();
		echo '<div>';
		foreach ( $views as $key => $view ) {
			echo '<p><b>' . $view['id'] . ' - ' . $view['name'] . '</b></p>';
			echo '<pre>' . print_r( unserialize( $view['value'] ), true ) . '</pre>';
		}
		echo '</div>';
		if ( class_exists( 'Kint' ) ) {
			d( $views );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_6_function() {
	$view_options = get_option( 'wpmtst_view_options' );
	if ( $view_options ) {
		strongdashboard_print_r( $view_options );
		if ( class_exists( 'Kint' ) ) {
			d( $view_options );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_7_function() {
	$view_default = get_option( 'wpmtst_view_default' );
	if ( $view_default ) {
		strongdashboard_print_r( $view_default );
		if ( class_exists( 'Kint' ) ) {
			d( $view_default );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_8_function() {
	if ( !class_exists( 'Strong_Templates' ) ) {
		echo '<em>not found</em>';
		return;
	}

	$strong_templates = new Strong_Templates();
	$templates = $strong_templates->get_templates();
	ob_start();
	echo '<div>';
	echo '<pre>'.print_r($templates,1).'</pre>';
	echo '</div>';
	$html = ob_get_contents();
	ob_end_clean();

	$html = str_replace(
		array(
			plugins_url(),
			str_replace( '\\', '/', dirname( plugin_dir_path( __FILE__ ) ) ),

			'strong-testimonials/templates',

			get_template_directory_uri() . '/strong-testimonials',
			str_replace( '\\', '/', get_template_directory() . '/strong-testimonials' ),

			get_stylesheet_directory_uri() . '/strong-testimonials',
			str_replace( '\\', '/', get_stylesheet_directory() . '/strong-testimonials' ),
		),
		array(
			'[plugins-URI]',
			'[plugins-path]',

			'[plugin-template-dir]',

			'[parent-theme-URI]',
			'[parent-theme-path]',

			'[child-theme-URI]',
			'[child-theme-path]',
		),
		str_replace( '\\', '/', $html )
	);

	echo $html;

	if ( class_exists( 'Kint' ) ) { d( $templates ); }
}

function strongdashboard_wpmtst_option_9_function() {
	$base_forms = get_option( 'wpmtst_base_forms' );
	if ( $base_forms ) {
		strongdashboard_print_r( $base_forms );
		if ( class_exists( 'Kint' ) ) {
			d( $base_forms );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_10_function() {
	$custom_forms = get_option( 'wpmtst_custom_forms' );
	if ( $custom_forms ) {
		strongdashboard_print_r( $custom_forms );
		if ( class_exists( 'Kint' ) ) {
			d( $custom_forms );
		}
	}
	else {
		echo '<em>not found</em>';
	}
}

/**
 * ==============================
 *  TEST FILTERS
 * ==============================
 */

//add_filter( 'wpmtst_field_required_tag', '__return_false' );
//add_filter( 'wpmtst_form_validation_script', '__return_false' );
