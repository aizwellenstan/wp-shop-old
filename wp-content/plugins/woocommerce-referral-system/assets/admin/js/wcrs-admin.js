jQuery( document ).ready(
	function () {

		jQuery( '#wcrs_eligible_status' ).select2();

		if (window.location.search == wcrs_object.current_url) {
			jQuery( '#log_list' ).DataTable();
		}
		jQuery( "#wcrs_page" ).change(
			function () {
				if (this.value == wcrs_object.shop_page_id) {
					jQuery( "[for=wcrs_product]" ).parent().parent().show( "slow" );
				} else {
					jQuery( "[for=wcrs_product]" ).parent().parent().hide( "slow" );
				}
			}
		);

		if (jQuery( "#wcrs_page option:selected" ).val() == wcrs_object.shop_page_id) {
			jQuery( "[for=wcrs_product]" ).parent().parent().show();
		} else {
			jQuery( "[for=wcrs_product]" ).parent().parent().hide();
		}

		jQuery( "#cwrs_reward_type" ).change(
			function () {
				value = jQuery( this ).val();
				if (value === 'percent') {
					jQuery( "[for=wcrs_action_purchase_referral_coupon_amount]" ).text( 'Enter % Value for Referrer' );
					var referrer_am = jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).val();
					jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + referrer_am + '% of referee order total amount.' );

					jQuery( "[for=wcrs_action_purchase_referee_coupon_amount]" ).text( 'Enter % Value for Referee' );
					var referee_am = jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).val();
					jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + referee_am + '% of referee order total amount.' );

				} else {
					jQuery( "[for=wcrs_action_purchase_referral_coupon_amount]" ).text( 'Coupon' );
					jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).next().text( 'Type in the coupon amount you want your referrers to get when referees purchase their first item.' );

					jQuery( "[for=wcrs_action_purchase_referee_coupon_amount]" ).text( 'Coupon' );
					jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).next().text( 'Type in the coupon amount you want your referees to get when referees purchase their first item.' );

				}
			}
		);

		if (jQuery( "#cwrs_reward_type" ).val() === 'percent') {
			jQuery( "[for=wcrs_action_purchase_referral_coupon_amount]" ).text( 'Enter % Value for Referrer' );
			var referrer_am = jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).val();
			jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + referrer_am + '% of referee order total amount.' );

			jQuery( "[for=wcrs_action_purchase_referee_coupon_amount]" ).text( 'Enter % Value for Referee' );
			var referee_am = jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).val();
					jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + referee_am + '% of referee order total amount.' );

		} else {

			jQuery( "[for=wcrs_action_purchase_referral_coupon_amount]" ).text( 'Coupon' );
			var referrer_am = jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).val();
			jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).next().text( 'Type in the coupon amount you want your referrers to get when referees purchase their first item.' );

			jQuery( "[for=wcrs_action_purchase_referee_coupon_amount]" ).text( 'Coupon' );
			var referee_am = jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).val();
					jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).next().text( 'Type in the coupon amount you want your referees to get when referees purchase their first item.' );

		}

		jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).on(
			'keyup',
			function () {
				if (jQuery( "#cwrs_reward_type" ).val() === 'percent') {
					jQuery( "#wcrs_action_purchase_referral_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + jQuery( this ).val() + '% of referee order total amount.' );
				}
			}
		);

		jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).on(
			'keyup',
			function () {
				if (jQuery( "#cwrs_reward_type" ).val() === 'percent') {
					jQuery( "#wcrs_action_purchase_referee_coupon_amount" ).next().text( 'Fixed coupon will be generated based on ' + jQuery( this ).val() + '% of referee order total amount.' );
				}
			}
		);

		if (jQuery( "#wcrspr_reward_type" ).val() === 'percent') {
			jQuery( "[for=wcrs_int_order_referrer]" ).text( 'Enter % Value for Referrer' );
			jQuery( "[for=wcrs_int_order_referee]" ).text( 'Enter % Value for Referee' );
		}

		jQuery( "#wcrspr_reward_type" ).change(
			function () {
				value = jQuery( this ).val();
				if (value === 'percent') {
					jQuery( "[for=wcrs_int_order_referrer]" ).text( 'Enter % Value for Referrer' );
					jQuery( "[for=wcrs_int_order_referee]" ).text( 'Enter % Value for Referee' );
				} else {
					jQuery( "[for=wcrs_int_order_referrer]" ).text( 'Order points for Referrer' );
					jQuery( "[for=wcrs_int_order_referee]" ).text( 'Order points for Referee' );

				}
			}
		);
	}
);
