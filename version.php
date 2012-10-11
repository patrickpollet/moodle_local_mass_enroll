<?php

defined('MOODLE_INTERNAL') || die();

// don't forget to bump this in case of change in local/db ...
$plugin->version   = 2012101009;        // The current plugin version (Date: YYYYMMDDXX)

$plugin->requires  = 2011120100;        // Moodle 2.2 onwards
$plugin->component = 'local_mass_enroll';       // Full name of the plugin (used for diagnostics)
$plugin->maturity = MATURITY_STABLE; // required for registering to Moodle's database of plugins 
$plugin->release = '2.2 (Build 20121001)';  // required for registering to Moodle's database of plugins
