<?php if ( ! defined( 'ABSPATH' ) ) exit;?>
<div class="input-group quantity">
	<span class="input-group-btn">
		<button type="button" class="btn btn-secondary btn-number" data-type="minus"
				data-field="quant-<?php echo $sales_item['ID']; ?>-<?php echo $student['ID']; ?>" disabled="disabled">
			<i class="fa fa-minus"></i>
		</button>
	</span>
	<input type="text" name="quant-<?php echo $sales_item['ID']; ?>-<?php echo $student['ID']; ?>" class="form-control dsm_sales-item-quantity" value="1" min="1" max="9">
	<span class="input-group-btn" style="float:left;width:40px;">
		<button type="button" class="btn btn-secondary btn-number" data-type="plus"
				data-field="quant-<?php echo $sales_item['ID']; ?>-<?php echo $student['ID']; ?>">
			<i class="fa fa-plus"></i>
		</button>
	</span>
</div>