<?php
/**
 * Dependencies File.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return about woocommerce is active or not.
 */
function wcrs_woocommerce_active() {

	register_activation_hook( __FILE__, 'wcrs_activate' );
	add_filter( 'plugin_action_links_' . WCRS_PLUGIN_BASENAME, 'wcrs_plugin_settings_link' );

	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

	if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) {

		add_action( 'admin_notices', 'wcrs_admin_notice_on_activation' );
		return true;

	} else {

		add_action( 'admin_notices', 'wcrs_admin_notice_plugin_dependencies' );
		return false;
	}

}


/**
 * Show admin notice once on plugin activation
 */
function wcrs_admin_notice_on_activation() {

	if ( get_transient( 'wcrs-admin-notice' ) ) {
		?>

<div class="updated notice is-dismissible">
	<p>
		<?php esc_html_e( 'Thank you for using WooCommerce Referral System. Make sure to configure plugin settings before using it.', 'codup-wc-referral-system' ); ?>
		<a href=" <?php echo esc_html( admin_url( 'admin.php?page=wc-settings&tab=settings_tabs' ) ); ?> ">
			<?php esc_html_e( 'Referral System Settings', 'codup-wc-referral-system' ); ?></a>
	</p>
</div>
		<?php
			delete_transient( 'wcrs-admin-notice' );
	}
}

/**
 * Show admin notice if WooCommerce plugin is not active
 */
function wcrs_admin_notice_plugin_dependencies() {
	?>
<div id="message" class="error">
	<p>
		<?php

		$install_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'install-plugin',
					'plugin' => 'woocommerce',
				),
				admin_url( 'update.php' )
			),
			'install-plugin_woocommerce'
		);

			/* translators: %s: is activated */
					printf( esc_html__( 'The %3$sWooCommerce plugin%4$s must be active for %1$sWooCommerce Referral System %2$s to work. Please %5$sinstall & activate WooCommerce &raquo;%6$s', 'codup-wc-referral-system' ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( $install_url ) . '">', '</a>' );
		?>


	</p>
</div>
	<?php
}

/**
 * Add settings hyper link on plugin activation page
 *
 * @param array $links Links.
 */
function wcrs_plugin_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'admin.php?page=wc-settings&tab=codup-wc-referral-system' ) .
		'">' . __( 'Settings' ) . '</a>';
	return $links;
}

/**
 * Activation Hook
 */
function wcrs_activate() {

	if ( ! in_array( 'woocommerce-points-and-rewards/woocommerce-points-and-rewards.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_action( 'admin_notices', 'wcrs_functionality_on_activation' );
		/**
		 * Points and rewards notice of activation.
		 */
		function wcrs_functionality_on_activation() {

			if ( get_transient( 'wcrs-admin-notice' ) ) {
				?>

<div class="updated notice is-dismissible">
	<p>
				<?php
						__(
							'WooCommerce Discount Coupon module has been enabled automatically since the current functionality is set to WooCommerce Referral System instead of Points and Rewards.',
							'codup-wc-referral-system'
						);
				?>
		<a href=" <?php echo esc_html( admin_url( 'admin.php?page=wc-settings&tab=settings_tabs' ) ); ?> ">
				<?php esc_html_e( 'Referral System Settings', 'codup-wc-referral-system' ); ?></a>
	</p>
</div>

				<?php
				delete_transient( 'wcrs-admin-notice' );
			}
		}
	}
}
