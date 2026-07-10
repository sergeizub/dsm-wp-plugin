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

	if (!gw_form_id_ach || gw_form_id_ach == undefined)
		var gw_form_id_ach = 'gateway-form';
</script>
<input type="hidden" name="etoken">

<div class="ach-fields">
	<div class="form-group row">
		<div class="<?php echo $label_class; ?> hidden ">
	        <div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Account Number</div>
			<div class="control-label text-right" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Routing Number</div>
		</div>
		<div class="<?php echo $label_class; ?> d-block d-sm-none">
			<div class="control-label text-left" style="margin-top: 8px !important;font-weight:bold;"><span class="text-warning">*</span> Bank Account Number, Bank Routing Number</div>
		</div>
        <div class="col-sm-7 pl-3">
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
	InitBluefinAch();
});	

function InitBluefinAch()
{
	jQuery('#ach_container').html('');

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
	jQuery('#dsm_loading').hide();
}

function SavePaymentMethodAch()
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
		jQuery('#' + gw_form_id_ach + ' input[name=etoken]').val(res.eToken);
        dsm_ajax_click("#", jQuery('#' + gw_form_id_ach));
	});
	return false;
}
</script>
