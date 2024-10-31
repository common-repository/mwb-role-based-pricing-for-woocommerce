<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Role_Based_Pricing_For_Woocommerce_Activator {

	/**
	 * It will run on the plugin activation
	 *
	 * @param object $network_wide .
	 */
	public static function mwb_mrbpfw_role_based_pricing_for_woocommerce_activate( $network_wide ) {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {
			// Get all blogs in the network and activate plugins on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				if ( function_exists( 'mwb_mrbpfw_save_user_setting' ) ) {
					mwb_mrbpfw_save_user_setting();
				}

				restore_current_blog();
			}
		} else {
			if ( function_exists( 'mwb_mrbpfw_save_user_setting' ) ) {
				mwb_mrbpfw_save_user_setting();
			}
		}
	}

}
