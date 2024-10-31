<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for system status.
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
// Template for showing information about system status.
global $mrbpfw_mwb_mrbpfw_obj;
$mrbpfw_default_status    = $mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_system_status();
$mrbpfw_wordpress_details = is_array( $mrbpfw_default_status['wp'] ) && ! empty( $mrbpfw_default_status['wp'] ) ? $mrbpfw_default_status['wp'] : array();
$mrbpfw_php_details       = is_array( $mrbpfw_default_status['php'] ) && ! empty( $mrbpfw_default_status['php'] ) ? $mrbpfw_default_status['php'] : array();
?>
<div class="mwb-mrbpfw-table-wrap">
	<div class="mwb-col-wrap">
		<div id="mwb-mrbpfw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-mrbpfw-table mdc-data-table__table mwb-table" id="mwb-mrbpfw-wp">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Variables', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'WP Values', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $mrbpfw_wordpress_details ) && ! empty( $mrbpfw_wordpress_details ) ) { ?>
							<?php foreach ( $mrbpfw_wordpress_details as $wp_key => $wp_value ) { ?>
								<?php if ( isset( $wp_key ) && 'wp_users' !== $wp_key ) { ?>
									<tr class="mdc-data-table__row">
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_key ); ?></td>
										<td class="mdc-data-table__cell"><?php echo esc_html( $wp_value ); ?></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="mwb-col-wrap">
		<div id="mwb-mrbpfw-table-inner-container" class="table-responsive mdc-data-table">
			<div class="mdc-data-table__table-container">
				<table class="mwb-mrbpfw-table mdc-data-table__table mwb-table" id="mwb-mrbpfw-sys">
					<thead>
						<tr>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'System Variables', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
							<th class="mdc-data-table__header-cell"><?php esc_html_e( 'System Values', 'mwb-role-based-pricing-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php if ( is_array( $mrbpfw_php_details ) && ! empty( $mrbpfw_php_details ) ) { ?>
							<?php foreach ( $mrbpfw_php_details as $php_key => $php_value ) { ?>
								<tr class="mdc-data-table__row">
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_key ); ?></td>
									<td class="mdc-data-table__cell"><?php echo esc_html( $php_value ); ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
