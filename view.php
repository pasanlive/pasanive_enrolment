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

global $CFG;
global $COURSE, $USER;

$context = context_course::instance($COURSE->id);

$isadmin = false;
$isteacher = false;

$roles = get_user_roles($context, $USER->id);
$admins = get_admins();

foreach($admins as $admin) {
	if ($USER->id == $admin->id) {
		$isadmin = true;
		echo 'you are a admin user';
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

// / Print the page header
$PAGE->set_url ( '/mod/pasanliveenrolment/view.php', array (
		'id' => $cm->id 
) );
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
	$mform = new admin_form();
	$mform->display();
} else if ($isteacher) {
	echo 'You are a teacher';
} else {
	require_once ('forms/course_selection_form.php');
	$mform = new course_slection_form();
	
	$mform->display();
}




// Finish the page

// echo '>>>>>';
echo $OUTPUT->footer ();
