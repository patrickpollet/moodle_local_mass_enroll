<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group
 * Version for Moodle 1.9.x courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
 * Version for Moodle 2.x by pp@patrickpollet.net March 2012
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once ('lib.php');
require_once ('mass_unenroll_form.php');


/// Get params

$id = required_param('id', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $id))) {
    error("Course is misconfigured");
}


/// Security and access check

require_course_login($course);
$context = context_course::instance($course->id);
require_capability('local/mass_enroll:unenrol', $context);

/// Start making page
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/local/mass_enroll/mass_unenroll.php', array('id'=>$id));

$strinscriptions = get_string('mass_unenroll', 'local_mass_enroll');

$PAGE->set_title($course->fullname . ': ' . $strinscriptions);
$PAGE->set_heading($course->fullname . ': ' . $strinscriptions);

echo $OUTPUT->header();

// Add tabs
$currenttab = 'mass_unenroll';
require('tabs.php');



$mform = new mass_unenroll_form($CFG->wwwroot . '/local/mass_enroll/mass_unenroll.php', array (
	'course' => $course,
	'context' => $context
));

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', array('id'=>$id)));
} else
if ($data = $mform->get_data(false)) { // no magic quotes
    echo $OUTPUT->heading($strinscriptions);

    $iid = csv_import_reader::get_new_iid('uploaduser');
    $cir = new csv_import_reader($iid, 'uploaduser');

    $content = $mform->get_file_content('attachment');

    $readcount = $cir->load_csv_content($content, $data->encoding, $data->delimiter_name);
    unset($content);

    if ($readcount === false) {
        print_error('csvloaderror', '', $returnurl);
    } else if ($readcount == 0) {
        print_error('csvemptyfile', 'error', $returnurl);
    }

    $result = mass_unenroll($cir, $course, $context, $data);
    
    $cir->close();
    $cir->cleanup(false); // only currently uploaded CSV file 

    if ($data->mailreport) {
        $a = new StdClass();
        $a->course = $course->fullname;
        $a->report = $result;
        email_to_user($USER, $USER, get_string('mail_unenrolment_subject', 'local_mass_enroll', $CFG->wwwroot),
        get_string('mail_unenrolment', 'local_mass_enroll', $a));
        $result .= "\n" . get_string('email_sent', 'local_mass_enroll', $USER->email);
    }

    echo $OUTPUT->box(nl2br($result), 'center');

    echo $OUTPUT->continue_button($PAGE->url); // Back to this page
    echo $OUTPUT->footer($course);
    //path must be relative to 'module name', here 'course'
    add_to_log($course->id, 'course', 'unenrol', '../local/mass_enroll/mass_unenroll.php?id='.$course->id,$strinscriptions);
    
    die();
}


echo $OUTPUT->heading_with_help($strinscriptions, 'mass_unenroll', 'local_mass_enroll','icon',get_string('mass_enroll', 'local_mass_enroll'));
echo $OUTPUT->box (get_string('mass_unenroll_info', 'local_mass_enroll'), 'center');
$mform->display();
echo $OUTPUT->footer($course);



?>
