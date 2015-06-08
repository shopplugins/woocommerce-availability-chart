<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *	Class WooCommerce_Availability_Chart
 *
 *	Main WAC class initializes the plugin
 *
 *	@class       WooCommerce_Availability_Chart
 *	@version     1.0.0
 *	@author      Jeroen Sormani
 */
class WAC_Admin {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add checkbox to general products panel
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'general_product_data_tab' ) );

		// Save checkbox from general products panel
		add_action( 'woocommerce_process_product_meta_simple', array( $this, 'process_product_meta' ) );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'process_product_meta' ) );

	}


	/**
	 * Add checkbox.
	 *
	 * Add checkbox to the general products data tab (when variable).
	 *
	 * @since 1.0.0
	 */
	public function general_product_data_tab() {

		?><div class='options_group show_if_simple show_if_variable'>

			<div class='wac_chart'><?php

				woocommerce_wp_checkbox( array(
					'id' 			=> '_availability_chart',
					'wrapper_class' => 'show_if_simple show_if_variable',
					'label' 		=> __('Availability chart', 'woocommerce-availability-chart' ),
					'description' 	=> __( 'Display availability chart on product page', 'woocommerce-availability-chart' )
				) );

			?></div><?php

		?></div><?php

	}


	/**
	 * Save setting.
	 *
	 * Save the availability chart setting.
	 *
	 * @since 1.0.0
	 */
	public function process_product_meta( $post_id ) {

		if ( ! empty( $_POST['_availability_chart'] ) ) :
			update_post_meta( $post_id, '_availability_chart', 'yes' );
		else :
			update_post_meta( $post_id, '_availability_chart', 'no' );
		endif;

	}

}
