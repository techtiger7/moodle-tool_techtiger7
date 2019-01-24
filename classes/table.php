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
 * @copyright 2018, Tom Dickman <twdickman@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_techtiger7;

defined('MOODLE_INTERNAL') || die();

class table extends \table_sql {

//    protected $columns;
//
//    protected $courses;

    public function __construct($uniqueid) {
        global $DB;

        parent::__construct($uniqueid);
        $courses = $DB->get_records('tool_techtiger7');
        $columns = array('courseid', 'name', 'completed', 'priority', 'timecreated', 'timemodified');
        self::define_columns(array_keys(get_object_vars($courses[key($courses)])));
        self::define_headers($columns);
        self::setup();
        self::add_rows($courses, $columns);
        self::finish_output();

    }


    public function define_headers($columns) {
        $headers = array_map(function ($column) {
            return get_string('tbl_' . $column, 'tool_techtiger7');
        }, $columns);

        parent::define_headers($headers);

    }

    public function add_rows($courses, $columns) {
        foreach ($courses as $course) {
            $course = get_object_vars($course);
            $row = array_filter($course, function ($key) use ($columns) {
                return in_array($key, $columns);
            }, ARRAY_FILTER_USE_KEY);
            // Convert to a numerically keyed row of data to add to the table.
            parent::add_data($row);
        }
    }

    public function finish_output($closeexportclassdoc = true) {
        parent::finish_output($closeexportclassdoc);
    }

}