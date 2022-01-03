<?php
/**
 * Woocommerce settings file.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Referral_System_Settings' ) ) {
	/**
	 * Class for settings features
	 */
	class Referral_System_Settings {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id = 'codup-wc-referral-system';

			$this->order_status_array =
			array(
				'processing' => 'Processing (default)',
				'pending'    => 'Pending',
				'on-hold'    => 'On-Hold',
				'completed'  => 'Completed',
				'cancelled'  => 'Cancelled',
				'refunded'   => 'Refunded',
				'failed'     => 'Failed',
			);

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings' ), 50 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
			add_action( 'plugins_loaded', array( $this, 'cwfs_load_plugin_textdomain' ) );

		}

		/**
		 * Save settings
		 */
		public function save() {
			global $current_section;

			$settings = $this->get_settings( $current_section );
			WC_Admin_Settings::save_fields( $settings );
		}

		/**
		 * Output the settings
		 */
		public function output() {
			global $current_section;
			$settings = $this->get_settings( $current_section );

			WC_Admin_Settings::output_fields( $settings );
		}

		/**
		 * Languages loaded.
		 */
		public function cwfs_load_plugin_textdomain() {
			load_plugin_textdomain( 'codup-wc-referral-system', false, basename( WCRS_ABSPATH ) . '/languages/' );
		}


		/**
		 * Add Referral Settings tab to WooCommerce Settings page
		 *
		 * @param array $settings_tab settings_tab.
		 */
		public function add_settings( $settings_tab ) {
			$settings_tab[ $this->id ] = __( 'Referral System', 'codup-wc-referral-system' );
			return $settings_tab;
		}

		/**
		 * Get sections
		 *
		 * @return array
		 */
		public function get_sections() {
			$sections = array(
				'0' => __( 'General', 'codup-wc-referral-system' ),
				'1' => __( 'Signup Based Rewards', 'codup-wc-referral-system' ),
				'2' => __( 'Order Based Rewards', 'codup-wc-referral-system' ),
			);

			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		/**
		 * Function Output settings.
		 */
		public function output_sections() {
			global $current_section;

			$sections = $this->get_sections();
			echo '<ul class="subsubsub">';
			foreach ( $sections as $id => $label ) {
				echo '<li>
				<a href="' . wp_kses_post( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . wp_kses_post( esc_html( $label, 'codup-wc-referral-system' ) ) . '</a><span class="lamda">  | </span></li>';

			}
			echo '</ul><br>';
		}


		/**
		 * Referral Settings Tab Content.
		 *
		 * @param int $current_section current_section.
		 */
		public function get_settings( $current_section = '0' ) {
			$link_to_settings_wcpr = "<a href='" . wp_kses_post( admin_url( 'admin.php?page=woocommerce-points-and-rewards&tab=settings' ) ) . "'>" . esc_html( 'here' ) . '</a>';

			$notice = __( 'Save the changes on this page first and then integration settings can be configured ', 'codup-wc-referral-system' ) . $link_to_settings_wcpr;

			if ( 0 == $current_section ) {
				$settings = array(
					'general_func'        => array(
						'name' => __( 'General Settings', 'codup-wc-referral-system' ),
						'type' => 'title',
						'id'   => 'wcrs_general_settings_title',
					),
					array(
						'name'    => __( 'Select the type of functionality', 'codup-wc-referral-system' ),
						'type'    => 'radio',
						'options' => array(
							'wcrs-func' => __( 'Standalone WooCommerce Referral System Functionality', 'codup-wc-referral-system' ),
							'wcpr-func' => __( 'Integrate with “WooCommerce Points and Rewards” System', 'codup-wc-referral-system' ),
						),
						'desc'    => __( 'Choose whether you want the standalone Referral System Functionality or integrate it with WooCommerce Points and Rewards plugin.', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_functionality_type',
					),
					array(
						'name' => __( 'Set Minimum Purchase Limit', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Select this action if you want to set minimum order amount to get rewards.', 'codup-wc-referral-system' ),
						'id'   => 'wcrspr_minimum_purchase_limit_enable',
					),
					array(
						'name' => '',
						'type' => 'number',
						'css'  => '',
						'desc' => __( 'Set Amount.', 'codup-wc-referral-system' ),
						'id'   => 'wcrspr_minimum_purchase_limit',
					),
					array(
						'name'    => __( 'Valid Number of Orders', 'codup-wc-referral-system' ),
						'type'    => 'number',
						'default' => '1',
						'desc'    => __( 'This means on how much orders award will provided.', 'codup-wc-referral-system' ),
						'id'      => 'wcrspr_valid_no_orders',
					),
					array(
						'name'    => __( 'Eligible Order Status', 'codup-wc-referral-system' ),
						'type'    => 'multiselect',
						'options' => $this->order_status_array,
						'desc'    => __( 'Which Statuses are eligible for reward.', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_eligible_status',
					),
					'general_func_end'    => array(
						'type' => 'sectionend',
						'id'   => 'wcrs_general_settings_title',
					),
					'wcpr_desc'           => array(
						'name' => __( 'Integration with WooCommerce Points & Rewards', 'codup-wc-referral-system' ),
						'type' => 'title',
						'desc' => __( $notice ),
						'id'   => 'wcrs_wcpr_desc_end',
					),
					'wcpr_desc_end'       => array(
						'type' => 'sectionend',
						'id'   => 'wcrs_wcpr_desc_end',
					),
					/**
					 * Scope v1
					 */
					'section_title'       => array(
						'type' => 'title',
						'desc' => __( 'Remember once you have changed your slug type and slug name it cannot be reset again', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_settings_title',
					),
					array(
						'name'    => __( 'Select your slug type', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => array(
							'Select' => __( 'Select', 'codup-wc-referral-system' ),
							'Id'     => 'Id',
							'Name'   => 'Name',
						),
						'desc'    => __( 'Choose whether you want the referral link to be generated with User ID or User Name.', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_type',
					),
					array(
						'name' => __( 'Slug Name', 'codup-wc-referral-system' ),
						'type' => 'text',
						'desc' => __( 'Create your own slug name for referral link.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_slug',
					),
					array(
						'name' => __( 'Cookie Expiry', 'codup-wc-referral-system' ),
						'type' => 'number',
						'desc' => __( 'Cookie Expiry in days from the day of visit.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_cookie_expiry_time',
						'css'  => 'width:70px;',
					),
					array(
						'name'    => __( 'Redirect to page', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => wcrs_get_pages(),
						'class'   => 'wcrs-dropdown',
						'desc'    => __( 'Redirect to user defined page', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_page',
					),
					array(
						'name'    => __( 'Redirect to product', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => wcrs_get_products(),
						'class'   => 'wcrs-dropdown',
						'desc'    => __( 'Redirect to user defined product page', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_product',
					),
					array(
						'name'        => __( 'Customizable Text for Email and Social Sharing', 'codup-wc-referral-system' ),
						'type'        => 'text',
						'placeholder' => __( 'Default : Come and Join To Earn Rewards Now!' ),
						'css'         => '',
						'desc'        => __( 'This text will be sent with your Referral URL.', 'codup-wc-referral-system' ),
						'id'          => 'wcrs_sharing_description',
					),
					array(
						'name'        => __( 'Customizable Text for Subject of Emails', 'codup-wc-referral-system' ),
						'type'        => 'text',
						'placeholder' => __( "Default : You've got Discount Coupon!" ),
						'css'         => '',
						'desc'        => __( 'This text set your subject for coupons emails.', 'codup-wc-referral-system' ),
						'id'          => 'wcrs_subject_emails',
					),
					array(
						'name'        => __( 'Customizable Text for Email Body for referrer on signup of referee.', 'codup-wc-referral-system' ),
						'type'        => 'textarea',
						'placeholder' => __( "Default : Thank you for referring a new customer. Here's a little treat from us. " ),
						'css'         => '',
						'id'          => 'referrer_signup_emails_body',
					),
					array(
						'name'        => __( 'Customizable Text for Email Body for referee on signup.', 'codup-wc-referral-system' ),
						'type'        => 'textarea',
						'placeholder' => __( "Default : Thank you for registering. Here's a little treat from us. " ),
						'css'         => '',
						'id'          => 'referee_signup_emails_body',
					),
					array(
						'name'        => __( 'Customizable Text for Email Body for referrer on Order Based Reward.', 'codup-wc-referral-system' ),
						'type'        => 'textarea',
						'placeholder' => __( 'Default : Thank you for referring a new customer. He has purchased from our store. ' ),
						'css'         => '',
						'id'          => 'referrer_purchase_emails_body',
					),
					array(
						'name'        => __( 'Customizable Text for Email Body for Referee on Order Based Reward.', 'codup-wc-referral-system' ),
						'type'        => 'textarea',
						'placeholder' => __( "Default : Thank you for purchasing from our store. Here's a little treat from us. " ),
						'css'         => '',
						'id'          => 'referee_purchase_emails_body',
					),
					'wcrs_settings_title' => array(
						'type' => 'sectionend',
						'id'   => 'wcrs_general_settings_title',
					),
				);
			} elseif ( 1 == $current_section ) {
				$settings = array(
					array(
						'name' => __( 'Signup Settings', 'codup-wc-referral-system' ),
						'type' => 'title',
						'id'   => 'singup_settings_title',
					),
					array(
						'name' => __( 'Select if you want Signup action for discount', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Select this action if your awardees get the coupon on this particular action.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_type',
					),
					array(
						'name'    => __( 'Coupon Type', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => array(
							'fixed_cart' => __( 'Fixed Price', 'codup-wc-referral-system' ),
							'percent'    => __( 'Percentage', 'codup-wc-referral-system' ),
						),
						'desc'    => __( 'Select one of the coupon type for your referrer/referee.', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_signup_discount_type',
					),
					array(
						'name' => __( 'Enable Coupon for Referrer', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Select if you want the referrer to be rewarded on Signup.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referral',
					),
					array(
						'name' => __( 'Coupon Amount', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Type in the coupon amount you want your referrers to get when referee signs up successfully', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referral_coupon_amount',
					),
					array(
						'name' => __( 'Coupon Expiry', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Write the coupon expiry days from the day the referee signs up in number of days only.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referral_coupon_expiry',
					),
					array(
						'name' => __( 'Enable Coupon for Referee', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Select if you want the referee to be rewarded on signup.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referee',
					),
					array(
						'name' => __( 'Coupon Amount', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Type in the coupon amount you want your referees to get when referee signs up successfully', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referee_coupon_amount',
					),
					array(
						'name' => __( 'Coupon Expiry', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Write the coupon expiry days from the day the referee signs up in number of days only.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_signup_referee_coupon_expiry',
					),
					'singup_func_end' => array(
						'type' => 'sectionend',
						'id'   => 'singup_settings_title',
					),

				);
			} elseif ( 2 == $current_section ) {
				$settings = array(
					'order'          => array(
						'name' => __( 'Order Settings', 'codup-wc-referral-system' ),
						'type' => 'title',
						'id'   => 'order_settings_title',
					),
					array(
						'name' => __( 'Enable Order Based Coupons', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Selecting this rewards the referrer and referee more discount coupons each time the referee places an order with your store', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_type',
					),
					array(
						'name'    => __( 'Reward Type', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => array(
							'fixed_cart' => __( 'Fixed', 'codup-wc-referral-system' ),
							'percent'    => __( 'Percentage', 'codup-wc-referral-system' ),
						),
						'desc'    => __( 'Give rewards based on order Sub-total(exclude Tax) amount in % or Fixed Amount.', 'codup-wc-referral-system' ),
						'id'      => 'cwrs_reward_type',
					),
					array(
						'name'    => __( 'Coupon Type', 'codup-wc-referral-system' ),
						'type'    => 'select',
						'options' => array(
							'fixed_cart' => __( 'Fixed Price', 'codup-wc-referral-system' ),
							'percent'    => __( 'Percentage', 'codup-wc-referral-system' ),
						),
						'desc'    => __( 'Select one of the coupon type for your referrer/referee.', 'codup-wc-referral-system' ),
						'id'      => 'wcrs_order_discount_type',
					),
					array(
						'name' => __( 'Minimum Purchase Amount', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Selecting this lets you set a minimum purchase amount on which the referrer and referee will get the coupon.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_minimum_purchase_limit_enable',
					),
					array(
						'name' => '',
						'type' => 'number',
						'css'  => 'display: none;',
						'desc' => __( 'Enter the minimum purchase amount.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_minimum_purchase_limit',
					),
					array(
						'name'     => __( 'Valid Number of Orders', 'codup-wc-referral-system' ),
						'type'     => 'number',
						'default'  => '1',
						'desc_tip' => __( 'This is the number of subsequent orders on which the reward is offered.', 'codup-wc-referral-system' ),
						'id'       => 'wcrs_valid_no_orders',
					),
					array(
						'name' => __( 'Enable Order Based Coupon for Referrers', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Rewards the referrer when the referee makes a purchase from your store.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referral',
					),
					array(
						'name' => __( 'Coupon', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Type in the coupon amount you want your referrers to get when referees purchase their first item.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referral_coupon_amount',
					),
					array(
						'name' => __( 'Coupon Expiry', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Write the coupon expiry days from the day the referee purchase first item in number of days only.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referral_coupon_expiry',
					),
					array(
						'name' => __( 'Select this option if referee gets the coupon', 'codup-wc-referral-system' ),
						'type' => 'checkbox',
						'desc' => __( 'Select this action if your awardees are referees.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referee',
					),
					array(
						'name' => __( 'Coupon', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Type in the coupon amount you want your referees to get when referees purchase their first item.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referee_coupon_amount',
					),
					array(
						'name' => __( 'Coupon Expiry', 'codup-wc-referral-system' ),
						'type' => 'number',
						'css'  => 'width:70px;',
						'desc' => __( 'Write the coupon expiry days from the day the referee purchase first item in number of days only.', 'codup-wc-referral-system' ),
						'id'   => 'wcrs_action_purchase_referee_coupon_expiry',
					),
					'order_func_end' => array(
						'type' => 'sectionend',
						'id'   => 'order_settings_title',
					),

				);
			}

			return apply_filters( 'wc_referral_settings', $settings );
		}

	}

}

new Referral_System_Settings();
