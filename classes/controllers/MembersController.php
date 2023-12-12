<?php
namespace DanceStudioManager;

class MembersController extends BaseController
{
    public function __construct()
    {
      parent::__construct();
    }
	
	public function Submit($data)
	{
		$data['dsm_action'] = 'members/edit';
		
		if ($data['I_AM'] == 'adult-student') {
			$data['IS_STUDENT'] = 1;
			$data['IS_GUARDIAN'] = 0;
		}
		elseif ($data['I_AM'] == 'guardian') {
			$data['IS_STUDENT'] = 0;
			$data['IS_GUARDIAN'] = 1;
		}
		elseif ($data['I_AM'] == 'guardian-student') {
			$data['IS_STUDENT'] = 1;
			$data['IS_GUARDIAN'] = 1;
		}
		
		$result = parent::Submit($data);
		
		$error_fields = array();
		if ($result->success == true) {
			App::GetError()->Success("Profile Updated.");
			unset($_POST);
		}
		elseif ($result->errors) {
			foreach ($result->errors as $k=>$v) {
				array_push($error_fields, $k);
				$msg .= "<br/>".$v;
			}
			App::GetError()->Show("Unable Update Profile.".$msg);
		}
		
		return $result;
	}
	
	public function SubmitStudent($data)
	{
		if ($data['student_id'])
			$data['dsm_action'] = 'members/edit/'.$data['student_id'];
		else
			$data['dsm_action'] = 'members/student';

		$result = parent::Submit($data);
		
		$error_fields = array();
		if ($result->success == true) {
			App::GetError()->Success("Student Submitted.");
			unset($_POST);
		}
		elseif ($result->errors) {
			foreach ($result_register->errors as $k=>$v) {
				array_push($error_fields, $k);
				$msg .= "<br/>".$v;
			}
			App::GetError()->Show("Unable Submit Student.".$msg);
		}
		return $result;
	}

    public function ChangePassword($data)
    {
		$data['dsm_action'] = 'members/change-password';
        $result = parent::Submit($data);
        return $result;
    }
	
	public function DeleteStudent($data)
	{
		$data['dsm_action'] = 'members/student/'.$data['student_id'];
		return parent::Delete($data);
	}
    
    public function RedeemGiftCard($data)
	{
        $data['dsm_action'] = 'members/gift-cards-redeem';
		$result = parent::Submit($data);
        return $result;
    }
	
	public function GetName()
	{
		$profile = $this->GetUserData();
		$name = '';
		if(!empty($profile)) {
			if (!empty($profile->FIRSTNAME))
				$name .= $profile->FIRSTNAME;
			if (!empty($profile->FIRSTNAME) && !empty($profile->LASTNAME))
				$name .= ' ';
			if (!empty($profile->LASTNAME))
				$name .= $profile->LASTNAME;
			}
		return $name;
	}
	
	public function GetUser($id = null)
	{
		
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");

		if(!empty($profile))
			return  $profile;
		else
			return NULL;
	}
	
	public function GetUserData($id = null)
	{
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");
		if(!empty($profile->user_data))
			return  $profile->user_data;
		elseif(!empty($profile->data))
			return $profile->data;
		else
			return NULL;
	}
	
	public function GetUserForm($id = null)
	{
		if($id)
			$profile = parent::GetList("members/edit/".$id);
		else
			$profile = parent::GetList("members/edit");
		$form = $profile->form;
		return $form;
	}
	
	public function GetChildList()
	{
		return parent::GetList("members/family");
	}
	
	public function GetStudentForm()
	{
		return parent::GetList("members/student");
	}
    
    public function GetMyClasses()
	{
		return parent::GetList("classes/my");
	}
    
    public function GetPrivateLessonsTotals()
	{
		return parent::GetList("members/private-lessons-totals");
	}
	
	public function GetPayments()
	{
	 return parent::GetList("members/payments");
	}
	
	public function GetCardsAccounts($params = array())
	{
		if (!empty($params))
			$get_string = '?'.http_build_query($params);
		$cards_accounts = json_decode(json_encode(parent::GetList("members/cards-accounts".$get_string)),true);
		return $cards_accounts['payment_sources'];
	}
	
	public function GetCharges()
	{
	 return parent::GetList("members/charges");
	}
	
	public function GetPurchases()
	{
	 return parent::GetList("members/purchases");
	}
	
    public function GetGiftCards()
	{
	 return parent::GetList("members/gift-cards");
	}
    
	public function GetWaivers()
	{
	 return parent::GetList("members/waivers");
	}

	public function SignWaiver($data)
	{
		$data['dsm_action'] = 'members/waivers/'.$data['id'];
		parent::Submit($data);
		exit(true);
	}
}