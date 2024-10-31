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

?>
<div class="mwb-overview__wrapper">
	<div class="mwb-overview__banner">
		<img src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL ); ?>admin/image/banner.png" alt="Overview banner image">
	</div>
	<div class="mwb-mrbpfw_overview-wrapper">
		<div>
			<h2 class="mwb-mrbpfw_overview_heading"><?php esc_html_e( 'Connect With Us and Explore More About MWB Role Based Pricing For WooCommerce', 'mwb-role-based-pricing-for-woocommerce' ); ?></h2>
			<div class="mwb-mrbpfw-overview__desc">
				<p><?php esc_html_e( 'The MWB Role Based Pricing For WooCommerce allows the WooCommerce merchants to show prices on their online store based on different user roles. For example, wholesalers, retailers, and distributors, etc. The merchants can create a dedicated pricing list for either selected products or selected categories using this plugin.', 'mwb-role-based-pricing-for-woocommerce' ); ?></p>
			</div>
		</div>
		<div class="mwb-mrbpfw_overview-video">
			<iframe width="960" height="460" src="https://www.youtube.com/embed/bvO4_rF3r-o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
	<div class="mwb-overview__content">
		<div class="mwb-overview__content-description">
			<h2><?php echo esc_html_e( 'What Is MWB Role Based Pricing For WooCommerce?', 'mwb-role-based-pricing-for-woocommerce' ); ?></h2>
			<p>
				<?php
				esc_html_e( 'The MWB Role Based Pricing For WooCommerce allows the WooCommerce merchants to show prices on their online store based on different user roles. For example, wholesalers, retailers, and distributors, etc. The merchants can create a dedicated pricing list for either selected products or selected categories using this plugin.', 'mwb-role-based-pricing-for-woocommerce' );
				?>
			</p>
			<h3><?php esc_html_e( 'With MWB Role Based Pricing For WooCommerce the admin can:', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
			<ul class="mwb-overview__features">
				<li><?php esc_html_e( 'Show the right price separately to wholesalers, retailers, distributors, and customers.', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Priority-based rule application on products for different roles.', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Apply role based pricing on particular selected products', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Hide price for selected user roles.', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Enable Role based pricing on your store with unlimited rule.', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Create, edit, and implement role based pricing rules', 'mwb-role-based-pricing-for-woocommerce' ); ?></li>
			</ul>
		</div>
		<h2><?php esc_html_e( 'Features Of MWB Role Based Pricing For WooCommerce ', 'mwb-role-based-pricing-for-woocommerce' ); ?></h2>
		<p> <?php esc_html_e( 'Take a glance at the key features of this plugin:', 'mwb-role-based-pricing-for-woocommerce' ); ?></p>
		<div class="mwb-overview__keywords">
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img class="align-img" src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/User-Role-Based-Add-To-Cart-Button.png' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'User Role Based Add-To-Cart Button', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'The plugin allows the admin to remove or replace the Add-to-cart button for guest users or other desired user roles. ',
								'mwb-role-based-pricing-for-woocommerce'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Priority-Based-Pricing-Rules.png' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'Priority-Based Pricing Rules', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'Admin can create priority-based pricing rules for different user roles. The admin can give priority to the created rules. The first priority rule will be applied first then the second and so on',
								'mwb-role-based-pricing-for-woocommerce'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Unlimited-User-Role-Based-Pricing-Rules.png' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'Unlimited User Role Based Pricing Rules', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							echo esc_html_e(
								'Admin can create unlimited pricing rules and apply them to the regular price or sale price as desired. Admin may apply restrictions to specific products, tags, or categories as well.',
								'mwb-role-based-pricing-for-woocommerce'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Enable-Disable-Role-Based-Pricing.png' ); ?>" alt="List-of-abandoned-users image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'Enable/ Disable Role Based Pricing', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							echo esc_html_e(
								'Admin can show or hide the role based price, Regular Price, and Sale price to users. Settings for the priority of each user have been provided.',
								'mwb-role-based-pricing-for-woocommerce'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card mwb-card-support">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Hide-Price-For-User-Roles.png' ); ?>" alt="Support image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'Hide Price For User-Roles', 'mwb-role-based-pricing-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'The MWB Role Based Pricing plugin lets the store owner hide certain specific product prices from specific user roles. Admin can completely adjust pricing for user roles.',
								'mwb-role-based-pricing-for-woocommerce'
							);
							?>
						</p>
					</div>
					<a href="https://makewebbetter.com/contact-us/" title=""></a>
				</div>
			</div>
			<?php do_action( 'mwb_mrbpfw_add_overview_cart' ); ?>
		</div>
	</div>
</div>
