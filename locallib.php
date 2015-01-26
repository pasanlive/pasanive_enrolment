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
 * @return array
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
	
	$count = 0;
	foreach ($selectedCourses as $courseId=>$course) {
		$date = new DateTime();
//		echo $date->getTimestamp();
		
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
//	print_r($rec);
	$lastinsertid = $DB->insert_record('pasanlive_course_allocation', $rec);
//	echo $lastinsertid;
	}	
}

/**
 * Load allocated courses by year, semester and student group
 * 
 * @param $year
 * @param $semester
 * @param group
 * 
 * @return array
 * 
 */
function load_allocated_courses($year, $semester, $group) {
	global $DB;
	
	return $DB->get_records('pasanlive_course_allocation', array('year'=>$year, 'semester'=>$semester, 'group_id'=>$group));
}

/**
 * Save selected courses by the student
 * 
 * @param $studentId
 * @param $semester
 * @param $year
 * @param $courses
 * 
 */
function save_course_selection($studentId, $semester, $year, $courses) {
	global $DB;
	
	$enrolmentInfoRecord = new stdClass();
	
	$enrolmentInfoRecord->student_id = $studentId;
	$enrolmentInfoRecord->semester = $semester;
	$enrolmentInfoRecord->year = $year;
	$enrolmentInfoRecord->enrolment_status = "pending";
	$date = new DateTime();
	$enrolmentInfoRecord->timecreated = $date->getTimestamp();
	$enrolmentInfoRecord->timemodified = $date->getTimestamp();
	
//	print("\nEnrolment Info record :\n");
//	print_r($enrolmentInfoRecord);

    $id = $DB->insert_record('pasanlive_enrolment_info', $enrolmentInfoRecord);

    $count = 0;
    foreach ($courses as $c) {
        $date = new DateTime();
//        echo $date->getTimestamp();

        $rec = new stdClass();
        $rec->student_id = $studentId;
        $rec->course_id = $c;
        $rec->enrolment_id = $id;
        $rec->timecreated = $date->getTimestamp();
        $rec->timemodified = $date->getTimestamp();
//        print("\nEnrolment record " . $count . ": \n");
//        print_r($rec);
        $lastinsertid = $DB->insert_record('pasanlive_enrolment_course_s', $rec);
//        echo $lastinsertid;
    }

}



function update_course_selection($studentId, $semester, $year, $courses) {
    global $DB;

    if (!is_enrolment_info_exist($studentId, $semester, $year)) {
        save_course_allocations($studentId, $semester, $year);
    } else {
        $params = array('student_id' => $studentId, 'semester' => $semester, 'year' => $year);
        $record = $DB->get_record('pasanlive_enrolment_info', $params);

//        print_r($record);

        if (is_enrolment_course_selection_exist($studentId, $record->id)) {
            $DB->delete_records('pasanlive_enrolment_course_s', array('enrolment_id' => $record->id));
//            echo("<br><br>record deleted<br><br >");
        }
        $count = 0;
        foreach ($courses as $c) {
//            echo "<br><br> creating enrolment entry for course : " . $c . "<br><br>";
            $date = new DateTime();
//            echo $date->getTimestamp();

            $rec = new stdClass();
            $rec->student_id = $studentId;
            $rec->course_id = $c;
            $rec->enrolment_id = $record->id;
            $rec->timecreated = $date->getTimestamp();
            $rec->timemodified = $date->getTimestamp();
//            print("<br>Enrolment record  : " . $count . "<br>");
//            print_r($rec);
            $lastinsertid = $DB->insert_record('pasanlive_enrolment_course_s', $rec);
//            echo "<br><br> last insert id" . $lastinsertid;
        }
    }
}


/**
 * check enrolment info availability
 *
 * @param $studentId
 * @param $semester
 * @param $year
 * @return bool
 */
function is_enrolment_info_exist($studentId, $semester, $year) {
	global $DB;

    $params = array('student_id' => $studentId, 'semester' => $semester, 'year' => $year);
	return ($DB->count_records('pasanlive_enrolment_info', $params) > 0);
}

function is_enrolment_course_selection_exist($studentId, $enrolmentId) {
    global $DB;

    $params = array('student_id' => $studentId, 'enrolment_id' => $enrolmentId);
    return ($DB->count_records('pasanlive_enrolment_course_s', $params) > 0);
}

function get_enrolment_id($studentId, $semester, $year) {
    global $DB;

    if (is_enrolment_info_exist($studentId, $semester, $year)) {
        $params = array('student_id' => $studentId, 'semester' => $semester, 'year' => $year);
        return $DB->get_record('pasanlive_enrolment_info', $params)->id;
    }
    return null;
}

function get_enrolled_courses($studentId, $enrollmentId) {
    global $DB;

    return $DB->get_records('pasanlive_enrolment_course_s', array('student_id'=>$studentId, 'enrolment_id'=>$enrollmentId));
}


function filter_selected_courses($data, $courses) {
    $selectedCourses = array();

    foreach ($data as $key => $value) {
        if (strpos($key, 'course_') === 0 && $value == 1) {
            $splited = explode("_", $key);
            foreach ($courses as $c) {
                if ($c->id == $splited[1]) {
                    array_push($selectedCourses, $c->id);
                    continue;
                }
            }
        }
    }
    return $selectedCourses;
}
