<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace mwb_role_based_pricing_for_woocommerce_public.
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Role_Based_Pricing_For_Woocommerce_Public {

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'public/css/mwb-role-based-pricing-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mwb_mrbpfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-role-based-pricing-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'mrbpfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Get Current user Role
	 */
	public function mwb_mrbpfw_get_current_role() {
		if ( ! is_user_logged_in() ) {
			$current_role = array( 'guest' );
		} else {
			$get_current_user_role = wp_get_current_user();
			$current_role          = $get_current_user_role->roles;
		}
		// Multisite site role compatability code for admin.
		if ( function_exists( 'is_multisite' ) && is_multisite() && 1 === get_current_user_id() ) {
			$current_role = array( 'administrator' );
		}
		return $current_role;
	}

	/**
	 * Function to change the product price for the product
	 *
	 * @param string $original_price is current product product price.
	 * @param object $product is the current product object.
	 * @return $new_price which can be modify price or original price based the conditions
	 */
	public function mwb_mrbpfw_role_based_price( $original_price, $product ) {
		$current_role = $this->mwb_mrbpfw_get_current_role();

		if ( class_exists( 'WC_Subscriptions_Product' ) && $product->is_subscription() ) {
			return $original_price;
		}
		$rule_apply  = get_option( 'mwb_mrbpfw_for_price_rule' );
		$get_options = get_option( 'user_setting_' . $current_role[0] );

		$new_price = $this->mwb_mrbpfw_get_product_actual_price( $product );
		if ( empty( $new_price ) || $new_price < 0 ) {
			return $original_price;
		}
		// total discount price for product.
		$total_discount = $this->mwb_mrbpfw_get_discount_price( $product );
		// Get the product actual price.
		if ( $product->is_on_sale() ) {
			$args1 = wp_parse_args(
				array(
					'qty'   => '',
					'price' => $product->get_sale_price(),
				)
			);
			$sale_price_excl_tax = wc_get_price_excluding_tax( $product, $args1 );
			$sale_price_incl_tax = wc_get_price_including_tax( $product, $args1 );
		}
		$args2 = wp_parse_args(
			array(
				'qty'   => '',
				'price' => $product->get_regular_price(),
			)
		);
		$regular_price_excl_tax = wc_get_price_excluding_tax( $product, $args2 );
		$regular_price_incl_tax = wc_get_price_including_tax( $product, $args2 );

		// Add/sub the product actual price by total_discount based on the backend setting.
		if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
			if ( isset( $rule_apply ) && 's_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					if ( isset( $total_discount ) && ! empty( $total_discount ) ) {
						$role_based_pricing = $sale_price_incl_tax - $total_discount;
					}
					$sale_price = $sale_price_incl_tax;
				}
			} elseif ( isset( $rule_apply ) && 'r_price' === $rule_apply ) {
				if ( isset( $total_discount ) && ! empty( $total_discount ) ) {
					$role_based_pricing = $regular_price_incl_tax - $total_discount;
				}
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_incl_tax;
				}
			}
		} else {
			if ( isset( $rule_apply ) && 's_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					if ( isset( $total_discount ) && ! empty( $total_discount ) ) {
						$role_based_pricing = $sale_price_excl_tax - $total_discount;
					}
					$sale_price = $sale_price_excl_tax;
				}
			} elseif ( isset( $rule_apply ) && 'r_price' === $rule_apply ) {
				if ( isset( $total_discount ) && ! empty( $total_discount ) ) {
					$role_based_pricing = $regular_price_excl_tax - $total_discount;
				}
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_excl_tax;
				}
			}
		}
		if ( isset( $role_based_pricing ) && ! empty( $role_based_pricing ) && ! empty( $get_options ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) ) {
			if ( ! isset( $sale_price ) ) {
				$sale_price = 0;
			}
			return array(
				'role_price' => apply_filters( 'mwb_mrbpfw_price', $role_based_pricing ),
				'sale_price' => $sale_price,
			);
		} else {
			return array(
				'role_price' => 0,
				'sale_price' => 0,
			);
		}
	}

	/**
	 * Function to get the actual price of the product to make discount on that price
	 *
	 * @param object $product .
	 */
	public function mwb_mrbpfw_get_product_actual_price( $product ) {
		$rule_apply  = get_option( 'mwb_mrbpfw_for_price_rule' );

		if ( $product->is_on_sale() ) {
			$args1               = wp_parse_args(
				array(
					'qty'   => '',
					'price' => $product->get_sale_price(),
				)
			);
			$sale_price_excl_tax = wc_get_price_excluding_tax( $product, $args1 );
			$sale_price_incl_tax = wc_get_price_including_tax( $product, $args1 );
		}
		$args2                  = wp_parse_args(
			array(
				'qty'   => '',
				'price' => $product->get_regular_price(),
			)
		);
		$regular_price_excl_tax = wc_get_price_excluding_tax( $product, $args2 );
		$regular_price_incl_tax = wc_get_price_including_tax( $product, $args2 );
		$current_role           = $this->mwb_mrbpfw_get_current_role();
		$get_options            = get_option( 'user_setting_' . $current_role[0] );
		$actual_price           = 0;
		if ( 'simple' === $product->get_type() || 'variable' === $product->get_type() || 'variation' === $product->get_type() ) {
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				if ( 'r_price' === $rule_apply ) {
					$actual_price = $regular_price_incl_tax;
				} elseif ( 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$actual_price = $sale_price_incl_tax;
					}
				} else {
					$actual_price = $product->get_price();
				}
			} else {
				if ( 'r_price' === $rule_apply ) {
					$actual_price = $regular_price_excl_tax;
				} elseif ( 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$actual_price = $sale_price_excl_tax;
					}
				} else {
					$actual_price = $product->get_price();
				}
			}
		}
		return $actual_price;
	}

	/**
	 * Function  to get the all rule ids depending upon the current user role.
	 *
	 * @param object $product .
	 */
	public function mwb_mrbpfw_get_all_rule_ids_for_current_role( $product ) {
		// Logic for calculating the new price here.
		$current_role  = $this->mwb_mrbpfw_get_current_role();
		$all_rules_ids = get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'post_type'      => 'mrbpfw_price_rules',
				'meta_query'     => array(
					array(
						'key'     => 'mwb_mrbpfw_role',
						'value'   => $current_role[0],
						'compare' => '==',
					),
					array(
						'key'     => 'mwb_mrbpfw_enable_rule',
						'value'   => 'on',
						'compare' => '==',
					),
				),
			)
		);
		// Priority_wise_rule_id .
		$priority_wise_rule_id = array();
		if ( ! empty( $all_rules_ids ) ) {
			foreach ( $all_rules_ids as $key => $id ) {
				$priority                           = get_post_meta( $id, 'mwb_mrbpfw_priority', true );
				$priority_wise_rule_id[ $priority ] = $id;
			}
		}
		return $priority_wise_rule_id;
	}

	/**
	 * Function to check if discount avaiable for the current product
	 *
	 * @param object $product is a product object.
	 * @return $discounts with flag and prices
	 */
	public function mwb_mrbpfw_discount_availability_for_product( $product ) {
		$discount       = 'no';
		$all_rules_ids  = $this->mwb_mrbpfw_get_all_rule_ids_for_current_role( $product );
		$filtered_array = array();
		if ( isset( $all_rules_ids ) && ! empty( $all_rules_ids ) ) {
			foreach ( $all_rules_ids as $key => $id ) {
				$rule_type       = get_post_meta( $id, 'mwb_mrbpfw_rule_type', true );
				$discount_type   = get_post_meta( $id, 'mwb_mrbpfw_discount_type', true );
				$price           = get_post_meta( $id, 'mwb_mrbpfw_price', true );
				$all_product_ids = get_post_meta( $id, 'mwb_mrbpfw_all_products', true );
				if ( $product->is_type( 'simple' ) ) {
					$product_id = $product->get_id();
				} elseif ( $product->is_type( 'variable' ) ) {
					$product_id = $product->get_id();
				} elseif ( $product->is_type( 'variation' ) ) {
					$product_id = $product->get_parent_id();
				} else {
					return array(
						'flag' => $discount,
						'data' => $filtered_array,
					);
				}
				if ( ! empty( $rule_type ) && ! empty( $price ) && ! empty( $discount_type ) ) {
					if ( 'all_products' === $rule_type ) {
						$discount               = 'yes';
						$filtered_array[ $key ] = $id;
					} elseif ( 'selected_products' === $rule_type && ! empty( $all_product_ids ) && in_array( $product_id, $all_product_ids ) ) {
						$discount               = 'yes';
						$filtered_array[ $key ] = $id;
					} elseif ( 'categories' === $rule_type ) {
						$categories = get_post_meta( $id, 'mwb_mrbpfw_categories', true );
						if ( ! empty( $categories ) && has_term( $categories, 'product_cat', $product_id ) ) {
							$discount               = 'yes';
							$filtered_array[ $key ] = $id;
						}
					} elseif ( 'tags' === $rule_type ) {
						$tags = get_post_meta( $id, 'mwb_mrbpfw_tags', true );
						if ( ! empty( $tags ) && has_term( $tags, 'product_tag', $product_id ) ) {
							$discount               = 'yes';
							$filtered_array[ $key ] = $id;
						}
					}
				}
			}
		}
		// logic the filter array based on the backend setting.
		$price_array = array();
		if ( ! empty( $filtered_array ) ) {
			foreach ( $filtered_array as $priority => $id ) {
				$discount_price                 = get_post_meta( $id, 'mwb_mrbpfw_price', true );
				$discount_type = get_post_meta( $id, 'mwb_mrbpfw_discount_type', true );
				if ( 'percentage' === $discount_type ) {
					$discount_price = $this->mwb_mrbpfw_calculate_price_percentage( $product, $discount_price );
				}
				$price_array[ $id ] = $discount_price;
			}
			$rule_price = get_option( 'mwb_mrbpfw_for_price_rule_priority', false );
			if ( 'min_price' === $rule_price ) {
				$filtered_array              = array();
				$price                       = min( $price_array );
				$id                          = array_search( $price, $price_array, true );
				$priority                    = get_post_meta( $id, 'mwb_mrbpfw_priority', true );
				$filtered_array[ $priority ] = $id;
			} elseif ( 'max_price' === $rule_price ) {
				$filtered_array              = array();
				$price                       = max( $price_array );
				$id                          = array_search( $price, $price_array, true );
				$priority                    = get_post_meta( $id, 'mwb_mrbpfw_priority', true );
				$filtered_array[ $priority ] = $id;
			} elseif ( 'min_priority' === $rule_price ) {
				$filtered_array = array( max( $filtered_array ) );
			} elseif ( 'max_priority' === $rule_price ) {
				$filtered_array = array( min( $filtered_array ) );
			} else {
				$filtered_array = apply_filters( 'mwb_mrbpfw_filter_rule_to_apply', $filtered_array );
			}
			ksort( $filtered_array );
		}
		$rule_apply = get_option( 'mwb_mrbpfw_for_price_rule' );
		if ( 's_price' === $rule_apply && ! $product->is_on_sale() ) {
			$discount = 'no';
		}
		return array(
			'flag' => $discount,
			'data' => $filtered_array,
		);
	}

	/**
	 * Get Total discount for the products
	 *
	 * @param object $product .
	 */
	public function mwb_mrbpfw_get_discount_price( $product ) {
		$new_price      = $this->mwb_mrbpfw_get_product_actual_price( $product );
		$discount       = $this->mwb_mrbpfw_discount_availability_for_product( $product );
		$all_rules_ids  = $discount['data'];
		$total_discount = array();
		if ( isset( $all_rules_ids ) && ! empty( $all_rules_ids ) && ! empty( $new_price ) && ! empty( $discount['data'] ) ) {
			foreach ( $all_rules_ids as $key => $id ) {
				$rule_type       = get_post_meta( $id, 'mwb_mrbpfw_rule_type', true );
				$discount_type   = get_post_meta( $id, 'mwb_mrbpfw_discount_type', true );
				$price           = get_post_meta( $id, 'mwb_mrbpfw_price', true );
				$all_product_ids = get_post_meta( $id, 'mwb_mrbpfw_all_products', true );
				floatval( $price );
				if ( $product->is_type( 'simple' ) ) {
					$product_id = $product->get_id();
				} elseif ( $product->is_type( 'variable' ) ) {
					$product_id = $product->get_id();
				} elseif ( $product->is_type( 'variation' ) ) {
					$product_id = $product->get_parent_id();
				} else {
					return 0;
				}
				if ( ! empty( $rule_type ) && ! empty( $price ) && ! empty( $discount_type ) && ! empty( $product_id ) ) {
					if ( 'all_products' === $rule_type ) {
						if ( 'fixed' === $discount_type ) {
							array_push( $total_discount, $price );
						} elseif ( 'percentage' === $discount_type ) {
							$per_price = ( $new_price * $price ) / 100;
							$per_price = round( $per_price, 2 );
							array_push( $total_discount, $per_price );
						}
					} elseif ( 'selected_products' === $rule_type && ! empty( $all_product_ids ) && in_array( $product_id, $all_product_ids ) ) {
						if ( 'fixed' === $discount_type ) {
							array_push( $total_discount, $price );
						} elseif ( 'percentage' === $discount_type ) {
							$per_price = ( $new_price * $price ) / 100;
							$per_price = round( $per_price, 2 );
							array_push( $total_discount, $per_price );
						}
					} elseif ( 'categories' === $rule_type ) {
						$categories = get_post_meta( $id, 'mwb_mrbpfw_categories', true );
						if ( ! empty( $categories ) && has_term( $categories, 'product_cat', $product_id ) ) {
							if ( 'fixed' === $discount_type ) {
								array_push( $total_discount, $price );
							} elseif ( 'percentage' === $discount_type ) {
								$per_price = ( $new_price * $price ) / 100;
								$per_price = round( $per_price, 2 );
								array_push( $total_discount, $per_price );
							}
						}
					} elseif ( 'tags' === $rule_type ) {
						$tags = get_post_meta( $id, 'mwb_mrbpfw_tags', true );
						if ( ! empty( $tags ) && has_term( $tags, 'product_tag', $product_id ) ) {
							if ( 'fixed' === $discount_type ) {
								array_push( $total_discount, $price );
							} elseif ( 'percentage' === $discount_type ) {
								$per_price = ( $new_price * $price ) / 100;
								$per_price = round( $per_price, 2 );
								array_push( $total_discount, $per_price );
							}
						}
					}
				}
			}
		}
		if ( ! empty( $total_discount ) ) {
			return array_sum( $total_discount );
		} else {
			return 0;
		}
	}

	/**
	 * Function to show the in-range for the varibale products.
	 *
	 * @param string $price is the current product price.
	 * @param object $product is the object of the current product.
	 * @return $price or $format_price price based on the conditions.
	 */
	public function mwb_mrbpfw_product_price( $price, $product ) {
		if ( is_admin() ) {
			return $price;
		}
		if ( 'simple' !== $product->get_type() && 'variable' !== $product->get_type() && 'variation' !== $product->get_type() ) {
			return $price;
		}
		$current_role = $this->mwb_mrbpfw_get_current_role();
		if ( class_exists( 'WC_Subscriptions_Product' ) && $product->is_subscription() ) {
			return $price;
		}
		$rule_apply  = get_option( 'mwb_mrbpfw_for_price_rule' );
		$get_options = get_option( 'user_setting_' . $current_role[0] );
		if ( isset( $get_options ) && empty( $get_options ) ) {
			return $price;
		}
		$mrbpfw_regular_price_text    = get_option( 'mrbpfw_regular_price_text' );
		$mrbpfw_sale_price_text       = get_option( 'mrbpfw_sale_price_text' );
		$mrbpfw_role_based_price_text = get_option( 'mrbpfw_role_based_price_text' );
		$show_regular_price           = in_array( 'regular_price_' . $current_role[0], $get_options, true );
		$show_sale_price              = in_array( 'on_sale_price_' . $current_role[0], $get_options, true );
		if ( ( 's_price' === $rule_apply ) || ( 'r_price' === $rule_apply ) ) {
			$reg_price = '';
			if ( $product->is_type( 'variable' ) ) {
				$variations          = $product->get_children();
				$reg_prices          = array();
				$sale_prices         = array();
				$real_sale_prices    = array();
				$real_regular_prices = array();
				foreach ( $variations as $value ) {
					$single_variation = new WC_Product_Variation( $value );
					if ( $single_variation->is_on_sale() ) {
						$args1               = wp_parse_args(
							array(
								'qty'   => '',
								'price' => $single_variation->get_sale_price(),
							)
						);
						$sale_price_excl_tax = wc_get_price_excluding_tax( $single_variation, $args1 );
						$sale_price_incl_tax = wc_get_price_including_tax( $single_variation, $args1 );
					}
					$args2                  = wp_parse_args(
						array(
							'qty'   => '',
							'price' => $single_variation->get_regular_price(),
						)
					);
					$regular_price_excl_tax = wc_get_price_excluding_tax( $single_variation, $args2 );
					$regular_price_incl_tax = wc_get_price_including_tax( $single_variation, $args2 );
					if ( $single_variation->is_on_sale() ) {
						$new_price = $this->mwb_mrbpfw_role_based_price( $price, $single_variation );
						if ( isset( $new_price['role_price'] ) ) {
							$new_price = $new_price['role_price'];
							if ( $new_price < 0 ) {
								$new_price = 0;
							}
							array_push( $sale_prices, $new_price );
						}
					}
					$new_price = $this->mwb_mrbpfw_role_based_price( $price, $single_variation );
					if ( isset( $new_price['role_price'] ) ) {
						$new_price = $new_price['role_price'];
						if ( $new_price < 0 ) {
							$new_price = 0;
						}
					}
					array_push( $reg_prices, $new_price );

					if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
						if ( $single_variation->is_on_sale() ) {
							array_push( $real_sale_prices, $sale_price_incl_tax );
						}
						array_push( $real_regular_prices, $regular_price_incl_tax );
					} else {
						if ( $single_variation->is_on_sale() ) {
							array_push( $real_sale_prices, $sale_price_excl_tax );
						}
						array_push( $real_regular_prices, $regular_price_excl_tax );
					}
				}
				sort( $reg_prices );
				sort( $sale_prices );
				sort( $real_sale_prices );
				sort( $real_regular_prices );
				$tax_label  = '';
				$tax_enable = get_option( 'woocommerce_calc_taxes' );
				if ( 'yes' === $tax_enable ) {
					if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
						$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'incl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
					} else {
						$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'excl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
					}
				}

				$discount = $this->mwb_mrbpfw_discount_availability_for_product( $product );
				$discount = $discount['flag'];
				if ( $product->is_on_sale() ) {
					if ( 's_price' === $rule_apply ) {
						$min_price = $sale_prices[0];
						$max_price = $sale_prices[ count( $sale_prices ) - 1 ];
					} elseif ( 'r_price' === $rule_apply ) {
						$min_price = $reg_prices[0];
						$max_price = $reg_prices[ count( $reg_prices ) - 1 ];
					}
					$min_sale_price = $real_sale_prices[0];
					$max_sale_price = $real_sale_prices[ count( $real_sale_prices ) - 1 ];
					$min_reg_price  = $real_regular_prices[0];
					$max_reg_price  = $real_regular_prices[ count( $real_regular_prices ) - 1 ];
					if ( isset( $min_sale_price ) ) {
						$min_sale_price = apply_filters( 'mwb_mrbpfw_price', $min_sale_price );
					}
					if ( isset( $max_sale_price ) ) {
						$max_sale_price = apply_filters( 'mwb_mrbpfw_price', $max_sale_price );
					}
					if ( isset( $min_reg_price ) ) {
						$min_reg_price = apply_filters( 'mwb_mrbpfw_price', $min_reg_price );
					}
					if ( isset( $max_reg_price ) ) {
						$max_reg_price = apply_filters( 'mwb_mrbpfw_price', $max_reg_price );
					}

					$min_sale_price1 = $min_sale_price;
					$min_price1      = $min_price;
					$min_price       = wc_price( $min_price );
					$max_price       = wc_price( $max_price );
					$min_sale_price  = wc_price( $min_sale_price );
					$max_sale_price  = wc_price( $max_sale_price );
					$min_reg_price   = wc_price( $min_reg_price );
					$max_reg_price   = wc_price( $max_reg_price );
					if ( ! empty( $get_options ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) && isset( $discount ) && 'yes' === $discount ) {
						$format_price = '';
						if ( ! empty( $show_regular_price ) || $show_regular_price ) {
							$reg_price    = wc_format_price_range( $mrbpfw_regular_price_text . ' ' . $min_reg_price, $max_reg_price );
							$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $reg_price . ' ' . $tax_label, true );
						}
						if ( ! empty( $show_sale_price ) || $show_sale_price ) {
							$sale_price = wc_format_price_range( $mrbpfw_sale_price_text . ' ' . $min_sale_price, $max_sale_price );
							if ( 'r_price' === $rule_apply && ! empty( $min_sale_price1 ) && $min_price1 > $min_sale_price1 ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $sale_price . ' ' . $tax_label, false );
							} else {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $sale_price . ' ' . $tax_label, true );
							}
						}
						$role_price = wc_format_price_range( $mrbpfw_role_based_price_text . ' ' . $min_price, $max_price );
						if ( 'r_price' === $rule_apply && ! empty( $min_sale_price1 ) && $min_price1 > $min_sale_price1 ) {
							$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $role_price . ' ' . $tax_label, true );
						} else {
							$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $role_price . ' ' . $tax_label, false );
						}
						$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
						return $format_price;
					} elseif ( ! empty( $get_options ) && in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) ) {
						$format_price = '';
						if ( ! empty( $show_regular_price ) || $show_regular_price ) {
							$reg_price    = wc_format_price_range( $mrbpfw_regular_price_text . ' ' . $min_reg_price, $max_reg_price );
							$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $reg_price . ' ' . $tax_label, true );
						}
						$sale_price    = wc_format_price_range( $mrbpfw_sale_price_text . ' ' . $min_sale_price, $max_sale_price );
						$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $sale_price . ' ' . $tax_label, false );
						$format_price  = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
						return $format_price;
					} elseif ( ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
						$reg_price    = wc_format_price_range( $mrbpfw_regular_price_text . ' ' . $min_reg_price, $max_reg_price );
						$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $reg_price . ' ' . $tax_label, false );
						$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
						return $format_price;
					} else {
						return false;
					}
				} elseif ( ! $product->is_on_sale() ) {
					$min_price     = $reg_prices[0];
					$max_price     = $reg_prices[ count( $reg_prices ) - 1 ];
					$min_reg_price = $real_regular_prices[0];
					$max_reg_price = $real_regular_prices[ count( $real_regular_prices ) - 1 ];

					if ( isset( $min_reg_price ) ) {
						$min_reg_price = apply_filters( 'mwb_mrbpfw_price', $min_reg_price );
					}
					if ( isset( $max_reg_price ) ) {
						$max_reg_price = apply_filters( 'mwb_mrbpfw_price', $max_reg_price );
					}

					$min_price     = wc_price( $min_price );
					$max_price     = wc_price( $max_price );
					$min_reg_price = wc_price( $min_reg_price );
					$max_reg_price = wc_price( $max_reg_price );
					if ( 's_price' === $rule_apply ) {
						if ( $show_regular_price ) {
							$format_price  = '';
							$role_price    = $mrbpfw_regular_price_text . ' ' . $min_reg_price . ' - ' . $max_reg_price;
							$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $role_price . ' ' . $tax_label, false );
							return $format_price;
						}
					}
					if ( ! empty( $get_options ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) && isset( $discount ) && 'yes' === $discount ) {
						$format_price = '';
						if ( ! empty( $show_regular_price ) || $show_regular_price ) {
							$reg_price    = wc_format_price_range( $mrbpfw_regular_price_text . ' ' . $min_reg_price, $max_reg_price );
							$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $reg_price . ' ' . $tax_label, true );
						}
						$role_price    = wc_format_price_range( $mrbpfw_role_based_price_text . ' ' . $min_price, $max_price );
						$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $role_price . ' ' . $tax_label, false );
						$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
						return $format_price;
					} elseif ( ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
						$reg_price    = $mrbpfw_regular_price_text . ' ' . $min_reg_price . ' - ' . $max_reg_price;
						$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $reg_price . ' ' . $tax_label, false );
						$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
						return $format_price;
					} else {
						return false;
					}
				}
			} elseif ( $product->is_type( 'simple' ) ) {
				return $this->mwb_mrbpfw_get_formatted_price( $price, $product );
			} else {
				return $price;
			}
		} else {
			return $price;
		}
	}

	/**
	 * Set the price on product page for variable after variation selected.
	 *
	 * @param string $original_price is current product product price.
	 * @param object $product is the current product object.
	 * @return $price or $original_price which can be modify price or original price based the conditions
	 */
	public function mwb_mrbpfw_variation_price( $original_price, $product ) {
		global $post, $woocommerce;
		$current_role = $this->mwb_mrbpfw_get_current_role();
		$get_options  = get_option( 'user_setting_' . $current_role[0] );
		$rule_apply   = get_option( 'mwb_mrbpfw_for_price_rule' );
		if ( is_admin() ) {
			return $original_price;
		}
		if ( class_exists( 'WC_Subscriptions_Product' ) && $product->is_subscription() ) {
			return $original_price;
		}
		if ( 'simple' !== $product->get_type() && 'variable' !== $product->get_type() && 'variation' !== $product->get_type() ) {
			return $original_price;
		}

		if ( $product->is_on_sale() ) {
			$args1               = wp_parse_args(
				array(
					'qty'   => '',
					'price' => $product->get_sale_price(),
				)
			);
			$sale_price_excl_tax = wc_get_price_excluding_tax( $product, $args1 );
			$sale_price_incl_tax = wc_get_price_including_tax( $product, $args1 );
		}
		$args2                  = wp_parse_args(
			array(
				'qty'   => '',
				'price' => $product->get_regular_price(),
			)
		);
		$regular_price_excl_tax = wc_get_price_excluding_tax( $product, $args2 );
		$regular_price_incl_tax = wc_get_price_including_tax( $product, $args2 );

		// Add/sub the product actual price by total_discount based on the backend setting.
		if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
			if ( 's_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_incl_tax;
				}
				$regular_price = $regular_price_incl_tax;
			} elseif ( 'r_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_incl_tax;
				}
				$regular_price = $regular_price_incl_tax;
			}
		} else {
			if ( 's_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_excl_tax;
				}
				$regular_price = $regular_price_excl_tax;
			} elseif ( 'r_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					$sale_price = $sale_price_excl_tax;
				}
				$regular_price = $regular_price_excl_tax;
			}
		}
		$change_price = $this->mwb_mrbpfw_role_based_price( $original_price, $product );
		// set the cart item product price.
		if ( ! empty( $get_options ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) ) {
			if ( isset( $change_price['role_price'] ) && isset( $change_price['sale_price'] ) ) {
				if ( 'r_price' === $rule_apply ) {
					$price = $change_price['role_price'];
				} elseif ( 's_price' === $rule_apply ) {
					if ( ! $product->is_on_sale() ) {
						$price = $change_price['role_price'];
					} else {
						if ( $change_price['role_price'] > $change_price['sale_price'] ) {
							$price = $change_price['sale_price'];
						} else {
							$price = $change_price['role_price'];
						}
					}
				}
			}
		} elseif ( $product->is_on_sale() && ! empty( $get_options ) && in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) ) {
			$price = $sale_price;
		} elseif ( $product->is_on_sale() && ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
			$price = $regular_price;
		} elseif ( ! $product->is_on_sale() && ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
			$price = $regular_price;
		} else {
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				$price = wc_get_price_including_tax( $product );
			} else {
				$price = wc_get_price_excluding_tax( $product );
			}
		}
		$tax_label  = '';
		$tax_enable = get_option( 'woocommerce_calc_taxes' );
		if ( 'yes' === $tax_enable ) {
			if ( isset( $price ) && $price < 0 ) {
				$price = 0;
			}
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'incl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
			} else {
				$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'excl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
			}
		}
		if ( ! isset( $price ) ) {
			$price = 0;
		}
		return wc_price( apply_filters( 'mwb_mrbpfw_price', $price ) ) . ' ' . $tax_label;
	}


	/**
	 * Function to update the cart item price based on the price rules.
	 *
	 * @param object $cart is the object of total cart item.
	 */
	public function mwb_mrbpfw_alter_price_cart( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$sale_price   = 0;
			$current_role = $this->mwb_mrbpfw_get_current_role();
			$product      = $cart_item['data'];
			$discount     = $this->mwb_mrbpfw_discount_availability_for_product( $product );
			$discount     = $discount['flag'];
			$get_options  = get_option( 'user_setting_' . $current_role[0] );
			$rule_apply   = get_option( 'mwb_mrbpfw_for_price_rule' );

			// Get the product_actual price.
			if ( $product->is_on_sale() ) {
				$args1               = wp_parse_args(
					array(
						'qty'   => '',
						'price' => $product->get_sale_price(),
					)
				);
				$sale_price_excl_tax = wc_get_price_excluding_tax( $product, $args1 );
				$sale_price_incl_tax = wc_get_price_including_tax( $product, $args1 );
			}
			$args2                  = wp_parse_args(
				array(
					'qty'   => '',
					'price' => $product->get_regular_price(),
				)
			);
			$regular_price_excl_tax = wc_get_price_excluding_tax( $product, $args2 );
			$regular_price_incl_tax = wc_get_price_including_tax( $product, $args2 );

			// total discount price for product.
			$total_discount = $this->mwb_mrbpfw_get_discount_price( $product );

			// Add/sub the product actual price by total_discount based on the backend setting.
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				if ( 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						if ( ! empty( $total_discount ) ) {
							$role_based_pricing = $sale_price_incl_tax - $total_discount;
						}
						$sale_price = $sale_price_incl_tax;
					}
					$regular_price = $regular_price_incl_tax;
				} elseif ( 'r_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$sale_price = $sale_price_incl_tax;
					}
					if ( ! empty( $total_discount ) ) {
						$role_based_pricing = $regular_price_incl_tax - $total_discount;
					}
					$regular_price = $regular_price_incl_tax;
				}
			} else {
				if ( 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						if ( ! empty( $total_discount ) ) {
							$role_based_pricing = $sale_price_excl_tax - $total_discount;
						}
						$sale_price = $sale_price_excl_tax;
					}
					$regular_price = $regular_price_excl_tax;
				} elseif ( 'r_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$sale_price = $sale_price_excl_tax;
					}
					if ( ! empty( $total_discount ) ) {
						$role_based_pricing = $regular_price_excl_tax - $total_discount;
					}
					$regular_price = $regular_price_excl_tax;
				}
			}
			// set the cart item product price.
			if ( ! empty( $get_options ) && isset( $role_based_pricing ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) && isset( $discount ) && 'yes' === $discount ) {
				if ( 'r_price' === $rule_apply ) {
					if ( $role_based_pricing <= 0 ) {
						$cart_item['data']->set_price( 0 );
					} elseif ( isset( $sale_price ) && in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) && ! empty( $sale_price ) && $role_based_pricing > $sale_price ) {
						$cart_item['data']->set_price( $sale_price );
					} else {
						$cart_item['data']->set_price( $role_based_pricing );
					}
				} elseif ( 's_price' === $rule_apply ) {
					if ( ! $product->is_on_sale() ) {
						$cart_item['data']->set_price( $regular_price );
					} else {
						if ( $role_based_pricing <= 0 ) {
							$cart_item['data']->set_price( 0 );
						} else {
							$cart_item['data']->set_price( $role_based_pricing );
						}
					}
				}
			} elseif ( $product->is_on_sale() && ! empty( $get_options ) && in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) ) {
				$cart_item['data']->set_price( $sale_price );
			} elseif ( $product->is_on_sale() && ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
				$cart_item['data']->set_price( $regular_price );
			} elseif ( ! $product->is_on_sale() && ! empty( $get_options ) && in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) {
				$cart_item['data']->set_price( $regular_price );
			} else {
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
					$cart_item['data']->set_price( wc_get_price_including_tax( $product ) );
				} else {
					$cart_item['data']->set_price( wc_get_price_excluding_tax( $product ) );
				}
			}
		}
		// Remove tax as the plugin handle the tax bcoz of incl/excl setting in the plugin.
		do_action( 'mwb_remove_tax_after_cart_table' );
	}

	/**
	 * Show tax label for price
	 *
	 * @param string $product_price .
	 * @param array  $cart_item .
	 * @param int    $cart_item_key .
	 */
	public function mrbpfw_show_subscription_price_on_cart( $product_price, $cart_item, $cart_item_key ) {
		$tax_label  = '';
		$tax_enable = get_option( 'woocommerce_calc_taxes' );
		if ( 'yes' === $tax_enable ) {
			$current_role = $this->mwb_mrbpfw_get_current_role();
			$get_options  = get_option( 'user_setting_' . $current_role[0] );
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'incl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
			} else {
				$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'excl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
			}
			$product_price = $product_price . ' ' . $tax_label;
		}
		return $product_price;
	}

	/**
	 * Remove the tax from the cart page and checkout page.
	 */
	public function mwb_mrbpfw_remove_taxes() {
		// Remove tax as the plugin handle the tax bcoz of incl/excl setting in the plugin.
		if ( is_cart() || is_checkout() ) {
			add_filter( 'wc_tax_enabled', '__return_false' );
		}
	}

	/**
	 * Function to show to discount fixed or percentage.
	 */
	public function mwb_mrbpfw_show_total_discount_tag() {
		global $product;
		if ( $product->is_type( 'simple' ) ) {
			$product_id = $product->get_id();
		} elseif ( $product->is_type( 'variable' ) || $product->is_type( 'variation' ) ) {
			$product_id = $product->get_parent_id();
		}
		$current_role   = $this->mwb_mrbpfw_get_current_role();
		$check_discount = get_option( 'user_setting_' . $current_role[0] );
		$discount       = $this->mwb_mrbpfw_discount_availability_for_product( $product );
		$get_options    = get_option( 'user_setting_' . $current_role[0] );
		$fix_discount   = array();
		$per_discount   = array();
		if ( ! empty( $check_discount ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) && in_array( 'show_total_discount_' . $current_role[0], $check_discount, true ) && isset( $discount['flag'] ) && 'yes' === $discount['flag'] ) {
			// Logic for calculating the new price here.
			$all_rules_ids = $discount['data'];
			if ( 'simple' === $product->get_type() || 'variable' === $product->get_type() || 'variation' === $product->get_type() ) {
				if ( isset( $all_rules_ids ) && ! empty( $all_rules_ids ) ) {
					foreach ( $all_rules_ids as $key => $id ) {
						$rule_type       = get_post_meta( $id, 'mwb_mrbpfw_rule_type', true );
						$discount_type   = get_post_meta( $id, 'mwb_mrbpfw_discount_type', true );
						$price           = get_post_meta( $id, 'mwb_mrbpfw_price', true );
						$all_product_ids = get_post_meta( $id, 'mwb_mrbpfw_all_products', true );
						if ( ! empty( $rule_type ) && ! empty( $discount_type ) && ! empty( $price ) ) {
							if ( 'all_products' === $rule_type ) {
								if ( 'fixed' === $discount_type ) {
									array_push( $fix_discount, $price );
								} elseif ( 'percentage' === $discount_type ) {
									array_push( $per_discount, $price );
								}
							} elseif ( 'selected_products' === $rule_type && ! empty( $all_product_ids ) && in_array( $product->get_id(), $all_product_ids ) ) {
								if ( 'fixed' === $discount_type ) {
									array_push( $fix_discount, $price );
								} elseif ( 'percentage' === $discount_type ) {
									array_push( $per_discount, $price );
								}
							} elseif ( 'categories' === $rule_type ) {
								$categories = get_post_meta( $id, 'mwb_mrbpfw_categories', true );
								if ( ! empty( $categories ) && has_term( $categories, 'product_cat', $product_id ) ) {
									if ( 'fixed' === $discount_type ) {
										array_push( $fix_discount, $price );
									} elseif ( 'percentage' === $discount_type ) {
										array_push( $per_discount, $price );
									}
								}
							} elseif ( 'tags' === $rule_type ) {
								$tags = get_post_meta( $id, 'mwb_mrbpfw_tags', true );
								if ( ! empty( $tags ) && has_term( $tags, 'product_tag', $product_id ) ) {
									if ( 'fixed' === $discount_type ) {
										array_push( $fix_discount, $price );
									} elseif ( 'percentage' === $discount_type ) {
										array_push( $per_discount, $price );
									}
								}
							}
						}
					}
				}
			}
		}
		if ( ! empty( $fix_discount ) || ! empty( $per_discount ) ) {
			?>
			<div class="mwb_mrbpfw-sale-perc">
				<?php
				if ( ! empty( $fix_discount ) ) {
					echo '<span class="mwb_mrbpfw-sale-perc-line">';
					echo esc_html__( 'Total Discount', 'mwb-role-based-pricing-for-woocommerce' );
					echo '&nbsp' . wp_kses_post( wc_price( apply_filters( 'mwb_mrbpfw_price', array_sum( $fix_discount ) ) ) );
					echo '</span>';
				}
				if ( ! empty( $per_discount ) ) {
					echo '<span class="mwb_mrbpfw-sale-perc-line">';
					echo esc_html( array_sum( $per_discount ) );
					echo '% ' . esc_html__( 'Discount', 'mwb-role-based-pricing-for-woocommerce' );
					echo '</span>';
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Return the filter price
	 *
	 * @param object $product .
	 * @param int    $price .
	 */
	public function mwb_mrbpfw_calculate_price_percentage( $product, $price ) {
		$rule_apply   = get_option( 'mwb_mrbpfw_for_price_rule' );
		$filter_price = array();
		$variations   = $product->get_children();
		if ( $product->is_type( 'variable' ) ) {
			foreach ( $variations as $value ) {
				$single_variation = new WC_Product_Variation( $value );
				if ( 'r_price' === $rule_apply ) {
					$product_price = $single_variation->get_regular_price();
				} elseif ( 's_price' === $rule_apply ) {
					if ( $single_variation->is_on_sale() ) {
						$product_price = $single_variation->get_sale_price();
					} else {
						$product_price = $single_variation->get_regular_price();
					}
				}
				$dis_price = ( $product_price * $price ) / 100;
				array_push( $filter_price, $dis_price );
			}
		} else {
			if ( 'r_price' === $rule_apply ) {
				$product_price = $product->get_regular_price();
			} elseif ( 's_price' === $rule_apply ) {
				if ( $product->is_on_sale() ) {
					$product_price = $product->get_sale_price();
				} else {
					$product_price = $product->get_regular_price();
				}
			}
			$dis_price = ( $product_price * $price ) / 100;
			array_push( $filter_price, $dis_price );
		}
		return min( $filter_price );
	}

	/**
	 * Function Hide the add to cart button from frontend.
	 */
	public function mwb_mrbpfw_add_to_cart_button_hide() {
		$current_role = $this->mwb_mrbpfw_get_current_role();
		$get_option   = get_option( 'user_setting_' . $current_role[0] );
		if ( ! empty( $current_role ) && ! empty( $get_option ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_option, true ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
	}

	/**
	 * Function to add or not <del> tag for price.
	 *
	 * @param string  $price_html is the current price html.
	 * @param boolean $del false or true to addd or not the <del> tag.
	 */
	public function mwb_mrbpfw_get_formatted_price_html( $price_html, $del ) {
		$price_formatted_html = '';
		if ( ! ( empty( $price_html ) ) ) {
			$price_formatted_html  = '<span class="mwb_mrbpfw_format-price">';
			$price_formatted_html .= $del ? '<del>' : '';
			$price_formatted_html .= $price_html;
			$price_formatted_html .= $del ? '</del>' : '';
			$price_formatted_html .= '</span>';
		}
		return apply_filters( 'mwb_price_format_html', $price_formatted_html );
	}

	/**
	 * Show someting inplace of add to cart button
	 */
	public function mwb_mrbpfw_after_add_to_cart_button() {
		$current_role = $this->mwb_mrbpfw_get_current_role();
		$get_option   = get_option( 'user_setting_' . $current_role[0] );
		if ( ! empty( $current_role ) && ! empty( $get_option ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_option, true ) ) {
			$atc_text = get_option( 'mrbpfw_atc_text', false );
			if ( ! empty( $atc_text ) ) {
				echo esc_html( $atc_text );
			}
		}
	}

	/**
	 * Function to format the price(html) based on the text setting to show on the shop/product page.
	 *
	 * @param string $original_price is the current product price based on the rule applied.
	 * @param object $product is the current product object.
	 * @return string the price based on the rule applied.
	 */
	public function mwb_mrbpfw_get_formatted_price( $original_price, $product ) {
		$current_role = $this->mwb_mrbpfw_get_current_role();
		$get_option   = get_option( 'user_setting_' . $current_role[0] );
		if ( isset( $get_option ) && empty( $get_option ) ) {
			return false;
		}
		$rule_apply = get_option( 'mwb_mrbpfw_for_price_rule' );
		if ( $product->is_on_sale() ) {
			$args1               = wp_parse_args(
				array(
					'qty'   => '',
					'price' => $product->get_sale_price(),
				)
			);
			$sale_price_excl_tax = wc_get_price_excluding_tax( $product, $args1 );
			$sale_price_incl_tax = wc_get_price_including_tax( $product, $args1 );
		}
		$args2                  = wp_parse_args(
			array(
				'qty'   => '',
				'price' => $product->get_regular_price(),
			)
		);
		$regular_price_excl_tax = wc_get_price_excluding_tax( $product, $args2 );
		$regular_price_incl_tax = wc_get_price_including_tax( $product, $args2 );

		if ( $product->is_type( 'simple' ) ) {
			$mrbpfw_regular_price_text    = get_option( 'mrbpfw_regular_price_text' );
			$mrbpfw_sale_price_text       = get_option( 'mrbpfw_sale_price_text' );
			$mrbpfw_role_based_price_text = get_option( 'mrbpfw_role_based_price_text' );
			$rule_apply                   = get_option( 'mwb_mrbpfw_for_price_rule' );
			$get_options                  = get_option( 'user_setting_' . $current_role[0] );
			$show_regular_price           = in_array( 'regular_price_' . $current_role[0], $get_options, true );
			$show_sale_price              = in_array( 'on_sale_price_' . $current_role[0], $get_options, true );
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
				if ( isset( $rule_apply ) && 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$sale_price = $sale_price_incl_tax;
					}
					$regular_price = $regular_price_incl_tax;
				} elseif ( isset( $rule_apply ) && 'r_price' === $rule_apply ) {
					if ( $product->is_on_sale() || ! $product->is_on_sale() ) {
						if ( $product->is_on_sale() ) {
							$sale_price = $sale_price_incl_tax;
						}
						$regular_price = $regular_price_incl_tax;
					}
				}
			} else {
				if ( isset( $rule_apply ) && 's_price' === $rule_apply ) {
					if ( $product->is_on_sale() ) {
						$sale_price = $sale_price_excl_tax;
					}
					$regular_price = $regular_price_excl_tax;
				} elseif ( isset( $rule_apply ) && 'r_price' === $rule_apply ) {
					if ( $product->is_on_sale() || ! $product->is_on_sale() ) {
						if ( $product->is_on_sale() ) {
							$sale_price = $sale_price_excl_tax;
						}
						$regular_price = $regular_price_excl_tax;
					}
				}
			}
			$tax_label  = '';
			$tax_enable = get_option( 'woocommerce_calc_taxes' );
			if ( 'yes' === $tax_enable ) {
				if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) ) {
					$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'incl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
				} else {
					$tax_label = apply_filters( 'mwb_mrbpfw_tax_lable', __( 'excl.VAT', 'mwb-role-based-pricing-for-woocommerce' ) );
				}
			}
			if ( isset( $regular_price ) ) {
				$regular_price = apply_filters( 'mwb_mrbpfw_price', $regular_price );
			}
			if ( isset( $sale_price ) ) {
				$sale_price = apply_filters( 'mwb_mrbpfw_price', $sale_price );
			}
			if ( isset( $price ) ) {
				$price = apply_filters( 'mwb_mrbpfw_price', $price );
			}

			$discount = $this->mwb_mrbpfw_discount_availability_for_product( $product );
			$discount = $discount['flag'];
			if ( ! empty( $get_options ) && in_array( 'role_based_price_' . $current_role[0], $get_options, true ) && 'yes' === $discount ) {
				$price = $this->mwb_mrbpfw_role_based_price( $original_price, $product );
				if ( isset( $price['role_price'] ) && $product->is_type( 'simple' ) ) {
					$price = $price['role_price'];
					if ( $price < 0 ) {
						$price = 0;
					}
					if ( 's_price' === $rule_apply ) {
						if ( $product->is_on_sale() ) {
							$format_price = '';
							if ( $show_regular_price ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, true );
							}
							if ( $show_sale_price ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_sale_price_text . ' ' . wc_price( $sale_price ) . ' ' . $tax_label, true );
							}
							$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_role_based_price_text . ' ' . wc_price( $price ) . ' ' . $tax_label, false );
							$format_price  = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
							return $format_price;
						} else {
							if ( $show_regular_price ) {
								$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, false );
								$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
								return $format_price;
							} else {
								return false;
							}
						}
					} elseif ( 'r_price' === $rule_apply ) {
						if ( $product->is_on_sale() ) {
							$format_price = '';
							if ( $show_regular_price ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, true );
							}
							$flag = false;
							if ( $show_sale_price ) {
								$flag = true;
								if ( ! empty( $price ) && $price > $sale_price ) {
									$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_sale_price_text . ' ' . wc_price( $sale_price ) . ' ' . $tax_label, false );
								} else {
									$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_sale_price_text . ' ' . wc_price( $sale_price ) . ' ' . $tax_label, true );
								}
							}
							if ( $flag && isset( $sale_price ) && isset( $price ) && $price > $sale_price ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_role_based_price_text . ' ' . wc_price( $price ) . ' ' . $tax_label, true );
							} else {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_role_based_price_text . ' ' . wc_price( $price ) . ' ' . $tax_label, false );
							}
							$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
							return $format_price;
						} elseif ( ! $product->is_on_sale() ) {
							$format_price = '';
							if ( ! empty( $show_regular_price ) && $show_regular_price ) {
								$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, true );
							}
							$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_role_based_price_text . ' ' . wc_price( $price ) . ' ' . $tax_label, false );
							$format_price  = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
							return $format_price;
						}
					} else {
						return $original_price;
					}
				} else {
					return $original_price;
				}
			} elseif ( $product->is_on_sale() && ! empty( $get_option ) && in_array( 'on_sale_price_' . $current_role[0], $get_option, true ) ) {
				$format_price = '';
				if ( $show_regular_price ) {
					$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, true );
				}
				$format_price .= $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_sale_price_text . ' ' . wc_price( $sale_price ) . ' ' . $tax_label, false );
				$format_price  = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
				return $format_price;
			} elseif ( $product->is_on_sale() && ! empty( $get_option ) && in_array( 'regular_price_' . $current_role[0], $get_option, true ) ) {
				if ( $show_regular_price ) {
					$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, false );
					$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
					return $format_price;
				} else {
					return false;
				}
			} elseif ( ! $product->is_on_sale() && ! empty( $get_option ) && in_array( 'regular_price_' . $current_role[0], $get_option, true ) ) {
				$format_price = $this->mwb_mrbpfw_get_formatted_price_html( $mrbpfw_regular_price_text . ' ' . wc_price( $regular_price ) . ' ' . $tax_label, false );
				$format_price = apply_filters( 'mwb_rbpfw_addon_symbol', $format_price, $product );
				return $format_price;
			} else {
				return false;
			}
		} else {
			return $original_price;
		}
	}

	/**
	 * Tax Label
	 *
	 * @param string $tax_label .
	 */
	public function mwb_mrbpfw_tax_label( $tax_label ) {
		$tax_label_setting = get_option( 'mrbpfw_tax_label' );
		if ( ! empty( $tax_label_setting ) && 'on' == $tax_label_setting ) {
			return false;
		} else {
			return $tax_label;
		}
	}

	/**
	 * Hide the Add to cart button if condition not matched.
	 *
	 * @param boolean $value is boolean.
	 * @param object  $product is the current product.
	 */
	public function mwb_mrbpfw_remove_add_to_cart_button( $value, $product ) {
		$current_role = $this->mwb_mrbpfw_get_current_role();
		$get_options  = get_option( 'user_setting_' . $current_role[0] );
		if ( 'OceanWP' === wp_get_theme()->name && ( empty( $get_options ) || ( ! empty( $get_options ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_options, true ) ) ) ) {
			return false;
		} elseif ( 'Avada' === wp_get_theme()->name && ( empty( $get_options ) || ( ! empty( $get_options ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_options, true ) ) ) ) {
			return false;
		} elseif ( 'Betheme' === wp_get_theme()->name && ( empty( $get_options ) || ( ! empty( $get_options ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_options, true ) ) ) ) {
			return false;
		} elseif ( 'Flatsome' === wp_get_theme()->name && ( empty( $get_options ) || ( ! empty( $get_options ) && ! in_array( 'add_to_cart_' . $current_role[0], $get_options, true ) ) ) ) {
			return false;
		}
		$rule_apply = get_option( 'mwb_mrbpfw_for_price_rule' );
		if ( isset( $get_options ) && ! empty( $get_options ) && ! in_array( 'regular_price_' . $current_role[0], $get_options, true ) && ! in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) && ! in_array( 'role_based_price_' . $current_role[0], $get_options, true ) ) {
			return false;
		} elseif ( ! empty( $current_role ) && ! empty( $get_options ) && 's_price' === $rule_apply && ! $product->is_on_sale() && ( ! in_array( 'regular_price_' . $current_role[0], $get_options, true ) ) ) {
			return false;
		} elseif ( ! empty( $current_role ) && ! empty( $get_options ) && 'r_price' === $rule_apply && ! $product->is_on_sale() && ( ! in_array( 'regular_price_' . $current_role[0], $get_options, true ) && in_array( 'on_sale_price_' . $current_role[0], $get_options, true ) && ! in_array( 'role_based_price_' . $current_role[0], $get_options, true ) ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Subscription compatibility code on the product.
	 *
	 * @param string $price .
	 * @param object $product .
	 */
	public function mwb_rbpfw_addon_symbol( $price, $product ) {
		if ( function_exists( 'mwb_sfw_check_product_is_subscription' ) && mwb_sfw_check_product_is_subscription( $product ) && function_exists( 'mwb_sfw_get_time_interval_for_price' ) ) {
			$mwb_recurring_number   = get_post_meta( $product->get_id(), 'mwb_sfw_subscription_number', true );
			$mwb_recurring_interval = get_post_meta( $product->get_id(), 'mwb_sfw_subscription_interval', true );
			$mwb_price_html         = mwb_sfw_get_time_interval_for_price( $mwb_recurring_number, $mwb_recurring_interval );
			return $price . ' / ' . $mwb_price_html;
		} else {
			return $price;
		}
	}

	/**
	 * Subscription compatibility code on the cart.
	 *
	 * @param string $product_price .
	 * @param object $cart_item .
	 */
	public function mwb_rbpfw_cart_price( $product_price, $cart_item ) {
		$product      = $cart_item['data'];
		$price_change = $this->mwb_mrbpfw_role_based_price( $product_price, $product );
		$price_change = $price_change['role_price'];
		if ( ! empty( $price_change ) ) {
			$current_role = $this->mwb_mrbpfw_get_current_role();
			$get_options  = get_option( 'user_setting_' . $current_role[0] );
			$tax_enable   = get_option( 'woocommerce_calc_taxes' );
			if ( isset( $get_options ) && ! empty( $get_options ) && in_array( 'show_tax_' . $current_role[0], $get_options, true ) && 'yes' === $tax_enable ) {
				$incr = 1;
				if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) && ! is_admin() ) {
					$incr = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $incr );
				}
			} else {
				$incr = 0;
			}
		}
		if ( $price_change < 0 ) {
			$price_change = 0;
		}
		return wc_price( $price_change + $incr );

	}

	/**
	 * Function to convert the price for multicurrency.
	 *
	 * @param string $price .
	 */
	public function mwb_mrbpfw_price( $price ) {
		if ( function_exists( 'mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) && ! is_admin() ) {
			$price = mwb_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
		}
		return $price;
	}
}
