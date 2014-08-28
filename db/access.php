<?php

/**
 * Capability definitions for the pasanlive_enrolment module
 *
 * The capabilities are loaded into the database table when the module is
 * installed or updated. Whenever the capability definitions are updated,
 * the module version number should be bumped up.
 *
 * @package    pasanlive_enrolment_module
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
/***************************** remove these comment marks and modify the code as needed

	'mod/pasantest:addinstance' => array(
			'riskbitmask' => RISK_XSS,
	
			'captype' => 'write',
			'contextlevel' => CONTEXT_COURSE,
			'archetypes' => array(
					'editingteacher' => CAP_ALLOW,
					'manager' => CAP_ALLOW
			),
			'clonepermissionsfrom' => 'moodle/course:manageactivities'
	),

    'mod/pasantest:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'guest' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),

    'mod/pasantest:submit' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'student' => CAP_ALLOW
        )
    ),
******************************/
);

