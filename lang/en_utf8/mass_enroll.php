<?php
$string['mass_enroll'] = 'Bulk enrolments';
$string['mass_enroll_info'] = <<<EOS
With this option you are going to enrol a list of known users
from a file with one account per line (empty lines or unknown accounts will be skipped). <br/>
The file may contains one or two columns, separated by a comma, a semi-column or a tabulation.
<br/>
<b>The first one must contains a unique account identifier : idnumber (by default) login or email  </b> of the target user. <br/>
The second <b>if present,</b> contains the group's name in wich you want that user be be added. <br/>
You may repeat this operation at will without dammages, for example if you forgot or mispelled the target group.
EOS;
$string['enroll'] = 'Enrol them to my course';
$string['mailreport'] = 'Send me a mail report';
$string['creategroups'] = 'Create group(s) if needed';
$string['creategroupings'] = 'Create  grouping(s) if needed';
$string['firstcolumn'] = 'First column contains';
$string['roleassign'] = 'Role to assign';
$string['idnumber'] = 'Id number';
$string['username'] = 'Login';
$string['mail_enrolment_subject'] = 'Bulk enrolments on {$a}';
$string['mail_enrolment']='
Hello,
You just enroled the following list of users to your course \'$a->course\'.
Here is a report of operations :
$a->report
Sincerly.
';
$string['email_sent'] = 'email sent to {$a}';
$string['im:opening_file'] = 'Opening file : {$a} ';
$string['im:user_unknown'] = '{$a} unknown - skipping line';
$string['im:already_in'] = '{$a} already enroled ';
$string['im:enrolled_ok'] = '{$a} enroled ';
$string['im:error_in'] = 'error enroling {$a}';
$string['im:error_addg'] = 'error adding group {$a->groupe}  to course {$a->courseid} ';
$string['im:error_g_unknown'] = 'error unkown group {$a} ';
$string['im:error_add_grp'] = 'error adding grouping {$a->groupe} to course {$a->courseid}';
$string['im:error_add_g_grp'] = 'error adding group {$a->groupe} to grouping {$a->groupe}';
$string['im:and_added_g'] = ' and added to Moodle\'s  group  {$a}';
$string['im:error_adding_u_g'] = 'error adding to group  {$a}';
$string['im:already_in_g'] = ' already in group {$a}';
$string['im:stats_i'] = '{$a} enroled';
$string['im:stats_g'] = '{$a->nb} group(s) created : {$a->what}';
$string['im:stats_grp'] = '{$a->nb} grouping(s) created : {$a->what}';
$string['im:err_opening_file'] = 'error opening file {$a}';
?>