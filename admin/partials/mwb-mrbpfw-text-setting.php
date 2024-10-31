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
$nonce = wp_create_nonce( 'general-setting-nonce' );
global $mrbpfw_mwb_mrbpfw_obj;
$mrbpfw_text_settings = apply_filters( 'mrbpfw_text_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-mrbpfw-gen-section-form">
<input type="hidden" name="general-setting-nonce" value="<?php echo esc_html( $nonce ); ?>" />
	<div class="mrbpfw-secion-wrap">
		<?php
		$mrbpfw_text_html = $mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_generate_html( $mrbpfw_text_settings );
		echo esc_html( $mrbpfw_text_html );
		?>
	</div>
</form>
