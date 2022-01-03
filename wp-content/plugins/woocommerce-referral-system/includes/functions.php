<?php
/**
 * Functions File.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all publish wp pages
 *
 * @return type
 */
function wcrs_get_pages() {
	$pages = array();
	$args  = array(
		'post_status' => 'publish',
	);

	$result = get_pages( $args, ARRAY_A, 'page' );
	foreach ( $result as $page ) {
		$pages[ $page->ID ] = $page->post_name;
	}

	return $pages;
}

/**
 * Get all published wc products
 *
 * @return object
 */
function wcrs_get_products() {
	$products                             = array();
	$products[ wc_get_page_id( 'shop' ) ] = 'Select a product';
	$args                                 = array(
		'posts_per_page' => -1,
		'post_type'      => 'product',
		'post_status'    => 'publish',
	);

	$result = get_posts( $args );
	foreach ( $result as $product ) {
		$products[ $product->ID ] = $product->post_name;
	}

	return $products;
}

/**
 * Set cookie.
 *
 * @param string $value value.
 * @param string $referrer referrer.
 * @param array  $time time array.
 */
function wcrs_set_cookie( $value, $referrer = null, $time = null ) {

	if ( null == $value ) {
		unset( $_COOKIE['wcrs_visitor_id'] );
		unset( $_COOKIE['wcrs_referrer'] );
	}

	if ( ! empty( $_COOKIE['wcrs_visitor_id'] ) ) {
		return;
	}

	if ( ! $time ) {
		$time = time() + ( DAY_IN_SECONDS * get_option( 'wcrs_cookie_expiry_time' ) );
	}

	setcookie( 'wcrs_referrer', $referrer, $time, COOKIEPATH, COOKIE_DOMAIN );
	setcookie( 'wcrs_visitor_id', $value, $time, COOKIEPATH, COOKIE_DOMAIN );
}

/**
 * Function to get configured discount type from admin panel
 *
 * @return type
 */
function wcrs_get_signup_discount_type() {

	$discount_type = get_option( 'wcrs_signup_discount_type' );
	if ( empty( $discount_type ) ) {
		$discount_type = get_option( 'wcrs_discount_type' );
	}
	return $discount_type;
}

/**
 * Function to get configured discount type from admin panel
 *
 * @return type
 */
function wcrs_get_order_discount_type() {
	$discount_type = get_option( 'wcrs_order_discount_type' );
	if ( empty( $discount_type ) ) {
		$discount_type = get_option( 'wcrs_discount_type' );
	}
	return $discount_type;
}

/**
 * Get cookie
 */
function wcrs_get_cookie() {
	if ( ! empty( $_COOKIE['wcrs_referrer'] ) && ! empty( $_COOKIE['wcrs_visitor_id'] ) ) {
		return $_COOKIE;
	}
}

/**
 * Add number of days from settings to the current date
 * and return in date format
 *
 * @param string $input date.
 * @return type.
 */
function get_converted_date( $input ) {
	$days = filter_var( get_option( $input ), FILTER_SANITIZE_NUMBER_INT );
	if ( null != $days ) {
		return gmdate( 'd-m-Y', strtotime( "+$days day" ) );
	}
	return 0;
}

/**
 * Get country by passing IP Address to "ipinfo.io"
 *
 * @param int $ip IP.
 * @return string
 */
function wcrs_get_country_by_ip( $ip = null ) {

	if ( ! $ip ) {
		$ip = getenv( 'REMOTE_ADDR' ); // 3 errors
	}
	$country = null;
	$results = file_get_contents( "http://ipinfo.io/{$ip}/json" );
	if ( $results ) {

		$ip_details = json_decode( $results );

		if ( isset( $ip_details->country ) ) {
			$country = $ip_details->country;
		}
	}

	return $country;
}

/**
 * Get redirect url after save logging
 *
 * @return type
 */
function wcrs_get_redirect_url() {

	$redirect_url     = site_url();
	$redirect_to_page = get_option( 'wcrs_page' );
	if ( $redirect_to_page ) {

		$shop_page_id = wc_get_page_id( 'shop' );

		if ( $redirect_to_page == $shop_page_id ) {

			$redirect_url        = get_permalink( wc_get_page_id( 'shop' ) );
			$redirect_to_product = get_option( 'wcrs_product' );
			if ( $redirect_to_product ) {

				$redirect_url = get_permalink( $redirect_to_product );
			}
		} else {
			$redirect_url = get_permalink( $redirect_to_page );
		}
	}

	return $redirect_url;
}

/**
 * Get all referral logs
 *
 * @global type $wpdb
 * @return type
 */
function wcrs_get_log() {
	global $wpdb;

	return $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'wcrs_logs' );
}

/**
 * Update referral log(s)
 *
 * @param array  $data Data.
 * @param string $where Where.
 */
function wcrs_update_log( $data, $where ) {
	global $wpdb;

	return $wpdb->update( $wpdb->prefix . 'wcrs_logs', $data, $where );
}

/**
 * Function to generate coupons upon given parameters
 *
 * @param string $discount_type discount_type.
 * @param int    $discount_amount discount_amount.
 * @param string $coupon_expiry coupon_expiry.
 * @param string $user_email user_email.
 * @param string $reason reason.
 * @return type.
 */
function wcrs_generate_coupon( $discount_type, $discount_amount, $coupon_expiry, $user_email, $reason ) {
	$coupon_code = substr( str_shuffle( '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 1, 8 );
	$coupon      = new WC_Coupon( $coupon_code );
	if ( 0 == $coupon->get_data()['id'] ) {
		$coupon = array(
			'post_title'   => $coupon_code,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_type'    => 'shop_coupon',
			'post_excerpt' => $reason,
		);

		$new_coupon_id = wp_insert_post( $coupon );

		$coupon_args = array(
			'discount_type'        => $discount_type,
			'coupon_amount'        => $discount_amount,
			'individual_use'       => 'yes',
			'usage_limit'          => '1',
			'usage_limit_per_user' => '1',
			'expiry_date'          => $coupon_expiry,
			'apply_before_tax'     => 'yes',
			'free_shipping'        => 'no',
			'wfs_awarded'          => 'yes',
			'customer_email'       => $user_email,
		);

		update_post_meta( $new_coupon_id, 'discount_type', $coupon_args['discount_type'] );
		update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_args['coupon_amount'] );
		update_post_meta( $new_coupon_id, 'individual_use', $coupon_args['individual_use'] );
		update_post_meta( $new_coupon_id, 'product_ids', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', $coupon_args['usage_limit'] );
		update_post_meta( $new_coupon_id, 'usage_limit_per_user', $coupon_args['usage_limit_per_user'] );
		update_post_meta( $new_coupon_id, 'expiry_date', date_format( date_create( $coupon_args['expiry_date'] ), 'Y-m-d H:i:s' ) );
		update_post_meta( $new_coupon_id, 'apply_before_tax', $coupon_args['apply_before_tax'] );
		update_post_meta( $new_coupon_id, 'free_shipping', $coupon_args['free_shipping'] );
		// set meta with coupon to make it easy identify.
		add_post_meta( $new_coupon_id, 'wfs_awarded', $coupon_args['wfs_awarded'] );
		// attached allowed user for coupon.
		update_post_meta( $new_coupon_id, 'customer_email', $coupon_args['customer_email'] );

		/*
		@name: wrs_coupon_args_after_updating_meta
		@desc: Modify Coupon meta args array.
		@param: (int) $new_coupon_id new_coupon_id.
		@param: (array) $coupon_args coupon_args array.
		@package: codup-wc-referral-system
		@module: backend
		@type: action
		*/
		do_action( 'wrs_coupon_args_after_updating_meta', $new_coupon_id, $coupon_args );

		return $new_coupon_id;
	} else {
		return wcrs_generate_coupon( $discount_type, $discount_amount, $coupon_expiry );
	}
}

/**
 * Return the row against visitor ID
 *
 * @param string $visitor_id visitor_id.
 * @return array,
 */
function wcrs_get_row_by_visitor_id( $visitor_id ) {
	global $wpdb;
	$placeholders[0] = $wpdb->prefix . 'wcrs_logs';
	$placeholders[1] = $visitor_id;

	$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %1s WHERE visitor_id='%2s';", $placeholders ) );

	if ( $result ) {
		return $result[0];
	}
	return false;
}

/**
 * Insert referral logs and return log id
 *
 * @param array $data data.
 * @return array.
 */
function wcrs_insert_logs( $data ) {

	global $wpdb;

	do_action( 'wcrs_before_add_log' );

	$result = $wpdb->insert( $wpdb->prefix . 'wcrs_logs', $data );

	do_action( 'wcrs_after_add_log' );

	return $result;
}

/**
 * Function to clear cookie
 *
 * @param string $cookie cookie.
 */
function wcrs_delete_cookie_if_exist( $cookie ) {
	if ( isset( $_COOKIE[ 'STYXKEY_' . $cookie ] ) ) {
		setcookie( 'STYXKEY_' . $cookie, '', time() - ( 15 * 60 ), COOKIEPATH, COOKIE_DOMAIN );
		unset( $_COOKIE[ 'STYXKEY_' . $cookie ] );
	}
}

/**
 * Get username by referrer type
 *
 * @param string $referrer referrer.
 */
function wcrs_get_user( $referrer ) {
	return username_exists( $referrer ) ? get_user_by( 'login', $referrer ) : get_user_by( 'id', $referrer );
}

/**
 * Get the referrer userID or username depending on setting
 *
 * @param int $user_id user_id.
 * @return type
 */
function wcrs_get_referrer_name_from_cookie( $user_id = 0 ) {

	$referrer_id = get_user_meta( $user_id, 'referrer_user', false );

	if ( $referrer_id ) {
		$referrer = get_user_by( 'id', $referrer_id[0] )->user_login;
	}

	if ( empty( $referrer ) ) {

		$referrer = ( wcrs_get_cookie( 'referral_username' ) ) ? wcrs_get_cookie( 'referral_username' ) : wcrs_get_cookie( 'referral_id' );

	}
	return $referrer;

}

/**
 * Get current user referral url
 *
 * @return string
 */
function wcrs_get_current_referral_url() {
	$current_user = wp_get_current_user();
	if ( $current_user ) {

		$type = get_option( 'wcrs_type' );
		$slug = get_option( 'wcrs_slug' );

		switch ( $type ) {
			case 'Id':
				$referral_url = add_query_arg( $slug, $current_user->ID, home_url() );
				break;
			default:
				$referral_url = add_query_arg( $slug, $current_user->user_login, home_url() );
		}

		return $referral_url;
	}
}

/**
 * Check mandatory settings of plugin
 *
 * @return boolean
 */
function wcrs_check_mandatory_settings() {
	if ( ! get_option( 'wcrs_slug' ) ) {
		update_option( 'wcrs_type', 'ref' );
	}

	if ( ! get_option( 'wcrs_type' ) ) {
		update_option( 'wcrs_type', 'Name' );
	}

	return true;
}

/**
 * Render message for cart and checkout page when WCP&R plugin enabled
 *
 * @param int    $points_earned points_earned.
 * @param string $message message.
 * @return type
 */
function wcrs_generate_checkout_message( $points_earned, $message ) {
	global $wc_points_rewards;
	$message = str_replace( '{points}', number_format_i18n( $points_earned ), $message );
	$message = str_replace( '{points_label}', $wc_points_rewards->get_points_label( $points_earned ), $message );
	return $message;
}

/**
 * Create table for logs.
 */
function wcrs_create_log_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . "wcrs_logs (
            visit_id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NULL,
            visitor_id varchar(55) NULL,
            IP varchar(55) DEFAULT '' NULL,
            Country varchar(55) DEFAULT '' NULL,
            login_username varchar(55) NULL,
            referrer_url varchar(55) DEFAULT '' NULL,
            landing_url varchar(55) DEFAULT '' NULL,
            referrer varchar(55) NULL,
            keytype varchar(55) NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NULL,
            updated_at datetime DEFAULT '0000-00-00 00:00:00' NULL,
            extra_meta text NULL,
            PRIMARY KEY  (visit_id)
        ) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Add Default Settings.
 */
function wcrs_add_default_settings() {
	if ( ! get_option( 'wcrs_slug' ) ) {
		add_option( 'wcrs_slug', 'ref' );
	}
	if ( ! get_option( 'wcrs_cookie_expiry_time' ) ) {
		add_option( 'wcrs_cookie_expiry_time', '5' );
	}

	if ( ! get_option( 'wcrs_functionality_type' ) ) {
		update_option( 'wcrs_functionality_type', 'wcrs-func' );
	}

	if ( ! get_option( 'wcrs_eligible_status' ) ) {
		update_option( 'wcrs_eligible_status', array( 'processing' ) );
	}
}

/**
 * Get All Referrers from log table.
 *
 * @return array.
 */
function wcrs_get_all_referrers() {
	global $wpdb;

	return $wpdb->get_results( 'SELECT DISTINCT referrer FROM ' . $wpdb->prefix . 'wcrs_logs' );
}

/**
 * Get All Referrers clicks.
 *
 * @return array.
 */
function wcrs_get_referrer_click_count() {
	global $wpdb;
	$referrers     = array();
	$all_referrers = wcrs_get_all_referrers();

	if ( $all_referrers ) {

		$placeholders[0] = $wpdb->prefix . 'wcrs_logs';

		$x = 0;

		foreach ( $all_referrers as $referrer ) {

			$placeholders[1] = $referrer->referrer;

			$total_clicks = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(referrer) as total_count FROM %1s WHERE referrer = '%2s' ", $placeholders ) );
			// $total_clicks = $wpdb->get_results( $sql );
			$referrers[ $x ]['referrer'] = $referrer->referrer;
			$referrers[ $x ]['clicks']   = $total_clicks[0]->total_count;

			$x++;
		}
	}
	return $referrers;
}

/**
 * Return if auth goes right else return error.
 */
function wrs_auth_user_from_api() {

	error_reporting( 0 );

	require_once WooCommerce::plugin_path() . '/includes/legacy/api/v3/class-wc-api-authentication.php';
	$test = new WC_API_Authentication();

	$user   = get_current_user();
	$result = $test->authenticate( $user );

	$result_array = (array) $result;

	if ( ! empty( $result_array['data']->ID ) && 0 != $result_array['data']->ID ) {
		return true;
	}

	return $result;

}

/**
 * Get passed user referral url
 *
 * @param array $current_user current_user.
 * @return string
 */
function wcrs_get_referral_url( $current_user ) {
	if ( $current_user ) {

		$type = get_option( 'wcrs_type' );
		$slug = get_option( 'wcrs_slug' );

		switch ( $type ) {
			case 'Id':
				$referral_url = add_query_arg( $slug, $current_user->ID, home_url() );
				break;
			default:
				$referral_url = add_query_arg( $slug, $current_user->user_login, home_url() );
		}

		return $referral_url;
	}
}

/**
 * Return options of all settings of plugin.
 *
 * @return Array
 */
function wcrs_options_all_settings() {
	$options['wcrs_functionality_type']              = array( 'wcrs-func', 'wcpr-func' );
	$options['wcrspr_minimum_purchase_limit_enable'] = array( 'yes', 'no' );
	$options['wcrs_type']                            = array( 'Id', 'Name' );
	$options['wcrs_page']                            = wcrs_get_pages();
	$options['wcrs_product']                         = wcrs_get_products();
	$options['wcrs_action_signup_type']              = array( 'yes', 'no' );
	$options['wcrs_signup_discount_type']            = array( 'fixed_cart', 'percent' );
	$options['wcrs_action_signup_referral']          = array( 'yes', 'no' );
	$options['wcrs_action_signup_referee']           = array( 'yes', 'no' );
	$options['wcrs_action_purchase_type']            = array( 'yes', 'no' );
	$options['cwrs_reward_type']                     = array( 'fixed_cart', 'percent' );
	$options['wcrs_order_discount_type']             = array( 'fixed_cart', 'percent' );
	$options['wcrs_minimum_purchase_limit_enable']   = array( 'yes', 'no' );
	$options['wcrs_action_purchase_referral']        = array( 'yes', 'no' );
	$options['wcrs_action_purchase_referee']         = array( 'yes', 'no' );

	$options['wcrs_integration_type_for_purchase'] = array( 'wcrs-integration-purchase', 'both-integration-purchase' );
	$options['wcrs_int_signup_type']               = array( 'yes', 'no' );
	$options['wcrs_int_purchase_type']             = array( 'yes', 'no' );
	$options['wcrspr_reward_type']                 = array( 'fixed_cart', 'percent' );

	return $options;
}

/**
 * Return true or false of all settings of plugin after validation.
 *
 * @param string $key key.
 * @param int    $value value.
 * @return Array
 */
function wcrs_validate_all_int_settings( $key, $value ) {
	$validation[] = 'wcrspr_minimum_purchase_limit';
	$validation[] = 'wcrspr_valid_no_orders';
	$validation[] = 'wcrs_cookie_expiry_time';
	$validation[] = 'wcrs_action_signup_referral_coupon_amount';
	$validation[] = 'wcrs_action_signup_referral_coupon_expiry';
	$validation[] = 'wcrs_action_signup_referee_coupon_amount';
	$validation[] = 'wcrs_action_signup_referee_coupon_expiry';
	$validation[] = 'wcrs_minimum_purchase_limit';
	$validation[] = 'wcrs_valid_no_orders';
	$validation[] = 'wcrs_action_purchase_referral_coupon_amount';
	$validation[] = 'wcrs_action_purchase_referral_coupon_expiry';
	$validation[] = 'wcrs_action_purchase_referee_coupon_amount';
	$validation[] = 'wcrs_action_purchase_referee_coupon_expiry';
	$validation[] = 'wcrs_int_signup_referrer';
	$validation[] = 'wcrs_int_signup_referee';
	$validation[] = 'wcrs_int_order_referrer';
	$validation[] = 'wcrs_int_order_referee';

	if ( in_array( $key, $validation ) ) {

		if ( is_numeric( $value ) && ( $value <= 0 ) ) {

			return false;

		}

		return is_numeric( $value );
	}

	return true;

}
