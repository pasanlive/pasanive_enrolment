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
   

    return true;
}
