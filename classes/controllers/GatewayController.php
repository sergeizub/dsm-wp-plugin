<?php
namespace DanceStudioManager;

class GatewayController extends BaseController
{
    public function __construct()
    {
		parent::__construct();
    }

	public function PaymentForm($data = array())
	{
		$data['dsm_action'] = 'gateway/payment';
		return parent::GetList($data);
	}
	
	public function SubmitCard($data)
	{
		$data['dsm_action'] = 'gateway/card';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitACH($data)
	{
		$data['dsm_action'] = 'gateway/account';
		if ($data['auto_payment'] == 'on' || $data['auto_payment'] == '1') 
			$data['auto_payment'] = '1';
		else
			$data['auto_payment'] = '0';

		return parent::Submit($data);
	}
	
	public function SubmitDefault($data)
	{
		$data['dsm_action'] = 'gateway/default';
		return parent::Submit($data);
	}
	
	public function SubmitAutopay($data)
	{
		$data['dsm_action'] = 'gateway/auto-pay';
		return parent::Submit($data);
	}
	
	public function Delete($data)
	{
		$data['dsm_action'] = 'gateway/delete';
		return parent::Delete($data);
	}

	public function GetConvenienceFeeJson($data)
	{
        if (DSM_OC_ALLOW_CONVENIENCE_FEE == 1 && $data['amount'] > 0)
			$amount = number_format(DSM_CONVENIENCE_FEE_AMOUNT + ($data['amount'] * DSM_CONVENIENCE_FEE_PERCENT * 0.01),2);
		else
			$amount = 0.00;
             
		echo json_encode(array('amount' => $amount));
	}

	public function MakePayment($data)
	{
		if (empty($data['charges'])) {
			App::GetError()->Show("Select at Least One Charge");
			return false;
		}
		$data['dsm_action'] = 'gateway/payment';
		return parent::Submit($data);
	}
}