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
$mrbpfw_active_tab   = isset( $_GET['mrbpfw_tab'] ) ? sanitize_key( $_GET['mrbpfw_tab'] ) : 'mwb-mrbpfw-general';
$mrbpfw_default_tabs = $mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_default_tabs();
?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php esc_html_e( 'MWB ROLE BASED PRICING FOR WOOCOMMERCE', 'mwb-role-based-pricing-for-woocommerce' ); ?></h1>
		<a href="https://docs.makewebbetter.com/mwb-role-based-pricing-for-woocommerce/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org" target="_blank" class="mwb-link"><?php esc_html_e( 'Documentation', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
		<span>|</span>
		<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org" target="_blank" class="mwb-link"><?php esc_html_e( 'Support', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
	</div>
</header>

<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $mrbpfw_default_tabs ) && ! empty( $mrbpfw_default_tabs ) ) {

				foreach ( $mrbpfw_default_tabs as $mrbpfw_tab_key => $mrbpfw_default_tabs ) {

					$mrbpfw_tab_classes = 'mwb-link ';

					if ( ! empty( $mrbpfw_active_tab ) && $mrbpfw_active_tab === $mrbpfw_tab_key ) {
						$mrbpfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $mrbpfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu' ) . '&mrbpfw_tab=' . esc_attr( $mrbpfw_tab_key ) ); ?>" class="<?php echo esc_attr( $mrbpfw_tab_classes ); ?>"><?php echo esc_html( $mrbpfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>

	<section class="mwb-section">
		<div>
			<?php
				do_action( 'mwb_mrbpfw_before_general_settings_form' );
						// if submenu is directly clicked on woocommerce.
			if ( empty( $mrbpfw_active_tab ) ) {
				$mrbpfw_active_tab = 'mwb_mrbpfw_plug_general';
			}

						// look for the path based on the tab id in the admin templates.
				$mrbpfw_tab_content_path = 'admin/partials/' . $mrbpfw_active_tab . '.php';

				$mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_load_template( $mrbpfw_tab_content_path );

				do_action( 'mwb_mrbpfw_after_general_settings_form' );
			?>
		</div>
	</section>
