<?php
namespace DanceStudioManager;

class Emailer
{
    public function __construct()
    {
       add_filter( 'wp_mail_content_type', function($content_type){
			return "text/html";
		});
    }
	
	public function ClientRegistrationNotification($family,$fields)
	{
		$labels = array();
		if (is_array($fields))
			foreach ($fields as $field)
				$labels[$field->name] = $field->label;
		
		$message = "Congratulations! Your account was registered successfully. <br /><br />";
		$message .=  "Please check your personal information:<br />";
		$message .=  "Name: " . $family['FIRSTNAME'] . " " . $family['LASTNAME'] . "<br />";
		$message .=  $labels['ADDRESS'].": " . $family['ADDRESS'] . " " . $family['ZIP'] . "<br />";
		$message .=  $labels['CITY'].": " . $family['CITY'] . "<br />";
		$message .=  $labels['STATE'].": " . $family['STATE'] . "<br /><br />";
		$message .=  !empty($family['PHONE1']) ? $labels['PHONE1'] . ": " . $family['PHONE1'] . "<br />" : '';
		$message .=  !empty($family['PHONE2']) ? $labels['PHONE2'] . ": " . $family['PHONE2'] . "<br />" : '';
		$message .=  !empty($family['PHONE3']) ? $labels['PHONE3'] . ": " . $family['PHONE3'] . "<br />" : '';
		$message .=  !empty($family['EMAIL'])  ? $labels['EMAIL']   .": " . $family['EMAIL'] . "<br />" : '';
		$message .=  !empty($family['EMAIL2']) ? $labels['EMAIL2']  .": " . $family['EMAIL2'] . "<br />" : '';
		$message .=  !empty($family['NOTES']) ? "<br />" . $family['NOTES'] . "<br />" : '';
		$message .= "Your DSM Url: ".get_option('dsm_api_url');
		
		$subject = "New Account";
		
		if (!empty($family['EMAIL']))
			$emails[] = $family['EMAIL'];

		if (!empty($family['EMAIL2']))
			$emails[] = $family['EMAIL2'];

		if (count($emails) > 0){
			wp_mail( $emails, $subject, $message);
		}
	}
}