<?php
// $Id: inscriptions_massives.php 356 2010-02-27 13:15:34Z ppollet $
/**
 * A bulk enrolment plugin that allow teachers to massively enrol existing accounts to their courses,
 * with an option of adding every user to a group.
 * courtesy of Patrick POLLET & Valery FREMAUX  France, February 2010
*/

require ('../../../../config.php');
require_once ($CFG->dirroot . '/local/course/admin/mass_enroll/mass_enroll_form.php');

// $locallangroot = $CFG->dirroot.'/local/course/admin/mass_enroll/lang/';
// since the help file MUST be copied to moodledata/lang/xx_utf8, let's also require
// that the translation file be there

$locallangroot = '';

/// Get params

$id = required_param('id', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $id))) {
	error("Course is misconfigured");
}

/// Security and access check

require_login($course);

$context = context_course::instance($course->id);
require_capability('moodle/course:update', $context);

/// Start making page

$strinscriptions = get_string('mass_enroll', 'mass_enroll', '', $locallangroot);

$navlinks = array (
	array (
		'name' => $strinscriptions,
		'link' => null,
		'type' => 'misc'
	)
);
$navigation = build_navigation($navlinks);

// a directory within moodleldata (required to be there !)
$destination_directory = 'tmp';

$mform = new mass_enroll_form($CFG->wwwroot . '/local/course/admin/mass_enroll/mass_enroll.php', array (
	'course' => $course,
	'context' => $context
));

if ($mform->is_cancelled()) {
	redirect($CFG->wwwroot . '/course/view.php?id=' . $id);
} else
	if ($data = $mform->get_data(false)) { // no magic quotes

		require_once ($CFG->dirroot . '/group/lib.php');
		$PAGE->set_title($course->fullname . ': ' . $strinscriptions);
		$PAGE->set_heading($course->fullname . ': ' . $strinscriptions);
		/* SCANMSG: may be additional work required for $navigation variable */
		echo $OUTPUT->header();
		echo $OUTPUT->heading($strinscriptions);

		$mform->save_files($destination_directory);
		$newfilename = $mform->get_new_filename();

		$result = mass_enroll($CFG->dataroot . '/' . $destination_directory . '/' . $newfilename, $course, $context, $data);

		if ($data->mailreport) {
			$a = new StdClass();
			$a->course = $course->fullname;
			$a->report = $result;
			email_to_user($USER, $USER, get_string('mail_enrolment_subject', 'mass_enroll', $CFG->wwwroot, $locallangroot), get_string('mail_enrolment', 'mass_enroll', $a, $locallangroot));
			$result .= "\n" . get_string('email_sent', 'mass_enroll', $USER->email, $locallangroot);
		}

		print_simple_box(nl2br($result), 'center', '90%');
		echo $OUTPUT->continue_button($CFG->wwwroot . '/course/view.php?id=' . $course->id); // Back to course page
		echo $OUTPUT->footer($course);
		exit;
	}

$PAGE->set_title($course->fullname . ': ' . $strinscriptions);
$PAGE->set_heading($course->fullname . ': ' . $strinscriptions);
/* SCANMSG: may be additional work required for $navigation variable */
echo $OUTPUT->header();

$icon = '<img class="icon" src="' . $OUTPUT->pix_url('/i/admin') . '" alt="' . get_string('mass_enroll', 'mass_enroll', '', $locallangroot) . '"/>';
print_heading_with_help($strinscriptions, 'mass_enroll', 'mass_enroll', $icon);
print_simple_box(get_string('mass_enroll_info', 'mass_enroll', '', $locallangroot), 'center', '90%');

$mform->display();
echo $OUTPUT->footer($course);
exit;

/**
* realizes the mass enrolment
*/
function mass_enroll($file, $course, $context, $data) {
    global $CFG, $locallangroot;

    $result = get_string('im:opening_file', 'mass_enroll', $file, $locallangroot) . "\n";

    $courseid=$course->id;
    $roleid = $data->roleassign;
    $useridfield = $data->firstcolumn;

    $enrollablecount = 0;
    $createdgroupscount = 0;
    $createdgroupingscount = 0;
    $createdgroups = '';
    $createdgroupings = '';

    $handle = fopen($file, 'rb');
    if ($handle) {
        $contents = fread($handle, filesize($file));
        // caution with files produced on Macs (only \r)
        $contents = preg_replace('/\r\n|\r/', "\n", $contents);
        $lines = explode("\n", $contents);

        foreach ($lines as $line) {

            $a = new StdClass();

            // convert separators ' or \t to ;
            $line = preg_replace('/\t|,/', ';', $line);
            // get rid on eventual double quotes
            $line = str_replace('"', '', $line);
            //and leading, ending spaces
            $line = trim($line);
            //
            if (empty ($line))
                continue;
            $fields = explode(';', $line);
            // 1rst column = id Moodle (idnumber,username or email)
            if (!$user = $DB->get_record('user', array($useridfield => trim($fields[0])))) {
                $result .= get_string('im:user_unknown', 'mass_enroll', $fields[0], $locallangroot) . "\n";
                continue;
            }
            //already enroled ?
            if (user_has_role_assignment($user->id, $roleid, $context->id)) {
                $result .= get_string('im:already_in', 'mass_enroll', fullname($user), $locallangroot);

            } else {
                //TODO take care of timestart/timeend in course settings
                // done in rev 1.1
                $timestart = time();
                // remove time part from the timestamp and keep only the date part
                $timestart = make_timestamp(date('Y', $timestart), date('m', $timestart), date('d', $timestart), 0, 0, 0);
                if ($course->enrolperiod) {
                    $timeend = $timestart + $course->enrolperiod;
                } else {
                    $timeend = 0;
                }

                if (!role_assign($roleid, $user->id, null, $context->id, $timestart, $timeend, 0, 'flatfile')) {
                    $result .= get_string('im:error_in', 'mass_enroll', fullname($user), $locallangroot) . "\n";
                    continue;
                }
                $result .= get_string('im:enrolled_ok', 'mass_enroll', fullname($user), $locallangroot);
                $enrollablecount++;
            }

            $group = trim($fields[1]);
            // 2nd column ?
            if (empty ($group)) {
                $result .= "\n";
                continue; // no group for this one
            }

            // create group if needed
            if (!($gid = mass_enroll_group_exists($group, $courseid))) {
                if ($data->creategroups) {
                    if (!($gid = mass_enroll_add_group($group, $courseid))) {
                        $a->group = $group;
                        $a->courseid = $courseid;
                        $result .= get_string('im:error_addg', 'mass_enroll', $a, $locallangroot) . "\n";
                        continue;
                    }
                    $createdgroupscount++;
                    $createdgroups .= " $group";
                } else {
                    $result .= get_string('im:error_g_unknown', 'mass_enroll', $group, $locallangroot) . "\n";
                    continue;
                }
            }

            // if groupings are enabled on the site (should be ?)
            if ($CFG->enablegroupings) {
                if (!($gpid = mass_enroll_grouping_exists($group, $courseid))) {
                    if ($data->creategroupings) {
                        if (!($gpid = mass_enroll_add_grouping($group, $courseid))) {
                            $a->group = $group;
                            $a->courseid = $courseid;
                            $result .= get_string('im:error_add_grp', 'mass_enroll', $a, $locallangroot) . "\n";
                            continue;
                        }
                        $createdgroupingscount++;
                        $createdgroupings .= " $group";
                    } else {
                        // don't complains,
                        // just do the enrolment to group
                    }
                }
                // if grouping existed or has just been created
                if ($gpid && !(mass_enroll_group_in_grouping($gid, $gpid))) {
                    if (!(mass_enroll_add_group_grouping($gid, $gpid))) {
                        $a->group = $group;
                        $result .= get_string('im:error_add_g_grp', 'mass_enroll', $a, $locallangroot) . "\n";
                        continue;
                    }
                }
            }

            // finally add to group if needed
            if (!groups_is_member($gid, $user->id)) {
                $ok = groups_add_member($gid, $user->id);
                if ($ok) {
                    $result .= get_string('im:and_added_g', 'mass_enroll', $group, $locallangroot) . "\n";
                } else {
                    $result .= get_string('im:error_adding_u_g', 'mass_enroll', $group, $locallangroot) . "\n";
                }
            } else {
                $result .= get_string('im:already_in_g', 'mass_enroll', $group, $locallangroot) . "\n";
            }

        }
        fclose($handle);
        unlink($file); //  clean up moodledata tmp area
        //recap final
        $result .= get_string('im:stats_i', 'mass_enroll', $enrollablecount, $locallangroot) . "\n";
        $a->nb = $createdgroupscount;
        $a->what = $createdgroups;
        $result .= get_string('im:stats_g', 'mass_enroll', $a, $locallangroot) . "\n";
        $a->nb = $createdgroupingscount;
        $a->what = $createdgroupings;
        $result .= get_string('im:stats_grp', 'mass_enroll', $a, $locallangroot) . "\n";
    } else {
        $result .= get_string('im:err_opening_file', 'mass_enroll', $file, $locallangroot) . "\n";
    }
    return $result;
}

/**
*
*
*/
function mass_enroll_add_group($newgroupname, $courseid) {
	$newgroup->name = $newgroupname;
	$newgroup->courseid = $courseid;
	$newgroup->lang = current_language();
	return groups_create_group($newgroup);
}

/**
*
*
*/
function mass_enroll_add_grouping($newgroupingname, $courseid) {
	$newgrouping->name = $newgroupingname;
	$newgrouping->courseid = $courseid;
	return groups_create_grouping($newgrouping);
}

/**
* @param string $name group name
* @param int $courseid course
*/
function mass_enroll_group_exists($name, $courseid) {
	return groups_get_group_by_name($courseid, $name);
}

/**
* @param string $name group name
* @param int $courseid course
*/
function mass_enroll_grouping_exists($name, $courseid) {
	return groups_get_grouping_by_name($courseid, $name);

}

/**
* @param int $gid group ID
* @param int $gpid grouping ID
*/
function mass_enroll_group_in_grouping($gid, $gpid) {
	global $CFG;
	$sql =<<<EOF
   select * from {$CFG->prefix}groupings_groups
   where groupingid = $gpid
   and groupid = $gid
EOF;
	return $DB->get_record_sql($sql);
}

/**
* @param int $gid group ID
* @param int $gpid grouping ID
*/
function mass_enroll_add_group_grouping($gid, $gpid) {
	$new->groupid = $gid;
	$new->groupingid = $gpid;
	$new->timeadded = time();
	return $DB->insert_record('groupings_groups', $new);
}
?>
