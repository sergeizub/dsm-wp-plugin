<?php
namespace DanceStudioManager;

class Error
{
    public function __construct()
    {}
    
    public function Show($msg)
	{
		echo '<br/>';
        echo '<div class="alert alert-danger">Error: '.$msg.'</div>';
    }
	
	public function Success($msg)
	{
		echo '<br/>';
        echo '<div class="alert alert-success" role="alert">Success: '.$msg.'</div>';
    }
}