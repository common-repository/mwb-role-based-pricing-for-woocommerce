<?php
/**
 * Provide a admin area view of the Price rule table
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.2.0
 *
 * @package    Mwb_Role_Based_Pricing_For_Woocommerce
 * @subpackage Mwb_Role_Based_Pricing_For_Woocommerce/admin/partials
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Class to resister the price rule table
 */
class Mwb_Mrbpfw_Price_Rule extends WP_List_Table {
	/**
	 * Store total price rule count.
	 *
	 * @var int
	 */
	public $mwb_total_count;
	/**
	 * Show the column
	 */
	public function get_columns() {
		$column_meta = array(
			'cb'                       => '<input type="checkbox" />',
			'mwb_mrbpfw_title'         => __( 'Title', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_role'          => __( 'Role', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_rule_type'     => __( 'Rule Type', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_discount_type' => __( 'Discount Type', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_price'         => __( 'Price', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_priority'      => __( 'Priority', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_enable_rule'   => __( 'Enable/Disable', 'mwb-role-based-pricing-for-woocommerce' ),
			'mwb_mrbpfw_published'     => __( 'Published', 'mwb-role-based-pricing-for-woocommerce' ),
		);
		return apply_filters( 'mwb_mrbpfw_add_extra_column', $column_meta );
	}

	/**
	 * Column data
	 *
	 * @param array  $item .
	 * @param string $column_name .
	 */
	public function column_default( $item, $column_name ) {
		$post_id       = $item['mwb_mrbpfw_role_id'];
		$title         = $item['mwb_mrbpfw_title'];
		$enable_rule   = get_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', true );
		$role          = get_post_meta( $post_id, 'mwb_mrbpfw_role', true );
		$rule_type     = get_post_meta( $post_id, 'mwb_mrbpfw_rule_type', true );
		$discount_type = get_post_meta( $post_id, 'mwb_mrbpfw_discount_type', true );
		$price         = get_post_meta( $post_id, 'mwb_mrbpfw_price', true );
		$priority      = get_post_meta( $post_id, 'mwb_mrbpfw_priority', true );
		$column_name   = apply_filters( 'mwb_add_extra_column_value', $column_name );
		switch ( $column_name ) {
			case 'mwb_mrbpfw_title':
				$url  = esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule&post_id=' . $post_id );
				$link = '<br><a href=' . $url . '>' . esc_html__( 'Edit', 'mwb-role-based-pricing-for-woocommerce' ) . '</a>';
				return esc_html( $title ) . $link;
			case 'mwb_mrbpfw_role':
				return esc_html( ucfirst( $role ) );
				break;
			case 'mwb_mrbpfw_rule_type':
				if ( 'all_products' === esc_html( $rule_type ) ) {
					return esc_html__( 'All Products', 'mwb-role-based-pricing-for-woocommerce' );
				} elseif ( 'selected_products' === esc_html( $rule_type ) ) {
					return esc_html__( 'Selected Products', 'mwb-role-based-pricing-for-woocommerce' );
				} elseif ( 'categories' === esc_html( $rule_type ) ) {
					return esc_html__( 'Categories', 'mwb-role-based-pricing-for-woocommerce' );
				} else {
					return esc_html__( 'Tags', 'mwb-role-based-pricing-for-woocommerce' );
				}
			case 'mwb_mrbpfw_discount_type':
				return ( 'fixed' === esc_html( $discount_type ) ) ? esc_html__( 'Fixed', 'mwb-role-based-pricing-for-woocommerce' ) : esc_html__( 'Percentage', 'mwb-role-based-pricing-for-woocommerce' );
			case 'mwb_mrbpfw_price':
				return esc_html( $price );
			case 'mwb_mrbpfw_priority':
				return esc_html( $priority );
			case 'mwb_mrbpfw_enable_rule':
				if ( 'on' === $enable_rule ) {
					return '<div class="mwb-switch"><input class="rule_enable mwb-switch-checkbox" type="checkbox" value=' . esc_html( $post_id ) . ' checked></div>';
				} else {
					return '<div class="mwb-switch"><input class="rule_enable mwb-switch-checkbox" type="checkbox" value=' . esc_html( $post_id ) . '></div>';
				}
			case 'mwb_mrbpfw_published':
				return esc_html( get_the_time( 'Y-m-d', $post_id ) );
			default:
		}
	}

	/**
	 * Get the price rule data
	 */
	public function mwb_mrbpfw_get_price_rule_list() {
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'mrbpfw_price_rules',
		);

		if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
			$data               = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
			$args['meta_query'] = array(
				array(
					'key'     => 's',
					'value'   => $data,
					'compare' => 'LIKE',
				),
			);
		}

		$mwb_mrbpfw_price_rules = get_posts( $args );
		$mwb_mrbpfw_data        = array();
		$total_count            = count( $mwb_mrbpfw_price_rules );
		if ( isset( $mwb_mrbpfw_price_rules ) && ! empty( $mwb_mrbpfw_price_rules ) && is_array( $mwb_mrbpfw_price_rules ) ) {
			foreach ( $mwb_mrbpfw_price_rules as $key => $value ) {
				$post_id       = $value->ID;
				$title         = $value->post_title;
				$role          = get_post_meta( $post_id, 'mwb_mrbpfw_role', true );
				$rule_type     = get_post_meta( $post_id, 'mwb_mrbpfw_rule_type', true );
				$discount_type = get_post_meta( $post_id, 'mwb_mrbpfw_discount_type', true );
				$price         = get_post_meta( $post_id, 'mwb_mrbpfw_price', true );
				$priority      = get_post_meta( $post_id, 'mwb_mrbpfw_priority', true );
				$enable_rule   = get_post_meta( $post_id, 'mwb_mrbpfw_enable_rule', true );
				$mwb_mrbpfw_data[] = apply_filters(
					'mwb_mrbpfw_price_rule_table_data',
					array(
						'mwb_mrbpfw_title'         => $title,
						'mwb_mrbpfw_role_id'       => $post_id,
						'mwb_mrbpfw_role'          => $role,
						'mwb_mrbpfw_rule_type'     => $rule_type,
						'mwb_mrbpfw_discount_type' => $discount_type,
						'mwb_mrbpfw_price'         => $price,
						'mwb_mrbpfw_priority'      => $priority,
						'mwb_mrbpfw_enable_rule'   => $enable_rule,
					)
				);
			}
		}
		$this->mwb_total_count = $total_count;
		return $mwb_mrbpfw_data;
	}

	/**
	 * Add some bulk action
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'mwb-role-based-pricing-for-woocommerce' ),
		);
		return apply_filters( 'mwb_mrbpfw_bulk_option', $actions );
	}

	/**
	 * Perform admin bulk action setting for price rule table.
	 */
	public function process_bulk_action() {
		$flag = false;
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['price_rule_list_table'] ) ) {
				$price_rule_list_table = sanitize_text_field( wp_unslash( $_POST['price_rule_list_table'] ) );
				if ( wp_verify_nonce( $price_rule_list_table, 'price_rule_list_table' ) ) {
					if ( isset( $_POST['mwb_mrbpfw_ids'] ) && ! empty( $_POST['mwb_mrbpfw_ids'] ) ) {
						$all_id = map_deep( wp_unslash( $_POST['mwb_mrbpfw_ids'] ), 'sanitize_text_field' );
						foreach ( $all_id as $key => $value ) {
							$flag = true;
							wp_delete_post( $value, true );
						}
					}
				}
			}
		}
		if ( $flag ) {
			?>
			<div class="notice notice-success is-dismissible"> 
				<p><?php esc_html_e( 'Price Rule Deleted Successfully', 'mwb-role-based-pricing-for-woocommerce' ); ?></p>
			</div>
			<?php
		}
		do_action( 'mwb_mrbpfw_process_bulk_reset_option', $this->current_action(), $_POST );

	}

	/**
	 * Add checkboxes.
	 *
	 * @param array $item .
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="mwb_mrbpfw_ids[]" value="%s" />',
			$item['mwb_mrbpfw_role_id']
		);
	}

	/**
	 * Sort the table
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'mwb_mrbpfw_title'   => array( 'mwb_mrbpfw_title', false ),
		);
		return $sortable_columns;
	}

	/**
	 * Prepare the items to be displayed
	 */
	public function prepare_items() {
		$per_page     = 10;
		$columns      = $this->get_columns();
		$hidden       = array();
		$sortable     = $this->get_sortable_columns();
		$data         = $this->mwb_mrbpfw_get_price_rule_list();
		$current_page = $this->get_pagenum();
		$total_items  = $this->mwb_total_count;
		usort( $data, array( $this, 'mwb_mrbpfw_usort_price_rule' ) );
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->process_bulk_action();
		$this->items           = $data;
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}
	/**
	 * Return sorted associative array.
	 *
	 * @param array $cloumna column of the price rule.
	 * @param array $cloumnb column of the price rule.
	 */
	public function mwb_mrbpfw_usort_price_rule( $cloumna, $cloumnb ) {
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'mwb_mrbpfw_title';
		$order   = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'desc';

		if ( is_numeric( $cloumna[ $orderby ] ) && is_numeric( $cloumnb[ $orderby ] ) ) {
			if ( $cloumna[ $orderby ] == $cloumnb[ $orderby ] ) {
				return 0;
			} elseif ( $cloumna[ $orderby ] < $cloumnb[ $orderby ] ) {
				$result = -1;
				return ( 'asc' === $order ) ? $result : -$result;
			} elseif ( $cloumna[ $orderby ] > $cloumnb[ $orderby ] ) {
				$result = 1;
				return ( 'asc' === $order ) ? $result : -$result;
			}
		} else {
			$result = strcmp( $cloumna[ $orderby ], $cloumnb[ $orderby ] );
			return ( 'asc' === $order ) ? $result : -$result;
		}
	}

}
?>
<div class="wrap post-type-mrbpfw_price_rules">
<?php
if ( ! isset( $_GET['post_id'] ) ) {
	?>
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Price Rules List', 'mwb-role-based-pricing-for-woocommerce' ); ?></h1>
	<a class="page-title-action" href="<?php echo esc_html( admin_url() . 'admin.php?page=mwb_role_based_pricing_for_woocommerce_menu&mrbpfw_tab=mwb-mrbpfw-price-rule&post_id=0' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'mwb-role-based-pricing-for-woocommerce' ); ?></a>
	<hr/>
	<form method="post" >
		<input type="hidden" name="page" value="price_rule_list_table">
		<?php wp_nonce_field( 'price_rule_list_table', 'price_rule_list_table' ); ?>
		<?php
		$mwb_mrbpfw_list = new Mwb_Mrbpfw_Price_Rule();
		$mwb_mrbpfw_list->prepare_items();
		$mwb_mrbpfw_list->search_box( __( 'Search Price Rule', 'mwb-role-based-pricing-for-woocommerce' ), 'mwb_mrbpfw_price_rule' );
		$mwb_mrbpfw_list->display();
		?>
	</form>
	<?php
} else {
	require_once MWB_ROLE_BASED_PRICING_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-mrbpfw-metafields.php';
}
?>
</div>
