<?php

/**
 * Email.php
 *
 * Handles email database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;
use DateTime;
use DB;

class Email extends Model
{
  /**
   * [table name for this Email model]
   * @var [string]
   */
  protected $table = 'emails';
  /**
   * [mass assignable attributes for this Email modell]
   * @var [array]
   */
  protected $fillable = ['email_template_id', 'email_content_id', 'offer_id', 'offer_report_id', 'send_date', 'status', 'type', 'confirm_send'];
  /**
   * [validation rules for editing an email]
   * @var [array]
   */
  public $edit_email_validation_rules = [
    'sender_email' => 'required|email',
    'subject' => 'required',
    'content' => 'required',
  ];
  /**
   * [validation attributes for this Email model]
   * @var [type]
   */
  public $validation_attribute = [
    'content' => 'template content',
    'send_date' => 'email send date'
  ];
  /**
   * [reverse, one to one relationship to Email_Content model]
   * @return [object] \App\Models\Email_Content
   */
  public function email_contents(){
    return $this->belongsTo('App\Models\Email_Content', 'email_content_id');
  }

  /**
   * [reverse, one to many relationship to Offer model]
   * @return [object] \App\Models\Offer
   */
  public function offers(){
    return $this->belongsTo('App\Models\Offer', 'offer_id');
  }

  /**
   * [offer_reports description]
   * @return [type] [description]
   */
  public function offer_reports(){
    return $this->belongsTo('App\Models\Offer_Report', 'offer_report_id');
  }

  /**
   * [accessor function of send_date attribute]
   * @param  [string] $value
   * @return \Carbon\Carbon date $value
   */
  public function getSendDateAttribute($value){
    return Carbon::parse($value)->format('d/m/Y');
    // return Carbon::parse($value)->format('Y-m-d');
  }

  /**
   * [return validation rules for editing an email]
   * @param  [array] $files
   * @return [array] $edit_email_validation_rules
   */
  public function getEditValidationRule($files){
    if(!empty($files)){
      foreach ($files as $key=>$value) {
        $this->edit_email_validation_rules['attachments.'.$key] = 'max:2000';
      }
    }
    $today_date = Carbon::now()->subDay()->format('Y-m-d');
    $this->edit_email_validation_rules['send_date'] = "required|date_format:Y-m-d|after:".$today_date.",";
    return $this->edit_email_validation_rules;
  }

  /**
   * [return validator attributes]
   * @param [array] $files
   * @return [array] $validation_attribute
   */
  public function getValidationAttributes($files){
    if(!empty($files)){
      foreach ($files as $key=>$value) {
        $this->validation_attribute['attachments.'.$key] = 'attachment file';
      }
    }
    return $this->validation_attribute;
  }

  /**
   * [return email details by email id]
   * @param  [int] $email_id
   * @return [type]           [description]
   */
  public function getEmailById($email_id){
    return $this->where('id', $email_id)->get();
  }

  /**
   * [insert new email]
   * @param  [array] $data [new email info]
   * @return [object] [newly inserted email info]
   */
  public function insertEmail($data){
    return $this->create($data);
  }

  /**
   * [return all pending/queue emails]
   * @return [collection object] [all pending emails]
   */
  public function allQueueEmails(){
    return $this->where('status', 'pending')->get();
  }

  /**
   * [return all emails that have been sent]
   * @return [collection object] [all sent emails]
   */
  public function allSentEmails(){
    return $this->where('status', 'sent')->get();
  }

  /**
   * [return all emails that is going to be sent]
   * @return [collection object] [to be sent emails]
   */
  public function tobeSentEmails(){
    $results = $this->where('status', 'pending')
                ->where('confirm_send', 1)
                ->get();
    $emails = $results->filter(function($item){
                if($item->offers->has_communication_package == "yes"){
                  return true;
                }
              });
    return $emails;
  }

  /**
   * [delete email from database]
   * @param  [int] $email_id [email id which record will be deleted]
   * @return [boolean]
   */
  public function deleteEmail($email_id){
    return $this->where('id', $email_id)->delete();
  }

  /**
   * [update info of existing emails]
   * @param  [int] $email_id [email id which info will be updated]
   * @param  [array] $data [new data/info to update]
   * @return [bool]
   */
  public function updateEmail($email_id, $data=array()){
    return $this->where('id', $email_id)->update($data);
  }

  /**
   * Return all the pending emails by offer id.
   * @param  int $offer_id
   * @return collection
   */
  public function pendingEmailsByOffer($offer_id){
    return $this->where('status', 'pending')
                ->where('offer_id', $offer_id)
                ->get();
  }

  /**
   * [get pending/queue emails by month and year]
   * @param  [string] $date
   * @return [collection object] [all emails for given year and month]
   */
  public function monthlyQueueEmails($date){
    $calendar_month = Carbon::CreateFromFormat('m-Y', $date)->format('m');
    $calendar_year = Carbon::CreateFromFormat('m-Y', $date)->format('Y');
    return $this->where(DB::raw('MONTH(send_date)'), $calendar_month)
                ->where(DB::raw('YEAR(send_date)'), $calendar_year)
                ->where('status', 'pending')
                ->get();;
  }

  /**
   * [deleteEmailsByOffer description]
   * @return [type] [description]
   */
  public function deleteEmailsByOffer($offer_id){
    return $this->where('offer_id', $offer_id)
                ->where('status', '!=', 'sent')
                ->delete();
  }

  /**
   * [deleteEmailByOfferDelete description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function getEmailContentsByOffer($offer_id, $email_type){
    $emails = $this->where('offer_id', $offer_id)
                  ->get();
    if($email_type == 'activation'){
      $emails = $this->where('offer_id', $offer_id)
                  ->where('type', '!=', $email_type)
                  ->get();
    }
    $email_content_ids = array();
    foreach ($emails as $email) {
      if($email->status != 'sent'){
        $email_content_ids[] = $email->email_content_id;
      }
    }
    return $email_content_ids;
 }

  /**
   * Delete emails by offer id and email type.
   * @param  [int] $offer_id
   * @param  [string] $email_type
   * @return [bool]
   */
  public function deleteEmailByTypeOffer($offer_id, $email_type){
    $emails = $this->where('offer_id', $offer_id)
                ->where('type', '!=', $email_type)
                ->get();
    foreach ($emails as $email) {
      $email->email_contents->delete();
      $this->deleteEmail($email->id);
    }
    return true;
  }

  /**
   * [getQueueEmailsByProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getQueueEmailsByProject($projects){
    $emails = $this->allQueueEmails();
    $emails = $emails->filter(function($item) use($projects){
                  $flag = false;
                  foreach ($projects as $project_user) {
                    if($item->offers->project_id == $project_user->id){
                      $flag = true;
                    }
                  }
                  return $flag;
                });
    return $emails;
  }

  /**
   * [getMonthlyQueueEmails description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function monthlyQueueEmailsByProject($date, $projects){
    $emails = $this->monthlyQueueEmails($date);
    $emails = $emails->filter(function($item) use($projects){
                  $flag = false;
                  foreach ($projects as $project_user) {
                    if($item->offers->project_id == $project_user->id){
                      $flag = true;
                    }
                  }
                  return $flag;
                });
    return $emails;
  }

  /**
   * [sentEmailsByProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function sentEmailsByProject($projects){
    $sent_emails = $this->where('status', 'sent')->get();
    $sent_emails = $sent_emails->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project_user) {
                  if($item->offers->project_id == $project_user->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $sent_emails;
  }

  /**
   * [search description]
   * @return [type] [description]
   */
  public function search($search_key){
    $results = $this->where('send_date', 'LIKE', '%'.$search_key.'%')
                ->orWhere('type', 'LIKE', '%'.$search_key.'%')
                ->orWhere('status', 'LIKE', '%'.$search_key.'%')
                ->get();
    $results = $results->filter(function($item){
                if($item->status == "pending" || $item->status == "sent"){
                  return true;
                }
              });
    return $results;
  }

  /**
   * [serachEmailData description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function serachEmailsData($data){
    $result = array();
    $sn_flag = 0;
    foreach ($data as $key => $value):
      if ($value->status == "pending"){
        $content = ($value->offers->title_en ? $value->offers->title_en : $value->offers->title_de);
        $custom_type = 'queue emails';
        $mode = 'queue-emails';
      }
      else {
        $content = $value->email_contents->sender_email;
        $custom_type = 'sent emails';
        $mode = 'sent-emails';
      }
      if(!isset($result['email'][$sn_flag]['search_content'])):
        $result['email'][$sn_flag]['search_content'] = $content;
      endif;
      $result['email'][$sn_flag]['controller'] = 'email';
      $result['email'][$sn_flag]['mode'] = $mode;
      $result['email'][$sn_flag]['custom_type'] = $custom_type;
      $sn_flag++;
    endforeach;
    return $result;
  }

  /**
   * [searchByGeneralUser description]
   * @param  [type] $search_key [description]
   * @param  [type] $projects   [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects){
    $results = $this->search($search_key);
    $results = $results->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project_user) {
                  if($item->offers->project_id == $project_user->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $results;
  }

}//End Class Email
