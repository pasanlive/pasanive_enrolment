<?php
/**
 * Definition of log events
 *
 * @package    pasanlive_enrolment_module
 */

defined('MOODLE_INTERNAL') || die();

global $DB;

$logs = array(
    array('module'=>'pasanlive_enrolment', 'action'=>'add', 'mtable'=>'pasanlive_enrolment', 'field'=>'id'),
    array('module'=>'pasanlive_enrolment', 'action'=>'update', 'mtable'=>'pasanlive_enrolment', 'field'=>'id'),
    array('module'=>'pasanlive_enrolment', 'action'=>'view', 'mtable'=>'pasanlive_enrolment', 'field'=>'id'),
    array('module'=>'pasanlive_enrolment', 'action'=>'view all', 'mtable'=>'pasanlive_enrolment', 'field'=>'id')
);
