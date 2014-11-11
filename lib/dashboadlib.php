<?php

/**
* author : Pasan Buddhika Weerathunga
* email : me@pasanlive.com
*/


function get_current_course_allocations() {
	global $DB;
	
	return $DB->get_records_sql('SELECT DISTINCT group_id, semester FROM {pasanlive_course_allocation}');
}

/**
 * Get the list of available groups
 *
 * @param
 * @return array
 */
function get_groups() {
	// TODO : add real implementation to load groups
	$groups = array('MCS', "MIT", 'MIS');
	return $groups;
}
