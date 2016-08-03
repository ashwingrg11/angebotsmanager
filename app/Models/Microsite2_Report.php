<?php

/**
 * Mirosite2_Report.php
 *
 * Handles microsite2 reports database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Microsite2_Report extends Model
{
  /**
   * [database table name for this Microsite2_Report model]
   * @var string $table
   */
  protected $table = "microsite2_reports";
  /**
   * [mass assignable properties of this model]
   * @var [array] $fillable
   */
  protected $fillable = ['offer_report_id', 'question1', 'question2', 'question3'];
  /**
   * [one to many eloquent relationship to Offer_Report model]
   * @return [object] [instance of Offer_Report model]
   */
  public function offer_reports(){
    return $this->belongsTo('App\Models\Offer_Report', 'offer_report_id');
  }

  /**
   * [getQuestion1Attribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getQuestion1Attribute($value){
    return json_decode($value);
  }

  /**
   * [getQuestion1Attribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getQuestion2Attribute($value){
    return json_decode($value);
  }

  /**
   * [getQuestion1Attribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getQuestion3Attribute($value){
    return json_decode($value);
  }

  /**
   * [insert new record into microsite2_report table]
   * @param [array] [new data/record]
   * @return [collection object] [an object of inserted record]
   */
  public function createNew($data){
    return $this->create($data);
  }

  /**
   * [update record by offer report id]
   * @param  [int] $id [offer report id]
   * @param  [array] $data
   * @return [bool]
   */
  public function updateByOfferReport($id, $data){
    return $this->where('offer_report_id', $id)->update($data);
  }

  /**
   * [return microsite2 reports data by offer report]
   * @param  [int] $id [offer report id]
   * @return [collection object]
   */
  public function getByOfferReport($id){
    return $this->where('offer_report_id', $id)->get();
  }

  /**
   * [serialize input data and update/create]
   * @param  [int] $id   [offer report id]
   * @param  [array] $data
   * @return [mixed]
   */
  public function saveMicrosite2Data($id, $data){
    //question1, input
    $question1['flag'] = isset($data['future_collaboration']) ? $data['future_collaboration']: null;
    $question1['text'] = $data['future_collaboration_text'];
    $db_data['question1'] = json_encode($question1);
    //question2, input
    $question2['text'] = $data['collective_feedback_text'];
    $db_data['question2'] = json_encode($question2);
    //question3, input
    $question3['flag'] = isset($data['overall_success']) ? $data['overall_success'] : null;
    $question3['text'] = $data['overall_success_text'];
    $db_data['question3'] = json_encode($question3);
    $db_data['offer_report_id'] = $id;
    if($this->getByOfferReport($id)->isEmpty()):
      //insert new record
      return $this->createNew($db_data);
    else:
      //update existing record
      return $this->updateByOfferReport($id, $db_data);
    endif;
  }

}
