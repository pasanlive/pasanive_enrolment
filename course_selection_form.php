<?php

// moodleform is defined in formslib.php
require_once ("$CFG->libdir/formslib.php");
class course_slection_form extends moodleform {
	public function definition() {
		global $CFG;
		
		$mform = $this->_form;
		
		$mform->addElement ( 'text', 'student_no', 'Student Number' );
		$mform->setType ( 'student_no', PARAM_NOTAGS );
// 		$mform->setDefault ( 'student_no', 'Please enter your student no' );
		
		$mform->addElement ( 'static', 'available_courses_caption', get_string('course_list_caption', 'pasanliveenrolment'));
		
		$list = get_courses ();
		
		foreach ( $list as $c ) {
			if ($c->category == '2') {
				$mform->addElement("checkbox", 'course_' .$c->id, '', $c->fullname);
			}
		}
		
		//normally you use add_action_buttons instead of this code
		$buttonarray=array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = &$mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
		
// 		$mform->addRule('student_no', "Student number required", 'required');
	}
}