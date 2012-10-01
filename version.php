<?php

defined('MOODLE_INTERNAL') || die();

// don't forget to bump this in case of change in local/db ...
$plugin->version   = 2012012415;        // The current plugin version (Date: YYYYMMDDXX)

$plugin->requires  = 2012062500; // Requires this Moodle version 2.3
$plugin->component = 'local_mass_enroll';       // Full name of the plugin (used for diagnostics)
$plugin->component = 'tool_uploaduser'; // Full name of the plugin (used for diagnostics)
$plugin->maturity = MATURITY_STABLE; // required for registering to Moodle's database of plugins 


?>
