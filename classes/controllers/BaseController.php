<?php
namespace DanceStudioManager;

abstract class BaseController
{
	private $short_class_name;
	
    public function __construct()
    {
       $class_name = get_class($this);
	   $file_parts = explode( '\\', $class_name );
	   
	   for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {
			$current = $file_parts[ $i ] ;
			$current = str_ireplace( 'DanceStudioManager', '', $current );
			if ( count( $file_parts ) - 1 === $i ) 
				$this->short_class_name = str_ireplace( 'Controller', '', $current );
	   }
	 
    }

	public function Submit($data) {
		return App::GetApi()->Submit($data);
	}
	
	public function Delete($data) {
		return  App::GetApi()->Delete($data);
	}
	
	public function GetList($data) {
		return App::GetApi()->GetList($data);
	}
}