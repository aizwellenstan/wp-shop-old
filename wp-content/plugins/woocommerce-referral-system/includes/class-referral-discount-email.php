<?php
/**
 * Email Template.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wc_Discount_Email' ) ) :

	/**
	 * A custom WooCommerce Email class
	 *
	 * @since 0.1
	 * @extends \WC_Email
	 */
	class REFERRAL_DISCOUNT_EMAIL extends WC_Email {

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->id          = 'wcrs_coupon_discount';
			$this->title       = __( 'Coupon Discount', 'codup-wc-referral-system' );
			$this->description = __( 'Email template when discount coupon is added to in the pocket of customer.', 'codup_woocommerce_referral_system' );

			$this->template_html  = 'emails/coupon-discount.php';
			$this->template_plain = 'emails/plain/coupon-discount.php';

			$custom_subject = get_option( 'wcrs_subject_emails', false );

			if ( empty( $custom_subject ) ) {
				$custom_subject = __( "You've got Discount Coupon!", 'codup-wc-referral-system' );
			}

			$this->subject = $custom_subject;
			$this->heading = __( 'Congratulations! You have been awarded', 'codup-wc-referral-system' );

			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Initialise Settings Form Fields
		 *
		 * @return void
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => 'Enable/Disable',
					'type'    => 'checkbox',
					'label'   => 'Enable this email notification',
					'default' => 'yes',
				),
				'subject'    => array(
					'title'       => __( 'Email subject', 'woocommerce' ),
					'type'        => 'text',
					/* translators: 1: Email Subject */
					'description' => sprintf( __( 'Subject of the email defaults to <code>%s</code>', 'woocommerce' ), $this->subject ),
					'placeholder' => $this->subject,
					'default'     => '',
				),
				'heading'    => array(
					'title'       => __( 'Email heading', 'woocommerce' ),
					'type'        => 'text',
					/* translators: 1: Email Heading */
					'description' => sprintf( __( 'Heading of the email defaults to <code>%s</code>', 'woocommerce' ), $this->heading ),
					'placeholder' => $this->heading,
					'default'     => '',
				),
				'email_type' => array(
					'title'       => __( 'Email type', 'woocommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type',
					'options'     => array(
						'plain' => __( 'Plain text', 'woocommerce' ),
						'html'  => __( 'HTML', 'woocommerce' ),
					),
				),
			);
		}

	}



endif;
