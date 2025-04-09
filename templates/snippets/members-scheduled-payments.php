<?php
namespace DanceStudioManager;
$scheduled_payments =  App::GetClient()->GetController('members')->GetScheduledPayments();
?>
<script>
	jQuery(function() {
		jQuery("#scheduled-payments").dataTable({
			"bFilter":false,
			"stateSave":false,
			"lengthMenu":[20,40,60],
			"columnDefs":[{target:0,visible: false,searchable: false},{target:2,className:"text-right"}],
			"order":[0,'asc'],
			"dom":"<i><t><lp>"
		});
	});
</script>
<div class="col-md-10 pt25">
<div class="table-responsive-xl">
	<div class="dsm-header"><h2>Scheduled Payments</h2></div>
	<table id="scheduled-payments" class="table table-striped display autorefresh" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>&nbsp;</th>
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
				<td><?php echo strtotime($sp->PAYMENT_DATE); ?></td>
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
		<tfoot>
			<tr>
				<th>&nbsp;</th>
			    <th>Date</th>
			    <th>Purchase</th>
			    <th>Amount</th>
			    <th>Payment Method</th>
			    <th>Status</th>
			</tr>
		</tfoot>
	</table>
</div>
</div>