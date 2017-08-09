<?php

namespace tool_questiongenerator;

/**
 * Class questions_table
 * @package tool_questiongenerator
 */
class questions_table extends \flexible_table {
    const PRED_BAD = 0;
    const PRED_OK = 1;
    const PRED_GOOD = 2;

    /**
     * questions_table constructor.
     * @param string $uniqueid
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);

        $baseurl = new \moodle_url('/tool/questiongenerator/index.php');
        $this->define_baseurl($baseurl);

        // Column definition.
        $this->define_columns([
            'question',
            'answer',
            'prediction'
        ]);

        $this->define_headers([
            get_string('title',   'tool_usertours'),
            get_string('content', 'tool_usertours'),
            get_string('target',  'tool_usertours'),
        ]);

        $this->set_attribute('class', 'admintable generaltable');

        $this->setup();
    }

    /**
     * @param $row
     * @return string
     * @throws \coding_exception
     */
    protected function col_prediction($row) {
        switch($row->prediction) {
            // TODO strings.
            case self::PRED_BAD :
                return "Bad question";
            case self::PRED_OK :
                return "Ok question";
            case self::PRED_GOOD :
                return "Good question";
            default:
                throw new \coding_exception("Invalid data");
        }
    }

    /**
     * @param $array
     * @return array
     */
    protected static function transpose($array) {
        return array_map(null, ...$array);
    }

    /**
     * @param $data
     * @return array
     * @throws \coding_exception
     */
    protected static function rearrange_data($data) {
        $answers = $data['Answer'];
        $ques = $data['Question'];
        $preds = $data['Prediction'];

        $tabledata = [];
        if ((count($answers) != count($ques)) || (count($ques) != count($preds))) {
            print_object($data);
            throw new \coding_exception("Invalid data returned by python module");
        }
        for ($i = 0; $i < count($ques); $i++) {
            $tabledata[] = ['question' => $ques[$i], 'answer' => $answers[$i], 'prediction' => $preds[$i]];
        }
        return $tabledata;
    }

    /**
     * @param $array
     * @param bool $finish
     */
    public function format_and_add_array_of_rows($array, $finish = true) {
        $array = self::rearrange_data($array);
        parent::format_and_add_array_of_rows($array, $finish);
    }
}