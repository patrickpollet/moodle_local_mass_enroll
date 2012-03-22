<?php
//// ajout PP inscription massive
    if ($course->id != SITEID) {
        if (!empty($CFG->allow_mass_enroll_feature)){
            if (has_capability('moodle/role:assign', $context)) {
                $this->content->items[]='<a href="'.$CFG->wwwroot.'/local/course/admin/mass_enroll/mass_enroll.php?id='.$this->instance->pageid.'">'.
                     get_string('mass_enroll', 'mass_enroll', '', $CFG->dirroot.'/local/course/admin/mass_enroll/lang/').'</a>';
                $this->content->icons[]='<img src="'.$OUTPUT->pix_url('/i/admin') . '" class="icon" alt="" />';
            }
        }
    }
?>