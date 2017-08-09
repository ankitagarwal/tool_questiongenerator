<?php

require_once('../../../config.php');

require_login();
// TODO cap check.

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/admin/tool/questiongenerator/index.php'));
$PAGE->set_title(get_string('questiongenerator', 'tool_questiongenerator'));

echo $OUTPUT->header();

// Print headers and things.
echo $OUTPUT->box(get_string('spamcleanerintro', 'tool_advancedspamcleaner'));

$mform = new tool_questiongenerator\generator_form();

if ($formdata = $mform->get_data()) {
    $mform->display();
    $text = $formdata->originaltext;
    $debug = empty($formdata->debug) ? false : true ;
    $path = "/home/ankit/re/ques/aqg/app.py";
    $txtpath = $CFG->tempdir . "questiongenerator" . sha1($text) . ".txt"; // TODO
    file_put_contents($txtpath, $text);

    $cmd = "/usr/bin/python $path -f $txtpath";
    $command = escapeshellcmd($cmd);
    $proc = proc_open($command,
        array(
            array("pipe","r"),
            array("pipe","w"),
            array("pipe","w")
        ),
        $pipes);
    $data = stream_get_contents($pipes[1]);
    if ($debug) {
        echo "<br /><b>Arguments</b><pre>";
        print stream_get_contents($pipes[0]);
        echo "</pre><b>Output</b><pre>";
        print $data;
        echo "</pre><b>Errors</b><pre>";
        print stream_get_contents($pipes[2]);
        echo "</pre>";
        print_object($data);
    }

    $data = json_decode($data, true);
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
