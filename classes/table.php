<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Table class for displaying plugin and moodle data
 *
 * @package tool_techtiger7
 * @copyright 2019, Tom Dickman <twdickman@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_techtiger7;

defined('MOODLE_INTERNAL') || die();

class table extends \table_sql {

    protected $plugincourses = array();

    /**
     * table constructor.
     *
     * @param string $uniqueid the unique name of this table
     * @param array $columnstodisplay an array of the database column names to display in table
     * @param int $courseid the course id to display in table
     *
     * @throws \dml_exception
     */
    public function __construct($uniqueid, array $columnstodisplay, $courseid) {
        global $DB;

        parent::__construct($uniqueid);
        $this->plugincourses = $DB->get_records('tool_techtiger7', array('courseid' => $courseid));
        self::define_columns($columnstodisplay);
        self::define_headers($columnstodisplay);
        self::setup();
        self::add_rows($this->plugincourses, $columnstodisplay);
        self::finish_output();

    }

    /**
     * Define headers based on plugin strings
     *
     * @param array $columns an array of column name strings
     */
    public function define_headers($columns) {
        $headers = array_map(function ($column) {
            return $this->col_name($column);
        }, $columns);

        parent::define_headers($headers);

    }

    /**
     * Format data and add a row to the table
     *
     * @param array $courses an array of standard objects containing course data
     * @param array $columns an array of string database field names to be included in table display
     */
    public function add_rows($courses, $columns) {
        foreach ($courses as $course) {
            $course = get_object_vars($course);

            // Filter row to only include specified columns.
            $row = array_filter($course, function ($key) use ($columns) {
                return in_array($key, $columns);
            }, ARRAY_FILTER_USE_KEY);

            // Convert time fields to date format.
            array_walk($row, function (&$value, $key) {
                if (preg_match('/^time|time$/', $key) && ! is_null($value)) {
                    $value = userdate($value, get_string('strftimedaydatetime'));
                }
            });

            // Convert completion status fields to strings 'yes' or 'no'.
            array_walk($row, function (&$value, $key) {
                if (preg_match('/complete/', $key)) {
                    $value = ($value <= 0) ? get_string('no') : get_string('yes');
                }
            });

            parent::add_data($row);
        }
    }

    /**
     * @param string $column database field name
     *
     * @return string
     * @throws \coding_exception
     */
    public function col_name($column) {
        return get_string('tbl_' . $column, 'tool_techtiger7');
    }

}