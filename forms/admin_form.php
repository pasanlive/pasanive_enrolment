<?php

echo 'Hi Admin';

require_once ("$CFG->libdir/formslib.php");
class admin_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;
		
		$mform = $this->_form;
		
		$date = usergetdate(time());
		
		$semesters = array(1, 2, 3, 4);
		$groups = array('MCS', "MIT", 'MIS');
// 		$added_courses = array();
		$list = get_courses();
		
		$availableCourses = array();
		
		foreach ( $list as $c ) {
			if ($c->category == '1') {
				$availableCourses[$c->idnumber] = $c->idnumber . ' : ' . $c->fullname;
			}
		}
		
		
		$mform->addElement('select', 'group', get_string('group_caption', 'pasanliveenrolment'), $groups);
		
		$mform->addElement('select', 'semester', get_string('semester_caption', 'pasanliveenrolment'), $semesters); 
		
		$mform->addElement('header', 'addCoursesForSemester', get_string('add_courses_for_semester', 'pasanliveenrolment'));
		
		$availableCourseList = $mform->addElement('select', 'available_course_list', '', $availableCourses);
		$availableCourseList->setMultiple(true);
		
		$mform->addElement('header', 'selectCompulsoryCoursesHeader', get_string('compulsory_courses_select_header', 'pasanliveenrolment'));
		
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