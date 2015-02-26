<?PHP
/*
Plugin Name: WooCommerce Availability Chart
Plugin URI: https://github.com/growdevelopment/woocommerce-availability-chart/
Description: WooCommerce Availability Chart displays a nice looking chart on variation product pages with the availability of products
Version: 1.0.0
Author: Shop Plugins, Jeroen Sormani, Daniel Espinoza
Author URI: http://shopplugins.com
Text Domain: woocommerce-availability-chart

 * Copyright Shop Plugins
 *
 *		This file is part of WooCommerce Availability Chart,
 *		a plugin for WordPress.
 *
 *		WooCommerce Availability Chart is free software:
 *		You can redistribute it and/or modify it under the terms of the
 *		GNU General Public License as published by the Free Software
 *		Foundation, either version 3 of the License, or (at your option)
 *		any later version.
 *
 *		WooCommerce Availability Chart is distributed in the hope that
 *		it will be useful, but WITHOUT ANY WARRANTY; without even the
 *		implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *		PURPOSE. See the GNU General Public License for more details.
 *
 *		You should have received a copy of the GNU General Public License
 *		along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WooCommerce_Availability_Chart.
 *
 * Main WAC class initializes the plugin.
 *
 * @class		WooCommerce_Availability_Chart
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WooCommerce_Availability_Chart {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

		// Check if WooCommerce is active
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) :
				return;
			endif;
		endif;

		// Add the availability chart
		add_action( 'woocommerce_single_product_summary', array( $this, 'wac_availability_chart' ), 45 );

		// Enqueue style
		add_action( 'wp_enqueue_scripts', array( $this, 'wac_enqueue_style' ) );

	}


	/**
	 * Availability chart.
	 *
	 * Add the availability chart to product page.
	 *
	 * @since 1.0.0
	 *
	 * @global int $product Get product object.
	 */
	public function wac_availability_chart() {

		global $product;
		$display_availability_chart = get_post_meta( $product->id, '_availability_chart', true );

		if ( 'no' == $display_availability_chart || empty ( $display_availability_chart ) || 'variable' != $product->product_type ) :
			return;
		endif;

		$available_variations = $product->get_available_variations();

		?>
		<h3 class='availability-chart-title'><?php _e( 'Availability', 'woocommerce-availability-chart' ); ?></h3>
		<div class='availability-chart'><?php

			// Loop variations
			foreach ( $available_variations as $variation ) :

				$max_stock 	= $product->get_total_stock();
				$var 		= wc_get_product( $variation['variation_id'] );

				if ( true == $var->variation_has_stock ) :

					// Get variation name
					$variation_name = $this->wac_variation_name( $variation['attributes'] );

					// Get an availability bar
					$this->wac_get_availability_bar( $variation['variation_id'], $max_stock, $variation_name );

				endif;

			endforeach;

		?></div><?php


	}


	/**
	 * Chart bar.
	 *
	 * Get an single chart bar.
	 *
	 * @since 1.0.0
	 *
	 * @param int		$variation_id 		ID of the variation.
	 * @param int 		$max_stock 			Stock quantity of the variation with the most stock.
	 * @param string 	$variation_name 	Name of the variation.
	 */
	public function wac_get_availability_bar( $variation_id, $max_stock, $variation_name ) {

		$stock 		= get_post_meta( $variation_id, '_stock', true );
		$percentage = round( $stock/$max_stock*100 );
		?><div class='bar-wrap'>

			<div class='variation-name'><?php echo $variation_name; ?></div>

			<div class='bar'>
				<div class='filled<?php if ($stock==0) echo ' out-of-stock'; ?>' style='width: <?php echo $percentage; ?>%;'><?php echo $stock; ?></div>
			</div>

		</div><?php

	}


	/**
	 * Variation name.
	 *
	 * Get the variation name based on the attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param 	array 	$attributes 	All the attributes of the variation
	 * @return 	string 					Variation name based on attributes.
	 */
	public function wac_variation_name( $attributes ) {

		$variation_name = '';

		foreach ( $attributes as $attr => $value ) :

			if ( term_exists( $value, str_replace( 'attribute_', '', $attr ) ) ) :

				$term = get_term_by( 'slug', $value, str_replace( 'attribute_', '', $attr ) );
				if ( isset( $term->name ) ) :
					$variation_name .= $term->name . ', ';

				endif;

			else :

				$variation_name .= $value . ', ';

			endif;

		endforeach;

		return rtrim( $variation_name, ', ' );

	}


	/**
	 * Enqueue style.
	 *
	 * @since 1.0.0
	 */
	public function wac_enqueue_style() {
		wp_enqueue_style( 'woocommerce-availability-chart', plugins_url( 'assets/css/woocommerce-availability-chart.css', __FILE__ ) );
	}

}

if ( is_admin() ) :

	/**
	 * Admin panel
	 */
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-wac-admin.php';
	/**
	 * Bulk edit Admin panel
	 */
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-wac-bulk-edit.php';
	/**
	 * Quick edit Admin panel
	 */
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-wac-quick-edit.php';

endif;

global $availability_chart;
$availability_chart = new WooCommerce_Availability_Chart();