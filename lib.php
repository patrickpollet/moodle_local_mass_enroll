<?php


// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Code for handling mass enrolment from a cvs file
 *

 *
 * @package local
 * @subpackage mass_enroll
 * @copyright 1999 onwards Martin Dougiamas and others {@link http://moodle.com}
 * @copyright 2012 onwards Patrick Pollet {@link mailto:pp@patrickpollet.net
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();



/**
 * process the mass enrolment
 * @param csv_import_reader $cir  an import reader created by caller
 * @param Object $course  a course record from table mdl_course
 * @param Object $context  course context instance
 * @param Object $data    data from a moodleform 
 * @return string  log of operations 
 */
function mass_enroll($cir, $course, $context, $data) {
    global $CFG,$DB;
    require_once ($CFG->dirroot . '/group/lib.php');

    $result = '';
    
    $courseid=$course->id;
    $roleid = $data->roleassign;
    $useridfield = $data->firstcolumn;

    $enrollablecount = 0;
    $createdgroupscount = 0;
    $createdgroupingscount = 0;
    $createdgroups = '';
    $createdgroupings = '';

    $role =$DB->get_record('role', array('id'=>$roleid));

    $result .= get_string('im:using_role', 'local_mass_enroll', $role->name)."\n"; 

    $plugin = enrol_get_plugin('manual');
    //Moodle 2.x enrolment and role assignment are different
    // make sure couse DO have a manual enrolment plugin instance in that course
    //that we are going to use (only one instance is allowed @see enrol/manual/lib.php get_new_instance)
    // thus call to get_record is safe 
    $instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'manual'));
    if (empty($instance)) {
        // Only add an enrol instance to the course if non-existent
        $enrolid = $plugin->add_instance($course);
        $instance = $DB->get_record('enrol', array('id' => $enrolid));
    }
    
   
    // init csv import helper
    $cir->init();
    while ($fields = $cir->next()) {
        $a = new StdClass();

        if (empty ($fields))
            continue;

        // print_r($fields);
        // $enrollablecount++;
        // continue;
        
        // 1rst column = id Moodle (idnumber,username or email)    
        // get rid on eventual double quotes unfortunately not done by Moodle CSV importer 
            $fields[0]= str_replace('"', '', trim($fields[0]));
        
        if (!$user = $DB->get_record('user', array($useridfield => $fields[0]))) {
            $result .= get_string('im:user_unknown', 'local_mass_enroll', $fields[0] ). "\n";
            continue;
        }
        //already enroled ?
       // if (user_has_role_assignment($user->id, $roleid, $context->id)) {
       // we DO NOT support multiple roles in a course 
        if ($ue = $DB->get_record('user_enrolments', array('enrolid'=>$instance->id, 'userid'=>$user->id))) {
            $result .= get_string('im:already_in', 'local_mass_enroll', fullname($user));

        } else {
            //TODO take care of timestart/timeend in course settings
            // done in rev 1.1
            $timestart = time();
            // remove time part from the timestamp and keep only the date part
            $timestart = make_timestamp(date('Y', $timestart), date('m', $timestart), date('d', $timestart), 0, 0, 0);
            if ($instance->enrolperiod) {
                $timeend = $timestart + $instance->enrolperiod;
            } else {
                $timeend = 0;
            }
            // not anymore so easy in Moodle 2.x
            // if (!role_assign($roleid, $user->id, null, $context->id, $timestart, $timeend, 0, 'flatfile')) {
            //    $result .= get_string('im:error_in', 'local_mass_enroll', fullname($user)) . "\n";
            //    continue;
            //}
            //
            // Enrol the user with this plugin instance (unfortunately return void, no more status )
            $plugin->enrol_user($instance, $user->id,$roleid,$timestart,$timeend);
            $result .= get_string('im:enrolled_ok', 'local_mass_enroll', fullname($user));
            $enrollablecount++;
        }

        $group = str_replace('"','',trim($fields[1]));
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
                    $result .= get_string('im:error_addg', 'local_mass_enroll', $a) . "\n";
                    continue;
                }
                $createdgroupscount++;
                $createdgroups .= " $group";
            } else {
                $result .= get_string('im:error_g_unknown', 'local_mass_enroll', $group) . "\n";
                continue;
            }
        }

        // if groupings are enabled on the site (should be ?)
        // if ($CFG->enablegroupings) { // not anymore in Moodle 2.x
        if (!($gpid = mass_enroll_grouping_exists($group, $courseid))) {
            if ($data->creategroupings) {
                if (!($gpid = mass_enroll_add_grouping($group, $courseid))) {
                    $a->group = $group;
                    $a->courseid = $courseid;
                    $result .= get_string('im:error_add_grp', 'local_mass_enroll', $a) . "\n";
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
                $result .= get_string('im:error_add_g_grp', 'local_mass_enroll', $a) . "\n";
                continue;
            }
        }
        //}

        // finally add to group if needed
        if (!groups_is_member($gid, $user->id)) {
            $ok = groups_add_member($gid, $user->id);
            if ($ok) {
                $result .= get_string('im:and_added_g', 'local_mass_enroll', $group) . "\n";
            } else {
                $result .= get_string('im:error_adding_u_g', 'local_mass_enroll', $group) . "\n";
            }
        } else {
            $result .= get_string('im:already_in_g', 'local_mass_enroll', $group) . "\n";
        }

    }

    //recap final
    $result .= get_string('im:stats_i', 'local_mass_enroll', $enrollablecount) . "\n";
    $a->nb = $createdgroupscount;
    $a->what = $createdgroups;
    $result .= get_string('im:stats_g', 'local_mass_enroll', $a) . "\n";
    $a->nb = $createdgroupingscount;
    $a->what = $createdgroupings;
    $result .= get_string('im:stats_grp', 'local_mass_enroll', $a) . "\n";

    return $result;
}


/**
 * process the mass enrolment
 * @param csv_import_reader $cir  an import reader created by caller
 * @param Object $course  a course record from table mdl_course
 * @param Object $context  course context instance
 * @param Object $data    data from a moodleform 
 * @return string  log of operations 
 */
function mass_unenroll($cir, $course, $context, $data) {
    global $CFG,$DB;
     $result = '';
    
    $courseid=$course->id;
    $useridfield = $data->firstcolumn;

    $unenrollablecount = 0;
 

    $plugin = enrol_get_plugin('manual');
    //Moodle 2.x enrolment and role assignment are different
    // make sure couse DO have a manual enrolment plugin instance in that course
    //that we are going to use (only one instance is allowed @see enrol/manual/lib.php get_new_instance)
    // thus call to get_record is safe 
    $instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'manual'));
    if (empty($instance)) {
        // Only add an enrol instance to the course if non-existent
        $enrolid = $plugin->add_instance($course);
        $instance = $DB->get_record('enrol', array('id' => $enrolid));
    }
    
   
    // init csv import helper
    $cir->init();
    while ($fields = $cir->next()) {
        $a = new StdClass();

        if (empty ($fields))
            continue;
    
        // 1rst column = id Moodle (idnumber,username or email)    
        // get rid on eventual double quotes unfortunately not done by Moodle CSV importer 
            $fields[0]= str_replace('"', '', trim($fields[0]));
        
        if (!$user = $DB->get_record('user', array($useridfield => $fields[0]))) {
            $result .= get_string('im:user_unknown', 'local_mass_enroll', $fields[0] ). "\n";
            continue;
        }
        //already enroled ?
        if (!$ue = $DB->get_record('user_enrolments', array('enrolid'=>$instance->id, 'userid'=>$user->id))) {
            // weird, user not enrolled      
            $result .= get_string('im:not_in', 'local_mass_enroll', fullname($user)). "\n";

        } else {
            // Enrol the user with this plugin instance (unfortunately return void, no more status )
            $plugin->unenrol_user($instance, $user->id);
            $result .= get_string('im:unenrolled_ok', 'local_mass_enroll', fullname($user)). "\n";
            $unenrollablecount++;
        }
    }

    //recap final
    $result .= get_string('im:stats_ui', 'local_mass_enroll', $unenrollablecount) . "\n";

    return $result;
}



/**
 * Enter description here ...
 * @param string $newgroupname
 * @param int $courseid
 * @return int id   Moodle id of inserted record 
 */
function mass_enroll_add_group($newgroupname, $courseid) {
    $newgroup = new stdClass();
    $newgroup->name = $newgroupname;
    $newgroup->courseid = $courseid;
    $newgroup->lang = current_language();
    return groups_create_group($newgroup);
}


/**
 * Enter description here ...
 * @param string $newgroupingname
 * @param int $courseid
 * @return int id Moodle id of inserted record
 */
function mass_enroll_add_grouping($newgroupingname, $courseid) {
    $newgrouping = new StdClass();
    $newgrouping->name = $newgroupingname;
    $newgrouping->courseid = $courseid;
    return groups_create_grouping($newgrouping);
}

/**
 * @param string $name group name
 * @param int $courseid course
 * @return string or false 
 */
function mass_enroll_group_exists($name, $courseid) {
    return groups_get_group_by_name($courseid, $name);
}

/**
 * @param string $name group name
 * @param int $courseid course
 * @return string or false
 */
function mass_enroll_grouping_exists($name, $courseid) {
    return groups_get_grouping_by_name($courseid, $name);

}

/**
 * @param int $gid group ID
 * @param int $gpid grouping ID
 * @return mixed a fieldset object containing the first matching record or false
 */
function mass_enroll_group_in_grouping($gid, $gpid) {
     global $DB;
    $sql =<<<EOF
   select * from {groupings_groups}
   where groupingid = ?
   and groupid = ?
EOF;
    $params = array($gpid, $gid);
    return $DB->get_record_sql($sql,$params,IGNORE_MISSING);
}

/**
 * @param int $gid group ID
 * @param int $gpid grouping ID
 * @return bool|int true or new id
 * @throws dml_exception A DML specific exception is thrown for any errors.
 */
function mass_enroll_add_group_grouping($gid, $gpid) {
     global $DB;
    $new = new stdClass();
    $new->groupid = $gid;
    $new->groupingid = $gpid;
    $new->timeadded = time();
    return $DB->insert_record('groupings_groups', $new);
}
