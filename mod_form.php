<?php

/**
 * The main pasanliveenrolment configuration form
 *
 * @package    mod_pasanlive_enrolment
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_pasanliveenrolment_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('pasanlive_enrolment_tname', 'pasanliveenrolment'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'pasanlive_enrolment_name', 'pasanliveenrolment');

        // Adding the standard "intro" and "introformat" fields
        $this->add_intro_editor();

        //-------------------------------------------------------------------------------
        // Adding the rest of pasanliveenrolment settings, spreeading all them into this fieldset
        // or adding more fieldsets ('header' elements) if needed for better logic
        $mform->addElement('static', 'label1', 'pasanliveenrolmentsetting1', 'Your pasanliveenrolment fields go here. Replace me!');

        $mform->addElement('header', 'pasanliveenrolmentfieldset', get_string('pasanliveenrolmentfieldset', 'pasanliveenrolment'));
        $mform->addElement('static', 'label2', 'pasanliveenrolmentsetting2', 'Your pasanliveenrolment fields go here. Replace me!');

        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
