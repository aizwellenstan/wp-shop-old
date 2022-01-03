jQuery(document).ready(function ($) {

	var label_wcrs_action_signup_referral = $("[for=wcrs_action_signup_referral]").parent().parent().parent();
	var label_wcrs_action_signup_referee = $("[for=wcrs_action_signup_referee]").parent().parent().parent();
	var label_wcrs_signup_discount_type = $("[for=wcrs_signup_discount_type]").parent().parent();

	var label_wcrs_action_signup_referral_coupon_amount = $("[for=wcrs_action_signup_referral_coupon_amount]").parent().parent();
	var label_wcrs_action_signup_referral_coupon_expiry = $("[for=wcrs_action_signup_referral_coupon_expiry]").parent().parent();

	var label_wcrs_action_signup_referee_coupon_amount = $("[for=wcrs_action_signup_referee_coupon_amount]").parent().parent();
	var label_wcrs_action_signup_referee_coupon_expiry = $("[for=wcrs_action_signup_referee_coupon_expiry]").parent().parent();

	var label_wcrs_action_purchase_referral_coupon_amount = $("[for=wcrs_action_purchase_referral_coupon_amount]").parent().parent();
	var label_wcrs_action_purchase_referral_coupon_expiry = $("[for=wcrs_action_purchase_referral_coupon_expiry]").parent().parent();

	var label_wcrs_action_purchase_referee_coupon_amount = $("[for=wcrs_action_purchase_referee_coupon_amount]").parent().parent();
	var label_wcrs_action_purchase_referee_coupon_expiry = $("[for=wcrs_action_purchase_referee_coupon_expiry]").parent().parent();

	var wcrs_minimum_purchase_limit_parent = $("#wcrs_minimum_purchase_limit").parent().parent();
	var wcrs_amount_based_bewards_parent = $("#wcrs_amount_based_bewards").parent().parent();

	var label_wcrs_discount_type = $("[for=wcrs_discount_type]").parent().parent();
	var label_wcrs_action_signup_type = $("[for=wcrs_action_signup_type]").parent().parent().parent();
	var label_wcrs_action_purchase_type = $("[for=wcrs_action_purchase_type]").parent().parent().parent();
	var label_wcrspr_minimum_purchase_limit_enable = $('[for=wcrspr_minimum_purchase_limit_enable]').parent().parent().parent();
	var label_wcrspr_minimum_purchase_limit = $('[for=wcrspr_minimum_purchase_limit]').parent().parent();
	var label_wcrspr_valid_no_orders = $('[for=wcrspr_valid_no_orders]').parent().parent();
	var label_wcrs_action_purchase_referral = $("[for=wcrs_action_purchase_referral]").parent().parent().parent();
	var label_wcrs_action_purchase_referee = $("[for=wcrs_action_purchase_referee]").parent().parent().parent();

	var label_wcrs_valid_no_orders = $("[for=wcrs_valid_no_orders]").parent().parent();
	var label_cwrs_reward_type = $("[for=cwrs_reward_type]").parent().parent();
	var label_wcrs_minimum_purchase_limit_enable = $("[for=wcrs_minimum_purchase_limit_enable]").parent().parent().parent();
	var label_wcrs_order_discount_type = $("[for=wcrs_order_discount_type]").parent().parent();

	var wcrspr_reward_type_parent = $("#wcrspr_reward_type").parent().parent();

	wcrs_minimum_purchase_limit_parent.hide();
	wcrs_amount_based_bewards_parent.hide();

	if ($( '#cwrs_reward_type' ).val() == 'percent') {
		$( '#wcrs_order_discount_type').val($( '#wcrs_order_discount_type option:eq(0)' ).val());
		$( '#wcrs_order_discount_type' ).attr('disabled','disabled');

		$('#wcrs_action_purchase_referral_coupon_amount').attr('type','number');
		$('#wcrs_action_purchase_referral_coupon_amount').attr('min','1');
		$('#wcrs_action_purchase_referral_coupon_amount').attr('max','100');

		$('#wcrs_action_purchase_referee_coupon_amount').attr('type','number');
		$('#wcrs_action_purchase_referee_coupon_amount').attr('min','1');
		$('#wcrs_action_purchase_referee_coupon_amount').attr('max','100');
	}

	if ($( '#wcrs_order_discount_type' ).val() == 'percent') {

		$('#wcrs_action_purchase_referral_coupon_amount').attr('type','number');
		$('#wcrs_action_purchase_referral_coupon_amount').attr('min','1');
		$('#wcrs_action_purchase_referral_coupon_amount').attr('max','100');

		$('#wcrs_action_purchase_referee_coupon_amount').attr('type','number');
		$('#wcrs_action_purchase_referee_coupon_amount').attr('min','1');
		$('#wcrs_action_purchase_referee_coupon_amount').attr('max','100');
	}

	if ($( '#wcrs_signup_discount_type' ).val() == 'percent') {

		$('#wcrs_action_signup_referral_coupon_amount').attr('type','number');
		$('#wcrs_action_signup_referral_coupon_amount').attr('min','1');
		$('#wcrs_action_signup_referral_coupon_amount').attr('max','100');

		$('#wcrs_action_signup_referee_coupon_amount').attr('type','number');
		$('#wcrs_action_signup_referee_coupon_amount').attr('min','1');
		$('#wcrs_action_signup_referee_coupon_amount').attr('max','100');
	}

	$( '#wcrs_order_discount_type' ).on(
		'change',
		function () {

			if ($(this).val() == 'percent') {

				$('#wcrs_action_purchase_referral_coupon_amount').attr('type','number');
				$('#wcrs_action_purchase_referral_coupon_amount').attr('min','1');
				$('#wcrs_action_purchase_referral_coupon_amount').attr('max','100');

				$('#wcrs_action_purchase_referee_coupon_amount').attr('type','number');
				$('#wcrs_action_purchase_referee_coupon_amount').attr('min','1');
				$('#wcrs_action_purchase_referee_coupon_amount').attr('max','100');
			}
			else{

				$('#wcrs_action_purchase_referral_coupon_amount').removeAttr('min');
				$('#wcrs_action_purchase_referral_coupon_amount').removeAttr('max');

				$('#wcrs_action_purchase_referee_coupon_amount').removeAttr('min');
				$('#wcrs_action_purchase_referee_coupon_amount').removeAttr('max');

			}

		});

	$( '#wcrs_signup_discount_type' ).on(
		'change',
		function () {

			if ($(this).val() == 'percent') {

				$('#wcrs_action_signup_referral_coupon_amount').attr('type','number');
				$('#wcrs_action_signup_referral_coupon_amount').attr('min','1');
				$('#wcrs_action_signup_referral_coupon_amount').attr('max','100');

				$('#wcrs_action_signup_referee_coupon_amount').attr('type','number');
				$('#wcrs_action_signup_referee_coupon_amount').attr('min','1');
				$('#wcrs_action_signup_referee_coupon_amount').attr('max','100');
			}
			else{

				$('#wcrs_action_signup_referral_coupon_amount').removeAttr('min');
				$('#wcrs_action_signup_referral_coupon_amount').removeAttr('max');

				$('#wcrs_action_signup_referee_coupon_amount').removeAttr('min');
				$('#wcrs_action_signup_referee_coupon_amount').removeAttr('max');

			}

		});

	$( '#cwrs_reward_type' ).on(
		'change',
		function () {

			if ($( '#cwrs_reward_type' ).val() == 'percent') {

				$( '#wcrs_order_discount_type').val($( '#wcrs_order_discount_type option:eq(0)' ).val());
				$( '#wcrs_order_discount_type' ).attr('disabled','disabled');

				$('#wcrs_action_purchase_referral_coupon_amount').attr('type','number');
				$('#wcrs_action_purchase_referral_coupon_amount').attr('min','1');
				$('#wcrs_action_purchase_referral_coupon_amount').attr('max','100');

				$('#wcrs_action_purchase_referee_coupon_amount').attr('type','number');
				$('#wcrs_action_purchase_referee_coupon_amount').attr('min','1');
				$('#wcrs_action_purchase_referee_coupon_amount').attr('max','100');

				
			} else {
				$( '#wcrs_order_discount_type' ).removeAttr('disabled');

				$( '#wcrs_action_purchase_referral_coupon_amount' ).removeAttr('min');
				$( '#wcrs_action_purchase_referral_coupon_amount' ).removeAttr('max');

				$( '#wcrs_action_purchase_referee_coupon_amount' ).removeAttr('min');
				$( '#wcrs_action_purchase_referee_coupon_amount' ).removeAttr('max');

			}
		}
		);

	if ($( 'input[name="wcrs_minimum_purchase_limit_enable"]' ).is( ":checked" )) {
		wcrs_minimum_purchase_limit_parent.show();
		$( '#wcrs_minimum_purchase_limit' ).show();
		$( '#wcrs_minimum_purchase_limit' ).attr('required','true');
	}
	else{
		wcrs_minimum_purchase_limit_parent.hide();
		$( '#wcrs_minimum_purchase_limit' ).removeAttr('required');

	}

	if ($( 'input[name="wcrs_amount_based_bewards_enabled"]' ).is( ":checked" )) {
		wcrs_amount_based_bewards_parent.show();
		$( '#wcrs_amount_based_bewards' ).show();
		$( '#wcrs_amount_based_bewards' ).attr('required','true');
	}

	$( 'input[name="wcrs_minimum_purchase_limit_enable"]' ).on(
		'click',
		function () {

			if ($( this ).is( ":checked" )) {
				$('#wcrs_minimum_purchase_limit').show();
				$('#wcrs_minimum_purchase_limit').parent().show();
				wcrs_minimum_purchase_limit_parent.show();
				$( '#wcrs_minimum_purchase_limit' ).attr('required','true');
			} else {

				wcrs_minimum_purchase_limit_parent.hide();
				$( '#wcrs_minimum_purchase_limit' ).removeAttr('required');
			}
		}
		);

	$( 'input[name="wcrs_amount_based_bewards_enabled"]' ).on(
		'click',
		function () {

			if ($( this ).is( ":checked" )) {
				wcrs_amount_based_bewards_parent.show();
				$( '#wcrs_amount_based_bewards' ).show();
				$( '#wcrs_amount_based_bewards' ).attr('required','true');
			} else {
				wcrs_amount_based_bewards_parent.hide();
				$( '#wcrs_amount_based_bewards' ).removeAttr('required');
			}
		}
		);


	if (!wcrs_obj.wcpr_activation) {
		$("[name='wcrs_functionality_type'][value='wcpr-func']").prop("disabled",true);
		$("[name='wcrs_functionality_type'][value='wcpr-func']").parent().addClass('disable-wcpr-function');
		$('<span>         &nbsp(To make this option enable, please activate WPR plugin first.)</span>').insertAfter($("[name='wcrs_functionality_type'][value='wcpr-func']").parent());
	}

	/**
	 * Sigup type 
	 */
	 if ($("#wcrs_action_signup_type").is( ":checked" )) {
	 	label_wcrs_action_signup_referral.show();
	 	label_wcrs_action_signup_referee.show();
	 	label_wcrs_signup_discount_type.show();
	 } else {

	 	label_wcrs_action_signup_referral.hide();
	 	label_wcrs_action_signup_referee.hide();
	 	label_wcrs_signup_discount_type.hide();
	 }

	/**
	 * Upon signup action referral
	 */
	 if ($("#wcrs_action_signup_referral").is( ":checked" )) {
	 	label_wcrs_action_signup_referral_coupon_amount.show();
	 	label_wcrs_action_signup_referral_coupon_expiry.show();
	 	$("[name=wcrs_action_signup_referral_coupon_expiry]").attr('required','true');
	 } else {
	 	label_wcrs_action_signup_referral_coupon_amount.hide();
	 	$("[name=wcrs_action_signup_referral_coupon_expiry]").removeAttr('required');
	 	label_wcrs_action_signup_referral_coupon_expiry.hide();
	 }

	/**
	 * Upon signup action referee
	 */
	 if ($("#wcrs_action_signup_referee").is( ":checked" )) {
	 	label_wcrs_action_signup_referee_coupon_amount.show();
	 	label_wcrs_action_signup_referee_coupon_expiry.show();
	 	$("[name=wcrs_action_signup_referee_coupon_expiry]").attr('required','true');
	 } else {
	 	label_wcrs_action_signup_referee_coupon_amount.hide();
	 	$("[name=wcrs_action_signup_referee_coupon_expiry]").removeAttr('required');
	 	label_wcrs_action_signup_referee_coupon_expiry.hide();
	 }

	/**
	 * Upon purchase action referal
	 */
	 if ($("#wcrs_action_purchase_referral").is( ":checked" )) {
	 	label_wcrs_action_purchase_referral_coupon_amount.show();
	 	label_wcrs_action_purchase_referral_coupon_expiry.show();
	 } else {
	 	label_wcrs_action_purchase_referral_coupon_amount.hide();
	 	label_wcrs_action_purchase_referral_coupon_expiry.hide();
	 }

	/**
	 * Upon purchase action referee
	 */
	 if ($("#wcrs_action_purchase_referee").is( ":checked" )) {
	 	label_wcrs_action_purchase_referee_coupon_amount.show();
	 	label_wcrs_action_purchase_referee_coupon_expiry.show();
	 } else {
	 	label_wcrs_action_purchase_referee_coupon_amount.hide();
	 	label_wcrs_action_purchase_referee_coupon_expiry.hide();
	 }

	/**
	 * Change function of all buttons
	 */
	 $("[name=wcrs_functionality_type]").change(function () {
	 	if ($(this).val() == 'wcrs-func') {
	 		label_wcrs_discount_type.show("slow");
	 		label_wcrs_action_signup_type.show("slow");
	 		label_wcrs_action_purchase_type.show("slow");
	 		$("#wcrs_wcpr_desc_end-description").prev().hide();
	 		$("#wcrs_wcpr_desc_end-description").hide();
	 		$('.subsubsub > li').not(":first-child").show();
	 		label_wcrspr_minimum_purchase_limit_enable.hide();
	 		label_wcrspr_minimum_purchase_limit.hide();
	 		label_wcrspr_valid_no_orders.hide();
	 		$( '#wcrspr_minimum_purchase_limit' ).removeAttr('required');
	 		$('.lamda').show();
	 	} else if ($(this).val() == 'wcpr-func') {
	 		label_wcrspr_minimum_purchase_limit_enable.show();
	 		label_wcrspr_valid_no_orders.show();

	 		if ($( 'input[name="wcrspr_minimum_purchase_limit_enable"]' ).is( ":checked" )) {
	 			label_wcrspr_minimum_purchase_limit.show();
	 			$( '#wcrspr_minimum_purchase_limit' ).attr('required','true');
	 		}

	 		$('.subsubsub > li').not(":first-child").hide();
	 		$('.lamda').hide();
	 		label_wcrs_discount_type.hide("slow");
	 		label_wcrs_action_signup_type.hide("slow");
	 		label_wcrs_action_purchase_type.hide("slow");
	 		label_wcrs_action_signup_referee_coupon_amount.hide("slow");
	 		label_wcrs_action_signup_referee_coupon_expiry.hide("slow");
	 		label_wcrs_action_signup_referral_coupon_amount.hide("slow");
	 		label_wcrs_action_signup_referral_coupon_expiry.hide("slow");
	 		label_wcrs_action_signup_referral.hide("slow");
	 		label_wcrs_action_signup_referee.hide("slow");
	 		label_wcrs_action_purchase_referral.hide("slow");
	 		label_wcrs_action_purchase_referee.hide("slow");
	 		label_wcrs_action_purchase_referee_coupon_amount.hide("slow");
	 		label_wcrs_action_purchase_referee_coupon_expiry.hide("slow");
	 		label_wcrs_action_purchase_referral_coupon_amount.hide("slow");
	 		label_wcrs_action_purchase_referral_coupon_expiry.hide("slow");
	 		$("#wcrs_action_signup_type").prop("checked", false);
	 		$("#wcrs_action_purchase_type").prop("checked", false);
	 		$("#wcrs_action_purchase_referral").prop("checked", false);
	 		$("#wcrs_action_purchase_referee").prop("checked", false);
	 		$("#wcrs_action_signup_referral").prop("checked", false);
	 		$("#wcrs_action_signup_referee").prop("checked", false);
	 		$("#wcrs_wcpr_desc_end-description").prev().show();
	 		$("#wcrs_wcpr_desc_end-description").show();
	 	}
	 });


	 if ( $("[name=wcrs_functionality_type]:checked").val() == 'wcpr-func') {
	 	$('.subsubsub > li').not(":first-child").hide();
	 	$('.lamda').hide();

	 	label_wcrspr_minimum_purchase_limit.hide();
	 	if ($( 'input[name="wcrspr_minimum_purchase_limit_enable"]' ).is( ":checked" )) {
	 		label_wcrspr_minimum_purchase_limit.show();
	 		$( '#wcrspr_minimum_purchase_limit' ).attr('required','true');
	 	}

	 }
	 else{
	 	$('.lamda').show();

	 	label_wcrspr_minimum_purchase_limit_enable.hide();
	 	label_wcrspr_minimum_purchase_limit.hide();
	 	label_wcrspr_valid_no_orders.hide();
	 	$("#wcrs_wcpr_desc_end-description").prev().hide();
	 	$("#wcrs_wcpr_desc_end-description").hide();
	 	
	 }

	 $( 'input[name="wcrspr_minimum_purchase_limit_enable"]' ).on(
	 	'click',
	 	function () {

	 		if ($( this ).is( ":checked" )) {
	 			label_wcrspr_minimum_purchase_limit.show();
	 			$( '#wcrspr_minimum_purchase_limit' ).attr('required','true');
	 		} 
	 		else {
	 			$("#wcrspr_minimum_purchase_limit").parent().parent().hide();
	 			$( '#wcrspr_minimum_purchase_limit' ).removeAttr('required');
	 		}
	 	}
	 	);

	/**
	 * Change if signup discount
	 */
	 $("[name=wcrs_action_signup_type]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_signup_referral.show("slow");
	 		$("[name=wcrs_action_signup_referral_coupon_expiry]").attr('required','true');
	 		label_wcrs_action_signup_referee.show("slow");
	 		$("[name=wcrs_action_signup_referee_coupon_expiry]").attr('required','true');
	 		label_wcrs_signup_discount_type.show("slow");
	 	} else {
	 		label_wcrs_action_signup_referral.hide("slow");
	 		label_wcrs_action_signup_referee.hide("slow");
	 		label_wcrs_action_signup_referee_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_signup_referee_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_signup_referee_coupon_expiry.hide("slow");
	 		label_wcrs_action_signup_referral_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_signup_referral_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_signup_referral_coupon_expiry.hide("slow");
	 		label_wcrs_signup_discount_type.hide("slow");
	 		$("#wcrs_action_signup_referral").prop("checked", false);
	 		$("#wcrs_action_signup_referee").prop("checked", false);
	 	}
	 });

	 $("[name=wcrs_action_signup_referral]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_signup_referral_coupon_amount.show("slow");
	 		label_wcrs_action_signup_referral_coupon_expiry.show("slow");
	 		$("[name=wcrs_action_signup_referral_coupon_expiry]").attr('required','true');
	 	} else {
	 		label_wcrs_action_signup_referral_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_signup_referral_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_signup_referral_coupon_expiry.hide("slow");
	 	}
	 });

	 $("[name=wcrs_action_signup_referee]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_signup_referee_coupon_amount.show("slow");
	 		label_wcrs_action_signup_referee_coupon_expiry.show("slow");
	 		$("[name=wcrs_action_signup_referee_coupon_expiry]").attr('required','true');
	 	} else {
	 		label_wcrs_action_signup_referee_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_signup_referee_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_signup_referee_coupon_expiry.hide("slow");
	 	}
	 });

	/**
	 * Change if purchase discount
	 */
	 $("[name=wcrs_action_purchase_type]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_purchase_referral.show("slow");
	 		label_wcrs_action_purchase_referee.show("slow");
	 		label_wcrs_valid_no_orders.show("slow");
	 		label_cwrs_reward_type.show("slow");
	 		label_wcrs_minimum_purchase_limit_enable.show("slow");
	 		label_wcrs_order_discount_type.show("slow");

	 		$( '#wcrs_minimum_purchase_limit' ).removeAttr("required");

	 		if ($( 'input[name="wcrs_minimum_purchase_limit_enable"]' ).is( ":checked" )) {
	 			$('#wcrs_minimum_purchase_limit').show();
	 			$('#wcrs_minimum_purchase_limit').parent().show();
	 			wcrs_minimum_purchase_limit_parent.show();
	 			$( '#wcrs_minimum_purchase_limit' ).attr('required','true');
	 		}

	 		if ($( 'input[name="wcrs_action_purchase_referral"]' ).is( ":checked" )) {

	 			label_wcrs_action_purchase_referral_coupon_amount.show();
	 			label_wcrs_action_purchase_referral_coupon_expiry.show();
	 			$("[name=wcrs_action_purchase_referral_coupon_expiry]").attr('required','true');
	 			

	 		}

	 		if ($( 'input[name="wcrs_action_purchase_referee"]' ).is( ":checked" )) {

	 			label_wcrs_action_purchase_referee_coupon_amount.show();
	 			label_wcrs_action_purchase_referee_coupon_expiry.show();
	 			$("[name=wcrs_action_purchase_referee_coupon_expiry]").attr('required','true');

	 		}




	 	} else {
	 		label_wcrs_action_purchase_referral.hide("slow");
	 		label_wcrs_action_purchase_referee.hide("slow");
	 		label_wcrs_action_purchase_referee_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_purchase_referee_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_purchase_referee_coupon_expiry.hide("slow");
	 		label_wcrs_action_purchase_referral_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_purchase_referral_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_purchase_referral_coupon_expiry.hide("slow");
	 		label_wcrs_valid_no_orders.hide();
	 		label_cwrs_reward_type.hide();
	 		label_wcrs_minimum_purchase_limit_enable.hide();
	 		wcrs_minimum_purchase_limit_parent.hide();
	 		label_wcrs_order_discount_type.hide();
	 		$( '#wcrs_minimum_purchase_limit' ).removeAttr("required");
	 	}
	 });
	 $("[name=wcrs_action_purchase_referral]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_purchase_referral_coupon_amount.show("slow");
	 		label_wcrs_action_purchase_referral_coupon_expiry.show("slow");
	 		$("[name=wcrs_action_purchase_referral_coupon_expiry]").attr('required','true');
	 	} else {
	 		label_wcrs_action_purchase_referral_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_purchase_referral_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_purchase_referral_coupon_expiry.hide("slow");
	 	}
	 });
	 $("[name=wcrs_action_purchase_referee]").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		label_wcrs_action_purchase_referee_coupon_amount.show("slow");
	 		label_wcrs_action_purchase_referee_coupon_expiry.show("slow");
	 		$("[name=wcrs_action_purchase_referee_coupon_expiry]").attr('required','true');
	 	} else {
	 		label_wcrs_action_purchase_referee_coupon_amount.hide("slow");
	 		$("[name=wcrs_action_purchase_referee_coupon_expiry]").removeAttr('required');
	 		label_wcrs_action_purchase_referee_coupon_expiry.hide("slow");
	 	}
	 });

	/*
	* Validation
	*/

	/*
	* Checks if it's on our settings page
	*/
	if ($("#wcrs_general_settings_title-description").length) {

		if ($("#wcrs_action_signup_referral").attr("checked")) {
			$("#wcrs_action_signup_referral_coupon_amount").attr("required", "required");
			$("#wcrs_action_signup_referral_coupon_expiry").attr("required", "required");
		}
		if ($("#wcrs_action_signup_referee").attr("checked")) {
			$("#wcrs_action_signup_referee_coupon_amount").attr("required", "required");
			$("#wcrs_action_signup_referee_coupon_expiry").attr("required", "required");
		}
		$("#wcrs_action_signup_referral").change(function () {
			if (jQuery( this ).is( ":checked" )) {
				$("#wcrs_action_signup_referral_coupon_amount").attr("required", "required");
				$("#wcrs_action_signup_referral_coupon_expiry").attr("required", "required");
			} else {
				$("#wcrs_action_signup_referral_coupon_amount").removeAttr("required");
				$("#wcrs_action_signup_referral_coupon_expiry").removeAttr("required");
			}
		});
		$("#wcrs_action_signup_referee").change(function () {
			if (jQuery( this ).is( ":checked" )) {
				$("#wcrs_action_signup_referee_coupon_amount").attr("required", "required");
				$("#wcrs_action_signup_referee_coupon_expiry").attr("required", "required");
			} else {
				$("#wcrs_action_signup_referee_coupon_amount").removeAttr("required");
				$("#wcrs_action_signup_referee_coupon_expiry").removeAttr("required");
			}
		});

		if ($("#wcrs_action_purchase_referral").is( ":checked" )) {
			$("#wcrs_action_purchase_referral_coupon_amount").attr("required", "required");
			$("#wcrs_action_purchase_referral_coupon_expiry").attr("required", "required");
		}
		if ($("#wcrs_action_purchase_referee").is( ":checked" )) {
			$("#wcrs_action_purchase_referee_coupon_amount").attr("required", "required");
			$("#wcrs_action_purchase_referee_coupon_expiry").attr("required", "required");
		}
		$("#wcrs_action_purchase_referral").change(function () {
			if (jQuery( this ).is( ":checked" )) {
				$("#wcrs_action_signup_referral_coupon_amount").attr("required", "required");
				$("#wcrs_action_signup_referral_coupon_expiry").attr("required", "required");
			} else {
				$("#wcrs_action_signup_referral_coupon_amount").removeAttr("required");
				$("#wcrs_action_signup_referral_coupon_expiry").removeAttr("required");
			}
		});
		$("#wcrs_action_purchase_referee").change(function () {
			if (jQuery( this ).is( ":checked" )) {
				$("#wcrs_action_purchase_referee_coupon_amount").attr("required", "required");
				$("#wcrs_action_purchase_referee_coupon_expiry").attr("required", "required");
			} else {
				$("#wcrs_action_purchase_referee_coupon_amount").removeAttr("required");
				$("#wcrs_action_purchase_referee_coupon_expiry").removeAttr("required");
			}
		});
	}


	/**
	 * WCPR Integration Admin Settings JS
	 */

	 if (!$("#wcrs_int_signup_type").is( ":checked" )) {
	 	$("#wcrs_int_signup_referrer").attr("disabled", "disabled");
	 	$("#wcrs_int_signup_referee").attr("disabled", "disabled");
	 } else {
	 	$("#wcrs_int_signup_referrer").removeAttr("disabled");
	 	$("#wcrs_int_signup_referee").removeAttr("disabled");
	 }
	 if (!$("#wcrs_int_purchase_type").is( ":checked" )) {
	 	$("#wcrs_int_order_referrer").attr("disabled", "disabled");
	 	$("#wcrs_int_order_referee").attr("disabled", "disabled");
	 	wcrspr_reward_type_parent.hide();

	 } else {
	 	$("#wcrs_int_order_referrer").removeAttr("disabled");
	 	$("#wcrs_int_order_referee").removeAttr("disabled");
	 	wcrspr_reward_type_parent.show();
	 }
	 $("#wcrs_int_signup_type").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		$("#wcrs_int_signup_referrer").removeAttr("disabled");
	 		$("#wcrs_int_signup_referee").removeAttr("disabled");
	 	} else {
	 		$("#wcrs_int_signup_referrer").attr("disabled", "disabled");
	 		$("#wcrs_int_signup_referee").attr("disabled", "disabled");
	 	}
	 });
	 $("#wcrs_int_purchase_type").change(function () {
	 	if (jQuery( this ).is( ":checked" )) {
	 		$("#wcrs_int_order_referrer").removeAttr("disabled");
	 		$("#wcrs_int_order_referee").removeAttr("disabled");
	 		wcrspr_reward_type_parent.show();
	 	} else {
	 		$("#wcrs_int_order_referrer").attr("disabled", "disabled");
	 		$("#wcrs_int_order_referee").attr("disabled", "disabled");
	 		wcrspr_reward_type_parent.hide();
	 	}
	 });

	/**
	 * purchase type
	 */
	 if ($("#wcrs_action_purchase_type").is( ":checked" )) {
	 	label_wcrs_action_purchase_referral.show();
	 	$("[name=wcrs_action_purchase_referral_coupon_expiry]").attr('required','true');
	 	label_wcrs_action_purchase_referee.show();
	 	$("[name=wcrs_action_purchase_referee_coupon_expiry]").attr('required','true');
	 	$("[for=wcrs_valid_no_orders]").parent().parent().parent().show();
	 	label_cwrs_reward_type.show();
	 	label_wcrs_minimum_purchase_limit_enable.show();
	 	label_wcrs_order_discount_type.show();

	 } else {
	 	label_wcrs_action_purchase_referral.hide();
	 	$("[name=wcrs_action_purchase_referral_coupon_expiry]").removeAttr('required');
	 	label_wcrs_action_purchase_referee.hide();
	 	$("[name=wcrs_action_purchase_referee_coupon_expiry]").removeAttr('required');
	 	label_wcrs_valid_no_orders.hide();
	 	label_cwrs_reward_type.hide();
	 	label_wcrs_minimum_purchase_limit_enable.hide();
	 	label_wcrs_order_discount_type.hide();

	 	label_wcrs_action_purchase_referral_coupon_amount.hide();
	 	label_wcrs_action_purchase_referral_coupon_expiry.hide();
	 	label_wcrs_action_purchase_referee_coupon_amount.hide();
	 	label_wcrs_action_purchase_referee_coupon_expiry.hide();

	 	$( '#wcrs_minimum_purchase_limit' ).parent().hide();
	 	$( '#wcrs_minimum_purchase_limit' ).attr('required','false');

	 	
	 }

	});
