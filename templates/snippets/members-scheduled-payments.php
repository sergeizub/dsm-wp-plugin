<?php
namespace DanceStudioManager;
$scheduled_payments =  App::GetClient()->GetController('members')->GetScheduledPayments();
?>
<h2 class="page-header">Scheduled Payments</h2>
<div class="table-responsive">
	<table class="table table-striped" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Date</th>
			    <th>Purchase</th>
			    <th>Amount</th>
			    <th>Payment Method</th>
			    <th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($scheduled_payments->scheduled_payments)) : ?>
			<?php foreach($scheduled_payments->scheduled_payments as $sp) :?>
			<tr>
				<td><?php echo $sp->PAYMENT_DATE;?></td>
				<td>
				<?php
				if ($sp->PURCHASE_ID > 0 || $sp->ORDER_ID > 0 || $row->SALES_ITEM_ID > 0)
					echo $sp->PURCHASE;
				else if(!empty($sp->CHARGE))
					echo 'Charge '.$sp->CHARGE;
				else
					echo 'Account Balance';
				?>
				</td>
				<td><?php echo DSM_CURRENCY_SIGN.number_format($sp->AMOUNT,2); ?></td>
				<td><?php echo $sp->PAYMENT_METHOD; ?></td>
				<td><?php echo App::GetClient()->GetController('members')->GetScheduledPaymentsStatusLabel($sp->SCHEDULED_PAYMENT_STATUS); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</tbody>	
	</table>
</div>