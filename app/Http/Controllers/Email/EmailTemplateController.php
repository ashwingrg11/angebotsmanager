<?php

/**
 * EmailTemplateController.php
 *
 * Handles email template module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Email;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Project as Project;
use App\Models\Email_Template as EmailTemplate;
use App\Models\Email_Content as EmailContent;
use Validator;
use File;
use Exception;
use Auth;

class EmailTemplateController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Email Template Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to email
  | template module. It includes project, email_template and email_content
  | models for it's related operations.
  |
  */
  public $project_model;
  public $email_template_model;
  public $email_content_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    //load project, email_template, email_content models
    $this->project_model = new Project;
    $this->email_template_model = new EmailTemplate;
    $this->email_content_model = new EmailContent;
  }

  /**
   * Return email templates list view to display all available email templates.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    try {
      $projects = $this->commonGetProjects(); //get projects
      if(Auth::user()->user_type == "general"){
        $email_templates = $this->email_template_model->getTemplatesByUser($projects);
      }//End if user_type general
      else{
        //get all available email templates
        $email_templates = $this->email_template_model->getAllEmailTemplates();
      }//End else user_type general
      //return email templates list view with all email templates data
      return view('emails.email_templates.list_view', compact('email_templates'));
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getIndex function

  /**
   * Return view to create new template with all available projects.
   * @return \Illuminate\Http\Response
   */
  public function getCreate(){
    try {
      //all available projects
      $projects = $this->project_model->getAllProjects();
      //return email templates add view with all available projects
      return view('emails.email_templates.add_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getCreate function

  /**
   * Valid user inputs of incoming email template post create request and insert new email
   * template data(content data, attachments), if all data are valid.
   * @param  \Illuminate\Http\Request $request [email template post create request]
   * @return mixed
   */
  public function postCreate(Request $request){
    $files = $request->file('attachments'); //input attachments
    //validate user inputs
    $validator = Validator::make($request->all(), $this->email_template_model->getNewValidationRule($files, $request));
    //set validation attribute names
    $validator->setAttributeNames($this->email_template_model->getValidationAttributes($files));
    if ($validator->fails()) {
      //Redirecct to email template create page with errors if validation fails
      return redirect('email-template/create')
        ->withErrors($validator)
        ->with('file_count', count($files))
        ->withInput();
    }//End validation fails, if
    $data = $this->constructData($request);
    //trim cc_emails input array
    $trimmedArray = array_filter($data['cc_emails']);
    //re-indexed cc_emails array
    $data['cc_emails'] = array_values($trimmedArray);
    try {
      //Validate email template, if already exists for same project and type
      $validate_flag = $this->email_template_model->validateEmailTemplate($data['project_id'], $data['type'], $data['language']);
      if(!$validate_flag->isEmpty()):
        throw new Exception('Exc');
      endif;//End email template exists, if
    } catch (\Exception $e){
      //Return response message if Email template already exists for same project and type
      return response()->json(['exc'=>utf8_encode($e->getMessage()), 'custom_message'=>'Email template already exists with this project, emailtype and language.']);
    }//End try-catch
    try {
      //attachments destination path to upload
      $destinationPath = base_path() . '/public/emails/attachments/';
      //if form data has attachments
      if(!empty($files)){
        $files_nos = count($files)-1;
        for ($i=0; $i < $files_nos; $i++) {
          $file_name = pathinfo($files[$i]->getClientOriginalName(), PATHINFO_FILENAME);
          $file_extension = $files[$i]->getClientOriginalExtension();
          $final_file = $file_name.'_'.time().'.'.$file_extension;
          //move attachment to destination upload path
          $files[$i]->move($destinationPath, $final_file);
          //push file name to attachments array
          $data['attachments'][]= $final_file;
        }//End all attachments, for loop
      }//End if, email template attachments
      //project id
      $template_data['project_id'] = $data['project_id'];
      //email template type
      $template_data['type'] = $data['type'];
      $template_data['language'] = $data['language'];
      unset($data['project_id'], $data['type'], $data['language']);
      //insert email content
      $email_content = $this->email_content_model->insertEmailContent($data);
      $template_data['email_content_id'] = $email_content->id; //email content id
      //insert email template
      $this->email_template_model->insertEmailTemplate($template_data);
      //Redirect to email template index page with created success notice
      return redirect('email-template/index')->with('added_notice', 'New email template has been created successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End email template, postCreate function

  /**
   * Return email template edit view with template details/data.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    try {
      //get email template id
      $template_id = $request->input('post_id');
      //get email template details by provided email template id
      $template_details = $this->email_template_model->getTemplateById($template_id);
      $projects = $this->commonGetProjects(); //get projects
      //return email template add view wih edit template and project details
      return view('emails.email_templates.add_view', compact('template_details', 'projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End email template postEdit function

  /**
   * Validate user inputs of incoming email template post update request and update email content & email data.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    //get email template edit id
    $template_id = $request->input('id');
    //get email template content edit id
    $content_id = $request->input('email_content_id');
    //get all requested files/attachments
    $files = $request->file('attachments');
    //validate user inputs of edit email template form
    $validator = Validator::make($request->all(), $this->email_template_model->getNewValidationRule($files, $request));
    //set validation attribute names
    $validator->setAttributeNames($this->email_template_model->getValidationAttributes($files));
    $template_details = $this->email_template_model->getTemplateById($template_id);
    if ($validator->fails()) { //form validation fails, if
      $projects = $this->commonGetProjects(); //get projects
      //return email templates add view with validator errors, template and project details
      return view('emails.email_templates.add_view', compact('template_details', 'projects'))->withErrors($validator)->withInputs(['content' => $request->input('content')]);
    }//End validation fails, if
    $data = $this->constructData($request);
    //trim input cc email array
    $trimmedArray = array_filter($data['cc_emails']);
    $data['cc_emails'] = json_encode(array_values($trimmedArray)); //json encoded cc emails
    try {
      $validate_flag = $this->email_template_model->validateEmailTemplate($data['project_id'], $data['type'], $data['language']);
      if(!$validate_flag->isEmpty()):
        //if email template already exists with same project and type
        if($validate_flag[0]->type != $template_details[0]->type || $validate_flag[0]->language != $template_details[0]->language):
          throw new Exception('Exc');
        endif;
      endif;//End email template template already exists, if
    } catch (\Exception $e){
      //return response message if email template already exists with same project and type
      return response()->json(['exc'=>utf8_encode($e->getMessage()), 'custom_message'=>'Email template already exists with this project, emailtype and language.']);
    }//End try-catch
    try {
      //define destination path to upload attachments
      $destinationPath = base_path() . '/public/emails/attachments/';
      //if form data has attachments
      if(count($files) > 1){
        $old_attachments = (array) $validate_flag[0]->email_contents->attachments;
        $files_nos = count($files)-1;
        for ($i=0; $i < $files_nos; $i++) {
          //rename attachment file name
          $file_name = pathinfo($files[$i]->getClientOriginalName(), PATHINFO_FILENAME);
          $file_extension = $files[$i]->getClientOriginalExtension();
          $final_file = $file_name.'_'.time().'.'.$file_extension;
          //move attachment to destination upload path
          $files[$i]->move($destinationPath, $final_file);
          //push uploaded attachment name to attachments array
          $new_attachments[]= $final_file;
        }//End attachments, for loop
        //merge old attachments & new attachments array
        $merged_array = array_merge($old_attachments, $new_attachments);
        //re-index attachments array
        $indexed_array = array_values($merged_array);
        $data['attachments'] = json_encode($indexed_array);//json encoded attachments data
      }//End form data has attachments, if
      $template_data['project_id'] = $data['project_id'];//project id, input
      $template_data['type'] = $data['type'];//template type, input
      $template_data['language'] = $data['language'];//template type, input
      unset($data['addplacement_submit'], $data['project_id'], $data['type'], $data['id'], $data['email_content_id'], $data['language']);
      //update email content data
      $this->email_content_model->updateEmailContent($data, $content_id);
      //update email template data
      $this->email_template_model->updateEmailTemplate($template_data, $template_id);
      //Redirect to email template index page with updated success notice
      return redirect('email-template/index')->with('updated_notice', 'Selected email template has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End email template postUpdate function

  /**
   * Get email template id, delete email template and related attachments.
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function deleteDelete(Request $request){
    try {
      //get email template id to delete attachments
      $delete_id = $request->input('delete_id');
      //delete attachments by email template id
      $this->deleteAttachments($delete_id);
      $email_template_info = $this->email_template_model->getTemplateById($delete_id);
      //delete email template by email template delete id
      $delete_flag = $this->email_template_model->deleteEmailTemplate($delete_id);
      $this->email_content_model->deleteEmailContent($email_template_info[0]->email_content_id);
      //get all available email templates
      $email_templates = $this->email_template_model->getAllEmailTemplates();
      $deleted_notice = "Chosen project has been deleted successfully.";
      //return email templates list view with all email templates
      return view('emails.email_templates.list_view', compact('email_templates'));
    } catch (\Exception $e) {
      $message = "Could not delete email template now. It's being used by email communications. Please edit this instead.";
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => $message]);
    }//End try-catch
  }//End email template delete function

  /**
   * Delete email template attachments by email template.
   * @param  int $id [email template id]
   * @return void
   */
  public function deleteAttachments($id){
    //get email template info by email template id
    $template_info = $this->email_template_model->getTemplateById($id);
    //get all attacments of email template and type cast to array
    $attachments = (array) $template_info[0]->email_contents->attachments;
    if(!empty($attachments)):
      foreach ($attachments as $attachment):
        //define emai attachment path
        $image_url = public_path().'/emails/attachments/'.$attachment;
        //delete attachment
        File::Delete($image_url);
      endforeach;//End attachments, foreach
    endif;//End attachments exist, if
  }//End deleteAttachments function

  /**
   * Delete an attachment by email content.
   * @param  \Illuminate\Http\Request $request
   * @return bool
   */
  public function postDeleteAttachment(Request $request){
    $file = $request->input('file');//attachment filename
    //get email content id
    $email_content_id = $request->input('id');
    echo $this->commonDeleteAttachment($email_content_id, $file);
  }//End postDeleteAttachment function

  /**
   * [getShow description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function getShow($id){
    $email_template_details = $this->email_template_model->getTemplateById($id);
    return view('emails.email_templates.show_view', compact('email_template_details'));
  }

  /**
   * [postShowDeleteAttachment description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postShowDeleteAttachment(Request $request){
    $file = $request->input('file');//attachment filename
    //get email content id
    $email_content_id = $request->input('content_id');
    $delete_flag = $this->commonDeleteAttachment($email_content_id, $file);
    if(!$delete_flag){
      return response()->json(['exc' => utf8_encode('Error while deleting an attachment.')]);
    }
    if($request->input('module_type') == "email_template" && $delete_flag == true){
      $email_content = $this->email_content_model->getContentById($email_content_id);
      return redirect('email-template/show/'.$email_content[0]->email_templates->id)->with('updated_notice', 'Selected attachment has been deleted successfully.');
    }
    $email_info = $this->email_content_model->getContentById($email_content_id);
    return redirect('email/show/'.$email_info[0]->emails->id)->with('updated_notice', 'Selected attachment has been deleted successfully.');

  }//End postShowDeleteAttachment function

  /**
   * [commonDeleteAttachment description]
   * @param  [type] $email_content_id [description]
   * @param  [type] $file             [description]
   * @return [type]                   [description]
   */
  public function commonDeleteAttachment($email_content_id, $file){
    //define attachment file path
    $file_path = public_path().'/emails/attachments/'.$file;
    if($this->checkIfFileExists($file_path)){
      File::Delete($file_path); //delete file
      return $this->email_content_model->updateAttachmentById($email_content_id, $file);
    }//End file exists, if
    return false;
    //update attachments value in database record
  }


}//End Class, EmailTemplateController
