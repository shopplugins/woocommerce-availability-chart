<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *	Class WAC_Admin_Quick_Edit.
 *
 *	Add an option to the quick edit settings.
 *
 *	@class       WAC_Admin_Quick_Edit
 *	@version     1.0.0
 *	@author      Jeroen Sormani
 */
class WAC_Admin_Quick_Edit {


	/**
	 * __construct function.
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct() {

		// Add select to bulk edit
		add_action( 'woocommerce_product_quick_edit_end', array( $this, 'wac_quick_edit_availability_chart' ) );

		// Save bulk edit availability chart setting
		add_action( 'woocommerce_product_quick_edit_save', array( $this, 'wac_quick_edit_save' ) );

	}


	/**
	 * Quick edit.
	 *
	 * Add option to quick edit.
	 *
	 * @since 1.0.0
	 */
	public function wac_quick_edit_availability_chart() {

		?>
		<div class="availability-chart-field">
			<label class="alignleft">
			    <span class="title"><?php _e( 'Availability chart', 'woocommerce-availability-chart' ); ?></span>
			    <span class="input-text-wrap">
			    	<select class="availability-chart" name="_availability_chart">
					<?php
						$options = array(
							''		=> __( '— No Change —', 'woocommerce' ),
							'yes'	=> __( 'Display chart', 'woocommerce-availability-chart' ),
							'no'	=> __( 'Don\'t display Chart', 'woocommerce-availability-chart' )
						);
						foreach ( $options as $key => $value 	) {
							echo '<option value="' . esc_attr( $key ) . '">'. $value .'</option>';
						}
					?>
					</select>
				</span>
			</label>
		</div>
		<?php

	}



	/**
	 * Save quick edit.
	 *
	 * Save the quick edit, only when variable.
	 *
	 * @since 1.0.0
	 *
	 * @param object $product Product object.
	 */
	public function wac_quick_edit_save( $product ) {

		if ( $product->is_type( 'variable' ) ) :
			if ( ! empty( $_REQUEST['_availability_chart'] ) ) :
				update_post_meta( $product->id, '_availability_chart', wc_clean( $_REQUEST['_availability_chart'] ) );
			endif;
		endif;

	}

}
$wac_admin_quick = new WAC_Admin_Quick_Edit();