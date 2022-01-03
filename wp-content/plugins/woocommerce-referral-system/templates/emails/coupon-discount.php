<?php
/**
 * Codup woocommerce referral system email template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/wcrf-email.php .
 *
 * @param  array  $coupon           The coupon which is rewarded
 *
 * @param  string $referrer_name    First name and last name of referrer who is being rewarded.
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooked with Email Header
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<div>      
	<?php /* translators: 1: Referrer Name */ ?>
	<?php printf( wp_kses_post( __( 'Hi <strong>%s</strong>,', 'codup_woocommerce_referral_system' ) ), esc_html( $referrer_name ) ); ?>  
</div>

<div>    
	<?php
	$message         = '';
	$c               = new WC_Coupon( $coupon );
	$coupon_amount   = $c->get_amount();
	$currency_symbol = get_option( 'woocommerce_currency' );
	$coupon_expiry   = isset( $c->get_data()['date_expires'] ) ? $c->get_data()['date_expires']->date( 'Y-m-d' ) : 'until store is alive.';
	$coupon_string   = '';
	$coupon_type     = $c->get_discount_type();

	switch ( $coupon_type ) {
		case 'fixed_cart':
			$coupon_string = " Enjoy the discount of $currency_symbol $coupon_amount by using <b>$coupon</b> on your "
					. "next order and it's validity is $coupon_expiry.";
			break;
		case 'percent':
			$coupon_string = " Enjoy the discount of $coupon_amount% by using <b>$coupon</b> on your "
					. "next order and it's validity is $coupon_expiry.";
			break;
	}
	switch ( $deserve ) :
		case 'referrer_signup':
			$custom_message = get_option( 'referrer_signup_emails_body', false );

			if ( empty( $custom_message ) ) {
				$custom_message = "Thank you for referring a new customer. Here's a little treat from us. ";
			}

			$message = $custom_message . $coupon_string;
			break;
		case 'referee_signup':
			$custom_message = get_option( 'referee_signup_emails_body', false );

			if ( empty( $custom_message ) ) {
				$custom_message = "Thank you for registering. Here's a little treat from us. ";
			}

			$message = $custom_message . $coupon_string;

			break;
		case 'referrer_purchase':
			$custom_message = get_option( 'referrer_purchase_emails_body', false );

			if ( empty( $custom_message ) ) {
				$custom_message = 'Thank you for referring a new customer. He has purchased from our store. ';
			}

			$message = $custom_message . $coupon_string;

			break;
		case 'referee_purchase':
			$custom_message = get_option( 'referee_purchase_emails_body', false );

			if ( empty( $custom_message ) ) {
				$custom_message = "Thank you for purchasing from our store. Here's a little treat from us. ";
			}

			$message = $custom_message . $coupon_string;

			break;

	endswitch;
	echo wp_kses_post( $message );
	?>
</div>
<div>
	<?php /* translators: 1: Site URL*/ ?>
	<?php printf( wp_kses_post( __( '<a href="%s">Shop now! </a>', 'codup-wc-referral-system' ) ), esc_html( site_url() ) . '/shop' ); ?>
</div>

<?php
/**
 * Hooked to email footer
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
