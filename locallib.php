<?php

/**
 * Internal library of functions for module pasanliveenrolment
 *
 * All the pasanliveenrolment specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_pasanlive_enrolment
 */

require_once ("$CFG->libdir/datalib.php");

defined('MOODLE_INTERNAL') || die();

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

/**
 * Generate course list to display in select list views
 * @param $courseIdList
 * @return array§§
 */
function generate_course_list_for_select($courseIdList) {
    $all_course_list = get_courses();
    $course_list = array();

    $addedCourseCount = 0;
    if ($courseIdList) {
    	foreach ($courseIdList as $c) {
    		for($i = 1; $i < count($all_course_list); $i++) {
    			if ($c == $all_course_list[$i]->idnumber) {
    				$course_list[$all_course_list[$i]->idnumber] = $all_course_list[$i]->idnumber . ' : ' . $all_course_list[$i]->fullname;
    				$addedCourseCount++;
    				continue;
    			}
    		}
    	}    	
    } 
    $course_list['0'] = $addedCourseCount . ' courses added';
	ksort($course_list, 1);
    return $course_list;
}

/**
 * Save course allocation data
 */
function save_course_allocations($data, $selectedCourses) {
	global $DB;
	$group = $data->group;
	$semester = $data->semester;
	
	$records = array();
	
	/*
	 * <FIELD NAME="id" SEQUENCE="true" TYPE="int" NOTNULL="true" LENGTH="10" UNSIGNED="true" NEXT="group"/>
            <FIELD NAME="group" SEQUENCE="false" TYPE="char" NOTNULL="true" LENGTH="10" PREVIOUS="id" NEXT="year"/>
            <FIELD NAME="year" SEQUENCE="false" TYPE="char" NOTNULL="true" LENGTH="4" PREVIOUS="group" NEXT="semester"/>
            <FIELD NAME="semester" SEQUENCE="false" TYPE="int" NOTNULL="true" LENGTH="1" PREVIOUS="year" NEXT="course_id"/>
            <FIELD NAME="course_id" SEQUENCE="false" TYPE="char" NOTNULL="true" LENGTH="10" PREVIOUS="semester" NEXT="is_optional"/>
            <FIELD NAME="is_compulsory" SEQUENCE="false" TYPE="int" NOTNULL="true" LENGTH="10" PREVIOUS="course_id" NEXT="timecreated"/>
            <FIELD NAME="timecreated" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="true" SEQUENCE="false" PREVIOUS="course_id" NEXT="timemodified"/>
        	<FIELD NAME="timemodified" TYPE="int" LENGTH="10" NOTNULL="true" UNSIGNED="t
	 */
	$count = 0;
	foreach ($selectedCourses as $courseId=>$course) {
		$date = new DateTime();
		echo $date->getTimestamp();
		
		$rec = new stdClass();
		$rec->group_id = $group;
		$rec->year = '2014';
		$rec->semester = $semester;
		$rec->course_id = $courseId;
		$key = 'course_' . $courseId;
		$rec->is_compulsory = (!empty($data->$key))?$data->$key:0;
		$rec->timecreated = $date->getTimestamp();
		$rec->timemodified = $date->getTimestamp();
		$records[$count++] = $rec;
	print_r($rec);
	$lastinsertid = $DB->insert_record('pasanlive_course_allocation', $rec);
	echo $lastinsertid;
	}
	
	
}

