<?php
namespace DanceStudioManager;
$result = App::GetClient()->GetController('members')->GetGiftCards();
$gift_card_data = $result->data;
?>
<div id="tab-members-gift-cards" class="tab-pane">
	<h2 class="page-header">Gift Cards</h2>
	<div class="col-12 row">
		<form method="post"  action="index.php" class="form-horizontal" role="form" id="redeem-gift-card-form">
		<input type="hidden" name="action" value="dsmclient"/>
		<input type="hidden" name="obj" value="members"/>
		<input type="hidden" name="method" value="RedeemGiftCard"/>
		<input type="hidden" name="boot_tab" value="tab-members-gift-cards"/>
		<label class="col-sm-6 col-form-label text-right">
			Redeem Gift Card
		</label>
		<div class="col-sm-4">
			<input type="text" name="code" id="redeem-gift-card-code" class="form-control" placeholder="Card Code" value="">
		</div>
		<div class="col-sm-2">
			<button id="redeem-gift-card" class="btn btn-success" type="submit"><i class="fa fa-gift"></i> Submit</button>
		</div>
		</form>
	</div>
	<br/>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<?php if (!empty($gift_card_data->table_header)) : ?>
				<?php foreach ($gift_card_data->table_header as $h) : ?>
				<th style="text-align:right;"><?php echo $h; ?></th>
				<?php endforeach; ?>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php if (!empty($gift_card_data->list)) : ?>
			<?php foreach ($gift_card_data->list as $row) : ?>
			<tr>
				<?php foreach ($row as $col): ?>
				<td  style="white-space: nowrap;text-align:right;"><?php echo $col; ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="6">No records found</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	</div>
</div>