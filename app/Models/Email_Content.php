<?php

/**
 * Email_Content.php
 *
 * Handles email content database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as Collection;

class Email_Content extends Model
{
  /**
   * [database table name for this Email_Content model]
   * @var string $table
   */
  protected $table = 'email_contents';
  /**
   * [mass assignable attributes of this model]
   * @var array $fillable
   */
  protected $fillable = ['sender_email', 'receiver_email', 'cc_emails', 'subject', 'content', 'attachments'];
  /**
   * [mutator function to json encode attachments attribute]
   * @param [void]
   */
  public function setAttachmentsAttribute($value){
    $this->attributes['attachments'] = json_encode($value);
  }

  /**
   * [accessor function to json decode value of attachments attribute]
   * @param  [json] $value
   * @return [array] $value [email content attachments]
   */
  public function getAttachmentsAttribute($value){
    return json_decode($value);
  }
  /**
   * [setCcEmailsAttribute description]
   * @param [type] $value [description]
   */
  public function setCcEmailsAttribute($value){
    $new_cc = json_encode($value);
    $this->attributes['cc_emails'] = $new_cc;
  }
  /**
   * [getCcEmailsAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getCcEmailsAttribute($value){
    return json_decode($value);
  }
  /**
   * [one to one relationship with Email_Template model]
   * @return [object] [instance of Email_Template model]
   */
  public function email_templates(){
    return $this->hasOne('App\Models\Email_Template', 'email_content_id');
  }
  /**
   * [one to one relationship with Email model]
   * @return [object] [instance of Email model]
   */
  public function emails(){
    return $this->hasOne('App\Models\Email', 'email_content_id');
  }
  /**
   * [insert new email content details into database]
   * @param  [array] $data [new data]
   * @return [collection object] [newly inserted record]
   */
  public function insertEmailContent($data){
    return $this->create($data);
  }
  /**
   * [return email content record as per provided content id]
   * @param  [int] $id [email content id]
   * @return [collection object] [email ontent details]
   */
  public function getContentById($id){
    return $this->where('id', $id)->get();
  }

  /**
   * [update email content details]
   * @param  [array] $data [new data]
   * @param  [int] $id [email content id]
   * @return [bool]
   */
  public function updateEmailContent($data, $id){
    return $this->where('id', $id)->update($data);
  }

  /**
   * [delete email content record from database]
   * @param  [type] $id [to be deleted email content id]
   * @return [bool]
   */
  public function deleteEmailContent($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * [update attachments data in table]
   * @param  [int] $id [email content id]
   * @param  [type] $file_name
   * @return [bool]
   */
  public function updateAttachmentById($id, $file_name){
    $content = $this->getContentById($id);
    $attachments = (array) $content[0]->attachments;
    $delete_file[] = $file_name;
    //remove new deleted file from attachments array
    $data['attachments']  = json_encode(array_diff($attachments, $delete_file));
    return $this->updateEmailContent($data, $id);
  }

  /**
   * [deleteEmailContents description]
   * @param  [type] $email_contents [description]
   * @return [type]                 [description]
   */
  public function deleteEmailContents($email_contents){
    $flag = null;
    foreach ($email_contents as $email_content_id) {
      $flag = $this->deleteEmailContent($email_content_id);
    }
    return $flag;
  }

  /**
   * [search description]
   * @return [type] [description]
   */
  public function search($search_key){
    return $this->where('sender_email', 'LIKE', '%'.$search_key.'%')
                ->orWhere('receiver_email', 'LIKE', '%'.$search_key.'%')
                ->orWhere('cc_emails', 'LIKE', '%'.$search_key.'%')
                ->orWhere('subject', 'LIKE', '%'.$search_key.'%')
                ->orWhere('content', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [serachEmailData description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function serachEmailContentsData($data, $sn){
    $result = array();
    $result_email_template = array();
    $sn_flag = 0;
    foreach ($data as $key => $value):
      if($value->emails){
        if ($value->emails->status == "pending"){
          $content = ($value->emails->offers->title_en ? $value->emails->offers->title_en : $value->emails->offers->title_de);
          $custom_type = 'queue emails';
          $mode = 'queue-emails';
        }
        else {
          $content = $value->sender_email;
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
      }
      else{
        if($value->email_templates){
          if(!isset($result_email_template['email template'][$sn_flag]['search_content'])):
            $content = $value->email_templates->projects->name;
            $result_email_template['email template'][$sn_flag]['search_content'] = $content;
          endif;
          $result_email_template['email template'][$sn_flag]['controller'] = 'email-template';
          $sn_flag++;
        }
      }
    endforeach;
    if($sn == 2){
      return $result_email_template;
    }
    return $result;
  }

  /**
   * [searchByGeneralUser description]
   * @param  [type] $search_key [description]
   * @param  [type] $projects   [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects){
    if(empty($projects)){
      return new Collection;
    }
    $results = $this->search($search_key);
    $results = $results->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project_user) {
                  if($item->emails){
                    if($item->emails->offers->project_id == $project_user->id){
                      $flag = true;
                    }
                  }
                }
                return $flag;
              });
    return $results;
  }

}
