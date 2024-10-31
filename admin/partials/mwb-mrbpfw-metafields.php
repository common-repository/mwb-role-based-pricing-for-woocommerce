<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}
$prod_cat_args     = array(
	'taxonomy' => 'product_cat', // woocommerce category slug.
	'orderby'  => 'name',
	'empty'    => 0,
);
$woo_categories    = get_categories( $prod_cat_args );
$terms             = get_terms(
	array(
		'taxonomy'   => 'product_tag',
		'hide_empty' => false,
	)
);
$roles_obj         = new WP_Roles();
$roles_names_array = $roles_obj->get_names();
$guest             = array( 'guest' => 'Guest' );
$roles_names_array = array_merge( $guest, $roles_names_array );
$all_products_ids  = get_posts(
	array(
		'post_type'   => 'product',
		'numberposts' => -1,
		'post_status' => 'publish',
		'fields'      => 'ids',
	)
);
if ( isset( $_GET['post_id'] ) ) {
	$my_post_id  = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : 0;
}
$my_title    = get_the_title( $my_post_id );
$enable_rule = get_post_meta( $my_post_id, 'mwb_mrbpfw_enable_rule', true );
$rule_role   = get_post_meta( $my_post_id, 'mwb_mrbpfw_role', true );
$rule_type   = get_post_meta( $my_post_id, 'mwb_mrbpfw_rule_type', true );
$price_type  = get_post_meta( $my_post_id, 'mwb_mrbpfw_discount_type', true );
$price       = get_post_meta( $my_post_id, 'mwb_mrbpfw_price', true );
$priority    = get_post_meta( $my_post_id, 'mwb_mrbpfw_priority', true );
if ( 'selected_products' === $rule_type ) {
	$all_products = get_post_meta( $my_post_id, 'mwb_mrbpfw_all_products', true );
} elseif ( 'categories' === $rule_type ) {
	$categories = get_post_meta( $my_post_id, 'mwb_mrbpfw_categories', true );
} elseif ( 'tags' === $rule_type ) {
	$tags = get_post_meta( $my_post_id, 'mwb_mrbpfw_tags', true );
}
?>
<h1 class="wp-heading-inline"><?php ( isset( $_GET['post_id'] ) && ! empty( $_GET['post_id'] ) ) ? esc_html_e( 'Update Price Rule', 'mwb-role-based-pricing-for-woocommerce' ) : esc_html_e( 'Add Price Rule', 'mwb-role-based-pricing-for-woocommerce' ); ?></h1>
<?php
if ( ! empty( $my_post_id ) ) {
	?>
<a class="page-title-action" href="<?php echo esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule&post_id=0' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
	<?php
}
?>
<hr/>
<div>
	<a class="button-secondary edit-mrbpfw_price_rules-button" href="<?php echo esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&amp;mrbpfw_tab=mwb-mrbpfw-price-rule' ); ?>"><?php esc_html_e( 'Back to Rule List', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
</div>
<form method="post">
	<input type="hidden" name="post_id" value="<?php echo esc_html( $my_post_id ); ?>">
	<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce( 'mrbpfw_meta_box_setting' ) ); ?>">
	<div class="mwb-form__wrap">
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="enable"><?php esc_html_e( 'Enable the Rule', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<div class="mwb-switch">
				<?php
				if ( isset( $enable_rule ) && ! empty( $enable_rule ) && 'on' === $enable_rule ) {
					$enable = esc_html( 'checked' );
				} else {
					$enable = '';
				}
				?>
					<input type="checkbox" class="mwb-switch-checkbox" name="enable_rule" id="enable_rule" value="<?php echo esc_html( $my_post_id ); ?>" <?php echo esc_html( $enable ); ?>>
				</div>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="roles"><?php esc_html_e( 'Add title', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<input type="text" name="post_title" value="<?php echo esc_html( $my_title ); ?>" placeholder="<?php esc_html_e( 'Please Enter the title', 'mwb-role-based-pricing-for-woocommerce' ); ?>" required>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="roles"><?php esc_html_e( 'Choose A Role', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<select name="roles" id="roles">
					<?php
					foreach ( $roles_names_array as $role_name ) {
						echo '<option value=' . esc_html( str_replace( ' ', '_', strtolower( $role_name ) ) ) . selected( $rule_role, esc_html( str_replace( ' ', '_', strtolower( $role_name ) ) ), true ) . '>' . esc_html( $role_name ) . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="rule_type"><?php esc_html_e( 'Select Rule Type', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<select name="rule_type" id="rule_type">
					<option value="all_products" <?php selected( 'product', $rule_type, true ); ?>><?php echo esc_html__( 'All Products', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
					<option value="selected_products" <?php selected( 'selected_products', $rule_type, true ); ?>><?php echo esc_html__( 'Select Products', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
					<option value="categories" <?php selected( 'categories', $rule_type, true ); ?>><?php echo esc_html__( 'Categories', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
					<option value="tags" <?php selected( 'tags', $rule_type, true ); ?>><?php echo esc_html__( 'Tags', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
				</select>
			</div>
		</div>
		<div id="selected_product">
			<div class="mwb-form__grp">
				<div class="mwb-form__label">
					<label for="selected_products"><?php esc_html_e( 'Choose Products', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
				</div>
				<div class="mwb-form__fields">
					<select name="selected_products[]" id="selected_products" multiple >
					<?php
					foreach ( $all_products_ids as $key => $product_id ) {
						$product = wc_get_product( $product_id );
						?>
					<option value="<?php echo esc_html( $product_id ); ?>"
						<?php
						if ( isset( $all_products ) && ! empty( $all_products ) ) {
							echo in_array( $product_id, $all_products, false ) ? 'selected' : '';
						}
						?>
					>
						<?php echo wp_kses_post( '(' . $product_id . ') ' . $product->get_title() ); ?>
					</option>
						<?php
					}
					?>
					</select>
				</div>
			</div>
		</div>
		<div id="product_category">
			<div class="mwb-form__grp">
				<div class="mwb-form__label">
					<label for="product_categories"><?php esc_html_e( 'Choose Product Categories', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
				</div>
				<div class="mwb-form__fields">
					<select name="product_categories[]" id="product_categories" multiple>
					<?php
					foreach ( $woo_categories as $key => $cat_obj ) {
						?>
					<option value="<?php echo esc_html( $cat_obj->term_id ); ?>"
						<?php
						if ( isset( $categories ) && ! empty( $categories ) ) {
							echo in_array( $cat_obj->term_id, $categories ) ? 'selected' : '';
						}
						?>
					>
						<?php echo esc_html( $cat_obj->name ); ?>
					</option>
						<?php
					}
					?>
					</select>
				</div>
			</div>
		</div>
		<div id="product_tag">
			<div class="mwb-form__grp">
				<div class="mwb-form__label">
					<label for="product_tags"><?php esc_html_e( 'Choose Product Tags', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
				</div>
				<div class="mwb-form__fields">
				<select name="product_tags[]" id="product_tags" multiple>
					<?php
					foreach ( $terms as $key => $tag_obj ) {
						?>
					<option value="<?php echo esc_html( $tag_obj->term_id ); ?>"
						<?php
						if ( isset( $tags ) && ! empty( $tags ) ) {
							echo in_array( $tag_obj->term_id, $tags ) ? 'selected' : '';
						}
						?>
					>
						<?php echo esc_html( $tag_obj->name ); ?></option>
						<?php
					}
					?>
					</select>
				</div>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="discount_type"><?php esc_html_e( 'Select Discount Type', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<select name="discount_type" id="discount_type">
					<option value="fixed" <?php selected( 'fixed', $price_type, true ); ?>><?php echo esc_html__( 'Fixed', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
					<option value="percentage" <?php selected( 'percentage', $price_type, true ); ?>><?php echo esc_html__( 'Percentage', 'mwb-role-based-pricing-for-woocommerce' ); ?></option>
				</select>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="price_field"><?php esc_html_e( 'Enter Price', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<input type="number" name="price_field" placeholder="<?php echo esc_html__( 'Please Enter the Price', 'mwb-role-based-pricing-for-woocommerce' ); ?>" value="<?php echo esc_html( $price ); ?>" required>
			</div>
		</div>
		<div class="mwb-form__grp">
			<div class="mwb-form__label">
				<label for="priority_field"><?php esc_html_e( 'Priority', 'mwb-role-based-pricing-for-woocommerce' ); ?></label>
			</div>
			<div class="mwb-form__fields">
				<input type="number" name="priority_field" id="priority_field" value="<?php echo esc_html( $priority ); ?>" placeholder="<?php echo esc_html__( 'Please Enter the Priority', 'mwb-role-based-pricing-for-woocommerce' ); ?>" required>
			</div>
		</div>
		<?php do_action( 'mwb_mrbpfw_extend_setting' ); ?>
		<div class="mwb-form__grp">
			<button class="mdc-button mdc-button--raised mdc-ripple-upgraded" type="submit" name="mrbpfw_meta_box_setting" id="mrbpfw_meta_box_setting"> <span class="mdc-button__ripple"></span>
				<span class="mdc-button__label mrbpfw-button-class"><?php ( isset( $_GET['post_id'] ) && ! empty( $_GET['post_id'] ) ) ? esc_html_e( 'Update', 'mwb-role-based-pricing-for-woocommerce' ) : esc_html_e( 'Publish', 'mwb-role-based-pricing-for-woocommerce' ); ?></span>
			</button>
		</div>
	</div>
</form>
