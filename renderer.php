<?php

/**
 * halomdatesting question renderer class.
 *
 * @package    qtype
 * @subpackage halomdatesting
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for halomdatesting questions.
 *
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_halomdatesting_renderer extends qtype_renderer 
  {
  public function formulation_and_controls(question_attempt $qa, question_display_options $options) 
    {
    $question = $qa->get_question();
    $Context = $question->questiontext;
    if( substr( $Context, 0, 3 ) == '<p>' )
      $Context = substr( $Context, 3, strlen( $Context ) - 7 );
    $Parms = explode(";", $Context );
    $ChpId =  $Parms[0];
    $Language = $Parms[1];
    $UsIdCard = $qa->get_step(0)->get_user_id().";".gethostbyname($_SERVER['SERVER_NAME']);
    $LastStep = $qa->get_last_step();
    $Data = $LastStep->get_behaviour_data();
    $Finally =  $Data['finish'] == '1' ? 1 : 0;
    if( $Finally )
      $LastResponse  = $qa->get_response_summary();
    else
      $LastResponse = ($LastStep->get_qt_data())['answer'];
    $Result = "";
    $TotalCount = "";
    if( !is_null($LastResponse) )
      {
      $Results = explode(";",  $LastResponse );
      $Result = $Results[1];
      $TotalCount = $Results[0];
      }
    $inputname = $qa->get_qt_field_name('answer');
    $questiontext.="<script src='https://halomda.org/WebTestManager/moodlescripts.js'></script>
    <script async src='https://halomda.org/WebTestManager/FormulaEditor.js'></script>
    <script async src='https://halomda.org/WebTestManager/LabelsHeb.js'></script>
    <link rel='stylesheet' type='text/css' href='https://halomda.org/WebTestManager/Chapter.css' />
    <table class='list_table' id='list_table' dir='rtl'>
    <tr>";
    if($Language == "heb" )
      $questiontext.="<th rowspan='2'>נושא</th>
        <th rowspan='2'>לימוד</th>
        <th rowspan='2'>תרגול</th>
        <th colspan='3'>מבחן</th>
    </tr>
    <tr>
      <th>מבחן</th>
      <th>שאלות</th>
      <th>ציון</th>";
    else  
       $questiontext.="<th rowspan='2'>Test name</th>
       <th rowspan='2'>Learn</th>
       <th rowspan='2'>Training</th>
       <th colspan='3'>Quiz</th>
      </tr>
    <tr>
      <th>Start</th>
      <th>Problems number</th>
      <th>Result</th>";
    $questiontext.="</tr>  
      <tr>  
        <td>".$question->name."</td>  	
        <td><img src='https://Halomda.org/WebTestManager/limud.png' onclick=\"SelectTopic('".$UsIdCard."','".$Language."',
          ".$ChpId.",'".$question->name."','".$inputname."','wrkLearn',".$Finally.",false);\"></td>
        <td><img src='https://Halomda.org/WebTestManager/tirgul.png' onclick=\"SelectTopic('".$UsIdCard."','".$Language."',
          ".$ChpId.",'".$question->name."','".$inputname."','wrkTrain',".$Finally.", false);\"></td>
        <td><img src='https://Halomda.org/WebTestManager/mivhan.png' onclick=\"SelectTopic('".$UsIdCard."','".$Language."',
          ".$ChpId.",'".$question->name."','".$inputname."','wrkExam',".$Finally.", false);\"></td>
        <td>".$TotalCount."</td>  
        <td>".$Result."</td>  
      </TR>  
    </table>";
    $inputattributes = array(
        'type' => 'hidden',
        'name' => $inputname,
        'id' => $inputname,
    );
    $input = html_writer::empty_tag('input', $inputattributes);
    return $questiontext.$input;
    }

    public function specific_feedback(question_attempt $qa) {
        return '';
    }

    public function correct_response(question_attempt $qa) {
        return '';
    }
}
