<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *	Class WAC_Admin_Bulk_Edit
 *
 *	Add an option to the bulk edit settings.
 *
 *	@class       WAC_Admin_Bulk_Edit
 *	@version     1.0.0
 *	@author      Jeroen Sormani
 */
class WAC_Bulk_Edit {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add select to bulk edit
		add_action( 'woocommerce_product_bulk_edit_end', array( $this, 'bulk_edit_availability_chart' ) );

		// Save bulk edit availability chart setting
		add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'bulk_edit_save' ) );

	}

	/**
	 * Bulk edit.
	 *
	 * Add option to bulk edit.
	 *
	 * @since 1.0.0
	 */
	public function bulk_edit_availability_chart() {

		?><div class="availability-chart-field">
			<label>
				<span class="title"><?php esc_html_e( 'Availability chart', 'woocommerce-availability-chart' ); ?></span>
				<span class="input-text-wrap">
					<select class="availability-chart" name="_availability_chart">
						<?php
						$options = array(
							''		=> __( '— No Change —', 'woocommerce' ),
							'yes'	=> __( 'Display chart', 'woocommerce-availability-chart' ),
							'no'	=> __( 'Don\'t display Chart', 'woocommerce-availability-chart' ),
						);
						foreach ( $options as $key => $value ) {
							echo '<option value="' . esc_attr( $key ) . '">' . $value . '</option>'; // WPCS: XSS ok.
						}
					?></select>
				</span>
			</label>
		</div><?php

	}

	/**
	 * Save bulk edit.
	 *
	 * Save the bulk edit, only when variable.
	 *
	 * @since 1.0.0
	 *
	 * @param $product WC_Product
	 */
	public function bulk_edit_save( $product ) {

		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			$product_id = $product->get_id();
		} else {
			$product_id = $product->id;
		}

		if ( $product->is_type( 'variable' ) ) {
			if ( isset( $_REQUEST['_availability_chart'] ) && 'yes' === $_REQUEST['_availability_chart'] ) { // Input var okay. CSRF ok.
				update_post_meta( $product_id, '_availability_chart', 'yes' );
			} else {
				update_post_meta( $product_id, '_availability_chart', 'no' );
			}
		}

	}

}
