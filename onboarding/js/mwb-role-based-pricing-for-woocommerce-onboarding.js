jQuery(document).ready(function($) {

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

    var dialog = "";
    if( $('.mwb-mrbpfw-boarding-dailog').length > 0 ){
        dialog = mdc.dialog.MDCDialog.attachTo(document.querySelector('.mwb-mrbpfw-boarding-dailog'));
    }

    /*if device is mobile*/
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        jQuery('body').addClass('mobile-device');
    }

    var deactivate_url = '';

    // Add Select2.
    jQuery('.on-boarding-select2').select2({
        placeholder: 'Select All Suitable Options...',
    });

    // On click of deactivate.
    if ('plugins.php' == mwb_mrbpfw_onboarding.mrbpfw_current_screen) {

        // Add Deactivation id to all deactivation links.
        mwb_mrbpfw_embed_id_to_deactivation_urls();
        mwb_mrbpfw_add_deactivate_slugs_callback(mwb_mrbpfw_onboarding.mrbpfw_current_supported_slug);

        jQuery(document).on('change', '.mrbpfw-on-boarding-radio-field', function(e) {

            e.preventDefault();
            if ('other' == jQuery(this).attr('id')) {
                jQuery('#deactivation-reason-text').removeClass('mwb-mrbpfw-keep-hidden');
            } else {
                jQuery('#deactivation-reason-text').addClass('mwb-mrbpfw-keep-hidden');
            }
        });
    } else {
        // Show Popup after 1 second of entering into the MWB pagescreen.
        if (jQuery('#mwb-mrbpfw-show-counter').length > 0 && jQuery('#mwb-mrbpfw-show-counter').val() == 'not-sent') {
            setTimeout(mwb_mrbpfw_show_onboard_popup(), 1000);
        }
    }

    /* Close Button Click */
    jQuery(document).on('click', '.mwb-mrbpfw-on-boarding-close-btn a', function(e) {
        e.preventDefault();
        mwb_mrbpfw_hide_onboard_popup();
    });

    /* Skip and deactivate. */
    jQuery(document).on('click', '.mwb-mrbpfw-deactivation-no_thanks', function(e) {

        window.location.replace(deactivate_url);
        mwb_mrbpfw_hide_onboard_popup();
    });

    /* Skip For a day. */
    jQuery(document).on('click', '.mwb-mrbpfw-on-boarding-no_thanks', function(e) {

        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: mwb_mrbpfw_onboarding.ajaxurl,
            data: {
                nonce: mwb_mrbpfw_onboarding.mrbpfw_auth_nonce,
                action: 'mrbpfw_skip_onboarding_popup',
            },
            success: function(msg) {
                mwb_mrbpfw_hide_onboard_popup();
            }
        });

    });

    /* Submitting Form */
    jQuery(document).on('submit', 'form.mwb-mrbpfw-on-boarding-form', function(e) {

        e.preventDefault();
        var form_data = JSON.stringify(jQuery('form.mwb-mrbpfw-on-boarding-form').serializeArray());

        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: mwb_mrbpfw_onboarding.ajaxurl,
            data: {
                nonce: mwb_mrbpfw_onboarding.mrbpfw_auth_nonce,
                action: 'mwb_mrbpfw_send_onboarding_data',
                form_data: form_data,
            },
            success: function(msg) {
                if ('plugins.php' == mwb_mrbpfw_onboarding.mrbpfw_current_screen) {
                    window.location.replace(deactivate_url);
                }
                mwb_mrbpfw_hide_onboard_popup();
            }
        });
    });

    /* Open Popup */
    function mwb_mrbpfw_show_onboard_popup() {
        dialog.open();
        if (!jQuery('body').hasClass('mobile-device')) {
            jQuery('body').addClass('mwb-on-boarding-wrapper-control');
        }
    }

    /* Close Popup */
    function mwb_mrbpfw_hide_onboard_popup() {
        dialog.close();
        if (!jQuery('body').hasClass('mobile-device')) {
            jQuery('body').removeClass('mwb-on-boarding-wrapper-control');
        }
    }



    /* Apply deactivate in all the MWB plugins. */
    function mwb_mrbpfw_add_deactivate_slugs_callback(all_slugs) {

        for (var i = all_slugs.length - 1; i >= 0; i--) {

            jQuery(document).on('click', '#deactivate-' + all_slugs[i], function(e) {

                e.preventDefault();
                deactivate_url = jQuery(this).attr('href');
                plugin_name = jQuery(this).attr('aria-label');
                plugin_name = plugin_name.replace('Deactivate ', '');
                jQuery('#plugin-name').val(plugin_name);
                jQuery('.mwb-mrbpfw-on-boarding-heading').text(plugin_name + ' Feedback');
                var placeholder = jQuery('#mwb-mrbpfw-deactivation-reason-text').attr('placeholder');
                jQuery('#mwb-mrbpfw-deactivation-reason-text').attr('placeholder', placeholder.replace('{plugin-name}', plugin_name));
                mwb_mrbpfw_show_onboard_popup();
            });
        }
    }

    /* Add deactivate id in all the plugins links. */
    function mwb_mrbpfw_embed_id_to_deactivation_urls() {
        jQuery('a').each(function() {
            if ('Deactivate' == jQuery(this).text() && 0 < jQuery(this).attr('href').search('action=deactivate')) {
                if ('undefined' == typeof jQuery(this).attr('id')) {
                    var slug = jQuery(this).closest('tr').attr('data-slug');
                    jQuery(this).attr('id', 'deactivate-' + slug);
                }
            }
        });
    }

    // End of scripts.
});