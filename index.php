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

require_once('../../../config.php');

require_login();
// TODO cap check.

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/admin/tool/questiongenerator/index.php'));
$PAGE->set_title(get_string('questiongenerator', 'tool_questiongenerator'));
$PAGE->set_pagelayout('report');

echo $OUTPUT->header();

// Print headers and things.
echo $OUTPUT->box(get_string('spamcleanerintro', 'tool_advancedspamcleaner'));

$mform = new tool_questiongenerator\generator_form();

if ($formdata = $mform->get_data()) {
    $mform->display();
    list($input, $output, $errors) = \tool_questiongenerator\api::generate_questions($formdata);

//    if ($debug) {
//        echo "<br /><b>Arguments</b><pre>";
//        print $input;
//        echo "</pre><b>Output</b><pre>";
//        print $output;
//        echo "</pre><b>Errors</b><pre>";
//        print $errors;
//        echo "</pre>";
//        print_object($data);
//    }

    $data = json_decode($output, true);
    $summary = $data['summary'];

    $replace = [];
    $finalsummary = [];
    foreach ($summary as $sent) {
        $finalsummary[] = trim($sent);
        $replace[] = "<b>" . $sent . "</b>";
    }
    $boldtext = str_replace($finalsummary, $replace, $text);
    echo "<b>Original text with important sentences bold</b><br /><br /><p>";
    echo $boldtext;
    echo "</p>";

    echo "<b>Summary</b><br /><br /><p>";
    echo implode(". ", $finalsummary);
    echo "</p>";

    $table = new \tool_questiongenerator\questions_table("qna");
    $table->format_and_add_array_of_rows($data['qna']);

} else {
    $mform->display();
}
echo $OUTPUT->footer();
