<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Client
{
	protected $controllers = array();
	protected $tab;
	
    public function __construct()
    {
		if(empty(session_id())) {
            session_start();
        }
	   add_action('wp_ajax_dsmclient', array($this,'AjaxClient'));
	   add_action('wp_ajax_nopriv_dsmclient', array($this,'AjaxClient'));
	  
	   $controller_files = glob(__DIR__.'/controllers/*Controller.php');
	   foreach ($controller_files as $file) {
			$controller = basename($file, ".php");
			if ($controller == 'BaseController')
				continue;
			$controller_name = 'DanceStudioManager\\'.$controller;
			$controller_key = strtolower(str_ireplace("Controller","",$controller));
			$this->controllers[$controller_key] = new $controller_name();
			
	   }
    }
	
	public function GetController($controller)
	{
		if (isset($this->controllers[$controller]))
			return $this->controllers[$controller];
		else throw new \Exception("Controller ".esc_html($controller)." not found");
	}
	
	public function GetTab()
	{
		return $this->tab;
	}
	
	public function AjaxClient() 
	{
		$boot_tab = isset($_POST['boot_tab']) ? sanitize_text_field($_POST['boot_tab']) : '';
		switch($boot_tab)
		{
			case 'logout' :
				echo esc_html(App::GetClient()->GetController('auth')->Logout());
				break;
			default :
				if (!empty($_POST['obj']) && !empty($_POST['method'])) {
					$obj = sanitize_text_field($_POST['obj']);
					$method =  sanitize_text_field($_POST['method']);
					$controller = $this->GetController($obj);
					if (is_callable([$controller, $method]))
						$controller->$method($_POST);
					else throw new \Exception("Method ".esc_html($method)." not found in controller ".esc_html($obj));
				}
				if ($boot_tab) {
					$tab = str_replace("#",'',$boot_tab);
					$tab = str_replace("tab-",'',$tab);
					$this->tab = $tab;
					$tab_path = explode("-",$tab);
					if (is_numeric($tab_path[count($tab_path)-1]))
					{
						App::GetApi()->SetIdParam($tab_path[count($tab_path)-1]);
						unset($tab_path[count($tab_path)-1]);
						$tab = implode("-",$tab_path);
					}
					if (isset($_REQUEST['type']) && $_REQUEST['type'] == "json"){
						App::GetTemplate()->Load($tab.'_json.php');
					}
					else
						App::GetTemplate()->Load($tab.'.php');
				}
			break; 
		}
		die;
	}
	
	public function Output()
	{
		//Allow output client only at the secure page
		if (!is_ssl()) {
			echo '<div class="alert alert-danger">DSM plugin runs only at secure (https) page</div>';
			return;
        }
		$this->tab = false;
		App::GetTemplate()->Load(  'header.php' );
		if (!empty($_POST['dsm_action'])) {
				$obj = sanitize_text_field($_POST['obj']);
				if (!empty($_POST['method'])){
					$method = sanitize_text_field($_POST['method']);
					$this->GetController($obj)->$method($_POST);
				}
				else
					$this->GetController($obj)->Submit($_POST);
				$this->tab = sanitize_text_field($_POST['boot_tab']);
		}
		
		if ($this->GetController('auth')->isLogged()) {
			App::GetTemplate()->Load(  'nav-logged.php' );
			App::GetTemplate()->Load(  'footer.php' );
		}
		else
		{
			App::GetApi()->AuthorizationSettings();
			if (App::GetApi()->stop_plugin)
				return;
			App::GetTemplate()->Load(  'nav.php' );
			App::GetTemplate()->Load(  'footer.php' );
		}
	}
	
	public function NavRedirect() 
	{
		$params = "";
		if (isset($_SESSION['dsm_redirect']) && !empty($_SESSION['dsm_redirect']['boot_tab'])) {
			foreach ($_SESSION['dsm_redirect'] as $k=>$v) {
				if ($k == 'boot_tab') continue;
				$params .= " jQuery(redir_form).append( \"<input type='hidden' name='".$k."' value='".$v."' /> \");";
			}
			return "var redir_form = jQuery('<form></form>'); var redir_link = jQuery('<a href=\"#".sanitize_text_field($_SESSION['dsm_redirect']['boot_tab'])."\"></a>'); ".$params.";dsm_ajax_click(redir_link,redir_form);";
			
		}
		return "dsm_ajax_click(jQuery('.default_tab'))";
	}
}