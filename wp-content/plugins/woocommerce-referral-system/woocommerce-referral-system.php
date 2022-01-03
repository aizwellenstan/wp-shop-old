<?php
/**
 * Plugin Name: WooCommerce Referral System
 * Plugin URI: http://woocommerce.com/products/woocommerce-extension/
 * Description: Its a referral system for woocommerce plugin.
 * Version: 1.3.9.9
 * Author: Codup.io
 * Author URI: http://codup.io/
 * Text Domain: codup-wc-referral-system
 * Domain Path: /languages
 * WC requires at least: 3.8.0
 * WC tested up to: 5.8.0
 * Woo: 4891277:01076a97b164c25fc2b091f343fcf72b
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants.
 */
define( 'WCRS_PLUGIN_DIR', __DIR__ );
define( 'WCRS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WCRS_TEMP_DIR', WCRS_PLUGIN_DIR . '/templates' );
define( 'WCRS_ASSETS_DIR_URL', WCRS_PLUGIN_DIR_URL . 'assets' );
if ( ! defined( 'WCRS_ABSPATH' ) ) {
	define( 'WCRS_ABSPATH', dirname( __FILE__ ) );
}
define( 'WCRS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


require_once WCRS_PLUGIN_DIR . '/includes/referral-system-dependencies.php';

if ( true == wcrs_woocommerce_active() ) {

	/**
	* Include global function file.
	*/
	require_once WCRS_PLUGIN_DIR . '/includes/functions.php';

	require_once WCRS_PLUGIN_DIR . '/includes/class-referral-system-core.php';
	require_once WCRS_PLUGIN_DIR . '/includes/class-referral-system-settings.php';
	require_once WCRS_PLUGIN_DIR . '/contact_us/class-wcrs-contact-us.php';

}
