<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
global $mrbpfw_mwb_mrbpfw_obj;
$roles_obj         = new WP_Roles();
$roles_names_array = $roles_obj->get_names();
$guest             = array( 'guest' => 'Guest' );
$roles_names_array = array_merge( $guest, $roles_names_array );
$nonce             = wp_create_nonce( 'user-setting-nonce' );
?>
<form method="post" action="" class="mwb-mrbpfw-gen-section-form">
	<input type="hidden" name="user-setting-nonce" value="<?php echo esc_html( $nonce ); ?>" />
	<div class="mrbpfw-secion-wrap mwb-user-setting-table-wrap">
		<table>
			<tr>
				<th><?php echo esc_html__( 'Roles', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'Regular Price', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'On Sale Price', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'Role Based Price', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'Add to Cart', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'Incl/Excl Tax', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
				<th><?php echo esc_html__( 'Show Discount', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
			</tr>
			<?php

			foreach ( $roles_names_array as $roles ) {
				$rule_role   = esc_html( str_replace( ' ', '_', strtolower( $roles ) ) );
				$get_options = get_option( 'user_setting_' . $rule_role );
				?>
			<tr>
				<th><span><b><?php echo esc_html( $roles ); ?></b></span></th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'regular_price_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'regular_price_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'on_sale_price_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'on_sale_price_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'role_based_price_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'role_based_price_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'add_to_cart_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'add_to_cart_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'show_tax_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
				<th><input type="checkbox" name="user_setting_<?php echo esc_html( $rule_role ) . '[]'; ?>" value="<?php echo esc_html( str_replace( ' ', '_', strtolower( 'show_total_discount_' . $rule_role ) ) ); ?>"
				<?php
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_total_discount_' . $rule_role, $get_options, true ) ) {
					echo 'checked'; } else {
						echo ''; }
					?>
				>
				</th>
			</tr>
				<?php
			}
			?>
		</table>
	</div>
	<input type="submit" class="submit-button" name="save_user_setting" value="<?php esc_html_e( 'Save User Setting', 'mwb-role-based-pricing-for-woocommerce' ); ?>">
</form>
