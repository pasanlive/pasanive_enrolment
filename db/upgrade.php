<?php

/**
 * This file keeps track of upgrades to the pasanlive_enrolment module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    pasanlive_enrolment_module
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute pasanlive_enrolment_module upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_pasanlive_enrolment_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // loads ddl manager and xmldb classes

    if ($oldversion < 2014082403) {
    	echo 'updating db<br />';;
    }
    
   /*  if (false) {
	  	echo 'updating db<br />';
    	$table = new xmldb_table('pasanlive_enrolment');
    	echo 'table initialized';

//     	<FIELD NAME="id" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="true" NEXT="student_id"/>
//     	<FIELD NAME="student_id" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" COMMENT="student id" PREVIOUS="id" NEXT="semester"/>
//     	<FIELD NAME="semester" TYPE="int" LENGTH="1" NOTNULL="true" SEQUENCE="false" COMMENT="semester of the enrolment" PREVIOUS="student_id" NEXT="year"/>
//     	<FIELD NAME="year" TYPE="int" LENGTH="4" NOTNULL="true" SEQUENCE="false" COMMENT="yar of the enrolment" PREVIOUS="semester" NEXT="enrolment_status"/>
//     	<FIELD NAME="enrolment_status" TYPE="text" LENGTH="20" NOTNULL="true" SEQUENCE="false" COMMENT="Enrolment status" PREVIOUS="year" NEXT="timecreated"/>
//     	<FIELD NAME="timecreated" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" PREVIOUS="enrolment_status" NEXT="timemodified"/>
//     	<FIELD NAME="timemodified" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" DEFAULT="0" SEQUENCE="false" PREVIOUS="timecreated"/>
    	
    	$field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
    	$field2 = new xmldb_field('student_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, 'id');
    	$field3 = new xmldb_field('semester', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'student_id');
    	$field4 = new xmldb_field('year', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'semester');
    	$field5 = new xmldb_field('enrolment_status', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null, 'year');
    	$field6 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','enrolment_status');
    	$field7 = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','timecreated');
    	
    	echo 'fields created<br />';
    	
		$key = new xmldb_key('primary');
		echo 'init primary key<br />';
		
		$key->set_attributes(XMLDB_KEY_PRIMARY, array('id'), null, null);
// 		$key->set_attributes(XMLDB_KEY_FORIGN, array('student_id', 'course_id'), null, null);
    	
		echo 'key created<br />';

		$table->addField($field1);
    	$table->addField($field2);
    	$table->addField($field3);
    	$table->addField($field4);
    	$table->addField($field5);
    	$table->addField($field6);
    	$table->addField($field7);
    	echo 'field added<br />';
    	
    	
    	$table->addKey($key);
    	echo 'key created<br />';
    	
    	$status = $dbman->create_table($table);
    	echo 'table create status : ' . $status;
    	
//     	<FIELD NAME="student_id" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="true" NEXT="courseid"/>
// 		<FIELD NAME="course_id" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" COMMENT="Selected course id" PREVIOUS="studentid" NEXT="enrolment_id"/>
//     	<FIELD NAME="enrolment_id" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" COMMENT="enrolment id" PREVIOUS="course_id" NEXT="timecreated"/>
//     	<FIELD NAME="timecreated" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" PREVIOUS="course_name" NEXT="timemodified"/>
//         <FIELD NAME="timemodified" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" DEFAULT="0" SEQUENCE="false" PREVIOUS="timecreated"/>
    	
    	$table = new xmldb_table('pasanlive_enrolment_course_selection');
    	echo 'table initialized';
    	
    	$field1 = new xmldb_field('student_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
    	$field2 = new xmldb_field('course_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, 'student_id');
    	$field3 = new xmldb_field('enrolment_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'course_id');
    	$field6 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','enrolment_id');
    	$field7 = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','timecreated');
    	 
    	echo 'fields created<br />';
    	
    	$table->addField($field1);
    	$table->addField($field2);
    	$table->addField($field3);
    	$table->addField($field4);
    	$table->addField($field5);
    	$table->addField($field6);
    	$table->addField($field7);
    	echo 'field added<br />';
    	 
    	$key = new xmldb_key('primary');
    	echo 'init primary key<br />';
    	
    	$key->set_attributes(XMLDB_KEY_PRIMARY, array('id'), null, null);
    	
    	$table->addKey($key);
    	echo 'key created<br />'; 	   	
    	 
    	$status = $dbman->create_table($table);
    	echo 'table create status : ' . $status;
    	 
    	
    	upgrade_mod_savepoint(true, 2014082400, 'pasantest');
    }    */

    return true;
}
