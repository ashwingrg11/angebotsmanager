<?php

/**
 * Email_Template.php
 *
 * Handles email template database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email_Template extends Model
{
  /**
   * [database table name for this Email_Template model]
   * @var string
   */
  protected $table = 'email_templates';
  /**
   * [mass assignable attributes for this model]
   * @var array
   */
  protected $fillable = ['project_id', 'email_content_id', 'type', 'language'];
  /**
   * [validation rules for creating an email template]
   * @var [array]
   */
  public $new_template_validation_rules = [
    'project_id' => 'required',
    'sender_email' => 'required|email',
    'subject' => 'required',
    'type' => 'required',
    'content' => 'required',
    'language' => 'required'
  ];
  /**
   * [validator attributes]
   * @var [array]
   */
  public $validation_attribute = [
    'project_id' => 'project',
    'type' => 'template type',
    'content' => 'template content'
  ];
  /**
   * [reverse, one to many eloquent relationship with Project model]
   * @return [object] [an instance of Project model]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }
  /**
   * [reverse, one to many eloquent relationship with Email_Content model]
   * @return [object] [an instance of Email_Content model]
   */
  public function email_contents(){
    return $this->belongsTo('App\Models\Email_Content', 'email_content_id');
  }
  /**
   * [return validation rules for creating/editing an email template]
   * @param [array] $files [attached files]
   * @return [array] $new_template_validation_rules
   */
  public function getNewValidationRule($files, $request){
    if(!empty($files)){
      foreach ($files as $key=>$value) {
        $this->new_template_validation_rules['attachments.'.$key] = 'max:2000';
      }
    }
    if($request->input('type') == "offer_extension"){
      unset($this->new_template_validation_rules['sender_email']);
    }
    return $this->new_template_validation_rules;
  }

  /**
   * [return attributes for validation]
   * @param [array] $files [attachments]
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
   * [insert new email template data]
   * @param  [array] $data [data to be inserted]
   * @return [object] [object of inserted data]
   */
  public function insertEmailTemplate($data){
    return $this->create($data);
  }

  /**
   * [return all available email templates]
   * @return [collection object] [email templates]
   */
  public function getAllEmailTemplates(){
    return $this->get();
  }

  /**
   * [delete email template from database]
   * @param [int] $id [email template id that will be deleted]
   * @return [boolean] [return true if deleted, otherwise return false]
   */
  public function deleteEmailTemplate($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * [getTemplateById description]
   * @return [type] [description]
   */
  public function getTemplateById($id){
    return $this->where('id', $id)->get();
  }

  /**
   * Return email templates as per project id and template type.
   * @param  int $project_id
   * @param  string $template_type
   * @return collection object
   */
  public function templateByProject($project_id, $template_type, $template_language){
    return $this->where('project_id', $project_id)
                ->where('type', $template_type)
                ->where('language', $template_language)
                ->get();
  }

  /**
   * Update data of email template.
   * @param  [array] $data [new data to update]
   * @param  [int] $id [template id]
   * @return [boolean]
   */
  public function updateEmailTemplate($data, $id){
    return $this->where('id', $id)->update($data);
  }

  /**
   * [return email templates as per provided project's id and type]
   * @param [int] $project_id
   * @param [string] $template_type
   * @return [collection object] [email templates]
   */
  public function validateEmailTemplate($project_id, $template_type, $language){
    return $this->where('project_id', $project_id)
                ->where('type', $template_type)
                ->where('language', $language)
                ->get();
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('type', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [getTemplatesByUser description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getTemplatesByUser($projects){
    $email_templates = $this->getAllEmailTemplates();
    $email_templates = $email_templates->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $email_templates;
  }//End getTemplatesByUser function

}//End Class Email_Template
