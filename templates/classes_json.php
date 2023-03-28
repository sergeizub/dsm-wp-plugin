<?php
namespace DanceStudioManager;
use \DateTime;
$classes_list = array();
$add_data = $filter = array();
$add_data_start = sanitize_text_field($_REQUEST['start']);

if(!empty($_REQUEST['start']))
	$add_data['start'] = $add_data_start;

$filter = json_decode(str_replace('\"','"',$_REQUEST['filter']),true);
if(empty($filter))
	$filter = array();
	
	if ($_SESSION['dsm_client_attrs']['week'] == "true") {
		
		if ($_SESSION['dsm_client_attrs']["start_date"])
			$date_now = date(DSM_PHPDATE, strtotime(sanitize_text_field($_SESSION['dsm_client_attrs']["start_date"])));
		else
			$date_now = date(DSM_PHPDATE);
		for ($i=0;$i<7;$i++) {
			$day = date(DSM_PHPDATE, strtotime($date_now. ' + '.$i.' days'));
			$add_data["start"] = $day;
			$classes_list[] = App::GetClient()->GetController('classes')->GetClasses((array)$_SESSION['dsm_client_attrs'] + $filter + $add_data);
		}
	}
	else if(!empty($_SESSION['dsm_client_attrs']))
		$classes_list[] = App::GetClient()->GetController('classes')->GetClasses((array)$_SESSION['dsm_client_attrs'] + $filter + $add_data);
	else
		$classes_list[] = App::GetClient()->GetController('classes')->GetClasses($filter + $add_data);

$i = 0;
$current_date = new DateTime($add_data_start);
$end_date = new DateTime($add_data_start);
$day = new DateTime($add_data_start);

if ($_REQUEST['schedule_week'] == "1") {
	$data = [
			'schedules' => [],
			'current_date' => $day->format('l, '.DSM_PHPDATE). ' - ' .$day->modify('+ 6 day')->format('l, '.DSM_PHPDATE),
			'prev_date' => $day->modify('- 13 day')->format(DSM_PHPDATE),
			'next_date' => $day->modify('+ 14 day')->format(DSM_PHPDATE)
		];
	$end_date->modify('+ 7 day');
} else {
	$data = [
			'schedules' => [],
			'current_date' => $day->format('l, '.DSM_PHPDATE),
			'prev_date' => $day->modify('- 1 day')->format(DSM_PHPDATE),
			'next_date' => $day->modify('+ 2 day')->format(DSM_PHPDATE)
		];
}
foreach ($classes_list as $list_item) {
	foreach ($list_item->schedules as $schedules) {
		if (is_array($schedules->data)) {
			foreach ($schedules->data as $schedule) {
				$schedule_start = new DateTime(date(DSM_PHPDATE, strtotime($schedule->START_DATE)));
				if ($schedule_start->format(DSM_PHPDATE) == $current_date->format(DSM_PHPDATE) && $schedule->STATUS != '2'){
					$data['schedules'][$i] = $schedule;
				}
				elseif ($_REQUEST['schedule_week'] == "1" && $schedule_start >= $current_date && $schedule->STATUS != '2') {
					$data['schedules'][$i] = $schedule;
				}
				$i++;
			}
		}
	}
}

if ($_REQUEST['schedule_week'] != "1")
	usort($data['schedules'], 'dsm_location_sort');

echo json_encode($data);