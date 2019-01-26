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
 * Strings for component 'techtiger7', language 'en', branch 'MOODLE_35_STABLE'
 *
 * @package tool_techtiger7
 * @copyright 2018, Tom Dickman <twdickman@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir . '/tablelib.php');


global $OUTPUT, $DB;

$url = new moodle_url('/admin/tool/techtiger7/index.php');

$cmid = required_param('id', PARAM_INT);
$url->param('id', $cmid);

$users = $DB->get_records('user');
$user = $DB->get_record('user', array('id' => 1));

$courses = $DB->get_records('course');

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('pluginname', 'tool_techtiger7'));
$PAGE->set_heading(get_string('pluginname', 'tool_techtiger7'));
require_login();

echo $OUTPUT->header();
echo $OUTPUT->heading('Overview');

echo html_writer::tag('p', 'Course ID: ' . get_string('plugincourseid', 'tool_techtiger7', ['id' => $cmid]));
$greeting = '<strong>' . get_string('helloworld', 'tool_techtiger7') . '</strong>';

echo html_writer::div(format_text($greeting));

echo html_writer::tag('p', get_string('plugindescription', 'tool_techtiger7'));

echo html_writer::div(get_string('currentuseremail', 'tool_techtiger7', ['email' => $user->email]));

$table = new \tool_techtiger7\table('tool_techtiger7_users', array('courseid', 'name', 'completed', 'priority', 'timecreated', 'timemodified'));

echo $OUTPUT->footer();