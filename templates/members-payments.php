<?php
namespace DanceStudioManager;
$result = App::GetClient()->GetController('members')->GetPayments();
?>
<div id="tab-members-payments" class="tab-pane">
	<h2 class="page-header">Payments</h2>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="150">Date</th>
				<th>Name</th>
				<th>Type</th>
				<th>Check #</th>
				<th>Notes</th>
				<th style="text-align:right" width="90">Paid, <?php echo DSM_CURRENCY_SIGN; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if (!empty($result->payments)) : ?>
			<?php foreach ($result->payments as $payment) : ?>
			<tr>
				<td><?php echo $payment->DATE; ?></td>
				<td><?php echo $payment->NAME; ?></td>
				<td><?php echo $payment->TYPE_NAME; ?></td>
				<td><?php echo $payment->RECEIPT_NUMBER; ?></td>
				<td><?php echo $payment->PAYMENT_NOTES; ?></td>
				<td><?php echo $payment->AMOUNT_PAID; ?></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<td colspan="6">Payments not added</td>
		<?php endif; ?>
		</tbody>
	</table>
	
	</div>
</div>