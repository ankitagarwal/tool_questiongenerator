<?php
// This file is part of Moodle plugin question generator.
//
// Moodle plugin question generator is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle plugin question generator is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace tool_questiongenerator;
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

/**
 * Class generator_form
 * @package tool_questiongenerator
 */
class generator_form extends \moodleform {
    // Define the form.
    public function definition() {

        // TODO add path to python code.
        $mfrom = $this->_form;

        // Options.
        $mfrom->addElement('header', 'options', get_string('options'));

        // Summary ratio. (Not used at the moment).
        $mfrom->addElement('text', 'summaryratio', get_string('summaryratio', 'tool_questiongenerator'));
        $mfrom->setType('summaryratio', PARAM_FLOAT);
        $mfrom->setDefault('summaryratio', 0.10);

        // Original text field.
        $mfrom->addElement('header', 'text', get_string('text', 'tool_questiongenerator'));
        $mfrom->addElement('textarea', 'originaltext', get_string('originaltext', 'tool_questiongenerator'), ['rows' => 30, 'cols' => 100]);
        $mfrom->setType('originaltext', PARAM_NOTAGS);
        $mfrom->addRule('originaltext', null, 'required');

        // Debug mode.
        $mfrom->addElement('checkbox', 'debug', get_string('debug'), get_string('enablescheduledinfo', 'workshopallocation_scheduled'), 0);
        $mfrom->setType('checkbox', PARAM_BOOL);

        // Action buttons.
        $this->add_action_buttons(false, get_string('generatequestions', 'tool_questiongenerator'));

    }

    /**
     * Add validations.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if ($data['summaryratio'] >= 1 || $data['summaryratio'] <= 0) {
            $errors['summaryratio'] = get_string('incorrectratio', 'tool_questiongenerator');
        }
        return $errors;
    }
}
