<?php
/**
 * Plugin Name: Strong Testimonials Dashboard
 * Plugin URI: https://strongplugins.com
 * Description: Add-on for the Strong Testimonials plugin.
 * Author: Chris Dillon
 * Version: 0.15
 * Author URI: https://strongplugins.com
 * Text Domain: strong-testimonials-dashboard
 * Requires: 4.0 or higher
 * License: GPLv2 or later
 *
 * Copyright 2016-2018  Chris Dillon  chris@strongwp.com
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

	public $not_found = '<em>not found</em>';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'add_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_script' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_info_widgets' ), 20 );
		add_action( 'wp_dashboard_setup', array( $this, 'add_view_widgets' ), 30 );
	}

	/**
	 * Some style
     *
     * @param $hook
	 */
	function add_style( $hook ) {
		if ( 'index.php' == $hook ) {
			wp_enqueue_style( 'strong-testimonials-dashboard', plugin_dir_url( __FILE__ ) . 'css/dashboard.css' );
		}
	}

	/**
	 * Reposition elements
	 *
	 * @param $hook
	 */
	function add_script( $hook ) {
		if ( 'index.php' == $hook ) {
			wp_enqueue_script( 'strong-testimonials-dashboard', plugin_dir_url( __FILE__ ) . 'js/dashboard.js', array( 'jquery' ), false );
		}
	}

	/**
	 * Our array printer
	 *
	 * @param $option
	 * @param bool $force_plain
	 */
	function printer( $option, $force_plain = false ) {
		echo '<div>';
		if ( class_exists( 'Kint' ) && ! $force_plain ) {
			echo Kint::dump( $option );
		} else {
			ob_start();
			echo '<pre>' . print_r( $option, true ) . '</pre>';
			$output = ob_get_clean();
			$output = str_replace( '    ', '  ', $output );
			$output = $this->trim_path( $output );
			echo $output;
		}
		echo '</div>';
	}

	/**
	 * Add dashboard widgets.
	 */
	function add_info_widgets() {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		$widgets = array(
			'the_versions'              => 'Versions',
			'the_history'               => 'History',
			'the_update_log'            => 'Update Log',
			'the_options'               => 'Options',
			'the_form_options'          => 'Form Options',
			'the_view_options'          => 'View Options',
			'the_compat_options'        => 'Compatibility Options',
			'the_captcha_plugins'       => 'Captcha Plugins',
			'the_default_view'          => 'Default View',
			'the_fields'                => 'Fields',
			'the_templates'             => 'Templates',
			'the_base_forms'            => 'Base Forms',
			'the_custom_forms'          => 'Custom Forms',
			'the_properties'            => 'Properties',
			'the_review_markup_options' => 'Review Markup Options',
			'the_aggregate_rating'      => 'Aggregate Rating',
			'the_assignment'            => 'Assignment',
		);

		foreach ( $widgets as $callback => $title ) {
			if ( method_exists( $this, $callback ) ) {
				wp_add_dashboard_widget(
					"strongdashboard_$callback",
					$title,
					array( $this, $callback )
				);
			}
		}
	}

	function add_view_widgets() {
		if ( function_exists( 'wpmtst_get_views' ) ) {
			$views = wpmtst_get_views();
			foreach ( $views as $key => $view ) {
				wp_add_dashboard_widget(
					"strongdashboard_view_{$view['id']}",
					"View {$view['id']}: {$view['name']}",
					//"View {$view['id']}",
					array( $this, 'a_view' ),
					null,
					array( 'view' => $view )
				);

			}
		}
	}

	function trim_path( $output ) {
		$plugin_path = str_replace( '/', '\\', wp_normalize_path( plugin_dir_path( __DIR__ ) ) );
		$output      = str_replace( $plugin_path, '...\\', $output );

		$plugin_url  = plugin_dir_url( __DIR__ );
		$output      = str_replace( $plugin_url, '.../', $output );

		return $output;
	}

	/**
	 * @param $control_callback
	 * @param $args
	 */
	function a_view( $control_callback, $args ) {
		$this->printer( unserialize( $args['args']['view']['value'] ) );
	}

	function the_versions() {
		$plugin_version = get_option( 'wpmtst_plugin_version' );
		printf( '<p><b>plugin version:</b> %s</p>', $plugin_version ? $plugin_version : $this->not_found );

		$db_version = get_option( 'wpmtst_db_version' );
        printf( '<p><b>database table version:</b> %s</p>', $db_version ? $db_version : $this->not_found );

		$addons = get_option( 'wpmtst_addons' );
		if ( $addons ) {
			echo '<p><b>add-ons:</b></p>';
			ob_start();
			$this->printer( $addons, true );
			$output = ob_get_clean();
			$output = $this->trim_path( $output );
			echo $output;
		} else {
		  echo '<p><b>add-ons:</b> ' . $this->not_found . '</p>';
		}
	}

	function the_options() {
		$options = get_option( 'wpmtst_options' );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo $this->not_found;
		}
	}

	function the_history() {
		$history = get_option( 'wpmtst_history' );
		if ( $history ) {
			$this->printer( $history );
		} else {
			echo $this->not_found;
		}
	}

	function the_fields() {
		$fields = apply_filters( 'wpmtst_fields', get_option( 'wpmtst_fields' ) );
		if ( $fields ) {
			$this->printer( $fields );
		} else {
			echo $this->not_found;
		}
	}

	function the_form_options() {
		$form_options = get_option( 'wpmtst_form_options' );
		if ( $form_options ) {
			$this->printer( $form_options );
		} else {
			echo $this->not_found;
		}
	}

	function the_view_options() {
		$view_options = get_option( 'wpmtst_view_options' );
		if ( $view_options ) {
			$this->printer( $view_options );
		} else {
			echo $this->not_found;
		}
	}

	function the_default_view() {
		$view_default = get_option( 'wpmtst_view_default' );
		if ( $view_default ) {
			$this->printer( $view_default );
		} else {
			echo $this->not_found;
		}
	}

	function the_templates() {
		if ( ! class_exists( 'Strong_Templates' ) ) {
			echo $this->not_found;
			return;
		}

		$strong_templates = new Strong_Templates();
		$templates        = $strong_templates->get_templates();

		$this->printer( $templates );
	}

	function the_base_forms() {
		$base_forms = get_option( 'wpmtst_base_forms' );
		if ( $base_forms ) {
			$this->printer( $base_forms );
		} else {
			echo $this->not_found;
		}
	}

	function the_custom_forms() {
		$custom_forms = get_option( 'wpmtst_custom_forms' );
		if ( $custom_forms ) {
			$this->printer( $custom_forms );
		} else {
			echo $this->not_found;
		}
	}

	function the_properties() {
		$properties = get_option( 'wpmtst_properties' );
		if ( $properties ) {
			$this->printer( $properties );
		} else {
			echo $this->not_found;
		}
	}

	function the_compat_options() {
		$options = get_option( 'wpmtst_compat_options' );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo $this->not_found;
		}
	}

	function the_update_log() {
		$options = get_option( 'wpmtst_update_log' );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo $this->not_found;
		}
	}

	function the_captcha_plugins() {
		$options = apply_filters( 'wpmtst_captcha_plugins', get_option( 'wpmtst_captcha_plugins' ) );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo $this->not_found;
		}
	}

	function the_review_markup_options() {
		$options = get_option( 'wpmtst_review_markup' );
		if ( $options ) {
			$this->printer( $options, true ); // force plain-text
		} else {
			echo $this->not_found;
		}
	}

	function the_aggregate_rating() {
		$options = get_option( 'wpmtst_aggregate_rating' );
		if ( $options ) {
			$this->printer( $options, true ); // force plain-text
		} else {
			echo $this->not_found;
		}
	}

	function the_assignment() {
		$options = get_option( 'wpmtst_assignment' );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo $this->not_found;
		}
		echo '<p><b>Excluded</b></p>';
		$options = get_option( 'wpmtst_assignment_excluded' );
		if ( $options ) {
			$this->printer( $options );
		} else {
			echo '<pre>none</pre>';
		}
	}

}

new Strong_Testimonials_Dashboard();
