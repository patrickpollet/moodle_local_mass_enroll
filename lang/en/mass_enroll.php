<?php
$string['pluginname'] = 'Mass enrolments';

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


$string['mass_enroll_help'] = <<<EOS
<h1>Bulk enrolments</h1>


<p>With this option you are going to enrol a list of known users
from a file with one account per line (empty lines or unknown accounts will be skipped).</p>

<p>
The file may contains one or two columns, separated by a comma, a semi-column or a tabulation.

You should prepare it from your usual spreadsheet program from official lists of students, for example,
and add if needed a column with groups to which you want these users to be added. Finally export it as CSV. (*)</p>

<p>
<b> The first one must contains a unique account identifier </b>: idnumber (by default) login or email  of the target user. (**). </p>

<p>
The second <b>if present,</b> contains the group's name in wich you want that user to be added. </p>

<p>
If the group name does not exist, it will be created in your course, together with a grouping of the same name to which the group will be added.
.<br/>
This is due to the fact that in Moodle, activities can be restricted to groupings (group of groups), not groups,
 so it will make your life easier. (this requires that groupings are enabled by your site administrator).

<p>
You may have in the same file different target groups or no groups for some accounts
</p>

<p>
You may unselect options to create groups and groupings if you are sure that they already exist in the course.
</p>

<p>
By default the users will be enroled as students but you may select other roles that you are allowed to manage (teacher, non editing teacher
or any custom roles)
</p>

<p>
You may repeat this operation at will without dammages, for example if you forgot or mispelled the target group.
</p>


<h2> Sample files </h2>

Id numbers and a group name to be created in needed in the course (*)
<pre>
" 2513110";" 4GEN"
" 2512334";" 4GEN"
" 2314149";" 4GEN"
" 2514854";" 4GEN"
" 2734431";" 4GEN"
" 2514934";" 4GEN"
" 2631955";" 4GEN"
" 2512459";" 4GEN"
" 2510841";" 4GEN"
</pre>

only idnumbers (**)
<pre>
2513110
2512334
2314149
2514854
2734431
2514934
2631955
</pre>

only emails (**)
<pre>
toto@insa-lyon.fr
titi@]insa-lyon.fr
tutu@insa-lyon.fr
</pre>

usernames and groups, separated by a tab :

<pre>
ppollet      groupe_de_test              will be in that group
codet        groupe_de_test              also him
astorck      autre_groupe                will be in another group
yjayet                                   no group for this one
                                         empty line skipped
unknown                                  unknown account skipped
</pre>

<p>
<span <font color='red'>(*) </font></span>: double quotes and spaces, added by some spreadsheet programs will be removed.
</p>

<p>
<span <font color='red'>(**) </font></span>: target account must exit in Moodle ; this is normaly the case if Moodle is synchronized with
some external directory (LDAP...)
</p>


EOS;


?>
