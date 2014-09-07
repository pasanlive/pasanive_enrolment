<?php

// moodleform is defined in formslib.php
require_once ("$CFG->libdir/formslib.php");
class course_slection_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;	

		$mform = $this->_form;
		
		$mform->addElement ( 'text', 'student_no', get_string('student_number_caption', 'pasanliveenrolment'), array('disabled'=>'true'));
		$mform->setType ( 'student_no', PARAM_NOTAGS );
		$mform->setDefault ( 'student_no', $USER->idnumber);
		
		$mform->addElement('text', 'student_name', get_string('student_name_caption', 'pasanliveenrolment'), array('disabled'=>'true'));
		$mform->setType ( 'student_name', PARAM_NOTAGS );
		$mform->setDefault('student_name', $USER->firstname . ' ' . $USER->lastname);
		
		$mform->addElement('header', 'nameforyourheaderelement', get_string('course_list_caption', 'pasanliveenrolment'));
		$list = get_courses();
		
		foreach ( $list as $c ) {	
			if ($c->category == '1') {
				$mform->addElement("advcheckbox", 'course_' .$c->id, '', $c->idnumber . ' : ' . $c->fullname, array('group' => 1));
			}
		}
		
		//normally you use add_action_buttons instead of this code
		$buttonarray=array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = &$mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
	}
}