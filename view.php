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

$id = optional_param ( 'id', 0, PARAM_INT ); // course_module ID, or
$n = optional_param ( 'n', 0, PARAM_INT ); // pasanlive_enrolment_module instance ID - it should be named as the first character of the module

if ($id) {
	$cm = get_coursemodule_from_id ( 'pasanlive_enrolment', $id, 0, false, MUST_EXIST );
	$course = $DB->get_record ( 'course', array (
			'id' => $cm->course 
	), '*', MUST_EXIST );
	$pasanliveenrolment = $DB->get_record ( 'pasanlive_enrolment', array (
			'id' => $cm->instance 
	), '*', MUST_EXIST );
} elseif ($n) {
	$pasanliveenrolment = $DB->get_record ( 'pasanlive_enrolment', array (
			'id' => $n 
	), '*', MUST_EXIST );
	$course = $DB->get_record ( 'course', array (
			'id' => $pasanliveenrolment->course 
	), '*', MUST_EXIST );
	$cm = get_coursemodule_from_instance ( 'pasanlive_enrolment', $pasanliveenrolment->id, $course->id, false, MUST_EXIST );
} else {
	print_error ( 'You must specify a course_module ID or an instance ID' );
}

require_login ( $course, true, $cm );
$context = context_module::instance ( $cm->id );

add_to_log ( $course->id, 'pasanlive_enrolment', 'view', "view.php?id={$cm->id}", $pasanliveenrolment->enrolment_status, $cm->id );

// / Print the page header

$PAGE->set_url ( '/mod/pasanliveenrolment/view.php', array (
		'id' => $cm->id 
) );
$PAGE->set_title ( format_string ( $pasanliveenrolment->name ) );
$PAGE->set_heading ( format_string ( $course->fullname ) );
$PAGE->set_context ( $context );

// other things you may want to set - remove if not needed
// $PAGE->set_cacheable(false);
// $PAGE->set_focuscontrol('some-html-id');
// $PAGE->add_body_class('pasanliveenrolment-'.$somevar);

// Output starts here
echo $OUTPUT->header ();

// if ($pasanliveenrolment->intro) { // Conditions to show the intro can change to look for own settings or whatever
// 	echo $OUTPUT->box ( format_module_intro ( 'pasanliveenrolment', $pasanliveenrolment, $cm->id ), 'generalbox mod_introbox', 'pasanliveenrolmentintro' );
// }

$list = get_courses ();

// Replace the following lines with you own code
echo $OUTPUT->heading ( 'Course Selection' );
// print_r ( $list [2] );
// echo '#################################<br />';
// foreach ( $list as $c ) {
// 	if ($c->category == '2') {
// 		print_r ( $c->fullname );
// 		echo '<br />';
// 	}
// }

// echo '>>>>>';


require_once ('course_selection_form.php');

$mform = new course_slection_form();

$mform->display();
// Finish the page

// echo '>>>>>';
echo $OUTPUT->footer ();
