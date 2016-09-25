<?php
/**
 * Plugin Name: Strong Testimonials Dashboard
 * Version 0.5.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Some style
 */
function strongdashboard_style() {
	if ( class_exists( 'Kint' ) ) {
		?>
		<style>div[id^="strongdashboard_"] .kint footer { font-size: inherit; }</style>
		<?php
	}
	else {
		?>
		<style>div[id^="strongdashboard_"] div pre { white-space: pre-wrap; }</style>
		<?php
	}
}
add_action( 'admin_head-index.php', 'strongdashboard_style' );


/**
 * Our array printer
 *
 * @param $option
 */
function strongdashboard_print_r( $option ) {
	echo '<div>';
	if ( class_exists( 'Kint' ) ) {
		echo d( $option );
	} else {
		echo '<pre>' . print_r( $option, true ) . '</pre>';
	}
	echo '</div>';
}

/**
 * Force one-column dashboard
 */
function strongdashboard_screen_layout_columns($columns) {
	$columns['dashboard'] = 1;
	return $columns;
}
//add_filter('screen_layout_columns', 'strongdashboard_screen_layout_columns');

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
		'Strong Testimonials &bull; Base Forms',
		'strongdashboard_wpmtst_option_9_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_10',
		'Strong Testimonials &bull; Custom Forms',
		'strongdashboard_wpmtst_option_10_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_5',
		'Strong Testimonials &bull; Views',
		'strongdashboard_wpmtst_option_5_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_8',
		'Strong Testimonials &bull; Templates',
		'strongdashboard_wpmtst_option_8_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_2',
		'Strong Testimonials &bull; Fields',
		'strongdashboard_wpmtst_option_2_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_7',
		'Strong Testimonials &bull; Default View',
		'strongdashboard_wpmtst_option_7_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_6',
		'Strong Testimonials &bull; View Options',
		'strongdashboard_wpmtst_option_6_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_4',
		'Strong Testimonials &bull; Form Options',
		'strongdashboard_wpmtst_option_4_function'
	);

	wp_add_dashboard_widget(
		'strongdashboard_wpmtst_1',
		'Strong Testimonials &bull; Options',
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
 * ------------------------------
 * WIDGETS
 * ------------------------------
 */

function strongdashboard_wpmtst_option_1_function() {
	$options = get_option( 'wpmtst_options' );
	if ( $options ) {
		strongdashboard_print_r( $options );
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_2_function() {
	$fields = get_option( 'wpmtst_fields' );
	if ( $fields ) {
		strongdashboard_print_r( $fields );
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_4_function() {
	$form_options = get_option( 'wpmtst_form_options' );
	if ( $form_options ) {
		strongdashboard_print_r( $form_options );
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
			echo '<p style="font-size: 1.5em; margin-bottom: 0.5em; padding-top: 0.5em;">' . $view['id'] . ' - ' . $view['name'] . '</p>';
			strongdashboard_print_r( unserialize( $view['value'] ) );
		}
		echo '</div>';
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_6_function() {
	$view_options = get_option( 'wpmtst_view_options' );
	if ( $view_options ) {
		strongdashboard_print_r( $view_options );
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_7_function() {
	$view_default = get_option( 'wpmtst_view_default' );
	if ( $view_default ) {
		strongdashboard_print_r( $view_default );
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

	strongdashboard_print_r( $templates );
}

function strongdashboard_wpmtst_option_9_function() {
	$base_forms = get_option( 'wpmtst_base_forms' );
	if ( $base_forms ) {
		strongdashboard_print_r( $base_forms );
	}
	else {
		echo '<em>not found</em>';
	}
}

function strongdashboard_wpmtst_option_10_function() {
	$custom_forms = get_option( 'wpmtst_custom_forms' );
	if ( $custom_forms ) {
		strongdashboard_print_r( $custom_forms );
	}
	else {
		echo '<em>not found</em>';
	}
}
