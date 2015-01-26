<?php
/**
 * Prints a particular instance of pasanlive_enrolment_module
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_pasanlive_enrolment
 */
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/config.php');
require_once (dirname ( __FILE__ ) . '/lib.php');

if (!isset($SESSION->addedCourses)) {
	$SESSION->addedCourses = array();
}

global $CFG;
global $COURSE, $USER;
global $idNo;
global $DB;

$context = context_course::instance($COURSE->id);

$isadmin = false;
$isteacher = false;

$roles = get_user_roles($context, $USER->id);
$admins = get_admins();

foreach($admins as $admin) {
	if ($USER->id == $admin->id) {
		$isadmin = true;
		break;
	}
}

if (user_has_role_assignment($USER->id, 3))
	$isteacher = true;

$id = optional_param ( 'id', 0, PARAM_INT ); // course_module ID, or
$n = optional_param ( 'n', 0, PARAM_INT ); // pasanlive_enrolment_module instance ID - it should be named as the first character of the module

if ($id) {
	$cm = get_coursemodule_from_id ( 'pasanliveenrolment', $id, 0, false, MUST_EXIST );
	$course = $DB->get_record ( 'course', array (
			'id' => $cm->course 
	), '*', MUST_EXIST );
	$pasanliveenrolment = $DB->get_record ( 'pasanliveenrolment', array (
			'id' => $cm->instance 
	), '*', MUST_EXIST );
} elseif ($n) {
	$pasanliveenrolment = $DB->get_record ( 'pasanliveenrolment', array (
			'id' => $n 
	), '*', MUST_EXIST );
	$course = $DB->get_record ( 'course', array (
			'id' => $pasanliveenrolment->course 
	), '*', MUST_EXIST );
	$cm = get_coursemodule_from_instance ( 'pasanliveenrolment', $pasanliveenrolment->id, $course->id, false, MUST_EXIST );
} else {
	print_error ( 'You must specify a course_module ID or an instance ID' );
}

require_login ( $course, true, $cm );
$context = context_module::instance ( $cm->id );

add_to_log ( $course->id, 'pasanlive_enrolment', 'view', "view.php?id={$cm->id}", $pasanliveenrolment->id, $cm->id );

$idNo = $cm->id;
// / Print the page header
$PAGE->set_url ( '/mod/pasanliveenrolment/view.php', array (
		'id' => $cm->id 
));
$PAGE->set_title ( format_string ( $pasanliveenrolment->name ) );
$PAGE->set_heading ( format_string ( $course->fullname ) );
$PAGE->set_context ( $context );

// Output starts here
echo $OUTPUT->header ();

$list = get_courses ();

// Replace the following lines with you own code
echo $OUTPUT->heading ( 'Course Selection' );

if ($isadmin) {
		require_once 'forms/admin_form.php';
		$mform = new admin_form(null, array('acourses'=> $SESSION->addedCourses));
	
		$isInitialDataSelected = false;
		
		if ($mform->is_cancelled()) {
			unset($SESSION->addedCourses);
		} else if ($data = $mform->get_data()) {
			if (isset($data->group) && isset($data->semester)) {
				$isInitialDataSelected = true;				
			}
			print_r($data);
			if (!empty($data->add_button) && !empty($data->available_course_list)) {
				foreach ($data->available_course_list as $acourse) {
					if (!isset($SESSION->addedCourses[$acourse])) {
						$SESSION->addedCourses[$acourse] = $acourse;
					}
				}					
			} else if (!empty($data->remove_button) && !empty($data->added_course_list)) {
				foreach ($data->added_course_list as $acourse) {
					unset($SESSION->addedCourses[$acourse]);
				}
			} else if (!empty($data->submitbutton)) {
				save_course_allocations($data, $SESSION->addedCourses);
			}
		} else {
			unset($SESSION->addedCourses);
// 			$SESSION->addedCourses = array();
		}
		// 	unset($_POST);
		$mform = new admin_form(null, array('acourses'=> $SESSION->addedCourses, 'isInitialDataSet'=>$isInitialDataSelected));
		$mform->display();
} else if ($isteacher) {
	echo 'You are a teacher';
} else {
	require_once ('forms/course_selection_form.php');
	$mform = new course_slection_form(null, array('courses'=>load_allocated_courses('2014', '0', '0')));
	
	if ($mform->is_cancelled()) {
		
	} else if ($data = $mform->get_data()) {
		
//		print_r($data);

        $studentId = $USER->idnumber;

        $courses = array();


		if ($data->submitbutton) {
            $courses = filter_selected_courses($data, get_courses());

//            print("<br/>");
//            print("<br/>");
//            print_r($courses);
//            print("<br/>");

            if (is_enrolment_info_exist($studentId, 1, 2014)) {
                update_course_selection($studentId, 1, 2014, $courses);
            } else {
                save_course_selection($studentId, 1, 2014, $courses);
            }

            require_once('forms/course_selection_success_form.php');

            $mform = new course_slection_success_form();
		}
		
	} else {
		
	}
	
	$mform->display();
}




// Finish the page

// echo '>>>>>';
echo $OUTPUT->footer ();
