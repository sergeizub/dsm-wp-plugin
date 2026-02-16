<?php
namespace DanceStudioManager;

class Error
{
    public function __construct()
    {}
    
    public function Show($msg)
	{
		echo '<br/>';
        echo '<div class="alert alert-danger">Error: '.esc_html($msg).'</div>';
    }
	
	public function Success($msg)
	{
		echo '<br/>';
        echo '<div class="alert alert-success" role="alert">Success: '.esc_html($msg).'</div>';
    }
}