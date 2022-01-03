<?php
/**
 * API work related to referral link.
 */

if ( ! class_exists( 'WRS_API_LOADER' ) ) {

	/**
	 * WRS_API_LOADER Class.
	 */
	class WRS_API_LOADER {

		/**
		 * Constructor
		 */
		public function __construct() {
			require_once 'front/class-wrs-referral-link-api.php';
			require_once 'front/class-wrs-coupons-api.php';
			require_once 'admin/class-wrs-settings-api.php';
		}

	}
	new WRS_API_LOADER();
}

