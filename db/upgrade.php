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
 * @package    mod_pasanlive_enrolment
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute pasanlive_enrolment_module upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
*/
function xmldb_pasanliveenrolment_upgrade($oldversion) {
	global $DB;

	$dbman = $DB->get_manager(); // loads ddl manager and xmldb classes

	if ($oldversion < 2014082403) {
		echo 'updating db<br />';;
		upgrade_mod_savepoint(true, 2014082403, 'pasanliveenrolment');
	}

	if ($oldversion < 2014090700) {
		$table = new xmldb_table('pasanlive_enrolment');

		if ($dbman->table_exists($table)) {
			$dbman->rename_table($table, 'pasanlive_enrolment_info', $continue=true, $feedback=true);
		} else {
			$table = new xmldb_table('pasanlive_enrolment_info');

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
				
			$table->addKey($key);
			echo 'key created<br />';
				
			$status = $dbman->create_table($table);
			echo 'table create status : ' . $status;
		}
		$table = new xmldb_table('pasanlive_enrolment');
		$field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		$field2 = new xmldb_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'id');
		$field3 = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'name');
		$field4 = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'semester');
		$field5 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','enrolment_status');
		$field6 = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','timecreated');
			
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

		$table->addKey($key);
		echo 'key created<br />';

		$status = $dbman->create_table($table);
		echo 'table create status : ' . $status;
		
		upgrade_mod_savepoint(true, 2014090700, 'pasanliveenrolment');
	}
	
	if ($oldversion < 2014090701) {
		$table = new xmldb_table('pasanlive_enrolment');
	
		if ($dbman->table_exists($table)) {
			$dbman->rename_table($table, 'pasanliveenrolment', $continue=true, $feedback=true);
		}
		
		upgrade_mod_savepoint(true, 2014090701, 'pasanliveenrolment');
	}
	
	if ($oldversion < 2014091201) {
		$table = new xmldb_table('pasanlive_course_allocation');
		
		$field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		$field2 = new xmldb_field('group', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, 'id');
		$field3 = new xmldb_field('year', XMLDB_TYPE_CHAR, '4', null, XMLDB_NOTNULL, null, null, 'group');
		$field4 = new xmldb_field('semester', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null, 'year');
		$field5 = new xmldb_field('course_id', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'semester');
		$field6 = new xmldb_field('is_optional', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null, 'course_id');
		$field7 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','is_optional');
		$field8 = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','timecreated');
			
		$key = new xmldb_key('primary');
		$key->set_attributes(XMLDB_KEY_PRIMARY, array('id'), null, null);
		
		$table->addField($field1);
		$table->addField($field2);
		$table->addField($field3);
		$table->addField($field4);
		$table->addField($field5);
		$table->addField($field6);
		$table->addField($field7);
		$table->addField($field8);
		
		$table->addKey($key);
		
		$status = $dbman->create_table($table);
		
		upgrade_mod_savepoint(true, 2014091201, 'pasanliveenrolment');
	}
	
	if ($oldversion < 2014091300) {
		$table = new xmldb_table('pasanlive_course_allocation');
		
		$field = new xmldb_field('is_optional', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null, 'course_id');
		$dbman->rename_field($table, $field, 'is_compulsory', $continue=true, $feedback=true);
		
		upgrade_mod_savepoint(true, 2014091300, 'pasanliveenrolment');
	}
	
	if ($oldversion < 2014101704) {
		$table = new xmldb_table('pasanlive_course_allocation');
		
		if ($dbman->table_exists($table)) {
			$dbman->drop_table($table, $continue=true, $feedback=true);
		}
		
		$field1 = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		$field2 = new xmldb_field('group_id', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, 'id');
		$field3 = new xmldb_field('year', XMLDB_TYPE_CHAR, '4', null, XMLDB_NOTNULL, null, null, 'group');
		$field4 = new xmldb_field('semester', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, null, 'year');
		$field5 = new xmldb_field('course_id', XMLDB_TYPE_CHAR, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'semester');
		$field6 = new xmldb_field('is_compulsory', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, 0, 'course_id');
		$field7 = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','is_optional');
		$field8 = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0','timecreated');
			
		$key = new xmldb_key('primary');
		$key->set_attributes(XMLDB_KEY_PRIMARY, array('id'), null, null);
		
		$table->addField($field1);
		$table->addField($field2);
		$table->addField($field3);
		$table->addField($field4);
		$table->addField($field5);
		$table->addField($field6);
		$table->addField($field7);
		$table->addField($field8);
		
		$table->addKey($key);
		
		$status = $dbman->create_table($table);
		upgrade_mod_savepoint(true, 2014101704, 'pasanliveenrolment');
		
	}


	return true;
}
