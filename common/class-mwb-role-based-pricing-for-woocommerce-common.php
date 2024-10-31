<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/common
 */

/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace mwb_role_based_pricing_for_woocommerce_common.
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/common
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Role_Based_Pricing_For_Woocommerce_Common {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-role-based-pricing-for-woocommerce-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-role-based-pricing-for-woocommerce-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name . 'common',
			'mrbpfw_common_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'discount_tag_color' => get_option(
					'mrbpfw_discount_tag_color',
					'#d3542e'
				),
			)
		);
		wp_enqueue_script( $this->plugin_name . 'common' );
	}

	/**
	 * Multicurrency related code to compatible for variation price.
	 *
	 * @param bool $flag .
	 * @return true
	 */
	public function mwb_currency_switcher_ajax_return( $flag ) {
		return true;
	}


	/**
	 * Preset Setting on the new site
	 *
	 * @param object $new_site .
	 * @return void
	 */
	public function mwb_mrbpfw_plugin_on_new_create_blog( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		// Check if the plugin has been activated on the network .
		if ( is_plugin_active_for_network( 'mwb-role-based-pricing-for-woocommerce/mwb-role-based-pricing-for-woocommerce.php' ) ) {
			$blog_id = $new_site->blog_id;
			switch_to_blog( $blog_id );

			if ( function_exists( 'mwb_mrbpfw_save_user_setting' ) ) {
				mwb_mrbpfw_save_user_setting();
			}

			restore_current_blog();
		}
	}
}
