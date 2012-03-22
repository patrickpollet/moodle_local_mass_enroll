<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');

class mass_enroll_form extends moodleform {

	function definition() {
		global $CFG;

		// $locallangroot = $CFG->dirroot.'/local/course/admin/mass_enroll/lang/';
		// since the help file MUST be copied to moodledata/lang/xx_utf8, let's also require
		// that the translation file be there
		$locallangroot = '';

		$mform = & $this->_form;
		$course = $this->_customdata['course'];
		$context = $this->_customdata['context'];

		// the upload manager is used directly in post precessing, moodleform::save_files() is not used yet
		$this->set_upload_manager(new upload_manager('attachment'));

		$mform->addElement('header', 'general', ''); //fill in the data depending on page params
		//later using set_data
		$mform->addElement('filepicker', 'attachment', get_string('location', 'enrol_flatfile'));

		$mform->addRule('attachment', null, 'required');

		/**
        $roles = get_assignable_roles_for_switchrole($context,'shortname');
        print_r($roles);
        foreach ($roles as $id=>$shortname ){
            $roles[$id]=get_string($shortname);
        }
        */
        $roles = get_assignable_roles_for_switchrole($context);


		$mform->addElement('select', 'roleassign', get_string('roleassign', 'mass_enroll', '', $locallangroot), $roles);
		$mform->setDefault('roleassign', 5); //student

		$ids = array (
			'idnumber' => get_string('idnumber', 'mass_enroll', '', $locallangroot),
			'username' => get_string('username', 'mass_enroll', '', $locallangroot),
			'email' => get_string('email')
		);
		$mform->addElement('select', 'firstcolumn', get_string('firstcolumn', 'mass_enroll', '', $locallangroot), $ids);
		$mform->setDefault('firstcolumn', 'idnumber');

		$mform->addElement('selectyesno', 'creategroups', get_string('creategroups', 'mass_enroll', '', $locallangroot));
		$mform->setDefault('creategroups', 1);
		//$mform->setHelpButton('creer_groupes', array('mass_enroll', get_string('mass_enroll', 'mass_enroll', '', $locallangroot), 'mass_enroll'));

		if ($CFG->enablegroupings) {
			$mform->addElement('selectyesno', 'creategroupings', get_string('creategroupings', 'mass_enroll', '', $locallangroot));
			$mform->setDefault('creategroupings', 1);
			//  $mform->setHelpButton('creategroupings', array('mass_enroll', get_string('mass_enroll', 'mass_enroll', '', $locallangroot), 'mass_enroll'));
		}

		$mform->addElement('selectyesno', 'mailreport', get_string('mailreport', 'mass_enroll', '', $locallangroot));
		$mform->setDefault('mailreport', 1);

		//-------------------------------------------------------------------------------
		// buttons

		$this->add_action_buttons(true, get_string('enroll', 'mass_enroll', '', $locallangroot));

		$mform->addElement('hidden', 'id', $course->id);
		$mform->setType('id', PARAM_INT);
	}

	function validation($data, $files) {
		$errors = parent :: validation($data, $files);
		return $errors;
	}
}
?>
