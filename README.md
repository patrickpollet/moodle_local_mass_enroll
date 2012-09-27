moodle_local_mass_enroll
========================

A tool to allow teachers to enrol existing users to their courses using CSV files (without bothering the admins)


One awkward thing about Moodle 1.x and the recent Moodle 2.x  is that teachers are allowed to manually enroll known Moodle accounts to a course as teachers, non editing teachers, students... , but not using a 'flat file' produced by some CSV export. 

They have to send a file to an admin user who will inject it into Moodle using the standard 'flatfile enrollment' method with the following limitations : 

Teachers must provide account's and course idnumbers and admin user must reformat the file by adding the required extra columns 'add' and 'target role', before copying the file to the appropriate location in moodledata directory.
the report information is sent to admin, not to the course's teacher; so admin has to forward it to the teacher after the cron job has completed.
there is no way in flatfile enrollment method to specify in which groups these accounts should be added, so teacher has to go manually again across the list to assign accounts to groups.

This little extension, that adds itself to course administration menu will hopefully help teachers to do it themselves. 

After installing it (see below) a new item 'Bulk enrolments' will appear in the course administration menu for teachers (see screenshot). 

It requires a CSV file, prepared by the teacher, with an unique Moodle id in the first column (idnumber, username or email) and optionally a group name in the second column.
The first line of the CSV file must be present but its content is currently ignored.

Any standard separator (comma, semi-column or tab will do) and if your spreadsheet program added some quotes around strings, they will be removed..

Options are provided to force group (and grouping) autocreation if they do not yet exist in the target course. 

Every line of the file may contain a different group name or even no group for some accounts

Enjoy.


INSTALLATION MOODLE 2.x
-------------------
1) Using git (recommanded) 

go to your moodle installation directory and clone the github repo by

cd /var/www/moodle

git clone  https://github.com/patrickpollet/moodle_local_mass_enroll.git  local/mass_enroll

echo 'local/mass_enroll' >> .git/info/exclude

eventually select the branch (moodle_22, moodle_23)

cd local/mass_enroll

git checkout moodle_2x  

2) using zip 

collect the zip file of the appropriate moodle_2x branch on github

unzip it into the local directory of your moodle installation  


3) in any case enable the extra item mass_enroll by adding the line

$CFG->allow_mass_enroll_feature=1;

at the end of section 6 of your config.php file.

To turn it off, juste remove that line or change it to 

$CFG->allow_mass_enroll_feature=0;


TESTING 
---------
Being logged in to Moodle as a teacher of some course, make a note of the course id (xxx) 
which is the last part on the URL when viewing the course 

http://youmoodle/course/view.php?id=xxx 

In another navigator window or tab call 

http://yourmoodle/local/mass_enroll/mass_enroll.php?id=xxx

You should see the bulk enrolments page and be able to experiment with it.

When (if) pleased, consider adding it to Moodle course admin menu by reading the following section. 

PATCH TO MOODLE 2.X CODE
------------------------

Unfortunately Moodle 2.x still does not allow local plugin to 'automagically' insert items in course's administration menu.
Thus you will have to patch the function enrol_add_course_navigation in file lib/enrollib.php

add the lines marqued with a '+' sign (without the '+' sign) just before the comment 
     // just in case nothing was actually added


diff --git a/lib/enrollib.php b/lib/enrollib.php

index b6736c4..fcd34ff 100644

--- a/lib/enrollib.php

+++ b/lib/enrollib.php

@@ -468,6 +468,16 @@ function enrol_add_course_navigation(navigation_node $coursenode, $course) {

               $usersnode->add(get_string('notenrolledusers', 'enrol'), $url, navigation_node::TYPE_SETTING, null, 'otherusers', new pix_icon('i/roles'

           }

      }
+    
+    
+    //PATCH PP mass enrol
+     if ($course->id != SITEID && !empty($CFG->allow_mass_enroll_feature)) {
+           if (has_capability('moodle/role:assign', $coursecontext)) {
+               $url = new moodle_url('/local/mass_enroll/mass_enroll.php', array('id'=>$course->id));
+               $usersnode->add(get_string('mass_enroll', 'local_mass_enroll'), $url, navigation_node::TYPE_SETTING, null, 'massenrols', new pix_icon
+           } 
+     }
+    // END patch PP
 
     // just in case nothing was actually added

     $usersnode->trim_if_empty();

 Thus a new menu item named 'Bulk enrolments' should appear under the item 'Users' in course admin menu 
 
     