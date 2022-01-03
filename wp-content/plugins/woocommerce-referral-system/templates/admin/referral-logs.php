<?php
/**
 * The Template for displaying Referral Logs in WP Admin -> WooCommerece Menu
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap wcrs-referral-logs">

	<h1 id="heading"><?php esc_html_e( 'REFERRAL SYSTEM LOGS', 'codup-wc-referral-system' ); ?></h1>

	<h3><?php esc_html_e( 'Referrer Clicks', 'codup-wc-referral-system' ); ?></h3>

	<?php if ( $referrers ) { ?>
		<table id="referrer" >

			<tr>
				<th><?php esc_html_e( 'Referrer', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Counts', 'codup-wc-referral-system' ); ?></th>
			</tr>

			<?php
			foreach ( $referrers as $referrer ) {
				?>
				<tr>
					<td><?php echo esc_html( $referrer['referrer'] ); ?></td>
					<td><?php echo esc_html( $referrer['clicks'] ); ?></td>
				</tr>
				<?php
			}
			?>
		</table>
	<?php } else { ?>
		<p><?php esc_html_e( 'No referrer found', 'codup-wc-referral-system' ); ?><p>
		<?php } ?>

	<h3><?php esc_html_e( 'Referral Logs', 'codup-wc-referral-system' ); ?></h3>

	<?php if ( $referral_logs ) { ?>

		<table id="log_list"> 
			<thead>
			<tr>
				<th><?php esc_html_e( 'Visit ID', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Time', 'codup-wc-referral-system' ); ?></th>
				<th><?php esc_html_e( 'Username', 'codup-wc-referral-system' ); ?></th> 
				<th><?php esc_html_e( 'IP', 'codup-wc-referral-system' ); ?></th> 
				<th><?php esc_html_e( 'Country', 'codup-wc-referral-system' ); ?></th> 
				<th><?php esc_html_e( 'Referral URL', 'codup-wc-referral-system' ); ?></th> 
				<th><?php esc_html_e( 'Landing URL', 'codup-wc-referral-system' ); ?></th> 
				<th><?php esc_html_e( 'Referrer Name', 'codup-wc-referral-system' ); ?></th> 
			</tr>
			</thead>
			<?php
			foreach ( $referral_logs as $referral_log ) {
				?>
				<tr>
					<td><?php echo esc_html( $referral_log->visit_id ); ?></td>
					<td><?php echo esc_html( $referral_log->time ); ?></td> 
					<td><?php echo esc_html( $referral_log->login_username ); ?></td> 
					<td><?php echo esc_html( $referral_log->IP ); ?></td> 
					<td><?php echo esc_html( $referral_log->Country ); ?></td> 
					<td><?php echo esc_html( $referral_log->referrer_url ); ?></td> 
					<td><?php echo esc_html( $referral_log->landing_url ); ?></td> 
					<td><?php echo esc_html( $referral_log->referrer ); ?></td> 
				</tr>
				<?php
			}
			?>
		</table>
	<?php } else { ?>
		<p><?php esc_html_e( 'No logs found', 'codup-wc-referral-system' ); ?><p>
		<?php } ?>

</div>
