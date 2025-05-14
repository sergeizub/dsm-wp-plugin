<?php
namespace DanceStudioManager;

$items = App::GetClient()->GetController('checkout')->GetSalesItems();
$sales_item_id = App::GetApi()->GetIdParam();
if (defined('DSM_OC_BUY_ITEM_PAGE_VIEW_TYPE') && DSM_OC_BUY_ITEM_PAGE_VIEW_TYPE == '1')
	$sales_products = $items['sales_items']['item'];
else
	$sales_products = $items['sales_items'];
$categories = $items['categories'];
?>
<div id="tab-sales-items" class="tab-pane">
<?php if (empty($sales_item_id)) : ?>
	<h2 class="page-header"><?php echo (DSM_OC_SALES_ITEMS_SECTION_TITLE); ?></h2>
<?php if (!empty($sales_products)) : ?>
	<?php foreach ($sales_products as $category_id=>$products) : ?>
	<?php if(!empty($_SESSION['dsm_client_attrs']['si_category_id']) && $_SESSION['dsm_client_attrs']['si_category_id'] != $category_id) continue;?>
	<h3><?php echo $categories[$category_id]; ?></h3>
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr>
				<th width="25%">Name</th>
				<th>Description</th>
				<th class="text-right">Price</th>
				<th width="10%"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($products as $product) : ?>
		<tr>
			<td><?php echo $product['NAME']; ?></td>
			<td>
				<?php echo $product['DESCRIPTION']; ?>
				<?php echo ((DSM_IGNORE_ITEMS_AVAILABLE_QUANTITY === '0' && $product['AVAILABLE_QUANTITY'] && $product['AVAILABLE_QUANTITY'] > 0) ? '<div class="label label-warning">Only '.$product['AVAILABLE_QUANTITY'].' items available</div>' : ''); ?>
			</td>
			<td class="text-right"><?php echo DSM_CURRENCY_SIGN;?><?php echo $product['PRICE'];?></td>
			<td class="text-right">
			<?php if (DSM_OC_SHOPPING_CART_ENABLED == '1') : ?>
				<?php if ($product['SALE_STARTED']) : ?>
					<?php if ($product['SALE_STARTED'] > 0 || DSM_IGNORE_ITEMS_AVAILABLE_QUANTITY == '1') : ?>	
						<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
							<a href="#tab-checkout-sales-items-<?php echo $product['ID']; ?>" title="Buy" class="btn btn-success dsm_ajax_tab"><i class="fa fa-shopping-cart"></i> Buy</a>
						<?php else: ?>
							<button dsm_sales-item_id="<?php echo $product['ID']; ?>" type="button" class="btn btn-success btn-login-alert"><i class="fa fa-shopping-cart"></i> Buy</button>
						<?php endif; ?>
					<?php else: ?>
						<div class="label label-default">Sold</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="label label-warning">Sale starts <?php echo $product['SALE_START_DATE']; ?></div>
				<?php endif; ?>
			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endforeach; ?>
<?php else: ?>
	<div class="alert alert-warning">No records found</div>
<?php endif; ?>
<?php else: ?>
	<h3 class="page-header">Select item for student</h3>
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/sales-item-details.php'; ?>
	<br/><br/>
	<a type="button" class="btn btn-primary geturl checkout dsm_ajax_tab" href="#tab-checkout-cart"><i class="fa fa-shopping-cart"></i> Checkout</a>
<?php endif; ?>
</div>
<?php if (strpos($_SESSION['dsm_redirect']['boot_tab'],'checkout-sales-items-') !== false) unset($_SESSION['dsm_redirect']['boot_tab']); ?>