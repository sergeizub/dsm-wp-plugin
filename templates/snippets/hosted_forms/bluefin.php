<?php
namespace DanceStudioManager;
$payment_form = App::GetClient()->GetController('gateway')->PaymentForm();

$account_id = isset($payment_form->form->account_id) ? $payment_form->form->account_id : '';
$base_url = isset($payment_form->form->base_url) ? $payment_form->form->base_url : '';

?>
<script>
	var account = "<?php echo $account_id; ?>",
		devServer = "https://<?php echo $base_url; ?>",
		paymentiFrame = '';

	if (!gw_form_id || gw_form_id == undefined)
		var gw_form_id = 'gateway-form';
</script>
<input type="hidden" name="etoken">

<div class="card-fields <?php echo ($payment_method == 'card') ? '' : 'hidden'; ?>">
	<div class="form-group">
		<div class="<?php echo $label_class; ?> ">
	        <div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Number</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Expiration</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Verification</div>
		</div>
        <div class="col-7 pl-3">
	        <div id="card_container"></div>
        </div>
	</div>
</div>

<div class="ach-fields <?php echo ($payment_method == 'ach') ? '' : 'hidden'; ?>">
	<div class="form-group row">
		<div class="<?php echo $label_class; ?> hidden ">
	        <div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Account Number</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Routing Number</div>
		</div>
		<div class="<?php echo $label_class; ?> d-block d-sm-none">
			<div class="control-label text-left" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Account Number, Bank Routing Number</div>
		</div>
        <div class="col-7 pl-3">
	        <div id="ach_container"></div>
        </div>
	</div>
	<div class="form-group row">
		<label class="<?php echo $label_class; ?> control-label"><span class="text-warning">*</span> Bank Account Type</label>
		<div class="col-sm-3">
			<select name="ach_account_type" class="form-control">
				<option value="CHECKING">Checking</option>
				<option value="SAVINGS">Savings</option>
			</select>
		</div>      
	</div>
</div>
	
<script>		
jQuery(function() {
	InitBluefin();
	jQuery(document).on('change', "#source_selector", InitBluefin);
});	

function InitBluefin()
{
	let tender_type = jQuery('#' + gw_form_id + ' input[name="tender_type"]').val();
	
	jQuery('#card_container').html('');
	jQuery('#ach_container').html('');
	
	if (tender_type == 'CARD') {
		paymentiFrame = new PaymentiFrame({
			create: true,
			iframeId: "payment_iframe",
			settings: {
				account         : account,
				parentId        : "card_container",
				lang            : "en",
				cvv             : "required",
				expy            : "single_input",
				layout          : "1",
				style           : "",
				width           : "100%",
				height          : "140px",
				showFrame       : false,
				devServer       : devServer,
				css       : {
					class_input_box : "padding:8px;margin:8px 0;width:100%;border-radius:4px;border: 1px solid #ccc;color:#444",
				},			
				text     : {
					number : {
						label       : "",
						placeholder : "Card Number"
					},
					cvv : {
						label       : "",
						placeholder : "CVV"
					},
					expy_single : {
						label       : "",
						placeholder : "MMYY",
					}
				 },			
			}
		});
	}
	else {
		paymentiFrame = new PaymentiFrame({
			create: true,
			iframeId: "payment_iframe",
			settings: {
				account         : account,
				parentId        : "ach_container",
				lang            : "en",
				cvv             : "required",
				expy            : "single_input",
				layout          : "1",
				style           : "",
				width           : "100%",
				height          : "100px",
				showFrame       : false,
				devServer       : devServer,
				payment_method  : "ach",
				css       : {
					class_input_box : "padding:8px;margin:8px 0;width:93%;border-radius:4px;border: 1px solid #ccc;color:#444",
				},			
				text     : {
					bank_account : {
						label       : "",
						placeholder : "eg 14251245645"
					},
					routing_number : {
						label       : "",
						placeholder : "eg 121122676"
					}
				 },			
			}
		});
	}	
	jQuery('#dsm_loading').hide();
}

function SavePaymentMethod()
{
	paymentiFrame.encrypt().failure(function(err) {
		jQuery('#dsm_loading').hide();
		alert("Error: " + err.message);
		jQuery('#gateway-form-submit, #gateway-form-checkout-submit').removeAttr('disabled');			
	}).invalidInput(function(data) {
		jQuery('#dsm_loading').hide();
		var err = 'Following errors occurred:<br>';
		for (var i = 0; i < data.invalidInputs.length; i++ ) {
			err += ' ' + data.invalidInputs[i].message + ' ' + data.invalidInputs[i].field + '.<br>';
		}
		jQuery('#gateway-form-submit, #gateway-form-checkout-submit').removeAttr('disabled');
		alert(err);
	}).success(function(res) {
		jQuery('#gateway-form-submit, #gateway-form-checkout-submit').attr('disabled', 'disabled');
    	jQuery('#dsm_loading').show();	
		jQuery('#' + gw_form_id + ' input[name=etoken]').val(res.eToken);
        dsm_ajax_click("#", jQuery('#' + gw_form_id));
	});
	return false;
}
</script>
