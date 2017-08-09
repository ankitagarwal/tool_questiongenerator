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

namespace tool_questiongenerator\output;
defined('MOODLE_INTERNAL') || die();

/**
 * Renderer class.
 *
 * Class renderer
 * @package tool_questiongenerator
 */
class renderer extends \plugin_renderer_base {
    /**
     * Main render method.
     *
     * @param renderable $renderable
     */
    public function render_renderable(renderable $renderable) {
        if ($renderable->debug) {
            // If debug is requested, print everything we have got.
            $this->display_debug_data($renderable);
        }
        $this->display_summary($renderable);
    }

    /**
     * Display debug data if needed.
     *
     * @param renderable $renderable
     */
    protected function display_debug_data(renderable $renderable) {
        echo "<br /><b>Arguments</b><pre>";
        print $renderable->input;
        echo "</pre><b>Output</b><pre>";
        print $renderable->output;
        echo "</pre><b>Errors</b><pre>";
        print $renderable->errors;
        echo "</pre>";
    }

    /**
     * Display both original text and generated summary.
     *
     * @param renderable $renderable
     */
    protected function display_summary(renderable $renderable) {
        echo "<b>Original text with important sentences bold</b><br /><br /><p>";
        echo $renderable->boldtext;
        echo "</p>";

        echo "<b>Summary</b><br /><br /><p>";
        echo implode(". ", $renderable->summary);
        echo "</p>";
    }
}