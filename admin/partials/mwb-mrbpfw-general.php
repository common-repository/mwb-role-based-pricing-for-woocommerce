<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $mrbpfw_mwb_mrbpfw_obj;
$nonce                   = wp_create_nonce( 'general-setting-nonce' );
$mrbpfw_genaral_settings = apply_filters( 'mrbpfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-mrbpfw-gen-section-form">
	<input type="hidden" name="general-setting-nonce" value="<?php echo esc_html( $nonce ); ?>" />
	<div class="mrbpfw-secion-wrap">
		<?php
		$mrbpfw_general_html = $mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_generate_html( $mrbpfw_genaral_settings );
		echo esc_html( $mrbpfw_general_html );
		?>
	</div>
</form>
<h6><?php esc_html_e( 'To create a new price rule click', 'mwb-role-based-pricing-for-woocommerce' ); ?>&nbsp;<a href="<?php echo esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule&post_id=0' ); ?>"><?php esc_html_e( 'here', 'mwb-role-based-pricing-for-woocommerce' ); ?></a><h6>
<h6><?php esc_html_e( 'To manage existing price rules click', 'mwb-role-based-pricing-for-woocommerce' ); ?>&nbsp;<a href="<?php echo esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule' ); ?>"><?php esc_html_e( 'here', 'mwb-role-based-pricing-for-woocommerce' ); ?></a><h6>
