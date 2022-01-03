<?php
/**
 * All order Functionality file.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Referral_System_Signup' ) ) {

	/**
	 * Class for order features
	 */
	class Referral_System_Signup {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'intialize' ) );

			add_action( 'wp', array( $this, 'logging' ), 10 );
			add_action( 'wp', array( $this, 'save_user_visitor_id_on_login' ), 20 );

			add_action( 'user_register', array( $this, 'register_visitor_id_on_signup' ) );
			add_action( 'user_register', array( $this, 'new_customer_created' ), 10, 1 );
		}

		/**
		 * Trigger functions on WP Initialize
		 */
		public function intialize() {
			add_action( 'template_redirect', array( $this, 'redirect' ) );
		}

		/**
		 * Trigger redirect logic .
		 */
		public function redirect() {

			$wcrs_slug = get_option( 'wcrs_slug' );
			if ( ! $wcrs_slug ) {
				return;
			}
			$wcrs_type = get_option( 'wcrs_type' );
			if ( ! $wcrs_type ) {
				return;
			}

			if ( ! is_user_logged_in() && ! empty( $wcrs_slug ) && isset( $_GET[ $wcrs_slug ] ) ) {
				$wcrs_cookie = $this->save_visitor_id( sanitize_text_field( wp_unslash( $_GET[ $wcrs_slug ] ) ) );

				$url = wcrs_get_redirect_url();

				if ( $url ) {
					wp_safe_redirect( $url );
					exit();
				}
			}

		}

		/**
		 * When user first time hit the URL, it saves the guest visitor id in cookie.
		 *
		 * @param string $referrer referrer.
		 */
		public static function save_visitor_id( $referrer ) {

			$wcrs_cookie = wcrs_get_cookie();

			if ( empty( $wcrs_cookie['wcrs_visitor_id'] ) ) {
				$visitors_id = uniqid();
				wcrs_set_cookie( $visitors_id, $referrer );
			}

			return wcrs_get_cookie();
		}

		/**
		 * Save referral logs in database.
		 */
		public function logging() {
			$wcrs_cookie = wcrs_get_cookie();

			if ( ! is_user_logged_in() && ! empty( $wcrs_cookie ) ) {

				if ( ! empty( $wcrs_cookie['wcrs_visitor_id'] ) ) {

					$created_datetime = current_time( 'mysql' );

					// Get Referrer URL.
					if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
						$referrer_url = filter_input( INPUT_SERVER, 'HTTP_REFERER' );
					} else {
						$referrer_url = 'unknown';
					}

					// Get country from Remote IP Address.
					$country = wcrs_get_country_by_ip();

					// Get landing url.
					$landing_url = filter_input( INPUT_SERVER, 'HTTP_HOST' ) . filter_input( INPUT_SERVER, 'REQUEST_URI' );

					// Get current username if user logged in.
					$username = null;
					if ( wp_get_current_user() ) {
						$username = wp_get_current_user()->user_login;
					}

					// Insert Logs.
					$logs = array(
						'login_username' => $username,
						'IP'             => filter_input( INPUT_SERVER, 'REMOTE_ADDR' ),
						'Country'        => $country,
						'time'           => $created_datetime,
						'created_at'     => $created_datetime,
						'updated_at'     => $created_datetime,
						'referrer'       => wcrs_get_user( $wcrs_cookie['wcrs_referrer'] )->user_login,
						'visitor_id'     => $wcrs_cookie['wcrs_visitor_id'],
						'landing_url'    => $landing_url,
						'referrer_url'   => $referrer_url,
						'keytype'        => get_option( 'wcrs_type' ),
					);

					if ( ! empty( $logs ) ) {
						wcrs_insert_logs( $logs );
					}
				}
			}

		}

		/**
		 * When a user is logged in replace visitor id with user's visitor id.
		 * If visitor id is not available in user meta then save vistor id in user meta
		 */
		public function save_user_visitor_id_on_login() {

			if ( is_user_logged_in() ) {

				$user = wp_get_current_user();

				global $wpdb;

				$user_visitor_id = get_user_meta( $user->ID, 'visitors_id', true );

				if ( empty( $user_visitor_id ) ) {

					$wcrs_cookie = wcrs_get_cookie();

					$cookie_visitor_id = isset( $wcrs_cookie['wcrs_visitor_id'] ) ? $wcrs_cookie['wcrs_visitor_id'] : '';

					update_user_meta( $user->ID, 'visitors_id', $cookie_visitor_id );

					$user_visitor_id = get_user_meta( $user->ID, 'visitors_id', true );

					$update_date = array(
						'visitor_id'     => $cookie_visitor_id,
						'login_username' => $user->user_login,
						'updated_at'     => current_time( 'mysql' ),
					);
					wcrs_update_log( $update_date, array( 'visitor_id' => $user_visitor_id ) );

					$cookie_refferer_id = isset( $wcrs_cookie['wcrs_referrer'] ) ? $wcrs_cookie['wcrs_referrer'] : '';
					$referrer_user      = wcrs_get_user( $cookie_refferer_id );

					if ( ! empty( $referrer_user->ID ) ) {
						update_user_meta( $user->ID, 'referrer_user', $referrer_user->ID );
					}
				} else {
					wcrs_set_cookie( null );
				}
			}

		}

		/**
		 * On Sign Up it saves visitor id to user meta
		 *
		 * @param int $user_id user_id.
		 */
		public function register_visitor_id_on_signup( $user_id ) {
			update_user_meta( $user_id, '_first_purchase', '0' );
		}

		/**
		 * Function called when a new customer/referee gets created to insert
		 * If admin defined the setting for action type signup it will be triggered
		 * Checks if referral or referee gets the coupon from admin panel settings
		 * and sends it if the settings are enabled
		 *
		 * @param int $customer_id customer_id.
		 */
		public function new_customer_created( $customer_id ) {

			$wcrs_get_cookie = wcrs_get_cookie();
			$referrer        = $wcrs_get_cookie['wcrs_referrer'];

			$disable_for_referrer = false;
			$disable_for_referee  = false;

			if ( get_option( 'wcrs_functionality_type' ) == 'wcrs-func' ) {
				if ( get_option( 'wcrs_action_signup_type' ) == 'yes' ) :

					if ( null == $referrer ) {
						update_user_meta( $customer_id, '_first_purchase', '0' );
						return;
					} else {

						$referrer_user = wcrs_get_user( $referrer );

						if ( 'on' == get_user_meta( $referrer_user->ID, 'wcrs_disable_signup_for_user', true ) ) {
							$disable_for_referrer = true;
						}

						if ( false == $disable_for_referrer && get_option( 'wcrs_action_signup_referral' ) != 'no' ) {
							$reason                                  = __( 'Referrer gets coupon on Sign up of its referee.' );
							$discount_for_referral_admin_provided    = ( get_option( 'wcrs_action_signup_referral_coupon_amount' ) != null ) ? get_option( 'wcrs_action_signup_referral_coupon_amount' ) : 0;
							$expiry_date_for_referral_admin_provided = get_converted_date( 'wcrs_action_signup_referral_coupon_expiry' );
							$discount_type                           = wcrs_get_signup_discount_type();

							$referrer_email = $referrer_user->data->user_email;

							$coupon_id = wcrs_generate_coupon( $discount_type, $discount_for_referral_admin_provided, $expiry_date_for_referral_admin_provided, $referrer_email, $reason ); // to be cchanged.

							$coupon_code = get_the_title( $coupon_id );

							/*
							 @name: wrs_before_signup_coupon_reward_referrer
							 @desc: Runs before signup coupon reward referrer.
							 @param: (Int) $referrer_user->data->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_before_signup_coupon_reward_referrer', $referrer_user->ID );

							Referral_System_Core::send_coupon_discount_email( $referrer_user->data, $coupon_code, 'referrer_signup', $discount_type );

							/*
							 @name: wrs_after_signup_coupon_reward_referrer
							 @desc: Runs After signup coupon reward referrer.
							 @param: (Int) $referrer_user->data->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_after_signup_coupon_reward_referrer', $referrer_user->ID );
						}

						if ( 'on' == get_user_meta( $customer_id, 'wcrs_disable_signup_for_user', true ) ) {
							$disable_for_referee = true;
						}

						if ( false == $disable_for_referee && get_option( 'wcrs_action_signup_referee' ) != 'no' ) {

							$reason = __( 'Referee gets coupon on Sign Up.' );

							$discount_for_referee_admin_provided    = ( get_option( 'wcrs_action_signup_referee_coupon_amount' ) != '' ) ? get_option( 'wcrs_action_signup_referee_coupon_amount' ) : 0;
							$expiry_date_for_referee_admin_provided = get_converted_date( 'wcrs_action_signup_referee_coupon_expiry' );
							$discount_type                          = wcrs_get_signup_discount_type();
							$referee_user                           = get_userdata( $customer_id );
							$user_email                             = $referee_user->user_email;

							$coupon_id   = wcrs_generate_coupon( $discount_type, $discount_for_referee_admin_provided, $expiry_date_for_referee_admin_provided, $user_email, $reason ); // to be cchanged.
							$coupon_code = get_the_title( $coupon_id );

							/*
							 @name: wrs_before_signup_coupon_reward_referee
							 @desc: Runs before signup coupon reward referee.
							 @param: (Int) $referee_user->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_before_signup_coupon_reward_referee', $referee_user->ID );

							Referral_System_Core::send_coupon_discount_email( $referee_user, $coupon_code, 'referee_signup', $discount_type );

							/*
							 @name: wrs_after_signup_coupon_reward_referee
							 @desc: Runs After signup coupon reward referee.
							 @param: (Int) $referee_user->ID user id.
							 @package: codup-wc-referral-system
							 @module: frontend
							 @type: action
							*/
							do_action( 'wrs_after_signup_coupon_reward_referee', $referee_user->ID );

						}
					}
				endif;
			} else {
				if ( get_option( 'wcrs_integration_type_for_purchase' ) != 'wcpr-integration-purchase' ) {
					if ( get_option( 'wcrs_int_signup_type' ) != 'no' ) {
						$referee_user = get_userdata( $customer_id );
						if ( null == $referrer ) {
							update_user_meta( $customer_id, '_first_purchase', '0' );
							return;
						} else {
							$referrer_user = wcrs_get_user( $referrer );
							if ( 'on' == get_user_meta( $referrer_user->ID, 'wcrs_disable_signup_for_user', true ) ) {
									$disable_for_referrer = true;
							}

							if ( false == $disable_for_referrer && get_option( 'wcrs_int_signup_referrer' ) != '' ) {

								$points_referrer = get_option( 'wcrs_int_signup_referrer' );

								/*
								 @name: wrs_before_signup_points_reward_referrer
								 @desc: Runs before signup points reward referrer.
								 @param: (Int) $referrer_user->data->ID user id.
								 @package: codup-wc-referral-system
								 @module: frontend
								 @type: action
								*/
								do_action( 'wrs_before_signup_points_reward_referrer', $referrer_user->ID );

								WC_Points_Rewards_Manager::increase_points( $referrer_user->ID, intval( $points_referrer ), 'referrer-signup' );

								/*
								 @name: wrs_after_signup_points_reward_referrer
								 @desc: Runs After signup points reward referrer.
								 @param: (Int) $referrer_user->data->ID user id.
								 @package: codup-wc-referral-system
								 @module: frontend
								 @type: action
								*/
								do_action( 'wrs_after_signup_points_reward_referrer', $referrer_user->ID );

							}

							if ( 'on' == get_user_meta( $customer_id, 'wcrs_disable_signup_for_user', true ) ) {
								$disable_for_referee = true;
							}

							if ( false == $disable_for_referee && get_option( 'wcrs_int_signup_referee' ) != '' ) {
								$points_referee = get_option( 'wcrs_int_signup_referee' );

								/*
								 @name: wrs_before_signup_points_reward_referee
								 @desc: Runs before signup points reward referee.
								 @param: (Int) $referee_user->ID user id.
								 @package: codup-wc-referral-system
								 @module: frontend
								 @type: action
								*/
								do_action( 'wrs_before_signup_points_reward_referee', $referee_user->ID );

								WC_Points_Rewards_Manager::increase_points( $referee_user->ID, intval( $points_referee ), 'referee-signup' );

								/*
								 @name: wrs_after_signup_points_reward_referee
								 @desc: Runs After signup points reward referee.
								 @param: (Int) $referee_user->ID user id.
								 @package: codup-wc-referral-system
								 @module: frontend
								 @type: action
								*/
								do_action( 'wrs_after_signup_points_reward_referee', $referee_user->ID );
							}
						}
					}
				}
			}
		}

	}

	new Referral_System_Signup();
}
