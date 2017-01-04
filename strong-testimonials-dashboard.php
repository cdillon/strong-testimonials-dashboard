<?php
/**
 * Plugin Name: Strong Testimonials Dashboard
 * Plugin URI: http://www.wpmission.com
 * Description: Add-on for the Strong Testimonials plugin.
 * Author: Chris Dillon
 * Version: 0.7
 * Author URI: http://wpmission.com
 * Text Domain: strong-testimonials-dashboard
 * Requires: 3.5 or higher
 * License: GPLv3 or later
 *
 * Copyright 2016  Chris Dillon  chris@wpmission.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Strong_Testimonials_Dashboard {

	public function __construct() {

		add_action( 'admin_head-index.php', array( $this, 'add_style' ) );

		//add_filter('screen_layout_columns', array( $this, 'screen_layout_columns' ) );

		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ), 20 );

	}

	/**
	* Some style
	*/
	function add_style() {
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

	/**
	 * Our array printer
	 *
	 * @param $option
	 */
	function printer( $option ) {
		echo '<div>';
		if ( class_exists( 'Kint' ) ) {
			echo Kint::dump( $option );
		}
		else {
			echo '<pre>' . print_r( $option, true ) . '</pre>';
		}
		echo '</div>';
	}

	/**
	 * Force one-column dashboard
	 */
	function screen_layout_columns( $columns ) {
		$columns['dashboard'] = 1;

		return $columns;
	}

	/**
	 * Add dashboard widgets.
	 */
	function add_dashboard_widgets() {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		// -----------------------
		// primary location [core]
		// -----------------------

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_11',
			'Strong Testimonials &bull; Add-ons',
			array( $this, 'wpmtst_option_11_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_12',
			'Strong Testimonials &bull; Licenses',
			array( $this, 'wpmtst_option_12_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_9',
			'Strong Testimonials &bull; Base Forms',
			array( $this, 'wpmtst_option_9_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_10',
			'Strong Testimonials &bull; Custom Forms',
			array( $this, 'wpmtst_option_10_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_5',
			'Strong Testimonials &bull; Views',
			array( $this, 'wpmtst_option_5_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_8',
			'Strong Testimonials &bull; Templates',
			array( $this, 'wpmtst_option_8_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_2',
			'Strong Testimonials &bull; Fields',
			array( $this, 'wpmtst_option_2_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_7',
			'Strong Testimonials &bull; Default View',
			array( $this, 'wpmtst_option_7_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_6',
			'Strong Testimonials &bull; View Options',
			array( $this, 'wpmtst_option_6_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_4',
			'Strong Testimonials &bull; Form Options',
			array( $this, 'wpmtst_option_4_function' )
		);

		wp_add_dashboard_widget(
			'strongdashboard_wpmtst_1',
			'Strong Testimonials &bull; Options',
			array( $this, 'wpmtst_option_1_function' )
		);

		//wp_add_dashboard_widget(
		//	'strongdashboard_icpo_1',
		//	'ICPO Options',
		//	array( $this, 'icpo_1_function' )
		//);
		//
		//wp_add_dashboard_widget(
		//	'strongdashboard_scpo_1',
		//	'SCPO Options',
		//	array( $this, 'scpo_1_function' )
		//);
		//
		//wp_add_dashboard_widget(
		//	'strongdashboard_cpto_1',
		//	'CPTO Options',
		//	array( $this, 'cpto_1_function' )
		//);

		// -------------------------
		// secondary location [side]
		// -------------------------
		//add_meta_box( '', 'Paths', array( $this, 'showdirs_function' ), 'dashboard', 'side', 'high' );

	}

	/**
	 * ------------------------------
	 * WIDGETS
	 * ------------------------------
	 */

	function wpmtst_option_1_function() {
		$options = get_option( 'wpmtst_options' );
		if ( $options ) {
			$this->printer( $options );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_2_function() {
		$fields = get_option( 'wpmtst_fields' );
		if ( $fields ) {
			$this->printer( $fields );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_4_function() {
		$form_options = get_option( 'wpmtst_form_options' );
		if ( $form_options ) {
			$this->printer( $form_options );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_5_function() {
		if ( function_exists( 'wpmtst_get_views' ) ) {
			$views = wpmtst_get_views();
			echo '<div>';
			foreach ( $views as $key => $view ) {
				echo '<p style="font-size: 1.5em; margin-bottom: 0.5em; padding-top: 0.5em;">' . $view['id'] . ' - ' . $view['name'] . '</p>';
				$this->printer( unserialize( $view['value'] ) );
			}
			echo '</div>';
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_6_function() {
		$view_options = get_option( 'wpmtst_view_options' );
		if ( $view_options ) {
			$this->printer( $view_options );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_7_function() {
		$view_default = get_option( 'wpmtst_view_default' );
		if ( $view_default ) {
			$this->printer( $view_default );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_8_function() {
		if ( ! class_exists( 'Strong_Templates' ) ) {
			echo '<em>not found</em>';

			return;
		}

		$strong_templates = new Strong_Templates();
		$templates        = $strong_templates->get_templates();

		$this->printer( $templates );
	}

	function wpmtst_option_9_function() {
		$base_forms = get_option( 'wpmtst_base_forms' );
		if ( $base_forms ) {
			$this->printer( $base_forms );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_10_function() {
		$custom_forms = get_option( 'wpmtst_custom_forms' );
		if ( $custom_forms ) {
			$this->printer( $custom_forms );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_11_function() {
		$addons = get_option( 'wpmtst_addons' );
		if ( $addons ) {
			$this->printer( $addons );
		}
		else {
			echo '<em>not found</em>';
		}
	}

	function wpmtst_option_12_function() {
		$licenses = get_option( 'wpmtst_licenses' );
		if ( $licenses ) {
			$this->printer( $licenses );
		}
		else {
			echo '<em>not found</em>';
		}
	}

}

new Strong_Testimonials_Dashboard();
