<?php

echo 'Hi Admin';

require_once ("$CFG->libdir/formslib.php");
class admin_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;
		
		$mform = $this->_form;
		
		$date = usergetdate(time());
		
		$mform->addElement('text', 'year', get_string('academic_year', 'pasanliveenrolment'));
		$mform->setType ( 'year', PARAM_NOTAGS );
		$mform->setDefault ( 'year', $date['year']);
		
		$mform->addElement('header', 'addCoursesForSemester', get_string('add_courses_for_semester', 'pasanliveenrolment'));
		
		$mform->addElement('header', 'selectCompulsoryCoursesHeader', get_string('compulsory_courses_select_header', 'pasanliveenrolment'));
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