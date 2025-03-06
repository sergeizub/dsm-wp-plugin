<?php
namespace DanceStudioManager;
$payment_sources = App::GetClient()->GetController('members')->GetCardsAccounts();
?>
<h2 class="page-header">Stored Cards</h2>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center">Default</th>
				<th>Card <?php echo ((DSM_OC_ALLOW_CARD_PAYMENTS == 1) ? '/ ACH' : ''); ?></th>
				<th>Description</th>
				<th>Auto Payment</th>
				<?php if (DSM_OC_REMOVE_PAYMENT_SOURCE == '1') : ?>
				<th  class="text-center">Action</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php if (!empty($payment_sources)) : ?>
			<?php foreach ($payment_sources as $payment_source) : ?>
			<tr>
				<td class="text-center">
						<input type="radio" name="default" value="<?php echo $payment_source['id']; ?>" class="dsm_ajax_tab"
						dsm_obj="gateway" dsm_method="SubmitDefault" dsm_id="<?php echo $payment_source['id']; ?>"
						<?php echo (($payment_source['default'] == '1') ? 'checked="checked"' : ''); ?>  dsm_boot_tab="tab-gateway-finance" dsm_sub_tab="#tab-members-cards-accounts"/>
				</td>
				<td><?php echo $payment_source['tender_type']; ?> **** **** <?php echo $payment_source['last4']; ?></td>
				<td><?php echo $payment_source['description']; ?></td>
				<td>
				<select name="auto_payment"  class="form-control" name="auto_payment"
						dsm_obj="gateway" dsm_method="SubmitAutopay" dsm_id="<?php echo $payment_source['id']; ?>" dsm_boot_tab="tab-gateway-finance" dsm_sub_tab="#tab-members-cards-accounts" dsm_auto_payment = "<?php echo $payment_source['auto_payment']; ?>"
						onchange="jQuery(this).attr('dsm_auto_payment',jQuery(this).val());dsm_ajax_click(this);">
					<option value="1" <?php echo (($payment_source['auto_payment'] == '1') ? 'selected="selected"' : ''); ?>>On</option>
					<option value="0" <?php echo (($payment_source['auto_payment'] == '0') ? 'selected="selected"' : ''); ?>>Off</option>
				</select>
				<?php if (DSM_OC_REMOVE_PAYMENT_SOURCE == '1') : ?>
				<td class="text-center">
					<a  href="#tab-gateway-finance" dsm_sub_tab="#tab-members-cards-accounts" dsm_obj="gateway" dsm_method="Delete"  dsm_id="<?php echo $payment_source['id']; ?>" onclick="if (confirm('Are you sure you want to delete Card/ACH?')) { dsm_ajax_click(this) };return false;" title="Delete Card/ACH" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i> Remove</a>
				</td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<td colspan="6">Accounts not added</td>
		<?php endif; ?>
		</tbody>
	</table>
	<?php if (DSM_OC_ALLOW_CARD_PAYMENTS) : ?>
		<div class="form-group">
			<a href="#tab-gateway-finance" dsm_sub_tab="#tab-gateway-card" class="dsm_ajax_tab btn btn-primary dsm_ajax_tab" onclick="return false;">Add Card</a>
		</div>
	<?php endif; ?>
	<?php if (DSM_OC_ALLOW_ACH_PAYMENTS) : ?>
		<div class="form-group">
			<a href="#tab-gateway-finance" dsm_sub_tab="#tab-gateway-account" class="dsm_ajax_tab btn btn-primary dsm_ajax_tab" onclick="return false;">Add ACH</a>
		</div>
	<?php endif; ?>
	</div>
</div>