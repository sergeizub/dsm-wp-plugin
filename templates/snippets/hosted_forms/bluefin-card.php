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

	if (!gw_form_id_card || gw_form_id_card == undefined)
		var gw_form_id_card = 'gateway-form';
</script>
<input type="hidden" name="etoken">

<div class="card-fields">
	<div class="form-group">
		<div class="<?php echo $label_class; ?> ">
	        <div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Number</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Expiration</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Card Verification</div>
		</div>
        <div class="col-sm-7 pl-3">
	        <div id="card_container"></div>
        </div>
	</div>
</div>
	
<script>		
jQuery(function() {
	InitBluefinCard();
	jQuery(document).on('change', "#source_selector", InitBluefinCard);
});	

function InitBluefinCard()
{
	jQuery('#card_container').html('');
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
	jQuery('#dsm_loading').hide();
}

function SavePaymentMethodCard()
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
		jQuery('#' + gw_form_id_card + ' input[name=etoken]').val(res.eToken);
        dsm_ajax_click("#", jQuery('#' + gw_form_id_card));
	});
	return false;
}
</script>
