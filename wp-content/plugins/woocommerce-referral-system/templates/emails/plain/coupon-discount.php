<?php

/**
 * Codup woocommerce referral system email template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/wcrf-email.php .
 *
 * @param  array  $coupon           The coupon which is rewarded
 *
 * @param  string $referrer_name    First name and last name of referrer who is being rewarded.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooked with Email header
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
/* translators: 1: Referrer Name */
echo esc_html( sprintf( __( 'Hi %s,', 'codup-wc-referral-system' ), $referrer_name ) );

echo "\n\n";
$message         = '';
$c               = new WC_Coupon( $coupon );
$coupon_amount   = $c->get_amount();
$currency_symbol = get_option( 'woocommerce_currency' );
$coupon_expiry   = isset( $c->get_data()['date_expires'] ) ? $c->get_data()['date_expires']->date( 'Y-m-d' ) : 'until store is alive.';
$coupon_string   = '';

$coupon_type = $c->get_discount_type();

switch ( $coupon_type ) {
	case 'fixed_cart':
		$coupon_string = "Enjoy the discount of $currency_symbol $coupon_amount by using <b>$coupon</b> on your "
				. "next order and it's validity is $coupon_expiry.";
		break;
	case 'percent':
		$coupon_string = "Enjoy the discount of $coupon_amount% by using <b>$coupon</b> on your "
				. "next order and it's validity is $coupon_expiry.";
		break;
}
switch ( $deserve ) :
	case 'referrer_signup':
		$message = "Thank you for referring a new customer. Here's a little treat from us. " . $coupon_string;
		break;
	case 'referee_signup':
		$message = "Thank you for registering. Here's a little treat from us. " . $coupon_string;
		break;
	case 'referrer_purchase':
		$message = 'Thank you for referring a new customer. He has purchased from our store. ' . $coupon_string;
		break;
	case 'referee_purchase':
		$message = "Thank you for purchasing from our store. Here's a little treat from us. " . $coupon_string;
		break;

endswitch;
echo wp_kses_post( $message, 'codup-wc-referral-system' );

echo "\n";
/* translators: 1: Site URL*/
echo wp_kses_post( sprintf( __( 'Shop now at %s', 'codup-wc-referral-system' ), site_url() . '/shop' ) );

echo "\n\n";

/**
 * Hooked with Email Footer
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
