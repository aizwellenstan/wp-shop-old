<?php
/**
 * API work related to referral link.
 *
 * @package WooCommerce Referral System.
 */

if ( ! class_exists( 'WRS_REFERRAL_LINK_API' ) ) {

	/**
	 * WRS_REFERRAL_LINK_API class.
	 */
	class WRS_REFERRAL_LINK_API {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'wrs_register_referral_link_route' ) );

		}

		/**
		 * Register routes.
		 */
		public function wrs_register_referral_link_route() {

			register_rest_route(
				'wrs',
				'/referral_link',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'wrs_view_referral_link' ),
					'permission_callback' => function() {
						return wrs_auth_user_from_api();
					},
				)
			);

		}

		/**
		 * Callback for get referral link.
		 *
		 * @param array $request request.
		 */
		public function wrs_view_referral_link( WP_REST_Request $request ) {

			if ( empty( $request['email'] ) ) {
				$response['status'] = 412;
				$errors             = __( 'Missing required parameter email.' );
				return new WP_Error( __( 'Something went wrong.' ), $errors, $response );
			}

				$user = get_user_by( 'email', $request['email'] );

			if ( false === $user ) {
				$response['status'] = 404;
				$errors             = __( 'The user with provided email not exist.' );
				return new WP_Error( __( 'Something went wrong.' ), $errors, $response );
			}

				$response['status'] = 200;
				$response['data']   = wcrs_get_referral_url( $user );
				return new WP_REST_Response( $response );
		}

	}

}
new WRS_REFERRAL_LINK_API();
