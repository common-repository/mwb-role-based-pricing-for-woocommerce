<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/onboarding/templates
 */

global $mrbpfw_mwb_mrbpfw_obj;
$mrbpfw_onboarding_form_fields = apply_filters( 'mwb_mrbpfw_on_boarding_form_fields', array() );
?>

<?php if ( ! empty( $mrbpfw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable mwb-mrbpfw-boarding-dailog">
		<div class="mwb-mrbpfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-mrbpfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-mrbpfw-on-boarding-close-btn">
						<a href="#"><span class="mrbpfw-close-form material-icons mwb-mrbpfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>

					<h3 class="mwb-mrbpfw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to MakeWebBetter', 'mwb-role-based-pricing-for-woocommerce' ); ?> </h3>
					<p class="mwb-mrbpfw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'mwb-role-based-pricing-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="mwb-mrbpfw-on-boarding-form">
						<?php
						$mrbpfw_onboarding_html = $mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_generate_html( $mrbpfw_onboarding_form_fields );
						echo esc_html( $mrbpfw_onboarding_html );
						?>
						<div class="mwb-mrbpfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-mrbpfw-on-boarding-form-submit mwb-mrbpfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-mrbpfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-mrbpfw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-mrbpfw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
