<?php
namespace DanceStudioManager;

class CheckoutController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
	
	public function Submit($data)
	{
		if ($data['token_id'] == 'do_not_add_card')
			unset($data['token_id']);
		elseif (empty($data['token_id']) && empty($data['use_account_credit'])) {
			$result_card = App::GetClient()->GetController('gateway')->SubmitCard($data);
			if ($result_card) {
				if(!empty($data['selected_account']))
					$payment_sources = App::GetClient()->GetController('members')->GetCardsAccounts(array('selected_account' => $data['selected_account']));
				else
					$payment_sources = App::GetClient()->GetController('members')->GetCardsAccounts();
				$payment_sources_end = end($payment_sources);
				if(!empty($payment_sources_end['id']))
					$data['token_id'] = $payment_sources_end['id'];
			}
		}
		unset($data['selected_account']);
		$data['dsm_action'] = 'checkout/confirm';
		$res = parent::Submit($data);
		return $res;
	}
	
	public function GetCart()
	{
		return  json_decode(json_encode(parent::GetList("checkout/cart")),true);
	}
	
	public function SubmitCartItem($data)
	{
		$data['dsm_action'] = 'checkout/cart';
		return parent::Submit($data);
	}
	
	public function DeleteCartItem($data)
	{
		$data['dsm_action'] = 'checkout/cart';
		return parent::Delete($data);
	}
	
	public function GetCartItemKey($class_id, $student_id, $schedule_id)
	{
		
		$cart_full_info = $this->GetCart();
		if (!empty($cart_full_info->list))
			$cart = json_decode(json_encode($cart_full_info->list),true);
	
		foreach ($cart as $student)
			foreach ($student['items'] as $item)
				if ($class_id == $item['class_id'] && $student_id == $item['student_id']  && $schedule_id == $item['schedule_id'])
					return $item['cart_item_key'];

		return false;
	}
	
	public function AddDiscount($data) {
		$data['dsm_action'] = 'checkout/discount';
		$result =  parent::Submit($data);
		return $result;
	}
	
	public function RemoveDiscount($data) {
		$data['dsm_action'] = 'checkout/discount';
		return parent::Delete($data);
	}
    
    public function GetSalesItems()
	{
		return  json_decode(json_encode(parent::GetList("checkout/sales-items")),true);
	}
    
    public function GetSalesItemInfo($sales_item_id)
	{
		if ($sales_item_id)
			return json_decode(json_encode(parent::GetList("checkout/sales-items?id=".$sales_item_id)),true);
		else
			return false;
	}
    
    public function SubmitSalesItem($data)
	{
		$data['dsm_action'] = 'checkout/sales-items';
		return parent::Submit($data);
	}
}