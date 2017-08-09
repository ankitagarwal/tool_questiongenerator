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
 * Class api
 * @package tool_questiongenerator
 */
class api {
    const PATH = "/home/ankit/re/ques/aqg/app.py"; // TODO

    /**
     * Generate questions by calling python module.
     *
     * @param $text
     * @return array
     */
    protected static function generate_questions($text) {
        global $CFG;
        $txtpath = $CFG->tempdir . "questiongenerator" . sha1($text) . ".txt"; // TODO
        file_put_contents($txtpath, $text);

        $cmd = "/usr/bin/python " . self::PATH . " -f $txtpath";
        $command = escapeshellcmd($cmd);
        $proc = proc_open($command, [
                ["pipe", "r"],
                ["pipe", "w"],
                ["pipe", "w"]
            ],
            $pipes);
        return[stream_get_contents($pipes[0]), stream_get_contents($pipes[1]), stream_get_contents($pipes[2])];

    }

    /**
     * Clean form data.
     *
     * @param $formdata
     * @return array
     */
    protected static function clean_form_data($formdata) {
        $text = $formdata->originaltext;
        $debug = empty($formdata->debug) ? false : true;
        return [$text, $debug];
    }

    /**
     * For a given text and summary bold the sentences in text that are also present in summary.
     *
     * @param $text
     * @param $summary
     * @return mixed
     */
    protected static function get_bold_text($text, $summary) {
        $replace = [];
        $finalsummary = [];
        foreach ($summary as $sent) {
            $finalsummary[] = trim($sent);
            $replace[] = "<b>" . $sent . "</b>";
        }
        return [str_replace($finalsummary, $replace, $text), $finalsummary];
    }

    public static function get_renderable($formdata) {
        list($text, $debug) = self::clean_form_data($formdata);
        list($input, $output, $errors) = self::generate_questions($text);

        $data = json_decode($output, true);
        $summary = $data['summary'];
        list($boldtext, $summary) = self::get_bold_text($text, $summary);
        $renderable = new output\renderable($input, $output, $errors, $debug, $data, $boldtext, $summary);
        return $renderable;
    }
}