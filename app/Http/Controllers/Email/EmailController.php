<?php

/**
 * EmailController.php
 *
 * Handles email module's functionalities and related operations.
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
use App\Models\Email as Email;
use App\Models\Email_Content as EmailContent;
use App\Models\Offer_Report as OfferReport;
use Exception;
use Validator;
use Carbon\Carbon as Carbon;

class EmailController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Email Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the functionalities/management related to
  | email module. It includes email, email_content and offer report
  | models for it's operations.
  |
  */
  public $email_model;
  public $email_content_model;

  /**
   * [__construct description]
   */
  public function __construct(){
    parent::__construct();
    $this->email_model = new Email;
    $this->email_content_model = new EmailContent;
    $this->offer_report_model = new OfferReport;
  }

  /**
   * Return emails view page to list all queue emails as per logged in user.
   * @return mixed
   */
  public function getQueueEmails(){
    try {
      //all available queue emails
      $queue_emails = $this->email_model->allQueueEmails();
      if(\Auth::user()->user_type == "general"){
        //projects as per logged in general user
        $projects_user = $this->commonGetProjects();
        //queue emails as per logged in general user
        $queue_emails = $this->email_model->getQueueEmailsByProject($projects_user);
        //return list queue emails view with queue emails data
        return view('emails.list_queue_emails', compact('queue_emails'));
      }//End user_type is general, if
      return view('emails.list_queue_emails', compact('queue_emails'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getQueueEmails function

  /**
   * Return view to list all emails that have been sent.
   * @return \Illuminate\Http\Response
   */
  public function getSentEmails(){
    try {
      //all sent emails
      $sent_emails = $this->email_model->allSentEmails();
      if(\Auth::user()->user_type == "general"){
        //projects as per logged in general user
        $projects_user = $this->commonGetProjects();
        //sent emails emails as per logged in general user
        $sent_emails = $this->email_model->sentEmailsByProject($projects_user);
      }//End if
      //return list sent emails view with sent emails data
      return view('emails.list_sent_emails', compact('sent_emails'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getSentEmails function

  /**
   * Get queue email edit id, return edit view and data of provided email details.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postEdit(Request $request){
    try {
      //get queue email edit id
      $email_id = $request->input('post_id');
      //get email details as per provided email id
      $email_details = $this->email_model->getEmailById($email_id);
      //return edit queue email view with email and it's report details
      return view('emails.edit_queue_email', compact('email_details', 'email_report'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEdit function

  /**
   * Validate incoming queue email post update request and update email's data if all data are valid.
   * @param  \Illuminate\Http\Request $request [queue email post update request]
   * @return \Illuminate\Http\Response
   */
  public function postUpdate(Request $request){
    //edit queue email id
    $email_id = $request->input('id');
    //edit email queue- email content id
    $email_content_id = $request->input('email_content_id');
    $files = $request->file('attachments'); //attachments
    //validate user inputs
    $validator = Validator::make($request->all(), $this->email_model->getEditValidationRule($files));
    //set validation attribute names
    $validator->setAttributeNames($this->email_model->getValidationAttributes($files));
    if ($validator->fails()) {
      $email_details = $this->email_model->getEmailById($email_id);
      $email_report = $this->offer_report_model->firstReportByOffer($email_details[0]->offer_id);
      return view('emails.edit_queue_email', compact('email_details', 'email_report'))->withErrors($validator)->withInputs(['content'=>$request->input('content')]);
    };//End validator fails, if
    $data = $this->constructData($request);
    $trimmedArray = array_filter($data['cc_emails']); //cc_emails, filtered
    $data['cc_emails'] = json_encode(array_values($trimmedArray)); //cc_emails, json encoded
    $send_date = Carbon::createFromFormat('Y-m-d', $data['send_date'])->toDateTimeString();
    try {
      //destination path to upload attachments
      $destinationPath = base_path() . '/public/emails/attachments/';
      //if attachment(s)
      if(count($files) > 1){
        //get email content id of edit queue email
        $content = $this->email_content_model->getContentById($email_content_id);
        //get old attachments data of queue email
        $old_attachments = (array) $content[0]->attachments; //type cast array
        $files_nos = count($files)-1;
        for ($i=0; $i < $files_nos; $i++) {
          $file_name = pathinfo($files[$i]->getClientOriginalName(), PATHINFO_FILENAME);
          $file_extension = $files[$i]->getClientOriginalExtension();
          $final_file = $file_name.'_'.time().'.'.$file_extension;
          //save file to destination attachment upload path
          $files[$i]->move($destinationPath, $final_file);
          //push uploaded attachment's filename to attachments array
          $new_attachments[]= $final_file;
        }//End attachments upload, for loop
        //merge array, old and new attachments
        $merged_array = array_merge($old_attachments, $new_attachments);
        //re-index array of attachments
        $indexed_array = array_values($merged_array);
        $data['attachments'] = json_encode($indexed_array);//json encoded, attachments
      }//End email has attachments, if
      //update email data
      $this->email_model->updateEmail($email_id, ['send_date' => $send_date]);
      unset($data['send_date'], $data['id'], $data['email_content_id'], $data['addplacement_submit']);
      //update queue email content data
      $this->email_content_model->updateEmailContent($data, $email_content_id);
      //Redirect to queue-emails page with updated success notice
      return redirect('email/queue-emails')->with('updated_notice', 'Selected queue email details has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get email delete id and delete the same email if valid.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminiate\Http\Response
   */
  public function deleteDelete(Request $request){
    try {
      $email_id = $request->input('delete_id'); //email id
      //get email info by email id
      $email_info = $this->email_model->getEmailById($email_id);
      //email content data of email
      $email_content_id = $email_info[0]->email_content_id;
      $this->email_model->deleteEmail($request->input('delete_id'));
      //delete email content of deleted email
      $this->email_content_model->deleteEmailContent($email_content_id);
      $deleted_notice = "Chosen email has been deleted successfully.";
      //if deleted email status was pending
      if($email_info[0]->status == 'pending'):
        //get all available queue emails
        $queue_emails = $this->email_model->allQueueEmails();
        //return list queue emails view with queue email records and deleted success notice
        return view('emails.list_queue_emails', compact('queue_emails', 'deleted_notice'));
      endif;//End, email status is pending, if
      //get all available sent emails
      $sent_emails = $this->email_model->allSentEmails();
      //return view with sent email recrods and deleted success notice
      return view('emails.list_sent_emails', compact('sent_emails', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this email because it is being used by other modules.']);
    }//End try-catch
  }//End email delete function

  /**
   * Change pending email confirmation status to true.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEnableConfirm(Request $request){
    try {
      return $this->emailConfirmation($request, 1);
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEnableConfirm function

  /**
   * Change pending email confirmation status to false.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate|http\Response
   */
  public function postDisableConfirm(Request $request){
    try {
      return $this->emailConfirmation($request, 0);
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postDisableConfirm function

  /**
   * Change pending/queue email's confirmation status.
   * @param  \Illuminate\Http\Request $request
   * @param  bool $confirm_status
   * @return \Illuminate\Http\RedirectResponse
   */
  public function emailConfirmation($request, $confirm_status){
    //get pending/queue email id
    $email_id = $request->input('post_id');
    //query to update email status to true or false
    $this->email_model->updateEmail($email_id, ['confirm_send' => $confirm_status]);
    //Redirect to queue-emails page with email status changed success notice
    return redirect('email/queue-emails')->with('updated_notice', 'Email confirmation status has been updated successfully.');
  }//End emailConfirmation funtion

  /**
   * Return all queue emails by provided calendar date.
   * @param string $date
   * @return \Illuminate\Http\Response
   */
  public function getMonthlyQueueEmails($date){
    try {
      //get all monthly queue emails by requested date
      $queue_emails = $this->email_model->monthlyQueueEmails($date);
      if(\Auth::user()->user_type == "general"){
        //get all projects as per logged in general user
        $projects_user = $this->commonGetProjects();
        //get monthly queue emails by logged in user's projects
        $queue_emails = $this->email_model->monthlyQueueEmailsByProject($date, $projects_user);
        return view('emails.list_queue_emails_partial', compact('queue_emails'));
      }//End logged in user type is general, if
      return view('emails.list_queue_emails_partial', compact('queue_emails'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getMonthlyQueueEmails function

  /**
   * [getShow description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function getShow($email_id){
    $email_details = $this->email_model->getEmailById($email_id);
    // print_r($email_details[0]->email_contents->content);
    if($email_details[0]->email_contents->content){
      // print_r($email_details[0]->email_contents->content);
      $email_details[0]->email_contents->content = $this->replaceEmailBody($email_details[0]);
    }
    return view('emails.show_view', compact('email_details'));
  }

  /**
   * [getDownloadAttachment description]
   * @param  [type] $email_content_id [description]
   * @param  [type] $image            [description]
   * @return [type]                   [description]
   */
  public function getDownloadAttachment($email_content_id, $image){
    //define attachment file path
    $file_path = public_path().'/emails/attachments/'.$image;
    $email_content = $this->email_content_model->getContentById($email_content_id);
    if($email_content->isEmpty()):
      return response()->json(['exc' => utf8_encode('Invalid Request, please proceed with valid request.')]);
    endif;
    $attachments = (array) $email_content[0]->attachments;
    if($this->checkIfFileExists($file_path) && in_array($image, $attachments)){
      return response()->download($file_path);
    }//End file exists, if
    return response()->json(['exc' => utf8_encode('Invalid Request, please proceed with valid request.')]);
  }//End getDownloadAttachment function

}//End Class, EmailController
