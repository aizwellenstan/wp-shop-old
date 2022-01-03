<?php
/**
 * The Template for displaying Referral Link content tab on WooCommerce My Account page.
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();

if ( $user_id ) {
	$user    = get_user_by( 'id', $user_id );
	$u_email = $user->data->user_email;

	$args = array(
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'desc',
		'post_type'      => 'shop_coupon',
		'post_status'    => 'publish',
	);

	$coupons = get_posts( $args );
}
?>

<div class='my_coupons_content'>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Coupon Code', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Expiry date', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Coupon type', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Coupon Amount / %', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Awarded For', 'codup-wc-referral-system' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( $coupons ) {
				foreach ( $coupons as $coupon ) {
					$not_user = false;
					$allowed  = get_post_meta( $coupon->ID, 'customer_email' );

					if ( $allowed ) {
						foreach ( $allowed as $key => $email ) {

							if ( $u_email != $email ) {
								$not_user = true;
							}
						}
					} else {
						continue;
					}

					$my_coupon = new WC_Coupon( $coupon->ID );

					$our_coupon = get_post_meta( $coupon->ID, 'wfs_awarded' );
					if ( 'yes' !== $our_coupon[0] || true == $not_user ) {
						continue;
					}

					$date_expires  = $my_coupon->get_date_expires() ? $my_coupon->get_date_expires()->format( 'Y-m-d' ) : '-';
					$discount_type = $my_coupon->get_discount_type();
					$coupon_amount = $my_coupon->get_amount();
					$reason        = $coupon->post_excerpt;
					?>
			<tr>
				<td><?php echo esc_html( $coupon->post_name ); ?></td>
				<td><?php echo esc_html( $date_expires ); ?></td>
				<td><?php echo esc_html( $discount_type ); ?></td>
				<td><?php echo esc_html( $coupon_amount ); ?></td>
				<td><?php echo esc_html( $reason ); ?></td>
			</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
</div>
