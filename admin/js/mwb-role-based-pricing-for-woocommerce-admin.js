(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(document).ready(function() {

		const MDCText = mdc.textField.MDCTextField;
        const textField = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
            return new MDCText(el);
        });
        const MDCRipple = mdc.ripple.MDCRipple;
        const buttonRipple = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
            return new MDCRipple(el);
        });
        const MDCSwitch = mdc.switchControl.MDCSwitch;
        const switchControl = [].map.call(document.querySelectorAll('.mdc-switch'), function(el) {
            return new MDCSwitch(el);
        });
		
		$('#selected_products').select2();
		$('#product_categories').select2();
		$('#product_tags').select2();
        $( document ).on( 'click','.mwb-password-hidden', function() {
            if ($('.mwb-form__password').attr('type') == 'text') {
                $('.mwb-form__password').attr('type', 'password');
            } else {
                $('.mwb-form__password').attr('type', 'text');
            }
        });
		function reset_price_rule() {
			if( $('#rule_type').val() == 'selected_products' ){
				$('#selected_product').show();
				$('#product_category').hide();
				$('#product_tag').hide();
				$('#product_categories').prop('required', false); 
				$('#product_tags').prop('required', false); 
				$('#selected_products').prop('required', true);
			} else if ( $('#rule_type').val() == 'categories' ) {
				$('#selected_product').hide();
				$('#product_category').show();
				$('#product_tag').hide();
				$('#product_categories').prop('required', true); 
				$('#product_tags').prop('required', false); 
				$('#selected_products').prop('required', false);
			} else if ( $('#rule_type').val() == 'tags' ) {
				$('#selected_product').hide();
				$('#product_category').hide();
				$('#product_tag').show();
				$('#product_tags').prop('required', true);
				$('#product_categories').prop('required', false); 
				$('#selected_products').prop('required', false);
			} else if (  $('#rule_type').val() == 'all_products' ) {
				$('#selected_product').hide();
				$('#product_category').hide();
				$('#product_tag').hide();
				$('#product_tags').prop('required', false);
				$('#product_categories').prop('required', false); 
				$('#selected_products').prop('required', false);
			}
		}
		reset_price_rule();
		$('#rule_type').change(function(){
			reset_price_rule();
		});
		$('.mwb-switch').on('click' ,function(){
			if ( $(this).children('.mwb-switch-checkbox').val() ){
				var post_id = $(this).children('.mwb-switch-checkbox').val();
				var data = {
					action:'mwb_mrbpfw_active_deactive_price_rule',
					post_id:post_id,
					check_nonce:mrbpfw_admin_param.create_nonce,
				};
				jQuery.ajax({
					url: mrbpfw_admin_param.ajaxurl, 
					type: "POST",  
					data: data,
					dataType :'json',	
					success: function(response) 
					{
						
					}
				});
			}
		});
		$(document).on( 'keyup', '#priority_field', rule_edit );
		$(document).on( 'change', '#roles', rule_edit );
		function rule_edit(){
			var role = $('#roles').val();
			var priority = $('#priority_field').val();
			if( role != '' && priority != '' && role != 'undefined' && priority != 'undefined' ) {
				var data = {
					action:'mwb_mrbpfw_check_if_priority_exist',
					role:role,
					priority:priority,
					check_nonce:mrbpfw_admin_param.create_nonce,
				}
				jQuery.ajax({
					url: mrbpfw_admin_param.ajaxurl, 
					type: "POST",  
					data: data,
					dataType :'json',	
					success: function(response) 
					{
						console.log(response);
						if( response ){
							$('#mrbpfw_meta_box_setting').hide();
							setTimeout(function(){ alert( 'Same Priority Already Exist' ); }, 300);
						} else {
							$('#mrbpfw_meta_box_setting').show();
						}
					}
				});
			}
		}
		
		$(document).on( 'click', '#screen-reader-text', function() {
			$( this ).parent('.is-dismissible').hide( 200 );
		});
		$('.mwb-switch').on('click', function () {
			$(this).children('.mwb-switch-checkbox').toggleClass('mwb-switch-checkbox--move');
			$(this).toggleClass('mwb-switch__bg');
		})
		$.each( $(".mwb-switch-checkbox"), function() {
			if ( $(this).is(':checked') ) {
				$(this).parent('.mwb-switch').addClass('mwb-switch__bg');
				$(this).addClass('mwb-switch-checkbox--move');
			} else {
				$(this).parent('.mwb-switch').removeClass('mwb-switch__bg');
				$(this).removeClass('mwb-switch-checkbox--move');
					
			}
		})
	});
	
	$(window).load(function(){
		if( $(document).find('.mwb-defaut-multiselect').length > 0 ) {
			$(document).find('.mwb-defaut-multiselect').select2();
		}
	});
	
	})( jQuery );
