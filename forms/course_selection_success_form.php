<?php
/**
 * User: Pasan Buddhika
 */

class course_slection_success_form extends moodleform {
    public function definition()
    {

        $mform = $this->_form;

        $mform->addElement('html', '<p style="color:#009933;text-align:center;margin-top: 50px;">' . get_string('course_selection_form_submit_success', 'pasanliveenrolment') . '</p>');
    }
    }