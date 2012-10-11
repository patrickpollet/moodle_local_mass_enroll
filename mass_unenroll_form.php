<?php
// $Id: inscriptions_massives_form.php 352 2010-02-27 12:16:55Z ppollet $

require_once ($CFG->libdir . '/formslib.php');

class mass_unenroll_form extends moodleform {

	function definition() {
		global $CFG;


		$mform = & $this->_form;
		$course = $this->_customdata['course'];
		$context = $this->_customdata['context'];

		$mform->addElement('header', 'general', ''); //fill in the data depending on page params
		//later using set_data
		$mform->addElement('filepicker', 'attachment', get_string('location', 'enrol_flatfile'));

		$mform->addRule('attachment', null, 'required');
		
		$choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = textlib::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');
		

		$ids = array (
			'idnumber' => get_string('idnumber', 'local_mass_enroll'),
			'username' => get_string('username', 'local_mass_enroll'),
			'email' => get_string('email')
		);
		$mform->addElement('select', 'firstcolumn', get_string('firstcolumn', 'local_mass_enroll'), $ids);
		$mform->setDefault('firstcolumn', 'idnumber');

		

		$mform->addElement('selectyesno', 'mailreport', get_string('mailreport', 'local_mass_enroll'));
		$mform->setDefault('mailreport', 1);

		//-------------------------------------------------------------------------------
		// buttons

		$this->add_action_buttons(true, get_string('unenroll', 'local_mass_enroll'));

		$mform->addElement('hidden', 'id', $course->id);
		$mform->setType('id', PARAM_INT);
	}

	function validation($data, $files) {
		$errors = parent :: validation($data, $files);
		return $errors;
	}
}
?>
