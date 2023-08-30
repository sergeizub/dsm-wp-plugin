var dsm_ajax = false;

jQuery(function () {
    jQuery(document).on('click', '.btn-login-alert', function() {
        if (jQuery(this).attr('dsm_class_id')) 
            jQuery("a[href$='#tab-auth-register']").attr('dsm_class_id', jQuery(this).attr('dsm_class_id'));  
        else
            jQuery("a[href$='#tab-auth-register']").removeAttr("dsm_class_id");
            
        if (jQuery(this).attr('dsm_schedule_id')) 
            jQuery("a[href$='#tab-auth-register']").attr('dsm_schedule_id', jQuery(this).attr('dsm_schedule_id'));
         else
            jQuery("a[href$='#tab-auth-register']").removeAttr("dsm_schedule_id");
            
        if (jQuery(this).attr('dsm_sales-item_id')) 
            jQuery("a[href$='#tab-auth-register']").attr('dsm_sales-item_id', jQuery(this).attr('dsm_sales-item_id'));  
        else
            jQuery("a[href$='#tab-auth-register']").removeAttr("dsm_sales-item_id");
        jQuery("a[href$='#tab-auth-register']").trigger("click");
		//alert("Please login to continue");
	});
    
    // Classes Filter	
	jQuery(document).on('change', '#schedule-filter select', function() {
		dsm_ajax_click(jQuery(this),  jQuery('#schedule-filter'));
	});
    
    jQuery(document).on('change', "#source_selector", function() {
		var option = jQuery('#source_selector option:selected');
        var tender_type = jQuery('input[name="tender_type"]');
        var card_info = jQuery('#card_info');
        var card_cvv_info = jQuery('#card_cvv_info');
        if (option.val() == 0) {
            tender_type.val('CARD');
            card_info.show();
            card_cvv_info.show();
        } else {
            tender_type.val(option.data('tender_type'));
            card_info.hide();
			if (window.payment_form_cvv) 
				card_cvv_info.show();
			else  
				card_cvv_info.hide();
        }
    });
    
    jQuery(document).on('change', "#use_account_credit", function() {
	    var account_credit = parseFloat(window.account_credit);
	    var cart_total = parseFloat(window.cart_total);

	    if (jQuery(this).prop('checked')) {
		    if (account_credit-cart_total > 0) {
		    	jQuery('#card_info').hide();
		    	jQuery('#card_cvv_info').hide();
		    	jQuery('#source_selector').hide();
		    }
		    var ta = cart_total-account_credit;
		    if (ta < 0) ta = 0;
		    jQuery("#transaction_amount").val(ta.toFixed(2));
	    }
	    else {
	    	jQuery('#card_info').show();
	    	jQuery('#card_cvv_info').show();
	    	jQuery('#source_selector').show();
	    	jQuery("#transaction_amount").val(cart_total.toFixed(2));
	    }
    });

    jQuery(document).on('click', "button.rwppi", function() {
		jQuery('a.checkout').hide(); // hide Checkout button
		var student_id = jQuery(this).data('student_id');
		var class_id = jQuery(this).data('class_id');
		var season_status = jQuery(this).data('season_status');
		var schedule_id = jQuery('#selected_schedule_id').val();

		jQuery('#ap_'+student_id+'_'+class_id).html(RegisterWithPurchasedItemForm(student_id, class_id, schedule_id, jQuery('#ap_options').html()));
		jQuery('#ap_'+student_id+'_'+class_id+' form ').on('submit', function(e) {
            e.preventDefault();
            dsm_ajax_click(jQuery('<a href="#tab-class-registration-' + class_id + '" dsm_schedule_id="' + class_id + '"></a>'), jQuery(this));
        });
        jQuery('#sch_cont_'+student_id+'_'+class_id).css('visibility', 'hidden');
        
		// Get available schedules
		jQuery('#crf_'+student_id+'_'+class_id+' select[name=purchase_id]').change(function() {
			var option = jQuery(this).find(':selected');
			var class_registration_method = jQuery(option).attr('data-class_registration_method');
			var lessons_available = jQuery(option).attr('data-lessons_available');
			var period = jQuery(option).attr('data-period');
			if (lessons_available == 'unlimited') lessons_available = '9999';
            jQuery('#sch_cont_'+student_id+'_'+class_id).css('visibility', 'visible');
            jQuery.ajax({
                type: "POST",
                url: dsmajax.url,
                dataType: "json",
                data:  {
                            action: "dsmclient",
                            obj: "classes",
                            method: "GetAvailableSchedulesJson",
                            class_id: class_id,
                            member_id: student_id,
                            lessons_available: lessons_available,
                            class_registration_method: class_registration_method,
                            selected_schedule_id: schedule_id
                        },
                success: function (response) {
                        if (response.data != '' && response.data != undefined) {
                            var s = '';
                            jQuery.each(response.data, function(i, schedule) {
                                s += schedule.title + '<input type="hidden" name="SCHEDULES[]" value="' + schedule.value + '"><br>';
                            });
                            jQuery('#sch_cont_' + student_id + '_' + class_id).html('<br><div class="alert alert-info text-center">' + s + '</div>');								
                        }
                        else {
                            jQuery('#sch_cont_' + student_id + '_' + class_id).html('<br><div class="alert alert-warning text-center">You can not register for this lesson with selected item</div>');
                            jQuery('button.crf-submit').attr('disabled', 'disabled');
                        }	
                    }
                });
        });
        setTimeout(function() {
			jQuery('#crf_'+student_id+'_'+class_id+' select[name=purchase_id]').trigger('change');
		}, 700);
	});
    
    jQuery(document).on('click', ".quantity button.btn-number", function(e) {
		e.preventDefault();

		var fieldName = jQuery(this).data('field');
		var type = jQuery(this).attr('data-type');
		var input = jQuery("input[name='" + fieldName + "']");
		var currentVal = parseInt(input.val());
		if (!isNaN(currentVal)) {
			if (type == 'minus') {

				if (currentVal > input.attr('min')) {
					input.val(currentVal - 1).change();
				}
				if (parseInt(input.val()) == input.attr('min')) {
					jQuery(this).attr('disabled', true);
				}

			} else if (type == 'plus') {

				if (currentVal < input.attr('max')) {
					input.val(currentVal + 1).change();
				}
				if (parseInt(input.val()) == input.attr('max')) {
					jQuery(this).attr('disabled', true);
				}

			}
		} else {
			input.val(0);
		}
	});
    
    jQuery(document).on('focusin', ".quantity .dsm_sales-item-quantity", function() {
		jQuery(this).data('oldValue', jQuery(this).val());
	});
	
	jQuery(document).on('change', ".quantity .dsm_sales-item-quantity", function() {
		var minValue = parseInt(jQuery(this).attr('min'));
		var maxValue = parseInt(jQuery(this).attr('max'));
		var valueCurrent = parseInt(jQuery(this).val());

		var name = jQuery(this).attr('name');
		if (valueCurrent >= minValue) {
			jQuery(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
		} else {
			alert('Sorry, the minimum value was reached');
			jQuery(this).val(jQuery(this).data('oldValue'));
		}
		if (valueCurrent <= maxValue) {
			jQuery(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
		} else {
			alert('Sorry, the maximum value was reached');
			jQuery(this).val(jQuery(this).data('oldValue'));
		}

        jQuery('#'+name).attr('dsm_quantity',jQuery(this).val());
	});
	
	jQuery(document).on('keydown', ".quantity .dsm_sales-item-quantity", function(e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
			// Allow: Ctrl+A
			(e.keyCode == 65 && e.ctrlKey === true) ||
			// Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
    
});

function InputDateInit() {
    jQuery('.input-group.date').datetimepicker({
        showClear: true,
        ignoreReadonly: true,
        viewMode: 'years',
        format: window.dtp_date
    });
}

function InitClassRegWithPurchesdItem() {
    jQuery('.active_purchases').each(function() {
        var student_id = jQuery(this).data('student_id');
        var class_id = jQuery(this).data('class_id');		
        var schedule_id = jQuery(this).data('schedule_id');		
        var season_status = jQuery(this).data('season_status');
        jQuery('#ap_'+student_id+'_'+class_id).html(RegisterWithPurchasedItemButton(student_id, class_id, schedule_id, season_status));
    });
}

function RegisterWithPurchasedItemButton(student_id, class_id, schedule_id, season_status) {
    return '<button class="btn btn-success rwppi" data-student_id="'+student_id+'" data-class_id="'+class_id+'" data-season_status="'+season_status+'" data-schedule_id="'+schedule_id+'">Register with previously purchased items</button><br/><br/>';	
}

function RegisterWithPurchasedItemForm(student_id, class_id, schedule_id,options)
{
	return	'<form action="index.php"  method="post" id="crf_'+student_id+'_'+class_id+'"><input type="hidden" name="action" value="dsmclient" /><input type="hidden" name="obj" value="classes" /><input type="hidden" name="method" value="RegisterWithPurchasedItem"><input type="hidden" name="student_id" value="'+student_id+'"><input type="hidden" name="class_id" value="'+class_id+'"><input type="hidden" name="schedule_id" value="'+schedule_id+'"><label>Register with previously purchased items</label><div class="row"><div class="col-md-10">'+options+'</div><div class="col-md-2 text-right"><button class="crf-submit btn btn-success" type="submit"><i class="fa fa-arrow-circle-right"></i> Register</button></div><div class="row"><div class="col-md-12" id="sch_cont_'+student_id+'_'+class_id+'"><select class="form-control selected-schedules" name="sch" id="schedules_'+student_id+'_'+class_id+'" multiple="multiple"></select></div></div></form><br/>';
}


function dsm_connect_ajax(link) {
    jQuery('#dsm-tab-content form').on('submit', function(e) {
		e.preventDefault();
        dsm_ajax_click(link, jQuery(this));
	});
    
    jQuery('#dsm-tab-content .dsm_ajax_tab').on('click', function(e) {
        e.preventDefault();
        dsm_ajax_click(this);
    });
}

function dsm_ajax_click(link, dsm_form = false) {
    if (dsm_ajax == true) {
        return false;
    }
    var dsm_data = { action : 'dsmclient' };
    
    if (dsm_form != false && dsm_form != undefined) {
        jQuery.each(dsm_form.serializeArray(), function() {
                if(this.name) {
                    dsm_data[this.name] = this.value;
                }
        });
    }
    else {
        if (link.attributes != undefined) {
            jQuery.each(link.attributes, function() {
                if(this.name) {
                    if (this.name.substring(0, 4) == 'dsm_') {
                        dsm_data[this.name.replace(/^dsm_/, "")] = this.value;
                    }
                }
            });
        }
    }
    var dsm_boot_tab = jQuery(link).attr('href');
    
    if ( dsm_boot_tab != undefined) { dsm_data.boot_tab = dsm_boot_tab  }
    //else if ( jQuery('.tab-pane').is(':visible') ) {   dsm_data.boot_tab = dsm_boot_tab = '#'+jQuery(".tab-pane:visible").attr('id'); }
    
    jQuery.ajax({
        type: "POST",
        url: dsmajax.url,
        data: dsm_data,
        beforeSend: function () {
             dsm_ajax = true;
             jQuery('#dsm_loading').show();
        },
        success: function (response) {
            if ((dsm_data['reload'] != undefined && dsm_data['reload'] == 'true') || response == '1' || response == 'true') {
                if(window.location.href.indexOf('qrnd=') != -1) {
                    var dsm_queryParams = new URLSearchParams(window.location.search);
                    dsm_queryParams.set("qrnd", Math.random().toString(36).substring(2,18));
					history.replaceState(null, null, "?"+dsm_queryParams.toString());
                    window.location.reload(false); 
				}
				else {
					window.location.reload(false); 
				}
            }
			else {
				jQuery('#dsm-tab-content').html(response);
                jQuery(link).tab('show');
				jQuery('a[href="'+dsm_boot_tab+'"]').show();
                jQuery("#dsm-tab-content").show();
                jQuery(".tab-pane").show();
				dsm_connect_ajax(link);
                
			}
        },
        complete: function (response) {
            dsm_ajax = false;
            jQuery('#dsm_loading').hide();
        }
    });
    return false;
}


