<?php
namespace DanceStudioManager;
use \DateTime, \DateInterval;

if ( ! defined( 'ABSPATH' ) ) exit;

$filter = array();

if (!empty($_REQUEST['start']))
	$filter['start'] = sanitize_text_field($_REQUEST['start']);
else
    $filter['start'] = date(DSM_PHPDATE);

if (!empty($_REQUEST['class_location']))
	$filter['class_location'] = sanitize_text_field($_REQUEST['class_location']);

if (isset($_SESSION['dsm_client_attrs']) && isset($_SESSION['dsm_client_attrs']['days_quantity']) && (int)$_SESSION['dsm_client_attrs']['days_quantity'] > 0) {
   $filter['days_quantity'] = $_SESSION['dsm_client_attrs']['days_quantity'];
} elseif(!isset($_SESSION['dsm_client_attrs']['class_days_limit']))  {
    $filter['days_quantity'] = '99999'; //Unlimited
}

$end_date = new DateTime($filter['start']);
$end_date->add(new DateInterval('P'.$filter['days_quantity'].'D')); 

$classes_list = App::GetClient()->GetController('classes')->GetClasses((array)$_SESSION['dsm_client_attrs'] + $filter);

foreach ($classes_list->schedules as $day) 
    foreach($day->data as $schedule) 
        $location_classes[] = $schedule;

echo json_encode(array('location_classes' => $location_classes, 'end_date' => $end_date->format(DSM_PHPDATE)));