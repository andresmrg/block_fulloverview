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
 * Newblock block caps.
 *
 * @package    block_fulloverview
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



defined('MOODLE_INTERNAL') || die();

class block_fulloverview extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_fulloverview');
    }

    function get_content() {

        global $CFG, $OUTPUT, $USER, $DB;
        //global $CFG, $SESSION, $USER, $COURSE, $SITE, $PAGE, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        //show my incomplete courses

        //$completion = get_completion($USER->id);
        //print_object($completion);

        //complete courses
        
        //show the courses the user is enrolled.

        $this->content->text .= '<h2>Enrolled Courses</h2>';
        $mycourses = enrol_get_my_courses();

        /**
        
            TODO:
            - Count how many courses the user is enrolled to
            - Count how many courses are complete (if timecompleted is not null)
            - 
        
         */
        
        //total courses
        $totalcourses = $DB->count_records('course_completions', array('userid'=>$USER->id)); 
        //incomplete courses
        //$incompletecourses = $DB->count_records('course_completions', array('userid'=>$USER->id,'timecompleted'=>NULL));
        //inprogress courses
        $sql = "SELECT * FROM {course_completions} WHERE userid = :userid AND timestarted <> :timestarted AND timecompleted IS NULL";
        $inprogress_records = $DB->get_records_sql($sql, array('userid'=>$USER->id,'timestarted'=>'0'));
        $inprogress_count = count($inprogress_records);
        //complete courses
        $sql = "SELECT * FROM {course_completions} WHERE userid = :userid AND timecompleted IS NOT :timecompleted";
        $complete_records = $DB->get_records_sql($sql, array('userid'=>$USER->id,'timecompleted'=>NULL));
        $completecourses = count($complete_records);

        //print_object($inprogress_count);
       
        
        foreach($mycourses as $course) {
            $this->content->text .= '<p><a href="">'.$course->shortname.'</a></p>';
        }

        
        //$this->content->text .= "Incomplete: " . $incompletecourses." / ".$totalcourses."<br>";
        $this->content->text .= "Inprogress: " . $inprogress_count."<br>";
        $this->content->text .= "Complete: " . $completecourses."<br>";
        $this->content->text .= "Total Courses: " . $totalcourses . "<br>";

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return false;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
                 // do something
                  
                      return true;
    }
}
