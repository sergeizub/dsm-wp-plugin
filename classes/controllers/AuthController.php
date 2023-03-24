<?php
namespace DanceStudioManager;

class AuthController extends BaseController
{
    public function __construct()
    {
	  $this->ParseAuthSettings();
      parent::__construct();
    }
	
	public function Submit($data)
	{
		$data['dsm_action'] = 'auth/register';
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
		//Check Required Primary Location Selected
		if (DSM_MEMBERS_PRIMARY_LOCATION_ENABLED == '1') {
			if (empty($data['PRIMARY_LOCATION'])) {
				App::GetError()->Show("Please Select Primary Location.");
				return false;
			}
		}
		
		$result = parent::Submit($data);
		$error_fields = array();
		if ($result->success == true) {
			
			$login_param = array("username" => $data["USERNAME"],"password" => $data["PASSWORD"]);
			if ($data['class_id'])
				$login_param['class_id'] = $data['class_id'];
			if ($data['schedule_id'])
				$login_param['schedule_id'] = $data['schedule_id'];
			if ($data['sales-item_id'])
				$login_param['sales-item_id'] = $data['sales-item_id'];
                
			$this->Login($login_param);
			
			//App::GetError()->Success("User Registered. Please check your email with confirmation");
			unset($_POST);
		}
		elseif ($result->errors) {
			foreach ($result->errors as $k=>$v) {
				array_push($error_fields, $k);
				$msg .= "<br/>".$v;
			}
			App::GetError()->Show("Unable Register User.".$msg);
		}
		return $result;
	}
	
	public function Login($data = array())
	{
		
		$data['dsm_action'] = 'auth/login';
		$response = parent::Submit($data);
		
		if (!empty($response->token))
				$this->SetAuthToken($response->token);
				
		if (!empty($response->settings))
			$this->SetAuthSettings($response->settings);
		
		//Redirect To Previous Class
		if (!empty($data['class_id']))
		{
			$_SESSION['dsm_redirect']['boot_tab'] = 'class-registration-'.$data['class_id'];
			if (!empty($data['schedule_id']))
				$_SESSION['dsm_redirect']['schedule_id'] = $data['schedule_id'];
		}
        elseif (!empty($data['sales-item_id']))
		{
			$_SESSION['dsm_redirect']['boot_tab'] = 'checkout-sales-items-'.$data['sales-item_id'];
		}
		if ($response->token) {
			echo true;
			exit();
		}
			
		return $response;
	}
	
	public function Logout($data = array())
	{
		 $this->SetAuthToken(false);
		 $this->SetAuthSettings(false);
		 return true;
	}
	
	public function isLogged()
	{
		if (!empty($_SESSION['dsm_auth_token']))
			return $_SESSION['dsm_auth_token'];
		else
			return false;
	}

    public function PasswordReset($data)
    {
        $data['dsm_action'] = 'auth/reset-password';
		$response = parent::Submit($data);
    }
	
	public function GetAuthToken()
	{
		if (!empty($_SESSION['dsm_auth_token']))
			return $_SESSION['dsm_auth_token'];
        return false;
	}
	
	public function SetAuthToken($token)
	{
		if (!empty($token)) {
			$_SESSION['dsm_auth_token'] = $token;
			return $_SESSION['dsm_auth_token'];
		}
		else {
			unset($_SESSION['dsm_auth_token']);
			return false;
		}
	}
	
	public function ParseAuthSettings()
	{
		if (!empty($_SESSION['dsm_auth_settings'])) {
			foreach ($_SESSION['dsm_auth_settings'] as $k_setting => $setting) {
				if (!defined('DSM_'.$k_setting))
					define ('DSM_'.$k_setting,$setting);
			}
			$this->InitDateTimeFormat(DSM_DSM_DATE_FORMAT, DSM_DSM_TIME_FORMAT);
		}
		else
			false;
	}
	
	public function SetAuthSettings($settings)
	{
		if (!empty($settings)) {
			$_SESSION['dsm_auth_settings'] = json_decode(json_encode($settings),true);
			$this->ParseAuthSettings();
			return $_SESSION['dsm_auth_settings'];
		}
		else {
			unset($_SESSION['dsm_auth_settings']);
			return false;
		}
	}
	
	private function InitDateTimeFormat($date_format, $time_format)
	{
		if ($date_format == 'US') {
			$sqldf = '%b %e, %Y';
			$phpdf = 'M j, Y';
			$dtpdf = 'MMM D, YYYY';
		}
		elseif ($date_format == 'AU') {
			$sqldf = '%e/%c/%Y';
			$phpdf = 'j/n/Y';
			$dtpdf = 'DD/MM/YYYY';			
		}
		
		if ($time_format == '12h') {
			$sqltf = '%l:%i %p';
			$phptf = 'h:i A';
			$ct = 'h(:mm)t';
		}
		elseif ($time_format == '24h') {
			$sqltf = '%k:%i';
			$phptf = 'G:i';
			$ct = 'H:mm';			
		}
		
		$date_time_formats = array(
			'SQLDATE' 		=> $sqldf,				// MySQL Date
			'SQLTIME' 		=> $sqltf,				// MySQL Time
			'SQLDATETIME' 	=> $sqldf.' '.$sqltf,	// MySQL Date and Time
			'SQLD' 			=> '%Y-%m-%d',			// MySQL Date
			'PHPDATE' 		=> $phpdf,				// PHP Date
			'PHPMONTHDAY' 	=> 'M j',				// PHP Month and Day
			'PHPTIME' 		=> $phptf,				// PHP Time
			'PHPDATETIME' 	=> $phpdf.' '.$phptf,	// PHP Date and Time
			'PHPDT' 		=> 'Y-m-d H:i:s',
			'PHPD' 			=> 'Y-m-d',
			'JQUDATE' 		=> 'M dd, yy',			// JQuery
			'DTPDATE' 		=> $dtpdf,				// DateTimePicker
			'CALENDARTIME'	=> $ct
		);

		foreach ($date_time_formats as $k => $v)
			if (!defined('DSM_'.$k))
				define('DSM_'.$k, $v);
	}	
	
	public function GetRegisterForm()
	{
		$profile = parent::GetList("auth/register");
		$form = $profile->form;
		return $form;
	}
}