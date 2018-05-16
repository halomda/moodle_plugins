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
 * YOURQTYPENAME question definition class.
 *
 * @package    qtype
 * @subpackage halomdatesting
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents a halomdatesting question.
 *
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_halomdatesting_question extends question_graded_automatically_with_countback {

    public function get_expected_data() {
        return array( "answer" => PARAM_RAW );
    }

    public function summarise_response(array $response) {
        if (isset($response['answer'])) 
          return $response['answer'];
        else
          return null;
     }

    public function is_complete_response(array $response) 
      {
      if( $response === null ) return false;
      $Response = explode(";", $response['answer'] );
      if( count( $Response ) != 3 ) return false;
      return $Response[2] == '0';
      }

    public function get_validation_error(array $response) {
        // TODO.
        return '';
    }

    public function is_same_response(array $prevresponse, array $newresponse) 
      {
      if( $newresponse === null ) return true;
      $Response = explode(";", $newresponse['answer'] );
      if( count( $Response ) != 3 ) return true;
      if( $prevresponse === null ) return false;
      return $newresponse['answer'] == $prevresponse['answer'];
      }


    public function get_correct_response() {
      return array( "answer" => 100 );
    }


    public function check_file_access($qa, $options, $component, $filearea,
            $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'hint') {
            return $this->check_hint_file_access($qa, $options, $args);

        } else {
            return parent::check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload);
        }
    }

    public function grade_response(array $response) 
      {
      if( $response === null ) return 0;
      $Response = explode(";", $response['answer'] );
      if( count( $Response ) != 3 ) return 0;
      $fraction = floatval($Response[1]) / 100.0;
      return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    public function compute_final_grade($responses, $totaltries) 
      {
      return $responses;
      }
}
