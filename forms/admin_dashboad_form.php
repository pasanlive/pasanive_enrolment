<?php

/**
* author : Pasan Buddhika Weerathunga
* email : me@pasanlive.com
*/

echo 'Hi Admin';

require_once ("$CFG->libdir/formslib.php");
require_once("locallib.php");
require_once 'lib/dashboadlib.php';
class admin_dashboad_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;
		
		$mform = $this->_form;
		
		$semesters = array(1, 2, 3, 4);
		$groups = get_groups();
		
		$mform->addElement('select', 'group', get_string('group_caption', 'pasanliveenrolment'), $groups);
		
		$mform->addElement('select', 'semester', get_string('semester_caption', 'pasanliveenrolment'), $semesters);
		
		
		$course_allocations = get_current_course_allocations();
	}
}