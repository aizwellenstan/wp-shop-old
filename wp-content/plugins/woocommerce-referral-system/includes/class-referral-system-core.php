<?php
/**
 * Core Functionality file.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Referral_System_Core' ) ) {
	/**
	 * Class for core features
	 */
	class Referral_System_Core {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'wcrs_default_migrations' ) );

			add_action( 'init', array( $this, 'intialize' ) );
			$this->include_wrs_main_files();

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_assets' ) );

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			add_filter( 'woocommerce_locate_core_template', array( $this, 'locate_core_template' ), 10, 3 );
			add_filter( 'woocommerce_email_classes', array( $this, 'add_referral_discount_email' ) );

			add_action( 'woocommerce_thankyou', array( $this, 'clear_cookie' ), 11, 1 );
			add_action( 'wp_logout', array( $this, 'clear_cookie' ) );

			if ( get_option( 'wcrs_functionality_type' ) == 'wcpr-func' ) {
				add_filter( 'wc_points_rewards_settings', array( $this, 'wcpr_integration_fields' ), 10, 1 );
				add_filter( 'wc_points_rewards_earn_points_message', array( $this, 'cart_message_config' ), 10, 2 );
			}

			add_filter( 'wc_points_rewards_increase_points', array( $this, 'disable_wcpr_points' ), 10, 3 );

			// add the event descriptions.
			add_filter( 'wc_points_rewards_event_description', array( $this, 'add_new_referral_events' ), 10, 3 );

		}

		/**
		 * Add Default values/settings.
		 */
		public function wcrs_default_migrations() {

			// Create log table if not exist.
			wcrs_create_log_table();

			// Add default settings.
			wcrs_add_default_settings();

		}

		/**
		 * Include all the main files of this plugin.
		 */
		public function include_wrs_main_files() {
			require_once 'class-referral-system-signup.php';
			require_once 'class-referral-system-my-account-menu.php';
			require_once 'class-referral-system-order.php';

			if ( is_admin() ) {
				require_once 'class-referral-system-edit-user.php';
			}

			require_once 'api/class-wrs-api-loader.php';
		}

		/**
		 * Trigger functions on WP Initialize
		 */
		public function intialize() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();

			add_action( 'wp_head', array( $this, 'add_fb_meta' ) );

		}

		/**
		 * Add Facebook mete in head of every page.
		 */
		public function add_fb_meta() {

			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );

			if ( empty( $image[0] ) ) {
				$image[0] = '';
			}

			/*
			 @name: wrs_image_for_fb_share
			 @desc: Modify Fb image url.
			 @param: (string) $image[0] image url.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$image[0] = apply_filters( 'wrs_image_for_fb_share', $image[0] );

			$url = get_bloginfo( 'url' );

			/*
			 @name: wrs_url_for_fb_share
			 @desc: Modify Fb url.
			 @param: (string) $url fb url.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$url = apply_filters( 'wrs_url_for_fb_share', $url );

			$title = get_bloginfo( 'title' );

			/*
			 @name: wrs_title_for_fb_share
			 @desc: Modify Fb title.
			 @param: (string) $title fb title.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$title = apply_filters( 'wrs_title_for_fb_share', $title );

			$description = get_bloginfo( 'description' );

			/*
			 @name: wrs_description_for_fb_share
			 @desc: Modify Fb description.
			 @param: (string) $description fb description.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$description = apply_filters( 'wrs_description_for_fb_share', $description );

			?>
			<meta property="og:url"           content="<?php echo wp_kses_post( esc_html( $url ) ); ?>" />
			<meta property="og:type"          content="website" />
			<meta property="og:title"         content="<?php echo wp_kses_post( esc_html( $title ) ); ?>" />
			<meta property="og:description"   content="<?php echo wp_kses_post( esc_html( $description ) ); ?>" />
			<meta property="og:image"         content="<?php echo wp_kses_post( esc_html( $image[0] ) ); ?>" />
			<script>
					function wcrsCopyReferralLinkFunction() {
					  var copyText = document.getElementById("referralLink");
					  copyText.select();
					  document.execCommand("copy");
					  alert("Referral Link Copied!");
					}
				</script>
				<script>
			  window.fbAsyncInit = function() {
				FB.init({
				  appId            : 'your-app-id',
				  autoLogAppEvents : true,
				  xfbml            : true,
				  version          : 'v7.0'
				});
			  };
			</script>
			<?php
		}


		/**
		 * Function to add custom event types and there description in points and rewards plugin
		 *
		 * @param string $event_description event_description.
		 * @param string $event_type event_type.
		 * @param event  $event event.
		 */
		public function add_new_referral_events( $event_description, $event_type, $event ) {

			$referrer_signup_label = __( 'referrer signup' );

			/*
			 @name: wrs_referrer_custom_event_label
			 @desc: Modify label description of referrer signup.
			 @param: (string) $referrer_signup_label referrer_signup_label.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$referrer_signup_label = apply_filters( 'wrs_referrer_custom_event_label', $referrer_signup_label );

			$referee_signup_label = __( 'referee signup' );

			/*
			 @name: wrs_referee_custom_event_label
			 @desc: Modify label description of referee signup.
			 @param: (string) $referee_signup_label referee_signup_label.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$referee_signup_label = apply_filters( 'wrs_referee_custom_event_label', $referee_signup_label );

			$order_label = __( 'order' );

			/*
			 @name: wrs_order_custom_event_label
			 @desc: Modify label description of order.
			 @param: (string) $order_label order_label.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: filter
			*/
			$order_label = apply_filters( 'wrs_order_custom_event_label', $order_label );

			// set the description if we know the type.
			switch ( $event_type ) {
				case 'referrer-signup':
					$event_description = $referrer_signup_label;
					break;
				case 'referee-signup':
					$event_description = $referee_signup_label;
					break;
				case 'order':
					$event_description = $order_label;
					break;
			}

			return $event_description;
		}

		/**
		 * Enqueue WP Admin JS and CSS files
		 */
		public function frontend_assets() {

			wp_enqueue_script( 'wcrs-fb', 'https://connect.facebook.net/en_US/sdk.js', array( 'jquery' ), 1 );
			wp_enqueue_script( 'wcrs-front-script', WCRS_ASSETS_DIR_URL . '/frontend/js/wcrs-main.js', array( 'jquery' ), 1 );
			wp_enqueue_style( 'wcrs-front-style', WCRS_ASSETS_DIR_URL . '/frontend/css/wcrs-style.css', null, 1 );
		}

		/**
		 * Function to disable WCPR settings if WCRS integration settings enabled
		 *
		 * @param int    $points points.
		 * @param int    $user_id user_id.
		 * @param string $event_type event_type.
		 * @return int
		 */
		public function disable_wcpr_points( $points, $user_id, $event_type ) {
			if ( ( get_option( 'wcrs_integration_type_for_purchase' ) == 'wcrs-integration-purchase' ) ) {
				switch ( $event_type ) :
					case 'account-signup':
						return 0;
						break;
					case 'order-placed':
						return 0;
						break;
				endswitch;
			}
			return $points;
		}

		/**
		 * Function to customize cart message according to integration setting
		 *
		 * @param string $message message.
		 * @param int    $points points.
		 * @return string
		 */
		public function cart_message_config( $message, $points ) {
			$referrer         = wcrs_get_referrer_name_from_cookie();
			$integration_type = get_option( 'wcrs_integration_type_for_purchase' );
			if ( null == $referrer ) {
				return $message;
			}
			$message = get_option( 'wc_points_rewards_earn_points_message' );

			// $message = apply_filters( 'wrs_custom_cart_message' ,$message );

			switch ( $integration_type ) :
				case 'wcrs-integration-purchase':
					$points_earned = get_option( 'wcrs_int_order_referee' );
					if ( empty( $points_earned ) ) {
						return;
					}
					$message = wcrs_generate_checkout_message( $points_earned, $message );

					$message = '<div class="woocommerce-info wc_points_rewards_earn_points">' . $message . '</div>';
					return $message;
					break;

				case 'both-integration-purchase':
					$points_earned = get_option( 'wcrs_int_order_referee' ) + $points;

					$message = wcrs_generate_checkout_message( $points_earned, $message );

					$message = '<div class="woocommerce-info wc_points_rewards_earn_points">' . $message . '</div>';
					return $message;
					break;

			endswitch;
		}

		/**
		 * Function to clear cookie upon Logout or product purchase
		 *
		 * @param array $order order.
		 */
		public function clear_cookie( $order = array() ) {
			/*
			 @name: wrs_before_clear_cookie_action
			 @desc: Runs before clear cookie upon Logout or product purchase.
			 @package: codup-wc-referral-system
			 @module: frontend
			 @type: action
			*/
			do_action( 'wrs_before_clear_cookie_action' );

			if ( empty( $order ) ) {
				$order = new WC_Order( $order );
				$user  = get_user_by( 'email', $order->get_billing_email() );
				if ( ! isset( $user->ID ) ) {
					wcrs_delete_cookie_if_exist( 'referral_username' );
					wcrs_delete_cookie_if_exist( 'referral_id' );

					/*
					 @name: wrs_after_clear_cookie_action
					 @desc: Runs After clear cookie upon Logout or product purchase.
					 @package: codup-wc-referral-system
					 @module: frontend
					 @type: action
					*/
					do_action( 'wrs_after_clear_cookie_action' );
				}
			} else {
				wcrs_delete_cookie_if_exist( 'referral_username' );
				wcrs_delete_cookie_if_exist( 'referral_id' );

				/*
				 @name: wrs_after_clear_cookie_action
				 @desc: Runs After clear cookie upon Logout or product purchase.
				 @package: codup-wc-referral-system
				 @module: frontend
				 @type: action
				*/
				do_action( 'wrs_after_clear_cookie_action' );
			}
		}

		/**
		 * Fields added to the existing WCPR settings page
		 *
		 * @param array $settings settings.
		 * @return type
		 */
		public function wcpr_integration_fields( $settings ) {
			$settings1 = array(
				array(
					'title' => __( 'WooCommerce Referral System Integration - Points Settings', 'codup-wc-referral-system' ),
					'type'  => 'title',
					'id'    => 'wcrs_int_points_settings',
				),
				array(
					'title'    => __( 'Select Integration type', 'codup-wc-referral-system' ),
					'desc_tip' => __( 'Select the integration option whether you want to use Points and Rewards Settings for Purchase of Referral System settings for Purchase or both', 'codup-wc-referral-system' ),
					'id'       => 'wcrs_integration_type_for_purchase',
					'type'     => 'radio',
					'options'  => array(
						'wcrs-integration-purchase' => __( 'Use Referral System setting only', 'codup-wc-referral-system' ),
						'both-integration-purchase' => __( 'Use Referral System and Points & Rewards settings', 'codup-wc-referral-system' ),
					),
				),
				array(
					'title' => __( 'Enable Signup Points', 'codup-wc-referral-system' ),
					'desc'  => __( 'Offers reward points on new user signup.', 'codup-wc-referral-system' ),
					'id'    => 'wcrs_int_signup_type',
					'css'   => 'width:70px;',
					'type'  => 'checkbox',
				),
				array(
					'title'    => __( 'Signup points for Referrer', 'codup-wc-referral-system' ),
					'desc_tip' => __( 'Set the number of points awarded to referrer on referring. Leaving blank will not reward any point on this action.', 'codup-wc-referral-system' ),
					'id'       => 'wcrs_int_signup_referrer',
					'css'      => 'width:70px;',
					'type'     => 'number',
				),
				array(
					'title'    => __( 'Signup points for Referee', 'codup-wc-referral-system' ),
					'desc_tip' => __( 'Set the number of points awarded to referee on being referred. Leaving blank will not reward any point on this action.', 'codup-wc-referral-system' ),
					'id'       => 'wcrs_int_signup_referee',
					'css'      => 'width:70px;',
					'type'     => 'number',
				),
				array(
					'title' => __( 'Enable Purchase Points', 'codup-wc-referral-system' ),
					'desc'  => __( 'Offers reward points when referee makes a purchase.', 'codup-wc-referral-system' ),
					'id'    => 'wcrs_int_purchase_type',
					'css'   => 'width:70px;',
					'type'  => 'checkbox',
				),
				array(
					'name'    => __( 'Reward Type', 'codup-wc-referral-system' ),
					'type'    => 'select',
					'options' => array(
						'fixed_cart' => __( 'Fixed', 'codup-wc-referral-system' ),
						'percent'    => __( 'Percentage', 'codup-wc-referral-system' ),
					),
					'desc'    => __( 'Give rewards based on order total amount in % or Fixed Amount.', 'codup-wc-referral-system' ),
					'id'      => 'wcrspr_reward_type',
				),
				array(
					'title'    => __( 'Order points for Referrer', 'codup-wc-referral-system' ),
					'desc_tip' => __( 'Set the number of points awarded to referrer on  order of his referee. Leaving blank will not reward any point on this action.', 'codup-wc-referral-system' ),
					'id'       => 'wcrs_int_order_referrer',
					'css'      => 'width:70px;',
					'type'     => 'number',
				),
				array(
					'title'    => __( 'Order points for Referee', 'codup-wc-referral-system' ),
					'desc_tip' => __( 'Set the number of points awarded to referee on his order. Leaving blank will not reward any point on this action.', 'codup-wc-referral-system' ),
					'id'       => 'wcrs_int_order_referee',
					'css'      => 'width:70px;',
					'type'     => 'number',
				),
			);

			/*
			 @name: wrs_add_extra_settings_in_points_and_rewards
			 @desc: add more Fields added to the existing WCPR settings page.
			 @param: (Array) $settings1 settings1.
			 @package: codup-wc-referral-system
			 @module: admin
			 @type: filter
			*/
			$settings1[] = apply_filters( 'wrs_add_extra_settings_in_points_and_rewards', $settings1 );

			$settings1[] = array(
				'type' => 'sectionend',
				'id'   => 'wcrs-int-points-settings-end',
			);

			return array_merge( $settings1, $settings );
		}

		/**
		 * Add custom discount email on the list
		 *
		 * @param array $email_classes email_classes.
		 * @return \WC_Discount_Coupon_Email
		 */
		public function add_referral_discount_email( $email_classes ) {
			require plugin_dir_path( __FILE__ ) . 'class-referral-discount-email.php';
			$email_classes['WCRS_Discount_Coupon_Email'] = new REFERRAL_DISCOUNT_EMAIL();
			return $email_classes;
		}

		/**
		 * Locate custom template files for discount emails
		 *
		 * @param string $core_file core_file.
		 * @param string $template template.
		 * @param string $template_base template_base.
		 * @return string
		 */
		public function locate_core_template( $core_file, $template, $template_base ) {
			$custom_template = array(
				'emails/coupon-discount.php',
				'emails/plain/coupon-discount.php',
			);

			if ( in_array( $template, $custom_template ) ) {
				$core_file = trailingslashit( WCRS_PLUGIN_DIR . '/templates' ) . $template;
			}

			return $core_file;
		}

		/**
		 * Enqueue WP Admin JS and CSS files
		 */
		public function admin_assets() {
			// JS.
			$wcpr_activation = false;
			if ( in_array( 'woocommerce-points-and-rewards/woocommerce-points-and-rewards.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$wcpr_activation = true;
			}
			wp_enqueue_script( 'wcrs-custom-script', WCRS_ASSETS_DIR_URL . '/admin/js/wcrs-admin.js', array( 'jquery' ), rand() );
			$screen = get_current_screen();
			if ( 'woocommerce_page_ref-logs' == $screen->id ) {
				wp_enqueue_script( 'wcrs-datatable-script', WCRS_ASSETS_DIR_URL . '/admin/js/datatables.min.js', array( 'jquery' ), 1 );
			}
			wp_enqueue_script( 'wcrs-custom-script-v2', WCRS_ASSETS_DIR_URL . '/admin/js/wcrs-settings.js', array( 'jquery' ), rand() );
			wp_localize_script(
				'wcrs-custom-script',
				'wcrs_object',
				array(
					'shop_page_id' => wc_get_page_id( 'shop' ),
					'current_url'  => '?page=ref-logs',
				)
			);

			$percentage_based = false;
			if ( 'percent' == get_option( 'cwrs_reward_type' ) ) {
				$percentage_based = true;
			}

			wp_localize_script(
				'wcrs-custom-script',
				'wcrs_obj',
				array(
					'wcpr_activation'  => $wcpr_activation,
					'percentage_based' => $percentage_based,
				)
			);

			// CSS.
			wp_enqueue_style( 'wcrs-custom-style', WCRS_ASSETS_DIR_URL . '/admin/css/wcrs-admin-style.css', null, 1 );
			wp_enqueue_style( 'wcrs-data-style', WCRS_ASSETS_DIR_URL . '/admin/css/datatables.min.css', null, 1 );

			// select2.
			wp_enqueue_style( 'select2-styling', WCRS_ASSETS_DIR_URL . '/admin/css/select2.min.css', null, true );
			wp_enqueue_script( 'select2-script', WCRS_ASSETS_DIR_URL . '/admin/js/select2.min.js', array( 'jquery' ), true );
		}

		/**
		 * Add "Referral Logs" sub-menu page in woocommerce tab.
		 */
		public function admin_menu() {
			add_submenu_page( 'woocommerce', 'Referral Logs', 'Referral Logs', 'manage_options', 'ref-logs', array( $this, 'referral_logs_content' ) );
		}

		/**
		 * Referral Logs Content
		 */
		public function referral_logs_content() {

			$referral_logs = wcrs_get_log();
			$referrers     = wcrs_get_referrer_click_count();

			wc_get_template(
				'/admin/referral-logs.php',
				array(
					'referral_logs' => $referral_logs,
					'referrers'     => $referrers,
				),
				'woocommerce-referral-system',
				WCRS_TEMP_DIR
			);
		}

		/**
		 * Use to send coupon.
		 *
		 * @param array  $user user.
		 * @param array  $coupon coupon.
		 * @param string $deserve deserve.
		 * @param string $coupon_type coupon_type.
		 */
		public static function send_coupon_discount_email( $user, $coupon, $deserve, $coupon_type ) {

			$mailer = WC()->mailer();
			if ( ! is_string( $user ) ) {
				$recipient    = $user->user_email;
				$display_name = $user->display_name;
			} else {
				$recipient    = $user;
				$display_name = 'Customer';
			}

			$custom_subject = get_option( 'wcrs_subject_emails', false );

			if ( empty( $custom_subject ) ) {
				$custom_subject = __( "You've got Discount Coupon!", 'codup-wc-referral-system' );
			}

			$subject = $custom_subject;

			$content = self::get_woo_email_template( $display_name, $coupon, $subject, $mailer, $deserve, $coupon_type );
			$headers = "Content-Type: text/html\r\n";
			$mailer->send( $recipient, $subject, $content, $headers );
		}


		/**
		 * Use to send coupon.
		 *
		 * @param string  $display_name display_name.
		 * @param array   $coupon coupon.
		 * @param boolean $heading heading.
		 * @param array   $mailer mailer.
		 * @param string  $deserve deserve.
		 * @param string  $coupon_type coupon_type.
		 */
		public static function get_woo_email_template( $display_name, $coupon, $heading = false, $mailer, $deserve, $coupon_type ) {

			$template = '/emails/coupon-discount.php';

			return wc_get_template_html(
				$template,
				array(
					'email_heading' => $heading,
					'referrer_name' => $display_name,
					'coupon'        => $coupon,
					'deserve'       => $deserve,
					'coupon_type'   => $coupon_type,
					'sent_to_admin' => false,
					'plain_text'    => false,
					'email'         => $mailer,
				),
				'woocommerce-referral-system',
				WCRS_TEMP_DIR
			);
		}

	}

	new Referral_System_Core();
}
