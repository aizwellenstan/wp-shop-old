<?php
/**
 * The Template for displaying Referral Link content tab on WooCommerce My Account page.
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( wcrs_check_mandatory_settings() ) {
	?>
	<h1>
		<b><?php esc_html_e( 'Referral Link', 'codup-wc-referral-system' ); ?></b>
	</h1>

	<h3>
		<?php esc_html_e( 'Use this referral link to invite users on this page', 'codup-wc-referral-system' ); ?>
	</h3>

	<h4>
		<i><?php esc_html_e( 'Your Referral Link:', 'codup-wc-referral-system' ); ?></i>
	</h4>

	<input type="text" id="referralLink" style="width:inherit;" value="<?php echo esc_attr( $referral_link ); ?> "/> 
	<button id="wcrs-link-copy" class="copy-referral-link" onclick="wcrsCopyReferralLinkFunction()"><?php esc_html_e( 'Copy!', 'codup-wc-referral-system' ); ?></button>
	<br>
	<?php wc_get_template( '/frontend/social-share.php', array( 'referral_link' => $referral_link ), 'woocommerce-referral-system', WCRS_TEMP_DIR ); ?>
<?php } else { ?>

	<h3>
		<?php /* translators: 1: Admin Settings Link*/ ?>
		<?php echo sprintf( esc_html_e( 'Configure <a href="%1$s">Referral System Settings</a>. %2$s.', 'codup-wc-referral-system' ), esc_html( admin_url( 'admin.php?page=wc-settings&tab=settings_tabs' ) ) ); ?>        
	</h3>";

<?php } ?>
