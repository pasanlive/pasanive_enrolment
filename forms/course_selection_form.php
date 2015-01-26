<?php

// moodleform is defined in formslib.php
require_once ("$CFG->libdir/formslib.php");
require_once("locallib.php");
class course_slection_form extends moodleform {
	public function definition() {
		global $CFG;
		global $COURSE, $USER;	
		global $idNo;

		$mform = $this->_form;
		
		$courses = $this->_customdata['courses'];

        $course_enrolments = null;

        $studentId = $USER->idnumber;
        if (is_enrolment_info_exist($studentId, 1, 2014)) {
            $mform->addElement('html', '<p style="ccolor: #9F6000;margin: 10px 0px;padding:12px;background-color: #FEEFB3;text-align:left;margin-top: 50px;">' . get_string('course_already_selected_warning', 'pasanliveenrolment') . '</p>');
            $enrolment_id = get_enrolment_id($studentId, 1, 2014);
//            echo 'enrolment id : ' .  $enrolment_id;
            if ($enrolment_id != null) {
                $course_enrolments = get_enrolled_courses($USER->idnumber, $enrolment_id);
//                print_r($course_enrolments);
            }
        }
		
		$mform->addElement ( 'text', 'student_no', get_string('student_number_caption', 'pasanliveenrolment'), array('disabled'=>'true'));
		$mform->setType ( 'student_no', PARAM_NOTAGS );
		$mform->setDefault ( 'student_no', $USER->idnumber);
		
		$mform->addElement('text', 'student_name', get_string('student_name_caption', 'pasanliveenrolment'), array('disabled'=>'true'));
		$mform->setType ( 'student_name', PARAM_NOTAGS );
		$mform->setDefault('student_name', $USER->firstname . ' ' . $USER->lastname);
		
//		echo $USER->department;
		
		$mform->addElement('header', 'nameforyourheaderelement', get_string('course_list_caption', 'pasanliveenrolment'));
		$list = get_courses();


        $coursesArray = array();
        foreach ($courses as $course) {
			foreach ( $list as $c ) {
				if ($course->course_id == $c->idnumber) {
                    $selected_course = false;
					$attr_array = array('group' => 1);
					if ($course->is_compulsory) {
//						$attr_array['disabled'] = 'disabled';
                        $attr_array['onclick'] = 'this.checked=!this.checked; alert("This course is compulsory for you.")';
					} else {
                        if ($course_enrolments != null) {
                            foreach ($course_enrolments as $ce) {
                                if ($ce->course_id == $c->id) {
                                    $selected_course = true;
                                }
                            }
                        }
                    }
					$mform->addElement("advcheckbox", 'course_' .$c->id, '', $c->idnumber . ' : ' . $c->fullname, $attr_array);
					$mform->setDefault('course_' .$c->id, ($course->is_compulsory || $selected_course));

//                    $element = $mform->createElement("advcheckbox", 'course_' .$c->id, '', $c->idnumber . ' : ' . $c->fullname, $attr_array);
//                    $coursesArray =& $element;
					continue;
				}
			}
		}
//        $mform->addGroup($coursesArray, 'courses');
		
		$id = $idNo;
		$mform->addElement('hidden', 'id', 14);
		$mform->setType('id', PARAM_INT);
		
		
		//normally you use add_action_buttons instead of this code
		$buttonarray=array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = &$mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
	}
}