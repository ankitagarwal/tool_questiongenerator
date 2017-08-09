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

/**
 * TODO \templatable,
 * Class renderable
 * @package tool_questiongenerator
 */
class renderable implements \renderable {

    protected $input;
    protected $output;
    protected $errors;
    protected $data;
    protected $boldtext;
    protected $summary;


    public function __construct($input, $output, $errors, $data, $boldtext, $summary) {
        $this->input = $input;
        $this->output = $ouput;
        $this->errors = $errors;
        $this->data = $data;
        $this->boldtext = $boldtext;
        $this->summary = $summary;
    }

    /**
     * Magic get.
     *
     * @param $var
     * @return mixed
     * @throws \coding_exception
     */
    public function __get($var) {
        if (isset($this->$var)) {
            return $this->$var;
        }
        throw new \coding_exception("Invalid property $var requested");
    }
}