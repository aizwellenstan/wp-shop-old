<?php
/**
 * API work related to admin settings.
 *
 * @package WooCommerce Referral System.
 */

if ( ! class_exists( 'WRS_SETTINGS_API' ) ) {

	/**
	 * WRS_SETTINGS_API class.
	 */
	class WRS_SETTINGS_API {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'wrs_register_settings_route' ) );
		}

		/**
		 * Register routes.
		 */
		public function wrs_register_settings_route() {

			register_rest_route(
				'wrs',
				'/update_settings',
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'wrs_update_setting_by_api' ),
					'permission_callback' => function() {
						return wrs_auth_user_from_api();
					},
				)
			);

			register_rest_route(
				'wrs',
				'/get_setting',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'wrs_get_setting_by_api' ),
					'permission_callback' => function() {
						return wrs_auth_user_from_api();
					},
				)
			);
		}

		/**
		 * Callback for update.
		 *
		 * @param array $request request.
		 */
		public function wrs_update_setting_by_api( WP_REST_Request $request ) {

			if ( empty( $request->get_params() ) ) {
				$response['status'] = 412;
				$errors             = __( 'Missing required values ex: setting_name as key and setting_value as setting value .' );
				return new WP_Error( __( 'Something went wrong.' ), $errors, $response );
			}

				$errors               = array();
				$response_options     = array();
				$current_values_array = array();

				$response['status'] = 200;

			foreach ( $request->get_params() as $setting_name => $value ) {

				$setting_value = get_option( $setting_name, false );

				$options = wcrs_options_all_settings();

				if ( false === $setting_value ) {
					$errors[ $setting_name ] = 'Setting with provided option name not exist.';
					continue;
				}

				if ( empty( $value ) ) {
					$errors[ $setting_name ] = 'Missing setting value.';
					continue;
				}

				if ( $options[ $setting_name ] ) {

					$response_options[ $setting_name ] = $options[ $setting_name ];

					if ( in_array( $value, $options[ $setting_name ] ) || array_key_exists( $value, $options[ $setting_name ] ) ) {

						if ( ( 'wcpr-func' == $value && 'wcrs_functionality_type' == $setting_name ) ) {
							if ( ! in_array( 'woocommerce-points-and-rewards/woocommerce-points-and-rewards.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
								$errors[ $setting_name ] = ' Please Install and Active “WooCommerce Points and Rewards” Plugin.';
								continue;
							}
						} elseif ( ( 'wcrs_order_discount_type' == $setting_name ) && 'percent' == $value ) {
							if ( ( 'percent' == get_option( 'cwrs_reward_type', true ) ) ) {
								$errors[ $setting_name ] = 'Value passed is not allowed. (Currently Reward Type is Percentage ).';
								continue;
							}
						}

						update_option( $setting_name, $value );
					} else {
						$errors[ $setting_name ] = 'Provided value not matched with possible options.';
						continue;
					}
				} else {

					$validation = wcrs_validate_all_int_settings( $setting_name, $value );

					if ( true === $validation ) {

						if ( 'wcrs_action_signup_referral_coupon_amount' == $setting_name || 'wcrs_action_signup_referee_coupon_amount' == $setting_name ) {

							if ( ( 'percent' == get_option( 'wcrs_signup_discount_type', true ) ) && $value > 100 ) {
								$errors[ $setting_name ] = 'Value should be less then or equal to 100 (Currently Coupon Type is Percentage ).';
								continue;
							}
						} elseif ( 'wcrs_action_purchase_referral_coupon_amount' == $setting_name || 'wcrs_action_purchase_referee_coupon_amount' == $setting_name ) {

							if ( ( 'percent' == get_option( 'cwrs_reward_type', true ) ) && $value > 100 ) {
								$errors[ $setting_name ] = 'Value should be less then or equal to 100 (Currently Reward Type is Percentage).';
								continue;
							}
						}

						update_option( $setting_name, $value );
					} else {
						$errors[ $setting_name ] = 'Provided value not matched with possible options (Integers should not be 0 or less).';
						continue;
					}
				}

				$setting_value                         = get_option( $setting_name, false );
				$current_values_array[ $setting_name ] = $setting_value;
			}

			if ( $errors ) {
				$response['errors']      = $errors;
				$response['error_count'] = count( $errors );
			}

			if ( $response_options ) {
				$response['data']['options'] = $response_options;
			}

			if ( $current_values_array ) {
				$response['data']['current_value'] = $current_values_array;
			}

				return new WP_REST_Response( $response );
		}

		/**
		 * Callback for get.
		 *
		 * @param array $request request.
		 */
		public function wrs_get_setting_by_api( WP_REST_Request $request ) {

			if ( empty( $request['setting_name'] ) ) {
				$response['status'] = 412;
				$errors             = __( 'Missing required parameter setting_name.' );
				return new WP_Error( __( 'Something went wrong.' ), $errors, $response );
			}

				$errors             = array();
				$response['status'] = 200;
				$setting_name       = $request['setting_name'];

				$setting_value = get_option( $setting_name, false );

				$options = wcrs_options_all_settings();

			if ( false === $setting_value ) {
				$errors[ $setting_name ] = 'Setting with provided option name not exist.';
			} else {

				$response['data']['current_value'] = $setting_value;
				if ( $options[ $setting_name ] ) {
					$response['data']['options'] = $options[ $setting_name ];
				}
			}

			if ( $errors ) {
				$response['errors']      = $errors;
				$response['error_count'] = count( $errors );
			}

				return new WP_REST_Response( $response );
		}

	}

}
new WRS_SETTINGS_API();
