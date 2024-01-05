<?php
namespace DanceStudioManager;
$classes_list = App::GetClient()->GetController('classes')->GetClasses();

$i = 0;
$monthly_schedule = array();
foreach ($classes_list->schedules as $schedules) {
    if (is_array($schedules->data)) {
        foreach ($schedules->data as $schedule) {
            $title = '';
            $title .= $schedule->CODE . ', ';
            $title .= $schedule->NAME. ', ';
            $title .= $schedule->LEVEL. ', ';
            $title .= $schedule->PROGRAM. ', ';
            $title .= $schedule->LOCATION. ', ';
            $title .= str_replace(' +0', '', $schedule->STUDENTS_QUANTITY. ' students, ');

            $title = rtrim($title, ', ');
           
            $color = $schedule->COLOR ? '#'.$schedule->COLOR : '';
            $monthly_schedules[$i]['title'] = $title;
            $monthly_schedules[$i]['schedule_id'] = $schedule->ID;
            $monthly_schedules[$i]['class_id'] = $schedule->CLASS_ID;
            $monthly_schedules[$i]['class_type'] = $schedule->CLASS_TYPE;
            $monthly_schedules[$i]['start_date'] = $schedule->START_DATE;
            $monthly_schedules[$i]['start'] = $schedule->START;
            $monthly_schedules[$i]['end'] = $schedule->END;
            $monthly_schedules[$i]['num_students'] = str_replace(' +0', '', $schedule->STUDENTS_QUANTITY);
            $monthly_schedules[$i]['max_students'] = $schedule->MAX_STUDENTS;
            $monthly_schedules[$i]['instructor_id'] = $schedule->INSTRUCTOR_ID;
            $monthly_schedules[$i]['instructor_name'] =  $schedule->INSTRUCTOR;
            $monthly_schedules[$i]['allDay'] = false;
            $monthly_schedules[$i]['backgroundColor'] = $color;
            $monthly_schedules[$i]['borderColor'] = $color;
            $monthly_schedules[$i]['menu'] = 1;
            $i++;
		}
    }
}
echo json_encode($monthly_schedules);