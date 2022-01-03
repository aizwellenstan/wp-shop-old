<?php
/**
 * Edit user functions class.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Referral_System_Edit_User' ) ) {

	/**
	 * Referral_System_Edit_User Class.
	 */
	class Referral_System_Edit_User {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'edit_user_profile', array( $this, 'show_wcrs_box' ) );

			add_action( 'show_user_profile', array( $this, 'show_wcrs_box' ) );

			add_action( 'edit_user_profile_update', array( $this, 'save_wcrs_fields' ), 10, 1 );
			add_action( 'personal_options_update', array( $this, 'save_wcrs_fields' ), 10, 1 );

		}

		/**
		 * Funtion to html callback.
		 *
		 * @param array $user user array.
		 */
		public function show_wcrs_box( $user ) {
			$nonce = wp_create_nonce( 'wcrs_nonce' );
			echo "<input type='hidden' name='wcrs_nonce' value='" . esc_attr( $nonce ) . "'>";
			?>
			<hr>
			<?php
			echo '<h3 class="heading">' . esc_html( 'Referral System Settings', 'codup-wc-referral-system' ) . '</h3>';
			?>
			<table class="form-table">
				<tr>
				   <th><label for="wcrs_disable_signup_for_user"><?php esc_html_e( 'Disabled Signup Based Reward', 'codup-wc-referral-system' ); ?></label></th>
					<td>
					<input type="checkbox" class="input-text form-control" name="wcrs_disable_signup_for_user" id="wcrs_disable_signup_for_user"
					<?php
					if ( 'on' == get_user_meta( $user->ID, 'wcrs_disable_signup_for_user', true ) ) {
						esc_html_e( 'checked' ); }
					?>
					></td>
				</tr>
				<tr>
				   <th><label for="wcrs_disable_order_for_user"><?php esc_html_e( 'Disabled Order Based Reward', 'codup-wc-referral-system' ); ?></label></th>
					<td>
					<input type="checkbox" class="input-checkbox form-control" name="wcrs_disable_order_for_user" id="wcrs_disable_order_for_user"
					<?php
					if ( 'on' == get_user_meta( $user->ID, 'wcrs_disable_order_for_user', true ) ) {
						esc_html_e( 'checked' ); }
					?>
					></td>
				</tr>
			</table>
			<hr>
			<?php
		}


		/**
		 * Funtion to save wcrs fields.
		 *
		 * @param int $user_id user_id.
		 */
		public function save_wcrs_fields( $user_id ) {

			if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'wcrs_nonce' ), 'wcrs_nonce' ) ) {
				return;
			}

			update_user_meta( $user_id, 'wcrs_disable_signup_for_user', 'off' );
			if ( isset( $_POST['wcrs_disable_signup_for_user'] ) ) {
				$disable_signup = filter_input( INPUT_POST, 'wcrs_disable_signup_for_user' );
				update_user_meta( $user_id, 'wcrs_disable_signup_for_user', $disable_signup );
			}

			update_user_meta( $user_id, 'wcrs_disable_order_for_user', 'off' );
			if ( isset( $_POST['wcrs_disable_order_for_user'] ) ) {
				$disable_order = filter_input( INPUT_POST, 'wcrs_disable_order_for_user' );
				update_user_meta( $user_id, 'wcrs_disable_order_for_user', $disable_order );
			}

		}

	}

	new Referral_System_Edit_User();
}


