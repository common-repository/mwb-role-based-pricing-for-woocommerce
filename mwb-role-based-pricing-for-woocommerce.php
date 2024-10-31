<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Mwb_Role_Based_Pricing_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       MWB Role Based Pricing For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/mwb-role-based-pricing-for-woocommerce/
 * Description:       The MWB Role Based Pricing For WooCommerce allows the WooCommerce merchants to show prices on their online store based on different user roles. For example, wholesalers, retailers, and distributors, etc. The merchants can create a dedicated pricing list for either selected products or selected categories using this plugin.
 * Version:           1.2.1
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org/
 * Text Domain:       mwb-role-based-pricing-for-woocommerce
 * Domain Path:       /languages
 * Requires at least: 4.6
 * Tested up to:      5.8.2
 * WC requires atleast : 4.0.0
 * WC tested up to:      5.9.0
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$activated      = true;
$active_plugins = get_option( 'active_plugins', array() );
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	$active_network_wide     = get_site_option( 'active_sitewide_plugins', array() );
	if ( ! empty( $active_network_wide ) ) {
		foreach ( $active_network_wide as $key => $value ) {
			$active_plugins[] = $key;
		}
	}
	if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
		$activated = false;
	}
} else {
	if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
		$activated = false;
	}
}
if ( $activated ) {
	if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) && ! is_admin() ) {
		/** Remove filter due to conflict with price with mwb multicurrency plugin. */
		function remove_filters() {
			global $wp_filter;
			unset( $wp_filter['woocommerce_format_price_range'] );
		}
		add_action( 'init', 'remove_filters' );
	}

	/**
	 * Define plugin constants.
	 *
	 * @since 1.0.0
	 */
	function define_mwb_role_based_pricing_for_woocommerce_constants() {

		mwb_role_based_pricing_for_woocommerce_constants( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_VERSION', '1.2.1' );
		mwb_role_based_pricing_for_woocommerce_constants( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		mwb_role_based_pricing_for_woocommerce_constants( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		mwb_role_based_pricing_for_woocommerce_constants( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
		mwb_role_based_pricing_for_woocommerce_constants( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'MWB Role Based Pricing For WooCommerce' );
	}


	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param   String $key    Key for contant.
	 * @param   String $value   value for contant.
	 * @since             1.0.0
	 */
	function mwb_role_based_pricing_for_woocommerce_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}

	// Discontinue notice.
	add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'mwb_rbpw_add_discontinue_notice', 0, 3 );

	/**
	 * Begins execution of the plugin.
	 *
	 * @param mixed $plugin_file The plugin file name.
	 * @param mixed $plugin_data The plugin file data.
	 * @param mixed $status      The plugin file status.
	 * @since 1.0.0
	 */
	function mwb_rbpw_add_discontinue_notice( $plugin_file, $plugin_data, $status ) {
		include_once plugin_dir_path( __FILE__ ) . 'onboarding/templates/makewebetter-plugin-discontinue-notice.html';
	}


	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-role-based-pricing-for-woocommerce-activator.php
	 *
	 * @param object $network_wide .
	 */
	function activate_mwb_mrbpfw_role_based_pricing_for_woocommerce( $network_wide ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-role-based-pricing-for-woocommerce-activator.php';
		Mwb_Role_Based_Pricing_For_Woocommerce_Activator::mwb_mrbpfw_role_based_pricing_for_woocommerce_activate( $network_wide );
		$mwb_mrbpfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_mrbpfw_active_plugin ) && ! empty( $mwb_mrbpfw_active_plugin ) ) {
			$mwb_mrbpfw_active_plugin['mwb-role-based-pricing-for-woocommerce'] = array(
				'plugin_name' => __( 'MWB Role Based Pricing For WooCommerce', 'mwb-role-based-pricing-for-woocommerce' ),
				'active'      => '1',
			);
		} else {
			$mwb_mrbpfw_active_plugin = array();
			$mwb_mrbpfw_active_plugin['mwb-role-based-pricing-for-woocommerce'] = array(
				'plugin_name' => __( 'MWB Role Based Pricing For WooCommerce', 'mwb-role-based-pricing-for-woocommerce' ),
				'active'      => '1',
			);
		}
		update_option( 'mwb_all_plugins_active', $mwb_mrbpfw_active_plugin );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-role-based-pricing-for-woocommerce-deactivator.php
	 *
	 * @param object $network_wide .
	 */
	function deactivate_mwb_mrbpfw_role_based_pricing_for_woocommerce( $network_wide ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-role-based-pricing-for-woocommerce-deactivator.php';
		Mwb_Role_Based_Pricing_For_Woocommerce_Deactivator::mwb_mrbpfw_role_based_pricing_for_woocommerce_deactivate( $network_wide );
		$mwb_mrbpfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_mrbpfw_deactive_plugin ) && ! empty( $mwb_mrbpfw_deactive_plugin ) ) {
			foreach ( $mwb_mrbpfw_deactive_plugin as $mwb_mrbpfw_deactive_key => $mwb_mrbpfw_deactive ) {
				if ( 'mwb-role-based-pricing-for-woocommerce' === $mwb_mrbpfw_deactive_key ) {
					$mwb_mrbpfw_deactive_plugin[ $mwb_mrbpfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option( 'mwb_all_plugins_active', $mwb_mrbpfw_deactive_plugin );
	}

	register_activation_hook( __FILE__, 'activate_mwb_mrbpfw_role_based_pricing_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_mrbpfw_role_based_pricing_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-role-based-pricing-for-woocommerce.php';


	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_mwb_mrbpfw_role_based_pricing_for_woocommerce() {
		define_mwb_role_based_pricing_for_woocommerce_constants();

		$mrbpfw_plugin_standard = new Mwb_Role_Based_Pricing_For_Woocommerce();
		$mrbpfw_plugin_standard->mwb_mrbpfw_run();
		$GLOBALS['mrbpfw_mwb_mrbpfw_obj'] = $mrbpfw_plugin_standard;

	}
	run_mwb_mrbpfw_role_based_pricing_for_woocommerce();

	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'mwb_mrbpfw_role_based_pricing_for_woocommerce_settings_link' );

	/**
	 * Settings link.
	 *
	 * @since    1.0.0
	 * @param   Array $links    Settings link array.
	 */
	function mwb_mrbpfw_role_based_pricing_for_woocommerce_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu' ) . '">' . __( 'Settings', 'mwb-role-based-pricing-for-woocommerce' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}

	/**
	 * Adding custom setting links at the plugin activation list.
	 *
	 * @param array  $links_array array containing the links to plugin.
	 * @param string $plugin_file_name plugin file name.
	 * @return array
	 */
	function mwb_mrbpfw_role_based_pricing_for_woocommerce_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
		if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
			$links_array[] = '<a href="https://demo.makewebbetter.com/mwb-role-based-pricing-for-woocommerce/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org" target="_blank"><img src="' . esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Demo.svg" class="mwb-info-img" alt="Demo image">' . __( 'Demo', 'mwb-role-based-pricing-for-woocommerce' ) . '</a>';
			$links_array[] = '<a href="https://docs.makewebbetter.com/mwb-role-based-pricing-for-woocommerce/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org" target="_blank"><img src="' . esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Documentation.svg" class="mwb-info-img" alt="documentation image">' . __( 'Documentation', 'mwb-role-based-pricing-for-woocommerce' ) . '</a>';
			$links_array[] = '<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-rolebased-org&utm_medium=MWB-org-page&utm_campaign=MWB-rolebased-org" target="_blank"><img src="' . esc_html( MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/image/Support.svg" class="mwb-info-img" alt="support image">' . __( 'Support', 'mwb-role-based-pricing-for-woocommerce' ) . '</a>';
		}
		return $links_array;
	}
	add_filter( 'plugin_row_meta', 'mwb_mrbpfw_role_based_pricing_for_woocommerce_custom_settings_at_plugin_tab', 10, 2 );
	/**Auto Enable all user setting checkboxes */
	function mwb_mrbpfw_save_user_setting() {
		$enable    = get_option( 'mrbpfw_enable_switch_plugin', 'no' );
		$check_pre = get_option( 'mwb_mrbpfw_setting_preset', false );
		if ( 'on' !== $enable && ! $check_pre ) {
			$roles_obj         = new WP_Roles();
			$roles_names_array = $roles_obj->get_names();
			$guest             = array( 'guest' => 'Guest' );
			$roles_names_array = array_merge( $guest, $roles_names_array );
			foreach ( $roles_names_array as $roles ) {
				$role         = esc_html( str_replace( ' ', '_', strtolower( $roles ) ) );
				$user_setting = array( 'regular_price_' . $role, 'on_sale_price_' . $role, 'role_based_price_' . $role, 'add_to_cart_' . $role, 'show_tax_' . $role, 'show_total_discount_' . $role );
				update_option( 'user_setting_' . $role, $user_setting );
			}
			// General setting.
			update_option( 'mrbpfw_enable_switch_plugin', 'on' );
			update_option( 'mwb_mrbpfw_for_price_rule_priority', 'combined_price' );
			update_option( 'mwb_mrbpfw_for_price_rule', 'r_price' );

			// Text Setting.
			update_option( 'mrbpfw_regular_price_text', 'Regular Price' );
			update_option( 'mrbpfw_sale_price_text', 'Sale Price' );
			update_option( 'mrbpfw_role_based_price_text', 'Your Price' );

			// Insert a sample price rule for admin.
			$post_id = wp_insert_post(
				array(
					'post_type'      => 'mrbpfw_price_rules',
					'post_title'     => 'Sample Price Rule Administrator Role',
					'post_status'    => 'publish',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				)
			);
			if ( $post_id ) {
				add_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', 'on' );
				add_post_meta( $post_id, 'mwb_mrbpfw_role', 'administrator' );
				add_post_meta( $post_id, 'mwb_mrbpfw_rule_type', 'all_products' );
				add_post_meta( $post_id, 'mwb_mrbpfw_discount_type', 'fixed' );
				add_post_meta( $post_id, 'mwb_mrbpfw_price', 5 );
				add_post_meta( $post_id, 'mwb_mrbpfw_priority', 1 );
			}
			update_option( 'mwb_mrbpfw_setting_preset', true );
		}
	}
} else {
	/**
	 * Show warning message if woocommerce is not install
	 *
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	function mwb_mrbpfw_plugin_error_notice() {
		unset( $_GET['activate'] );
		?>
		<div class="error notice is-dismissible">
			<p><?php esc_html_e( 'Woocommerce is not activated, Please activate Woocommerce first to install MWB Role Based Pricing for WooCommerce.', 'mwb-role-based-pricing-for-woocommerce' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Call Admin notices
	 *
	 * @name mwb_mrbpfw_plugin_deactivate()
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	function mwb_mrbpfw_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'network_admin_notices', 'mwb_mrbpfw_plugin_error_notice' );
		add_action( 'admin_notices', 'mwb_mrbpfw_plugin_error_notice' );
	}
	add_action( 'admin_init', 'mwb_mrbpfw_plugin_deactivate' );
}
