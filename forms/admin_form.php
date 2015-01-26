<?php

echo 'Hi Admin';

require_once ("$CFG->libdir/formslib.php");
require_once("locallib.php");
class admin_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;
		global $idNo;
		global $availableCourses;
		
		$mform = $this->_form;
		$addedCourses = $this->_customdata['acourses'];
		$isInitialDataSet = isset($this->_customdata['isInitialDataSet'])?$this->_customdata['isInitialDataSet']:false;
		
		$date = usergetdate(time());
		
		$semesters = array(1, 2, 3, 4);
		$groups = get_groups();
		
// 		$added_courses = array();
		$list = get_courses();
		
		$availableCourses = array();
		$addedCourses = generate_course_list_for_select($this->_customdata['acourses']);
		
		$availableCourseCount = 0;
		if ($list) {		
			foreach ( $list as $c ) {
				if ($c->category == '1' && !isset($addedCourses[$c->idnumber])) {
					$availableCourses[$c->idnumber] = $c->idnumber . ' : ' . $c->fullname;
					$availableCourseCount++;
				}
			}
		} 
		$availableCourses['0'] = $availableCourseCount . ' courses available';
		ksort($availableCourses);
		
		
		$mform->addElement('select', 'group', get_string('group_caption', 'pasanliveenrolment'), $groups);
		
		$mform->addElement('select', 'semester', get_string('semester_caption', 'pasanliveenrolment'), $semesters); 
		
// 		if ($isInitialDataSet) {
		$mform->addElement('header', 'addCoursesForSemester', get_string('add_courses_for_semester', 'pasanliveenrolment'));
		
		$mform->addElement('html', '<div class="inline_block" style="display:inline; float:left">');
		$availableCourseList = $mform->addElement('select', 'available_course_list', '', $availableCourses);
		$availableCourseList->setMultiple(true);
		$mform->addElement('html', '</div>');

		$mform->addElement('html', '<div class="inline_block" style="display:inline; float:left;padding:10px">');
		$mform->addElement('submit', 'add_button', 'add >>');
		$mform->addElement('submit', 'remove_button', '<< remove');
		$mform->addElement('html', '</div>');

		$mform->addElement('html', '<div class="inline_block" style="display:block; float:left">');
		$addedCourseList = $mform->addElement('select', 'added_course_list', '', $addedCourses);
		$addedCourseList->setMultiple(true);
// 		echo '<br />Available courses : ';
// 		print_r($availableCourses);
// 		echo '<br />Added courses : ';
// 		print_r($addedCourses);

		$mform->addElement('html', '</div>');
		$mform->addElement('header', 'selectCompulsoryCoursesHeader', get_string('compulsory_courses_select_header', 'pasanliveenrolment'));
		
		foreach ( $addedCourses as $k=>$c ) {
			if ($k != '0')
				$mform->addElement("advcheckbox", 'course_' .$k, '', $c, array('group' => 1));			
		}
// 		}
		$id = $idNo;
		$mform->addElement('hidden', 'id', $id);
		$mform->setType('id', PARAM_INT);
		
		//normally you use add_action_buttons instead of this code
		$buttonarray=array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = &$mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
		
	}
	
	public function addCourse($selectedCourseList) {
// 		global $addedCourses;
		
		$i = count($this->addedCourses);
		foreach ($selectedCourseList as $course){
			$this->addedCourses[++$i] = $course;
		}
		print_r($this->addedCourses);		
	}
}