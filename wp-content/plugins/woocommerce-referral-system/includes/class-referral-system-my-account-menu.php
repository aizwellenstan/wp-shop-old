<?php
/**
 * All order Functionality file.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Referral_System_My_Account_Menu' ) ) {

	/**
	 * Class for order features
	 */
	class Referral_System_My_Account_Menu {

		public $endpoints = array( 'referral-link', 'my-coupons' );

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'add_referral_link_endpoint' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'referral_link_tab' ) );
			add_action( 'woocommerce_account_referral-link_endpoint', array( $this, 'referral_link_tab_content' ) );

			add_action( 'init', array( $this, 'add_my_coupons_endpoint' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'my_coupons_tab' ) );
			add_action( 'woocommerce_account_my-coupons_endpoint', array( $this, 'my_coupons_tab_content' ) );

			add_action( 'wp', array( $this, 'front_init' ) );

			add_filter( 'woocommerce_get_query_vars', array( $this, 'endpoint_query_vars' ), 0 );
		}

		/**
		 * Setting query vars for endpoints.
		 *
		 * @param array $vars vars.
		 */
		public function endpoint_query_vars( $vars ) {

			foreach ( $this->endpoints as $value ) {
				$vars[ $value ] = $value;
			}

				return $vars;
		}

		/**
		 * Trigger functions on WP Initialize frontend
		 */
		public function front_init() {

			add_action( 'woocommerce_thankyou', array( $this, 'modify_thankyou_page' ), 10, 1 );
		}

		/**
		 * Add sharing div on thank you page.
		 *
		 * @param int $order_id order_id.
		 */
		public function modify_thankyou_page( $order_id ) {
			if ( is_user_logged_in() ) {
				$referral_link = wcrs_get_current_referral_url();
				?>
				<div class="wcrs_thankyou_wrapper">
					<span>
						<?php wc_get_template( '/frontend/referral-link-content.php', array( 'referral_link' => $referral_link ), 'woocommerce-referral-system', WCRS_TEMP_DIR ); ?>
					</span>
				</div>
				<?php
			}
		}

		/**
		 * Adding Referral Link tab to my account page.
		 */
		public function add_referral_link_endpoint() {
			add_rewrite_endpoint( 'referral-link', EP_ALL );
		}

		/**
		 * Adding Referral Link tab on my account page menu.
		 *
		 * @param array $items items.
		 */
		public function referral_link_tab( $items ) {
			$items['referral-link'] = __( 'Referral Link', 'codup-wc-referral-system' );
			return $items;
		}

		/**
		 * Add Referral Link tab content on my account page.
		 */
		public function referral_link_tab_content() {
			$referral_link = wcrs_get_current_referral_url();

			wc_get_template( '/frontend/referral-link-content.php', array( 'referral_link' => $referral_link ), 'woocommerce-referral-system', WCRS_TEMP_DIR );
		}

		/**
		 * Make end-point for My Coupons.
		 */
		public function add_my_coupons_endpoint() {
			add_rewrite_endpoint( 'my-coupons', EP_ALL );
		}

		/**
		 * Adding My Coupons tab on my account page menu.
		 *
		 * @param array $items items.
		 */
		public function my_coupons_tab( $items ) {
			$items['my-coupons'] = __( 'My Coupons', 'codup-wc-referral-system' );
			return $items;
		}

		/**
		 * Add My Coupons tab content on my account page.
		 */
		public function my_coupons_tab_content() {
			wc_get_template( '/frontend/my-coupons-content.php', array(), 'woocommerce-referral-system', WCRS_TEMP_DIR );
		}

	}

	new Referral_System_My_Account_Menu();
}
