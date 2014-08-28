<?php

/**
 * This is a one-line short description of the file
 *
 *
 * @package    mod_pasanlive_enrolment
 */

/// Replace pasantest with the name of your module and remove this line

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course);

add_to_log($course->id, 'pasanlive_enrolment', 'view all', 'index.php?id='.$course->id, '');

$coursecontext = context_course::instance($course->id);

$PAGE->set_url('/mod/pasanlive_enrolment/index.php', array('id' => $id));
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($coursecontext);

echo $OUTPUT->header();

if (! $pasantests = get_all_instances_in_course('pasanlive_enrolment', $course)) {
    notice(get_string('nopasantests', 'pasanlive_enrolment'), new moodle_url('/course/view.php', array('id' => $course->id)));
}

$table = new html_table();
if ($course->format == 'weeks') {
    $table->head  = array(get_string('week'), get_string('name'));
    $table->align = array('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array(get_string('topic'), get_string('name'));
    $table->align = array('center', 'left', 'left', 'left');
} else {
    $table->head  = array(get_string('name'));
    $table->align = array('left', 'left', 'left');
}

foreach ($pasantests as $pasantest) {
    if (!$pasantest->visible) {
        $link = html_writer::link(
            new moodle_url('/mod/pasanlive_enrolment.php', array('id' => $pasantest->coursemodule)),
            format_string($pasantest->name, true),
            array('class' => 'dimmed'));
    } else {
        $link = html_writer::link(
            new moodle_url('/mod/pasanlive_enrolment.php', array('id' => $pasantest->coursemodule)),
            format_string($pasantest->name, true));
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array($pasantest->section, $link);
    } else {
        $table->data[] = array($link);
    }
}

echo $OUTPUT->heading(get_string('modulenameplural', 'pasanlive_enrolment'), 2);
echo html_writer::table($table);
echo $OUTPUT->footer();
