<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Role_Based_Pricing_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_Role_Based_Pricing_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $mrbpfw_onboard    To initializsed the object of class onboard.
	 */
	protected $mrbpfw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_VERSION' ) ) {

			$this->version = MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'mwb-role-based-pricing-for-woocommerce';

		$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_dependencies();
		$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_admin_hooks();
		} else {
			$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_public_hooks();
		}
		$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_common_hooks();

		$this->mwb_mrbpfw_role_based_pricing_for_woocommerce_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Role_Based_Pricing_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Role_Based_Pricing_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Mwb_Role_Based_Pricing_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Mwb_Role_Based_Pricing_For_Woocommerce_Common. Defines all hooks for the common area.
	 * - Mwb_Role_Based_Pricing_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-role-based-pricing-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-role-based-pricing-for-woocommerce-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-role-based-pricing-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'Mwb_Role_Based_Pricing_For_Woocommerce_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-role-based-pricing-for-woocommerce-onboarding-steps.php';
			}

			if ( class_exists( 'Mwb_Role_Based_Pricing_For_Woocommerce_Onboarding_Steps' ) ) {
				$mrbpfw_onboard_steps = new Mwb_Role_Based_Pricing_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-role-based-pricing-for-woocommerce-public.php';

		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-mwb-role-based-pricing-for-woocommerce-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-mwb-role-based-pricing-for-woocommerce-common.php';

		$this->loader = new Mwb_Role_Based_Pricing_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Role_Based_Pricing_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_locale() {

		$plugin_i18n = new Mwb_Role_Based_Pricing_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_admin_hooks() {

		$mrbpfw_plugin_admin = new Mwb_Role_Based_Pricing_For_Woocommerce_Admin( $this->mrbpfw_get_plugin_name(), $this->mrbpfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_enqueue_scripts' );

		// Add settings menu for MWB Role Based Pricing For WooCommerce.
		$this->loader->add_action( 'admin_menu', $mrbpfw_plugin_admin, 'mwb_mrbpfw_options_page' );
		$this->loader->add_action( 'admin_menu', $mrbpfw_plugin_admin, 'mwb_mrbpfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_submenu_page', 15 );
		$this->loader->add_filter( 'mrbpfw_general_settings_array', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_general_settings_page', 10 );

		// Register the text setting fields.
		$this->loader->add_filter( 'mrbpfw_text_settings_array', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_text_settings_page', 10 );

		// Saving tab settings.
		$this->loader->add_action( 'admin_init', $mrbpfw_plugin_admin, 'mwb_mrbpfw_admin_save_tab_settings' );

		// Register the CPT for Price Rule.
		$this->loader->add_action( 'init', $mrbpfw_plugin_admin, 'mwb_mrbpfw_register_custom_post_type' );

		// Save the Metabox field details in the DB.
		$this->loader->add_action( 'init', $mrbpfw_plugin_admin, 'mwb_mrbpfw_save_metabox_data' );

		// Enable/Disable the price rule.
		$this->loader->add_action( 'wp_ajax_mwb_mrbpfw_active_deactive_price_rule', $mrbpfw_plugin_admin, 'mwb_mrbpfw_active_deactive_price_rule' );

		$this->loader->add_action( 'wp_ajax_mwb_mrbpfw_check_if_priority_exist', $mrbpfw_plugin_admin, 'mwb_mrbpfw_check_if_priority_exist' );

	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_common_hooks() {

		$mrbpfw_plugin_common = new Mwb_Role_Based_Pricing_For_Woocommerce_Common( $this->mrbpfw_get_plugin_name(), $this->mrbpfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $mrbpfw_plugin_common, 'mwb_mrbpfw_common_enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $mrbpfw_plugin_common, 'mwb_mrbpfw_common_enqueue_scripts' );

		// Multicurrency compatibility.
		$this->loader->add_filter( 'mwb_currency_switcher_ajax_is_result_return', $mrbpfw_plugin_common, 'mwb_currency_switcher_ajax_return', 10, 1 );

		$this->loader->add_action( 'wp_initialize_site', $mrbpfw_plugin_common, 'mwb_mrbpfw_plugin_on_new_create_blog', 900 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_public_hooks() {

		$mrbpfw_plugin_public = new Mwb_Role_Based_Pricing_For_Woocommerce_Public( $this->mrbpfw_get_plugin_name(), $this->mrbpfw_get_version() );
		$enable_plugin        = get_option( 'mrbpfw_enable_switch_plugin' );
		if ( 'on' === $enable_plugin ) {
			$this->loader->add_action( 'wp_enqueue_scripts', $mrbpfw_plugin_public, 'mwb_mrbpfw_public_enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $mrbpfw_plugin_public, 'mwb_mrbpfw_public_enqueue_scripts' );

			// Set the price on product page for variable after variation selected.
			$this->loader->add_filter( 'woocommerce_get_price_html', $mrbpfw_plugin_public, 'mwb_mrbpfw_variation_price', 10, 2 );

			// Set the price on the cart page based on the price rule.
			$this->loader->add_action( 'woocommerce_before_calculate_totals', $mrbpfw_plugin_public, 'mwb_mrbpfw_alter_price_cart', 10, 2 );
			// Set the price on the frontend based on the price rule for simple/variable/variation product.
			$this->loader->add_filter( 'woocommerce_get_price_html', $mrbpfw_plugin_public, 'mwb_mrbpfw_product_price', 20, 2 );
			if ( 'Betheme' === wp_get_theme()->name ) {
				$shop_page_discount_tag = 'woocommerce_after_shop_loop_item_title';
			} elseif ( 'OceanWP' === wp_get_theme()->name ) {
				$shop_page_discount_tag = 'ocean_after_archive_product_title';
			} else {
				$shop_page_discount_tag = 'woocommerce_shop_loop_item_title';
			}
			// Show the discount tag on the shop page.
			$this->loader->add_action( $shop_page_discount_tag, $mrbpfw_plugin_public, 'mwb_mrbpfw_show_total_discount_tag' );

			if ( 'OceanWP' === wp_get_theme()->name ) {
				$product_page_discount_tag = 'ocean_after_single_product_title';
			} elseif ( 'Avada' === wp_get_theme()->name ) {
				$product_page_discount_tag = 'get_template_part_templates/wc-single-title';
			} else {
				$product_page_discount_tag = 'woocommerce_single_product_summary';
			}
			// Show the discount tag on the single product page.
			$this->loader->add_action( $product_page_discount_tag, $mrbpfw_plugin_public, 'mwb_mrbpfw_show_total_discount_tag' );

			// Hide the add to cart button.
			$this->loader->add_action( 'init', $mrbpfw_plugin_public, 'mwb_mrbpfw_add_to_cart_button_hide' );

			$this->loader->add_action( 'woocommerce_is_purchasable', $mrbpfw_plugin_public, 'mwb_mrbpfw_remove_add_to_cart_button', 10, 2 );

			// Remove tax from checkout and cart.
			$this->loader->add_filter( 'mwb_remove_tax_after_cart_table', $mrbpfw_plugin_public, 'mwb_mrbpfw_remove_taxes' );

			// Subscription compatibility.
			$this->loader->add_action( 'mwb_rbpfw_cart_price', $mrbpfw_plugin_public, 'mwb_rbpfw_cart_price', 99, 2 );
			$this->loader->add_filter( 'mwb_rbpfw_addon_symbol', $mrbpfw_plugin_public, 'mwb_rbpfw_addon_symbol', 99, 2 );

			// Tax Label.
			$this->loader->add_filter( 'mwb_mrbpfw_tax_lable', $mrbpfw_plugin_public, 'mwb_mrbpfw_tax_label', 99 );

			// mwb multicurrency compatibility.
			$this->loader->add_filter( 'mwb_mrbpfw_price', $mrbpfw_plugin_public, 'mwb_mrbpfw_price', 10, 1 );

			// placeholder to add to cart button.
			$this->loader->add_action( 'woocommerce_after_shop_loop_item', $mrbpfw_plugin_public, 'mwb_mrbpfw_after_add_to_cart_button' );

			// Show the tax label.
			$this->loader->add_filter( 'woocommerce_cart_item_price', $mrbpfw_plugin_public, 'mrbpfw_show_subscription_price_on_cart', 20, 3 );

		}
	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function mwb_mrbpfw_role_based_pricing_for_woocommerce_api_hooks() {

		$mrbpfw_plugin_api = new Mwb_Role_Based_Pricing_For_Woocommerce_Rest_Api( $this->mrbpfw_get_plugin_name(), $this->mrbpfw_get_version() );

		$this->loader->add_action( 'rest_api_init', $mrbpfw_plugin_api, 'mwb_mrbpfw_add_endpoint' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_run() {
		$this->loader->mwb_mrbpfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function mrbpfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_Role_Based_Pricing_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function mrbpfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_Role_Based_Pricing_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function mrbpfw_get_onboard() {
		return $this->mrbpfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function mrbpfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_mrbpfw_plug tabs.
	 *
	 * @return  Array       An key=>value pair of MWB Role Based Pricing For WooCommerce tabs.
	 */
	public function mwb_mrbpfw_plug_default_tabs() {

		$mrbpfw_default_tabs = array();
		$mrbpfw_default_tabs['mwb-mrbpfw-overview'] = array(
			'title' => esc_html__( 'Overview', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-overview',
		);
		$mrbpfw_default_tabs['mwb-mrbpfw-general']  = array(
			'title' => esc_html__( 'General Setting', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-general',
		);
		$mrbpfw_default_tabs['mwb-mrbpfw-user-role']    = array(
			'title' => esc_html__( 'User Role Setting', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-user-role',
		);
		$mrbpfw_default_tabs['mwb-mrbpfw-price-rule'] = array(
			'title' => esc_html__( 'Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-price-rule',
		);
		$mrbpfw_default_tabs['mwb-mrbpfw-text-setting'] = array(
			'title' => esc_html__( 'Text Setting', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-text-setting',
		);
		$mrbpfw_default_tabs = apply_filters( 'mwb_mrbpfw_plugin_standard_admin_settings_tabs', $mrbpfw_default_tabs );

		$mrbpfw_default_tabs['mwb-mrbpfw-system-status'] = array(
			'title' => esc_html__( 'System Status', 'mwb-role-based-pricing-for-woocommerce' ),
			'name'  => 'mwb-mrbpfw-system-status',
		);

		return $mrbpfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_mrbpfw_plug_load_template( $path, $params = array() ) {

		$mrbpfw_file_path = MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_PATH . $path;

		if ( file_exists( $mrbpfw_file_path ) ) {

			include $mrbpfw_file_path;
		} else {

			/* translators: %s: file path */
			$mrbpfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'mwb-role-based-pricing-for-woocommerce' ), $mrbpfw_file_path );
			$this->mwb_mrbpfw_plug_admin_notice( $mrbpfw_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $mrbpfw_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_mrbpfw_plug_admin_notice( $mrbpfw_message, $type = 'error' ) {

		$mrbpfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$mrbpfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$mrbpfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$mrbpfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$mrbpfw_classes .= 'notice-error is-dismissible';
		}

		$mrbpfw_notice  = '<div class="' . esc_attr( $mrbpfw_classes ) . ' mwb-errorr-8">';
		$mrbpfw_notice .= '<p>' . esc_html( $mrbpfw_message ) . '</p>';
		$mrbpfw_notice .= '</div>';

		echo wp_kses_post( $mrbpfw_notice );
	}


	/**
	 * Show WordPress and server info.
	 *
	 * @return  Array $mrbpfw_system_data       returns array of all WordPress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_mrbpfw_plug_system_status() {
		global $wpdb;
		$mrbpfw_system_status    = array();
		$mrbpfw_wordpress_status = array();
		$mrbpfw_system_data      = array();

		// Get the web server.
		$mrbpfw_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$mrbpfw_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the server's IP address.
		$mrbpfw_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$mrbpfw_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$mrbpfw_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the server path.
		$mrbpfw_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the OS.
		$mrbpfw_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get WordPress version.
		$mrbpfw_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get and count active WordPress plugins.
		$mrbpfw_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// See if this site is multisite or not.
		$mrbpfw_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'mwb-role-based-pricing-for-woocommerce' ) : __( 'No', 'mwb-role-based-pricing-for-woocommerce' );

		// See if WP Debug is enabled.
		$mrbpfw_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'mwb-role-based-pricing-for-woocommerce' ) : __( 'No', 'mwb-role-based-pricing-for-woocommerce' );

		// See if WP Cache is enabled.
		$mrbpfw_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'mwb-role-based-pricing-for-woocommerce' ) : __( 'No', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the total number of WordPress users on the site.
		$mrbpfw_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the number of published WordPress posts.
		$mrbpfw_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'mwb-role-based-pricing-for-woocommerce' );

		// Get PHP memory limit.
		$mrbpfw_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the PHP error log path.
		$mrbpfw_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'mwb-role-based-pricing-for-woocommerce' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$mrbpfw_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get PHP max post size.
		$mrbpfw_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE === 4 ) {
			$mrbpfw_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE === 8 ) {
			$mrbpfw_system_status['php_architecture'] = '64-bit';
		} else {
			$mrbpfw_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$mrbpfw_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Show the number of processes currently running on the server.
		$mrbpfw_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the memory usage.
		$mrbpfw_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$mrbpfw_system_status['is_windows']        = true;
			$mrbpfw_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'mwb-role-based-pricing-for-woocommerce' );
		}

		// Get the memory limit.
		$mrbpfw_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		// Get the PHP maximum execution time.
		$mrbpfw_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'mwb-role-based-pricing-for-woocommerce' );

		$mrbpfw_system_data['php'] = $mrbpfw_system_status;
		$mrbpfw_system_data['wp']  = $mrbpfw_wordpress_status;

		return $mrbpfw_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $mrbpfw_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_mrbpfw_plug_generate_html( $mrbpfw_components = array() ) {
		if ( is_array( $mrbpfw_components ) && ! empty( $mrbpfw_components ) ) {
			foreach ( $mrbpfw_components as $mrbpfw_component ) {
				if ( ! empty( $mrbpfw_component['type'] ) && ! empty( $mrbpfw_component['id'] ) ) {
					switch ( $mrbpfw_component['type'] ) {

						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
						<div class="mwb-form-group mwb-mrbpfw-<?php echo esc_attr( $mrbpfw_component['type'] ); ?>">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<?php if ( 'number' !== $mrbpfw_component['type'] ) { ?>
												<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?></span>
											<?php } ?>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input
									class="mdc-text-field__input <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" 
									name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"
									type="<?php echo esc_attr( $mrbpfw_component['type'] ); ?>"
									value="<?php echo ( isset( $mrbpfw_component['value'] ) ? esc_attr( $mrbpfw_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?>"
									>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'password':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input 
									class="mdc-text-field__input <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?> mwb-form__password" 
									name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"
									type="<?php echo esc_attr( $mrbpfw_component['type'] ); ?>"
									value="<?php echo ( isset( $mrbpfw_component['value'] ) ? esc_attr( $mrbpfw_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?>"
									>
									<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb-form-label" for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label"><?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?></span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<span class="mdc-text-field__resizer">
										<textarea class="mdc-text-field__input <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>" id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" placeholder="<?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $mrbpfw_component['value'] ) ? esc_textarea( $mrbpfw_component['value'] ) : '' ); // WPCS: XSS ok. ?></textarea>
									</span>
								</label>

							</div>
						</div>

							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb-form-label" for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<div class="mwb-form-select">
									<select id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : '' ); ?><?php echo ( 'multiselect' === $mrbpfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $mrbpfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
										<?php
										foreach ( $mrbpfw_component['options'] as $mrbpfw_key => $mrbpfw_val ) {
											?>
											<option value="<?php echo esc_attr( $mrbpfw_key ); ?>"
												<?php
												if ( is_array( $mrbpfw_component['value'] ) ) {
													selected( in_array( (string) $mrbpfw_key, $mrbpfw_component['value'], true ), true );
												} else {
													selected( $mrbpfw_component['value'], (string) $mrbpfw_key );
												}
												?>
												>
												<?php echo esc_html( $mrbpfw_val ); ?>
											</option>
											<?php
										}
										?>
									</select>
									<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $mrbpfw_component['description'] ); ?><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>

							<?php
							break;

						case 'checkbox':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control mwb-pl-4">
								<div class="mdc-form-field">
									<div class="mdc-checkbox">
										<input 
										name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"
										type="checkbox"
										class="mdc-checkbox__native-control <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>"
										value="<?php echo ( isset( $mrbpfw_component['value'] ) ? esc_attr( $mrbpfw_component['value'] ) : '' ); ?>"
										<?php checked( $mrbpfw_component['value'], '1' ); ?>
										/>
										<div class="mdc-checkbox__background">
											<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
												<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
											</svg>
											<div class="mdc-checkbox__mixedmark"></div>
										</div>
										<div class="mdc-checkbox__ripple"></div>
									</div>
									<label for="checkbox-1"><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control mwb-pl-4">
								<div class="mwb-flex-col">
									<?php
									foreach ( $mrbpfw_component['options'] as $mrbpfw_radio_key => $mrbpfw_radio_val ) {
										?>
										<div class="mdc-form-field">
											<div class="mdc-radio">
												<input
												name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
												value="<?php echo esc_attr( $mrbpfw_radio_key ); ?>"
												type="radio"
												class="mdc-radio__native-control <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>"
												<?php checked( $mrbpfw_radio_key, $mrbpfw_component['value'] ); ?>
												>
												<div class="mdc-radio__background">
													<div class="mdc-radio__outer-circle"></div>
													<div class="mdc-radio__inner-circle"></div>
												</div>
												<div class="mdc-radio__ripple"></div>
											</div>
											<label for="radio-1"><?php echo esc_html( $mrbpfw_radio_val ); ?></label>
										</div>	
										<?php
									}
									?>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio-switch':
							?>

						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label for="" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="mwb-form-group__control">
								<div>
									<div class="mdc-switch">
										<div class="mdc-switch__track"></div>
										<div class="mdc-switch__thumb-underlay">
											<div class="mdc-switch__thumb"></div>
											<input name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>" type="checkbox" id="<?php echo esc_html( $mrbpfw_component['id'] ); ?>" value="on" class="mdc-switch__native-control <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" role="switch" aria-checked="
											<?php
											if ( 'on' === $mrbpfw_component['value'] ) {
												echo 'true';
											} else {
												echo 'false';
											}
											?>
											"
											<?php checked( $mrbpfw_component['value'], 'on' ); ?>
											>
										</div>
									</div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'button':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label"></div>
							<div class="mwb-form-group__control">
								<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $mrbpfw_component['button_text'] ) ? esc_html( $mrbpfw_component['button_text'] ) : '' ); ?></span>
								</button>
							</div>
						</div>

							<?php
							break;

						case 'multi':
							?>
							<div class="mwb-form-group mwb-isfw-<?php echo esc_attr( $mrbpfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
									</div>
									<div class="mwb-form-group__control">
									<?php
									foreach ( $mrbpfw_component['value'] as $component ) {
										?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( $component['type'] ); ?>"
												value="<?php echo ( isset( $mrbpfw_component['value'] ) ? esc_attr( $mrbpfw_component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $mrbpfw_component['placeholder'] ) ? esc_attr( $mrbpfw_component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : '' ); ?>
												>
											</label>
								<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'color':
						case 'date':
						case 'file':
							?>
							<div class="mwb-form-group mwb-isfw-<?php echo esc_attr( $mrbpfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mrbpfw_component['title'] ) ? esc_html( $mrbpfw_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field1 mdc-text-field--outlined1">
										<input 
										class="<?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $mrbpfw_component['type'] ); ?>"
										value="<?php echo ( isset( $mrbpfw_component['value'] ) ? esc_attr( $mrbpfw_component['value'] ) : '' ); ?>"
										<?php echo esc_html( ( 'date' === $mrbpfw_component['type'] ) ? 'max=' . gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . ' min=' . gmdate( 'Y-m-d' ) . '' : '' ); ?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mrbpfw_component['description'] ) ? esc_attr( $mrbpfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'submit':
							?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo ( isset( $mrbpfw_component['name'] ) ? esc_html( $mrbpfw_component['name'] ) : esc_html( $mrbpfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $mrbpfw_component['id'] ); ?>"
								class="<?php echo ( isset( $mrbpfw_component['class'] ) ? esc_attr( $mrbpfw_component['class'] ) : '' ); ?>"
								value="<?php echo esc_attr( $mrbpfw_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
							<?php
							break;
						default:
							break;
					}
				}
			}
		}
	}
}
