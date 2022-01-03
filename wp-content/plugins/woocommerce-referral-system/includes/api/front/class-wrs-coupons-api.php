<?php
/**
 * API work related coupons.
 *
 * @package WooCommerce Referral System.
 */

if ( ! class_exists( 'WRS_COUPONS_API' ) ) {

	/**
	 * WRS_COUPONS_API class.
	 */
	class WRS_COUPONS_API {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'wrs_register_rcoupons_route' ) );

		}

		/**
		 * Register routes.
		 */
		public function wrs_register_rcoupons_route() {

			register_rest_route(
				'wrs',
				'/coupons',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'wrs_get_all_coupons' ),
					'permission_callback' => function() {
						return wrs_auth_user_from_api();
					},
				)
			);

		}

		/**
		 * Callback for get coupons.
		 *
		 * @param array $request request.
		 */
		public function wrs_get_all_coupons( WP_REST_Request $request ) {

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

				$u_email = $request['email'];

				$args = array(
					'posts_per_page' => -1,
					'orderby'        => 'date',
					'order'          => 'desc',
					'post_type'      => 'shop_coupon',
					'post_status'    => 'publish',
				);

				$coupons          = get_posts( $args );
				$response['data'] = array();

				if ( $coupons ) {
					foreach ( $coupons as $coupon ) {
						$not_user = false;
						$allowed  = get_post_meta( $coupon->ID, 'customer_email' );

						if ( $allowed ) {
							foreach ( $allowed as $key => $email ) {

								if ( $u_email != $email ) {
									$not_user = true;
								}
							}
						} else {
							continue;
						}

						$my_coupon  = new WC_Coupon( $coupon->ID );
						$result     = array();
						$our_coupon = get_post_meta( $coupon->ID, 'wfs_awarded' );
						if ( 'yes' !== $our_coupon[0] || true == $not_user ) {
							continue;
						}

						$result['coupon_code']   = $coupon->post_name;
						$result['date_expires']  = $my_coupon->get_date_expires()->format( 'Y-m-d' );
						$result['discount_type'] = $my_coupon->get_discount_type();
						$result['coupon_amount'] = $my_coupon->get_amount();
						$result['reason']        = $coupon->post_excerpt;

						array_push( $response['data'], $result );

					}
				}

				$response['status'] = 200;
				if ( empty( $response['data'] ) ) {
					$response['message'] = 'Coupons not found for this user.';
				} else {
					$response['message'] = count( $response['data'] ) . ' Coupons found for this user.';
				}
				return new WP_REST_Response( $response );
		}

	}

}
new WRS_COUPONS_API();
