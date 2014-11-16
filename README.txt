moodle_local_mass_enroll
========================


A Moodle 2.x tool for all teachers to enrol/unenrol existing users to their courses using CSV files (without bothering their admins)

Main features are :

* users can be specified by username, id number or email 
* users can be optionnally enroled to groups/groupings (autocreated if needed)
* email reports can be send
* import can be repeated if some users are to be in several groups
* usage can be restricted by modifying specific capabilities (local/mass_enroll:enrol and local:/mass_enroll:unenrol) 
* can be inserted in Course's admin menu or called from a specific Moodle block

This plugin has been tested with Moodle 2.3, 2.4, 2.5, 2.6 and 2.7.
With Moodle 2.8, two deprecation notices are emitted in 'full developper debug mode' concerning add_to_log() API call.

See the wikai page https://github.com/patrickpollet/moodle_local_mass_enroll/wiki for installation and usage. 
