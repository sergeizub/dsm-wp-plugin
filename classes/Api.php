<?php
namespace DanceStudioManager;

class Api
{
    protected $token = null;
    protected $url = null;
	protected $api_key = null;
    protected $api_version = null;
	public $stop_plugin = false;
	protected static $api_version_list = array('v1' => 'v1');
	protected $id_param = null;
	const SOURCE_ID = "4";

    public function __construct()
    {
        $this->url = get_option('dsm_api_url');
		$this->api_key = get_option('dsm_api_key');
        //if (empty($this->api_key))
            //App::GetError()->Show("Incorrect Api Key");
        $this->api_version = get_option('dsm_api_version');

    }

	public function ValidateUrl()
	{
        if (filter_var( $this->url, FILTER_VALIDATE_URL) !== false)
			return true;
		else
			App::GetError()->Show("Sumbit Valid DSM Url");
    }

	public function ValidateApiVersion()
	{
        if (in_array($this->api_version,self::$api_version_list))
			return true;
		else
			App::GetError()->Show("Select Valid Api Version");
    }

	public function ValidateDSMUrl()
	{
		if (!$this->ValidateUrl())
			return false;

		if (!$this->ValidateApiVersion())
			return false;

		return true;
	}

	public static function GetApiVersionList()
	{
		return self::$api_version_list;
	}

	public function SetIdParam($id_param)
	{
		$this->id_param = $id_param;
	}

	public function GetIdParam()
	{
		return  $this->id_param;
	}

    public function GetAuthorizationToken()
	{
		$auth_token = App::GetClient()->GetController('auth')->GetAuthToken();
		if (!empty($auth_token))
			$this->token = $auth_token;

		if (!empty($this->token))
			return "Bearer ".$this->token;
		else
			return false;
    }
    
    public function AuthorizationSettings() 
	{
        if (!empty($this->api_key))
            App::GetClient()->GetController('auth')->SetAuthSettings($this->GetList(array('dsm_action' => 'settings/')));
    }

	public function ClassInfo($class_id)
	{
		if(!$this->ValidateDSMUrl())
			return false;

		$authorization_token = App::GetApi()->GetAuthorizationToken();
		if (!empty($authorization_token) && !empty($class_id)) {
			$response = $this->GetList('classes/'.$class_id);
			if (is_object($response->groupclasses) || is_array($response->groupclasses))
				foreach ($response->groupclasses as $k => $v)
					$result = $v;

			return $result;
		}
		return false;
	}

	public function ClassesListCollection()
	{
		$schedules_list = $this->GetList('classes/');
		if (!$this->ValidateDSMUrl() || $schedules_list == false)
			return false;
		$calsses_list = array();

		foreach ($schedules_list->schedules as $a=>$b) {
			foreach ($b->data as $c => $d) {
                    if ($d->STATUS != '2')
                        continue;
					if (!isset($calsses_list[$d->CLASS_ID]))
					{
						$calsses_list[$d->CLASS_ID] = $d;
					}
				}
		}
		return $calsses_list;
	}

	public function Submit($post)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		if (empty($post['dsm_action']))
			return false;

		$post_action = str_replace('_','/',$post['dsm_action']);

		if ($post['dsm_action'] != 'auth/login' && $post['dsm_action'] != 'auth/register')
			$authorization_token = App::GetApi()->GetAuthorizationToken();

		if($post_action == 'members/edit' && !empty($this->GetIdParam()))
			$post_action .= '/'.$this->GetIdParam();
        
        $httpheader = array('Content-Type' => 'application/json', 'Source-Id' => self::SOURCE_ID);
        
        if (!empty($this->api_key))
                $httpheader += ['x-api-key' => $this->api_key];
			
		if (!empty($authorization_token))
                $httpheader += ['Authorization' => $authorization_token];
        
		$result = wp_remote_post( $this->url."api/".$this->api_version.'/'.$post_action , array( 'body' => json_encode($post), 'headers' => $httpheader ));
        $response = json_decode(wp_remote_retrieve_body($result));
        
		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		elseif (!empty($response->errors) && !empty($response->errors->message)) {
			App::GetError()->Show($response->errors->message);
			return false;
		}
		elseif (isset($response->success) && $response->success == false) {
			if (!empty($response->message)) {
				App::GetError()->Show($response->message);
				return false;
			}
			elseif (!empty($response->errors))
				if (is_array($response->errors) || is_object($response->errors))
					foreach ($response->errors as $k_error => $v_error) {
						if(isset($k_error) && !is_numeric($k_error))
							App::GetError()->Show($k_error.":".$v_error);
						else
							App::GetError()->Show($v_error);
						echo '<br/>';
						if ($k_error == 'x-api-key')
							$this->stop_plugin = true;
						return false;
					}
				else {
					App::GetError()->Show($response->errors);
					return false;
				}
			elseif (!empty($response->system)) {
				App::GetError()->Show($response->system);
				return false;
			}
			return true;
		}
		else {
            if (!empty($response->message)) {
				App::GetError()->Success($response->message);
			}
			if (!empty($response->token))
				$this->token = $response->token;
			return $response;
		}
	}

	public function Delete($post)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		if (empty($post['dsm_action']))
			return false;

		$post_action = str_replace('_','/',$post['dsm_action']);
		unset($post['dsm_action']);

		$authorization_token = App::GetApi()->GetAuthorizationToken();
        
        $httpheader = array('Content-Type' => 'application/json', 'Source-Id' => self::SOURCE_ID);
        
        if (!empty($this->api_key))
                $httpheader += ['x-api-key' => $this->api_key];
			
		if (!empty($authorization_token))
                $httpheader += ['Authorization' => $authorization_token];
                
        $result = wp_remote_request( $this->url."api/".$this->api_version.'/'.$post_action , array( 'body' => json_encode($post), 'method' => 'DELETE', 'headers' => $httpheader ));
        $response = json_decode(wp_remote_retrieve_body($result));

		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		else if (!empty($response->errors) && !empty($response->errors->message)) {
			App::GetError()->Show($response->errors->message);
			return false;
		}
		elseif (isset($response->success) && $response->success == false) {
			if (!empty($response->message))
				App::GetError()->Show($response->message);
			elseif (!empty($response->errors))
				foreach($response->errors as $k_error => $v_error) {
					App::GetError()->Show($k_error.":".$v_error);
					echo '<br/>';
					if ($k_error == 'x-api-key')
						$this->stop_plugin = true;
				}
			elseif (!empty($response->system))
				App::GetError()->Show($response->system);
			return false;
		}
		elseif (isset($response->success) && $response->success == true) {
			$this->SetIdParam(NULL);
			return $response;
		}
		else {
			return $response;
		}
	}

	public function GetList($get)
	{
		if (!$this->ValidateDSMUrl())
			return false;

		if (empty($get))
			return false;
		elseif (is_array($get)) {
			$action = $get['dsm_action'];
			$params = "?".http_build_query($get);
		}
		else {
			$action = $get;
			$params = '';
		}
		//$action = str_replace('_','/',$action);
		$action = str_replace('schedule/id','schedule_id',$action);

		$dsm_action_path = explode("/",$action);

        if($action != 'auth/login' && $action != 'auth/register' && $action != 'classes/filters/' && $action != 'classes/data'  && $action != 'settings/')
			$authorization_token = App::GetApi()->GetAuthorizationToken();

		if($action == 'members/edit' && !empty($this->GetIdParam()))
			$action .= '/'.$this->GetIdParam();
        
        $httpheader = array('Content-Type' => 'application/json', 'Source-Id' => self::SOURCE_ID);
        
        if (!empty($this->api_key))
                $httpheader += ['x-api-key' => $this->api_key];
			
		if (!empty($authorization_token))
                $httpheader += ['Authorization' => $authorization_token];
		
        $result = wp_remote_get( $this->url."api/".$this->api_version."/".$action.$params , array( 'headers' => $httpheader,  'timeout' => 120 ));
        
		$response = json_decode(wp_remote_retrieve_body($result));
		
		if (!empty($response->error)) {
			App::GetError()->Show($response->error);
			return false;
		}
		elseif (!empty($response->errors)) {
			if (is_iterable($response->errors) || get_class($response->errors) === 'stdClass') {
				foreach($response->errors as $k_error => $v_error) {
					App::GetError()->Show($v_error);
					if ($k_error == 'x-api-key')
						$this->stop_plugin = true;
				}
			}
			else
				App::GetError()->Show($response->errors);
			return false;
		}
		elseif (isset($response->success) && $response->success == false) {
			App::GetError()->Show($response->message);
			return false;
		}
		else {
			return $response;
		}
	}
}