<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Role_Based_Pricing_For_Woocommerce_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function mwb_mrbpfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_mwb_role_based_pricing_for_woocommerce_menu' === $screen->id || 'mrbpfw_price_rules' === $screen->id || 'edit-mrbpfw_price_rules' === $screen->id || 'mbpfw_price_rules' === $screen->id || 'plugins' === $screen->id ) {
			wp_enqueue_style( 'mwb-mrbpfw-select2-css', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-role-based-pricing-for-woocommerce-select2.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-mrbpfw-meterial-css', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-mrbpfw-meterial-css2', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-mrbpfw-meterial-lite', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-mrbpfw-meterial-icons-css', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-role-based-pricing-for-woocommerce-admin-global.css', array( 'mwb-mrbpfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( 'mwb-admin-min-css', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'mwb-admin-css', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin.css', array(), $this->version, 'all' );
		}
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function mwb_mrbpfw_admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_mwb_role_based_pricing_for_woocommerce_menu' === $screen->id || 'edit-mrbpfw_price_rules' === $screen->id || 'mrbpfw_price_rules' === $screen->id ) {
			wp_enqueue_script( 'mwb-mrbpfw-select2', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-role-based-pricing-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-mrbpfw-metarial-js', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-mrbpfw-metarial-js2', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-mrbpfw-metarial-lite', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-role-based-pricing-for-woocommerce-admin.js', array( 'jquery', 'mwb-mrbpfw-select2', 'mwb-mrbpfw-metarial-js', 'mwb-mrbpfw-metarial-js2', 'mwb-mrbpfw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'mrbpfw_admin_param',
				array(
					'ajaxurl'               => admin_url( 'admin-ajax.php' ),
					'reloadurl'             => admin_url( 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu' ),
					'mrbpfw_gen_tab_enable' => get_option( 'mrbpfw_enable_switch_plugin' ),
					'create_nonce'          => wp_create_nonce( 'create_nonce' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
	}
	/**
	 * Adding settings menu for MWB Role Based Pricing For WooCommerce.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( 'MakeWebBetter', 'MakeWebBetter', 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/MWB_Grey-01.svg', 15 );
			$mrbpfw_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $mrbpfw_menus ) && ! empty( $mrbpfw_menus ) ) {
				foreach ( $mrbpfw_menus as $mrbpfw_key => $mrbpfw_value ) {
					add_submenu_page( 'mwb-plugins', $mrbpfw_value['name'], $mrbpfw_value['name'], 'manage_options', $mrbpfw_value['menu_link'], array( $mrbpfw_value['instance'], $mrbpfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_mrbpfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}


	/**
	 * MWB Role Based Pricing For WooCommerce mrbpfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function mwb_mrbpfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __( 'MWB Role Based Pricing For WooCommerce', 'mwb-role-based-pricing-for-woocommerce' ),
			'slug'      => 'mwb_role_based_pricing_for_woocommerce_menu',
			'menu_link' => 'mwb_role_based_pricing_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'mwb_mrbpfw_options_menu_html',
		);
		$menu[]  = apply_filters( 'mwb_add_more_tab', $menus );
		return $menus;
	}


	/**
	 * MWB Role Based Pricing For WooCommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * MWB Role Based Pricing For WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_options_menu_html() {
		include_once MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-mrbpfw-admin-dashboard.php';
	}


	/**
	 * MWB Role Based Pricing For WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $mrbpfw_settings_general Settings fields.
	 */
	public function mwb_mrbpfw_admin_general_settings_page( $mrbpfw_settings_general ) {
		$mrbpfw_settings_general   = array(
			array(
				'title'   => __( 'Enable Plugin\'s Functionality', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'    => 'radio-switch',
				'id'      => 'mrbpfw_enable_switch_plugin',
				'value'   => get_option( 'mrbpfw_enable_switch_plugin' ),
				'class'   => 'mrbpfw-radio-switch-class',
				'options' => array(
					'yes' => 'YES',
					'no'  => 'NO',
				),
			),
			array(
				'title'   => __( 'Price Rule Apply on', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'    => 'radio',
				'id'      => 'mwb_mrbpfw_for_price_rule',
				'value'   => get_option( 'mwb_mrbpfw_for_price_rule' ),
				'class'   => 'rbpfw-radio-class',
				'options' => array(
					'r_price' => __( 'Regular Price', 'mwb-role-based-pricing-for-woocommerce' ),
					's_price' => __( 'Sale Price', 'mwb-role-based-pricing-for-woocommerce' ),
				),
			),
			array(
				'title'   => __( 'Price Rule Apply Using', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'    => 'radio',
				'id'      => 'mwb_mrbpfw_for_price_rule_priority',
				'value'   => get_option( 'mwb_mrbpfw_for_price_rule_priority' ),
				'class'   => 'rbpfw-radio-class',
				'options' => array(
					'min_price'      => __( 'Min Discount', 'mwb-role-based-pricing-for-woocommerce' ),
					'max_price'      => __( 'Max Discount', 'mwb-role-based-pricing-for-woocommerce' ),
					'combined_price' => __( 'All Discount Combined', 'mwb-role-based-pricing-for-woocommerce' ),
					'min_priority'   => __( 'Min Priority', 'mwb-role-based-pricing-for-woocommerce' ),
					'max_priority'   => __( 'Max Priority', 'mwb-role-based-pricing-for-woocommerce' ),
				),
			),
		);
		$mrbpfw_settings_general   = apply_filters( 'mrbpfw_general_setting', $mrbpfw_settings_general );
		$mrbpfw_settings_general[] = array(
			'type'        => 'button',
			'id'          => 'mrbpfw_save_general_setting',
			'button_text' => __( 'Save Setting', 'mwb-role-based-pricing-for-woocommerce' ),
			'class'       => 'mrbpfw-button-class',
		);
		return $mrbpfw_settings_general;
	}

	/**
	 * MWB Role Based Pricing For WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $mrbpfw_settings_text Text Settings fields.
	 */
	public function mwb_mrbpfw_admin_text_settings_page( $mrbpfw_settings_text ) {
		$mrbpfw_settings_text   = array(
			array(
				'title'   => __( 'Enable to Hide Tax Label', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'    => 'radio-switch',
				'id'      => 'mrbpfw_tax_label',
				'value'   => get_option( 'mrbpfw_tax_label' ),
				'class'   => 'mrbpfw-radio-switch-class',
				'options' => array(
					'yes' => 'YES',
					'no'  => 'NO',
				),
			),
			array(
				'title'       => __( 'Regular Price text', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This text appears next to regular price.', 'mwb-role-based-pricing-for-woocommerce' ),
				'id'          => 'mrbpfw_regular_price_text',
				'value'       => get_option( 'mrbpfw_regular_price_text' ),
				'class'       => 'mrbpfw-number-class',
				'placeholder' => 'Enter the Regular Price Text',
			),
			array(
				'title'       => __( 'On Sale Price text', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This text appears next to on sale price.', 'mwb-role-based-pricing-for-woocommerce' ),
				'id'          => 'mrbpfw_sale_price_text',
				'value'       => get_option( 'mrbpfw_sale_price_text' ),
				'class'       => 'mrbpfw-number-class',
				'placeholder' => 'Enter the on Sale Price Text',
			),
			array(
				'title'       => __( 'Role Based Price text', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This text appears next to role based price.', 'mwb-role-based-pricing-for-woocommerce' ),
				'id'          => 'mrbpfw_role_based_price_text',
				'value'       => get_option( 'mrbpfw_role_based_price_text' ),
				'class'       => 'mrbpfw-number-class',
				'placeholder' => 'Enter the Role Based Price Text',
			),
			array(
				'title'       => __( 'Add to cart button text', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This text appears in-place of Add To Cart button, if the button is disable.', 'mwb-role-based-pricing-for-woocommerce' ),
				'id'          => 'mrbpfw_atc_text',
				'value'       => get_option( 'mrbpfw_atc_text' ),
				'class'       => 'mrbpfw-number-class',
				'placeholder' => 'Enter the Placeholder Text for Add to Cart Button',
			),
			array(
				'title' => __( 'Discount Tag Color', 'mwb-role-based-pricing-for-woocommerce' ),
				'type'  => 'color',
				'id'    => 'mrbpfw_discount_tag_color',
				'value' => get_option( 'mrbpfw_discount_tag_color', '#d3542e' ),
				'class' => 'mrbpfw-color-class',
			),
		);
		$mrbpfw_settings_text   = apply_filters( 'mrbpfw_text_setting', $mrbpfw_settings_text );
		$mrbpfw_settings_text[] = array(
			'type'        => 'button',
			'id'          => 'mrbpfw_save_text_setting',
			'button_text' => __( 'Save Setting', 'mwb-role-based-pricing-for-woocommerce' ),
			'class'       => 'mrbpfw-button-class',
		);
		return $mrbpfw_settings_text;
	}

	/**
	 * MWB Role Based Pricing For WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function mwb_mrbpfw_admin_save_tab_settings() {
		global $mrbpfw_mwb_mrbpfw_obj;
		if ( ( isset( $_POST['mrbpfw_save_general_setting'] ) || isset( $_POST['mrbpfw_save_text_setting'] ) ) && isset( $_POST['general-setting-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['general-setting-nonce'] ) ), 'general-setting-nonce' ) ) {
			$mwb_mrbpfw_gen_flag = false;
			if ( isset( $_POST['mrbpfw_save_general_setting'] ) ) {
				$mrbpfw_genaral_settings = apply_filters( 'mrbpfw_general_settings_array', array() );
			} elseif ( isset( $_POST['mrbpfw_save_text_setting'] ) ) {
				$mrbpfw_genaral_settings = apply_filters( 'mrbpfw_text_settings_array', array() );
			}

			$mrbpfw_button_index     = array_search( 'button', array_column( $mrbpfw_genaral_settings, 'type' ), true );
			if ( isset( $mrbpfw_button_index ) && ( null === $mrbpfw_button_index || '' === $mrbpfw_button_index ) ) {
				$mrbpfw_button_index = array_search( 'button', array_column( $mrbpfw_genaral_settings, 'type' ), true );
			}
			if ( isset( $mrbpfw_button_index ) && '' !== $mrbpfw_button_index ) {
				unset( $mrbpfw_genaral_settings[ $mrbpfw_button_index ] );
				if ( is_array( $mrbpfw_genaral_settings ) && ! empty( $mrbpfw_genaral_settings ) ) {
					foreach ( $mrbpfw_genaral_settings as $mrbpfw_genaral_setting ) {
						if ( isset( $mrbpfw_genaral_setting['id'] ) && '' !== $mrbpfw_genaral_setting['id'] ) {
							if ( isset( $_POST[ $mrbpfw_genaral_setting['id'] ] ) ) {
								update_option( $mrbpfw_genaral_setting['id'], sanitize_text_field( wp_unslash( $_POST[ $mrbpfw_genaral_setting['id'] ] ) ) );
							} else {
								update_option( $mrbpfw_genaral_setting['id'], '' );
							}
						} else {
							$mwb_mrbpfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_mrbpfw_gen_flag ) {
					$mwb_mrbpfw_error_text = esc_html__( 'Id of some field is missing', 'mwb-role-based-pricing-for-woocommerce' );
					$mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_admin_notice( $mwb_mrbpfw_error_text, 'error' );
				} else {
					$mwb_mrbpfw_error_text = esc_html__( 'Settings saved !', 'mwb-role-based-pricing-for-woocommerce' );
					$mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_admin_notice( $mwb_mrbpfw_error_text, 'success' );
				}
			}
		}
		if ( isset( $_POST['save_user_setting'] ) && isset( $_POST['user-setting-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['user-setting-nonce'] ) ), 'user-setting-nonce' ) ) {
			// User role setting register.
			$roles_obj         = new WP_Roles();
			$roles_names_array = $roles_obj->get_names();
			$guest             = array( 'guest' => 'Guest' );
			$roles_names_array = array_merge( $guest, $roles_names_array );
			foreach ( $roles_names_array as $key => $role_name ) {
				$role_name = sanitize_text_field( wp_unslash( $role_name ) );
				$role_name = str_replace( ' ', '_', strtolower( $role_name ) );
				if ( isset( $_POST ) && ! array_key_exists( 'user_setting_' . $role_name, $_POST ) ) {
					update_option( 'user_setting_' . $role_name, '' );
				}
			}
			if ( ! empty( $_POST ) ) {
				foreach ( map_deep( wp_unslash( $_POST ), 'sanitize_text_field' ) as $key => $value ) {
					if ( 'save_user_setting' === $key ) {
						break;
					}
					update_option( $key, $value );
				}
			}
			$mwb_mrbpfw_error_text = esc_html__( 'Settings saved !', 'mwb-role-based-pricing-for-woocommerce' );
			$mrbpfw_mwb_mrbpfw_obj->mwb_mrbpfw_plug_admin_notice( $mwb_mrbpfw_error_text, 'success' );
		}
	}

	/**
	 * Register CPT for price rules.
	 */
	public function mwb_mrbpfw_register_custom_post_type() {
		$labels = array(
			'name'               => esc_html__( 'Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'singular_name'      => esc_html__( 'Price Rules', 'mwb-role-based-pricing-for-woocommerce' ),
			'menu_name'          => esc_html__( 'Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'parent_item_colon'  => esc_html__( 'Parent Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'all_items'          => esc_html__( 'All Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'view_item'          => esc_html__( 'View Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'add_new_item'       => esc_html__( 'Add New Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'add_new'            => esc_html__( 'Add New', 'mwb-role-based-pricing-for-woocommerce' ),
			'edit_item'          => esc_html__( 'Edit Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'update_item'        => esc_html__( 'Update Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'search_items'       => esc_html__( 'Search Price Rule', 'mwb-role-based-pricing-for-woocommerce' ),
			'not_found'          => esc_html__( 'Not Found', 'mwb-role-based-pricing-for-woocommerce' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', 'mwb-role-based-pricing-for-woocommerce' ),
		);
		// Set other options for Case Study type.
		$args = array(
			'label'               => esc_html__( 'price-rules', 'mwb-role-based-pricing-for-woocommerce' ),
			'description'         => esc_html__( 'Price Rules', 'mwb-role-based-pricing-for-woocommerce' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 10,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'menu_icon'           => 'dashicons-id',
		);
		register_post_type( 'mrbpfw_price_rules', $args );
	}

	/**
	 * Function to save the meta box field data into postmeta
	 */
	public function mwb_mrbpfw_save_metabox_data() {
		wp_verify_nonce( 'mrbpfw_meta_box_setting1' );
		if ( isset( $_POST['mrbpfw_meta_box_setting'] ) && isset( $_POST['post_id'] ) ) {
			$post_id    = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : 0;
			$post_title = isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '';
			$roles      = isset( $_POST['roles'] ) ? sanitize_text_field( wp_unslash( $_POST['roles'] ) ) : '';
			$rule_type  = isset( $_POST['rule_type'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_type'] ) ) : '';
			if ( 'selected_products' === $rule_type && isset( $_POST['selected_products'] ) ) {
				$product_ids = map_deep( wp_unslash( $_POST['selected_products'] ), 'sanitize_text_field' );
			} elseif ( 'categories' === $rule_type && isset( $_POST['product_categories'] ) ) {
				$categories = map_deep( wp_unslash( $_POST['product_categories'] ), 'sanitize_text_field' );
			} elseif ( 'tags' === $rule_type && isset( $_POST['product_tags'] ) ) {
				$tags = map_deep( wp_unslash( $_POST['product_tags'] ), 'sanitize_text_field' );
			}
			$discount_type = isset( $_POST['discount_type'] ) ? sanitize_text_field( wp_unslash( $_POST['discount_type'] ) ) : '';
			$price         = isset( $_POST['price_field'] ) ? sanitize_text_field( wp_unslash( $_POST['price_field'] ) ) : 0;
			$priority      = isset( $_POST['priority_field'] ) ? sanitize_text_field( wp_unslash( $_POST['priority_field'] ) ) : 0;
			do_action( 'mwb_mrbpfw_update_rule_meta', $post_id );
			// Saving the data.
			if ( empty( $post_id ) ) {
				$new_post = array(
					'post_title'  => $post_title,
					'post_status' => 'publish',
					'post_date'   => gmdate( 'Y-m-d H:i:s' ),
					'post_author' => get_current_user_id(),
					'post_type'   => 'mrbpfw_price_rules',
				);
				$post_id = wp_insert_post( $new_post );
				update_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', 'on' );

			} else {
				wp_update_post(
					array(
						'ID'         => $post_id,
						'post_title' => $post_title,
					)
				);
			}
			update_post_meta( $post_id, 'mwb_mrbpfw_role', $roles );
			update_post_meta( $post_id, 'mwb_mrbpfw_rule_type', $rule_type );
			if ( 'selected_products' === $rule_type ) {
				update_post_meta( $post_id, 'mwb_mrbpfw_all_products', $product_ids );
			} elseif ( 'categories' === $rule_type ) {
				update_post_meta( $post_id, 'mwb_mrbpfw_categories', $categories );
			} elseif ( 'tags' === $rule_type ) {
				update_post_meta( $post_id, 'mwb_mrbpfw_tags', $tags );
			}
			update_post_meta( $post_id, 'mwb_mrbpfw_discount_type', $discount_type );
			update_post_meta( $post_id, 'mwb_mrbpfw_price', $price );
			update_post_meta( $post_id, 'mwb_mrbpfw_priority', $priority );
			wp_safe_redirect( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule' );
		}
	}

	/**
	 * Function Enable/Disable the price rule.
	 */
	public function mwb_mrbpfw_active_deactive_price_rule() {
		if ( isset( $_POST['post_id'] ) && isset( $_POST['check_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['check_nonce'] ) ), 'create_nonce' ) ) {
			$post_id    = sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
			$rule_check = get_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', true );
			if ( empty( $rule_check ) ) {
				update_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', 'on' );
			} else {
				update_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', '' );
			}
			wp_die();
		}
	}

	/**
	 * Mwb_check_if_priority_exist.
	 */
	public function mwb_mrbpfw_check_if_priority_exist() {
		if ( isset( $_POST['role'] ) && isset( $_POST['priority'] ) && isset( $_POST['check_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['check_nonce'] ) ), 'create_nonce' ) ) {
			$role          = sanitize_text_field( wp_unslash( $_POST['role'] ) );
			$priority      = sanitize_text_field( wp_unslash( $_POST['priority'] ) );
			$all_rules_ids = get_posts(
				array(
					'fields'         => 'ids',
					'posts_per_page' => -1,
					'post_type'      => 'mrbpfw_price_rules',
					'meta_query'     => array(
						array(
							'key'     => 'mwb_mrbpfw_role',
							'value'   => $role,
							'compare' => '==',
						),
						array(
							'key'     => 'mwb_mrbpfw_priority',
							'value'   => $priority,
							'compare' => '==',
						),
					),
				)
			);
			if ( isset( $all_rules_ids ) && ! empty( $all_rules_ids ) ) {
				echo wp_json_encode( map_deep( $all_rules_ids, 'esc_html' ) );
				wp_die();
			}
		}
	}
}
