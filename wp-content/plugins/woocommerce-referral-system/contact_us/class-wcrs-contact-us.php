<?php
/**
 * Contact Us Box on settings page.
 *
 * @package codup/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCRS_Contact_Us' ) ) {

	/**
	 * Class Codup_Contact_Us
	 */
	class WCRS_Contact_Us {

		/**
		 * Function Construct
		 */
		public function __construct() {

			if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) ) {
				if ( 'wc-settings' == $_GET['page'] && 'codup-wc-referral-system' == $_GET['tab'] ) {

					add_action( 'admin_notices', array( $this, 'wcrs_contact_us' ), 999999 );

				}
			}

			add_filter( 'plugin_row_meta', array( $this, 'wcrs_support_and_faq_links' ), 10, 4 );
		}

		/**
		 * Function wcrs support and faq links
		 *
		 * @param array  $links_array Links Array.
		 * @param string $plugin_file_name Plugin File.
		 * @param array  $plugin_data Plugin Data.
		 * @param string $status Status.
		 */
		public function wcrs_support_and_faq_links( $links_array, $plugin_file_name, $plugin_data, $status ) {

			if ( 'woocommerce-referral-system/woocommerce-referral-system.php' == $plugin_file_name || 'codup-wc-referral-system/woocommerce-referral-system.php' == $plugin_file_name ) {

				$links_array[] = __( 'Having trouble in configuration? ', 'codup-wc-referral-system' ) . '<a href="http://ecommerce.codup.io/support/tickets/new" target="_blank">' . __( 'Get Support', 'codup-wc-referral-system' ) . '</a>';

			}
			return $links_array;
		}

		/**
		 * Function generate settings link.
		 *
		 * @param array $links_array Links Array.
		 */
		public function wcrs_settings_link( $links_array ) {

			array_unshift( $links_array, '<a href="' . site_url() . '/wp-admin/admin.php?page=wc-settings&tab=settings_tabs">Settings</a>' );
			return $links_array;

		}

		/**
		 * Function wcrs contact us form.
		 */
		public function wcrs_contact_us() {

			?>
			<div class="notice notice-success codup-contact-us" style="position: absolute;right: 20px;top:35%;z-index:9999;">
				<p>
					<?php echo wp_kses_post( __( 'Having trouble in configuration?', 'codup-wc-referral-system' ) ); ?><a href="http://ecommerce.codup.io/support/tickets/new"><?php echo wp_kses_post( __( 'Contact us', 'codup-wc-referral-system' ) ); ?></a><?php echo wp_kses_post( __( 'for support.', 'codup-wc-referral-system' ) ); ?>
				</p>
				<p>
					<?php echo wp_kses_post( __( 'Or email your query at', 'codup-wc-referral-system' ) ); ?> <a href="mailto:woosupport@codup.io"> <?php echo wp_kses_post( __( 'woosupport@codup.io', 'codup-wc-referral-system' ) ); ?> </a>
				</p>        
			</div>
			<div class="clear"></div>
			<?php
		}
	}

}
new WCRS_Contact_Us();
