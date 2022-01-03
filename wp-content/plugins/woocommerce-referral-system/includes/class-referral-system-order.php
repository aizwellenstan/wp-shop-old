<?php
/**
 * All order Functionality file.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Referral_System_Order' ) ) {

	/**
	 * Class for order features
	 */
	class Referral_System_Order {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'front_init' ) );
		}

		/**
		 * Trigger functions on WP Initialize frontend
		 */
		public function front_init() {

			add_action( 'woocommerce_thankyou', array( $this, 'thank_you_page_based_actions' ), 10, 1 );

			if ( ! is_user_logged_in() ) {
				WC()->session = new WC_Session_Handler();
				WC()->session->init();
			}
		}

		/**
		 * Function gets called when a succesfull purchase happens
		 * If admin defines the setting for the purchase action
		 * then this function will be triggered and check further
		 * to whom, the coupon will be given.
		 *
		 * @param int $order_id order_id.
		 */
		public function thank_you_page_based_actions( $order_id ) {

			if ( ! empty( get_post_meta( $order_id, '_wcrs_referrer', false ) ) ) {
				return;
			}

			$order = new WC_Order( $order_id );

			if ( ! in_array( $order->get_status(), get_option( 'wcrs_eligible_status' ) ) ) {
				return;
			}

			$user_id = $order->get_user_id() != null ? $order->get_user_id() : false;

			$order_count_of_user = wc_get_customer_order_count( $user_id );

			$associated_orders = 0;
			$limit_exceed      = false;

			if ( get_option( 'wcrs_functionality_type' ) == 'wcrs-func' ) {
				$max_order_allowed_limit = get_option( 'wcrs_valid_no_orders' );
			} else {

				$max_order_allowed_limit = get_option( 'wcrspr_valid_no_orders' );
			}

			if ( is_user_logged_in() ) {
				$customer_orders = get_posts(
					array(
						'numberposts' => -1,
						'meta_key'    => '_customer_user',
						'meta_value'  => get_current_user_id(),
						'post_type'   => wc_get_order_types(),
						'post_status' => array_keys( wc_get_order_statuses() ),
					)
				);

				if ( ! empty( $customer_orders ) ) {

					foreach ( $customer_orders as $order_post ) {
						$order = wc_get_order( $order_post->ID );
						foreach ( $order->get_meta_data() as $meta_data ) {
							if ( '_wcrs_referrer' == $meta_data->get_data()['key'] ) {

								$associated_orders++;

								if ( $associated_orders >= $max_order_allowed_limit ) {
									$limit_exceed = true;

									/*
									 @name: wrs_valid_order_limit_reach
									 @desc: Runs once limit of order set by admin reached.
									 @param: (Array) $order order object.
									 @package: codup-wc-referral-system
									 @module: frontend
									 @type: action
									*/
									do_action( 'wrs_valid_order_limit_reach', $order );

									return;
								}
							}
						}
					}
				}
			}

			$disable_order_for_referrer = false;
			$disable_order_for_referee  = false;

			$order = new WC_Order( $order_id );

			if ( get_option( 'wcrs_functionality_type' ) == 'wcrs-func' ) {
				if ( get_option( 'wcrs_action_purchase_type' ) != 'no' ) :

					if ( 'yes' == get_option( 'wcrs_minimum_purchase_limit_enable' ) ) {

						$minimum_limit_amount = get_option( 'wcrs_minimum_purchase_limit' );

						if ( $order->get_subtotal() < $minimum_limit_amount ) {
							return;
						}
					}

					if ( ! $user_id ) {
						/**
						 * If referee didn't created the account.
						 */
						$referee_email = $order->get_data()['billing']['email'];
					} else {
						/**
						 * If referee created the account.
						 */
						$referee_email = get_user_by( 'id', $user_id )->data->user_email;
					}
					/**
					 * Check if user is actually referred by someone.
					 */
					$referrer = wcrs_get_referrer_name_from_cookie( $user_id );

					if ( $referrer ) {

						$referrer_user = wcrs_get_user( $referrer );

						if ( true == $limit_exceed || empty( $referrer_user ) ) {
							return;
						}

						$order = new WC_Order( $order_id );

						if ( 'on' == get_user_meta( $referrer_user->ID, 'wcrs_disable_order_for_user', true ) ) {
							$disable_order_for_referrer = true;
						}

						/**
						 * If referrer gets the coupon on purchase action.
						 */
						if ( false == $disable_order_for_referrer && 'yes' == get_option( 'wcrs_action_purchase_referral' ) && false == $limit_exceed ) {
							/*
							 @name: wrs_before_order_reward_referrer
							 @desc: Before referrer gets the coupon on purchase action.
							 @param: (Array) $order order object.
							 @param: (Int) $referrer_user->data->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_before_order_reward_referrer', $order, $referrer_user->data->ID );

							$reason = __( 'Referrer gets coupon on purchase of its referee.' );

							$discount_for_referral_admin_provided = ( get_option( 'wcrs_action_purchase_referral_coupon_amount' ) != null ) ? get_option( 'wcrs_action_purchase_referral_coupon_amount' ) : 0;

							if ( 'percent' == get_option( 'cwrs_reward_type' ) ) {

								$percetage_set_for_total              = get_option( 'wcrs_action_purchase_referral_coupon_amount' );
								$discount_for_referral_admin_provided = ( $order->get_subtotal() / 100 ) * $percetage_set_for_total;
							}

							$expiry_date_for_referral_admin_provided = get_converted_date( 'wcrs_action_purchase_referral_coupon_expiry' );

							$referrer_email = $referrer_user->user_email;

							$discount_type = wcrs_get_order_discount_type();
							$coupon_id     = wcrs_generate_coupon( $discount_type, $discount_for_referral_admin_provided, $expiry_date_for_referral_admin_provided, $referrer_email, $reason ); // to be cchanged.
							$coupon_code   = get_the_title( $coupon_id );
							update_post_meta( $order_id, '_wcrs_referrer', $referrer_user->data->ID );
							Referral_System_Core::send_coupon_discount_email( $referrer_user->data, $coupon_code, 'referrer_purchase', $discount_type );

							/*
							 @name: wrs_after_order_reward_referrer
							 @desc: After referrer gets the coupon on purchase action.
							 @param: (Array) $order order object.
							 @param: (Int) $referrer_user->data->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_after_order_reward_referrer', $order, $referrer_user->data->ID );
						}

						if ( 'on' == get_user_meta( $user_id, 'wcrs_disable_order_for_user', true ) ) {
							$disable_order_for_referee = true;
						}

						/**
						 * If referee gets the coupon on purchase action.
						 */
						if ( false == $disable_order_for_referee && 'yes' == get_option( 'wcrs_action_purchase_referee' ) && false == $limit_exceed ) {

							$referee_user = get_userdata( $user_id );

							/*
							 @name: wrs_before_order_reward_referee
							 @desc: Before referee gets the coupon on purchase action.
							 @param: (Array) $order order object.
							 @param: (Int) $referee_user->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_before_order_reward_referee', $order, $referee_user->ID );

							$reason = __( 'Referee gets coupon on purchase' );

							$discount_for_referee_admin_provided = ( get_option( 'wcrs_action_purchase_referee_coupon_amount' ) != null ) ? get_option( 'wcrs_action_purchase_referee_coupon_amount' ) : 0;

							if ( 'percent' == get_option( 'cwrs_reward_type' ) ) {

								$percetage_set_for_total             = get_option( 'wcrs_action_purchase_referee_coupon_amount' );
								$discount_for_referee_admin_provided = round( ( $order->get_subtotal() / 100 ) * $percetage_set_for_total );
							}

							$expiry_date_for_referee_admin_provided = get_converted_date( 'wcrs_action_purchase_referee_coupon_expiry' );
							$discount_type                          = wcrs_get_order_discount_type();
							$coupon_id                              = wcrs_generate_coupon( $discount_type, $discount_for_referee_admin_provided, $expiry_date_for_referee_admin_provided, $referee_email, $reason ); // to be cchanged.
							$coupon_code                            = get_the_title( $coupon_id );

							update_post_meta( $order_id, '_wcrs_referrer', $referrer_user->data->ID );
							Referral_System_Core::send_coupon_discount_email( get_user_by( 'email', $referee_email ), $coupon_code, 'referee_purchase', $discount_type );

							/*
							 @name: wrs_after_order_reward_referee
							 @desc: After referee gets the coupon on purchase action.
							 @param: (Array) $order order object.
							 @param: (Int) $referee_user->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_after_order_reward_referee', $order, $referee_user->ID );
						}
					}
				endif;
			} else {

				$order = new WC_Order( $order_id );

				if ( 'yes' == get_option( 'wcrspr_minimum_purchase_limit_enable' ) ) {

					$minimum_limit_amount = get_option( 'wcrspr_minimum_purchase_limit' );

					if ( $order->get_subtotal() < $minimum_limit_amount ) {
						return;
					}
				}

				if ( 'wcpr-integration-purchase' != get_option( 'wcrs_integration_type_for_purchase' ) && false == $limit_exceed ) {

					if ( 'no' != get_option( 'wcrs_int_purchase_type' ) ) {

						if ( get_userdata( $user_id ) ) {
							$referee_user  = get_userdata( $user_id );
							$referrer      = wcrs_get_referrer_name_from_cookie( $user_id );
							$referrer_user = wcrs_get_user( $referrer );

							if ( null == $referrer ) {
								return;
							} else {

								if ( 'on' == get_user_meta( $referrer_user->data->ID, 'wcrs_disable_order_for_user', true ) ) {
									$disable_order_for_referrer = true;
								}

								if ( false == $disable_order_for_referrer && get_option( 'wcrs_int_order_referrer' ) != '' ) {

									$points_referrer = get_option( 'wcrs_int_order_referrer' );

									if ( 'percent' == get_option( 'wcrspr_reward_type' ) ) {

										$percetage_set_for_total = get_option( 'wcrs_int_order_referrer' );
										$points_referrer         = round( $order->get_subtotal() / 100 * $percetage_set_for_total );
									}

									/*
									 @name: wrs_before_reward_referrer
									 @desc: Before referrer gets the points on purchase action.
									 @param: (Array) $order order object.
									 @param: (Int) $referrer_user->data->ID user id.
									 @package: codup-wc-referral-system
									 @module: frontend
									 @type: action
									*/
									do_action( 'wrs_before_reward_referrer', $order, $referrer_user->data->ID );
									WC_Points_Rewards_Manager::increase_points( $referrer_user->data->ID, $points_referrer, 'order' );
									update_post_meta( $order_id, '_wcrs_referrer', $referrer_user->data->ID );

									/*
									 @name: wrs_after_reward_referrer
									 @desc: After referrer gets the points on purchase action.
									 @param: (Array) $order order object.
									 @param: (Int) $referrer_user->data->ID user id.
									 @package: codup-wc-referral-system
									 @module: frontend
									 @type: action
									*/
									do_action( 'wrs_after_reward_referrer', $order, $referrer_user->data->ID );
								}

								if ( 'on' == get_user_meta( $referrer_user->data->ID, 'wcrs_disable_order_for_user', true ) ) {
									$disable_order_for_referrer = true;
								}

								if ( false == $disable_order_for_referrer && get_option( 'wcrs_int_order_referee' ) != '' ) {
									$points_referee = get_option( 'wcrs_int_order_referee' );

									if ( 'percent' == get_option( 'wcrspr_reward_type' ) ) {

										$percetage_set_for_total = get_option( 'wcrs_int_order_referee' );
										$points_referee          = round( $order->get_subtotal() / 100 * $percetage_set_for_total );
									}

									/*
									 @name: wrs_before_reward_referee
									 @desc: Before referee gets the points on purchase action.
									 @param: (Array) $order order object.
									 @param: (Int) $referee_user->ID user id.
									 @package: codup-wc-referral-system
									 @module: frontend
									 @type: action
									*/
									do_action( 'wrs_before_reward_referee', $order, $referee_user->ID );

									WC_Points_Rewards_Manager::increase_points( $referee_user->ID, $points_referee, 'order' );
									update_post_meta( $order_id, '_wcrs_referrer', $referrer_user->data->ID );

									/*
									 @name: wrs_after_reward_referee
									 @desc: After referee gets the points on purchase action.
									 @param: (Array) $order order object.
									 @param: (Int) $referee_user->ID user id.
									 @package: codup-wc-referral-system
									 @module: frontend
									 @type: action
									*/
									do_action( 'wrs_after_reward_referee', $order, $referee_user->ID );
								}
							}
						}
					}
				}
			}
		}

	}

	new Referral_System_Order();
}
