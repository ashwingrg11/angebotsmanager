<?php

namespace App\Handlers\Events;

use App\Events\OfferWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Offer as Offer;
use App\Models\Email_Template as EmailTemplate;
use App\Models\Email_Content as EmailContent;
use App\Models\Communication_Package as CommunicationPackage;
use App\Models\Email as Email;
use App\Models\Offer_Report as OfferReport;
use App\Models\Microsite2_Report as Microsite2Report;
use Carbon\Carabon as Carbon;

class CreateMicrosite1Emails
{
  /**
   * Create the event handler.
   *
   * @return void
   */
  public function __construct()
  {
    $this->offer_model = new Offer;
    $this->email_template_model = new EmailTemplate;
    $this->email_content_model = new EmailContent;
    $this->email_model = new Email;
    $this->communication_package_model = new CommunicationPackage;
    $this->offer_report_model = new OfferReport;
    $this->microsite2_report_model = new Microsite2Report;
  }

  /**
   * Handle the event.
   *
   * @param  OfferWasCreated  $event
   * @return void
   */
  public function handle(OfferWasCreated $event)
  {
    $offer_info = $this->offer_model->getOfferById($event->offer_id);
    $project_id = $offer_info[0]->project_id;
    $valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_from);
    $old_valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_from);
    $valid_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_until);
    $original_valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_from);
    $original_valid_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_until);
    $dates = array();
    $commn_package = $this->communication_package_model->getCommnPackageByProject($project_id);
    if($commn_package->isEmpty()){
      $mc1_monthly_days = 1;
      $mc2_final_days = 1;
      $mc2_reminder_days = 1;
    }else{
      $mc1_monthly_days = $commn_package[0]->microsite1_monthly;
      $mc2_final_days = $commn_package[0]->microsite2_final;
      $mc2_reminder_days = $commn_package[0]->microsite2_reminder;
    }
    if($original_valid_from->format('Y') == $original_valid_until->format('Y') && $original_valid_from->format('m') == $original_valid_until->format('m')){
      $valid_from->modify('first day of next month');
      $new = $valid_from;
      $new->day = 1;
      $dates[] = $new->format('Y-m-d');
    }
    else{
      while ($valid_from <= $valid_until) {
        $valid_from->modify('first day of next month');
        $new = $valid_from;
        $new->day = 1;
        $dates[] = $new->format('Y-m-d');
      }
    }
    $mc2_final = end($dates);
    $mc2_final = end($dates);
    $mc1_dates = $report_dates = $dates;
    if(count($mc1_dates) > 1){
      array_pop($mc1_dates);
    }
    $final_mc1_dates = array();
    foreach ($mc1_dates as $mc1_date) {
      $mc1 = \Carbon\Carbon::createFromFormat('Y-m-d', $mc1_date);
      $mc1->day = $mc1_monthly_days;
      $final_mc1_dates[] = $mc1->format('Y-m-d');
    }
    $mc2_final_date = \Carbon\Carbon::createFromFormat('Y-m-d', $mc2_final);
    $mc2_final_date->day = $mc2_final_days;
    $mc2_final_date = $mc2_final_date->format('Y-m-d');
    $mc2_reminder_date = \Carbon\Carbon::createFromFormat('Y-m-d', $mc2_final);
    $mc2_reminder_date->day = $mc2_final_days + $mc2_reminder_days;
    $mc2_reminder_date = $mc2_reminder_date->format('Y-m-d');
    $template_language = "en";
    if($offer_info[0]->contacts->language_id == 2){
      $template_language = "de";
    }
    if($offer_info[0]->has_communication_package == "yes"):
      if($original_valid_from->format('Y') == $original_valid_until->format('Y') && $original_valid_from->format('m') == $original_valid_until->format('m')){
        $this->commonEmailCreation($mc2_final_date, $offer_info, 'microsite2_final', $template_language);
        $this->commonEmailCreation($mc2_reminder_date, $offer_info, 'microsite2_reminder', $template_language);
      }
      else{
        $this->createMicrosite1Emails($final_mc1_dates, $offer_info, $report_dates, $template_language);
        $this->commonEmailCreation($mc2_final_date, $offer_info, 'microsite2_final', $template_language);
        $this->commonEmailCreation($mc2_reminder_date, $offer_info, 'microsite2_reminder', $template_language);
      }
    endif;

  }


  /**
   * [sendMicrosite1Emails description]
   * @return [type] [description]
   */
  public function createMicrosite1Emails($send_dates, $offer_info, $report_dates, $template_language){
    $template_flag = $this->email_template_model->validateEmailTemplate($offer_info[0]->project_id, "microsite1", $template_language);
    $date_sn = 0;
    foreach ($send_dates as $send_date) {
      if($template_flag->isEmpty()):
        $template_id = NULL;
        $new_content = [];
        $new_content['receiver_email'] = $offer_info[0]->contacts->email;
        $new_content['sender_email'] = $offer_info[0]->ji_contact->email;
      else:
        $template_id = $template_flag[0]->id;
        $content = $template_flag[0]->email_contents;
        $new_content['sender_email'] = $content->sender_email;
        $new_content['receiver_email'] = $offer_info[0]->contacts->email;
        $new_content['cc_emails'] = $content->cc_emails;
        $new_content['subject'] = $content->subject;
        $new_content['content'] = $content->content;
        $new_content['attachments'] = $content->attachments;
      endif;
      $insert_content = $this->email_content_model->insertEmailContent($new_content);
      $content_id = $insert_content->id;
      // $report_date = $send_date;
      $report_date = $report_dates[$date_sn];
      $report_date = \Carbon\Carbon::createFromFormat('Y-m-d', $report_date);
      $report_date = $report_date->subMonth();
      $report_flag = $this->offer_report_model->getReportByDateType($report_date, $offer_info[0]->id, 'microsite1');
      // $report_flag = $this->verifyReportFlag($report_date, $offer_info, 'microsite1');
      $email_data = ['email_template_id' => $template_id, 'email_content_id' => $content_id, 'offer_id' => $offer_info[0]->id, 'send_date' => $send_date, 'confirm_send' => 1, 'type' => 'microsite1', 'offer_report_id' => $report_flag[0]->id];
      $this->email_model->insertEmail($email_data);
      $date_sn++;
    }

  }

  /**
   * [commonEmailCreation description]
   * @param  [type] $send_date  [description]
   * @param  [type] $offer_info [description]
   * @return [type]             [description]
   */
  public function commonEmailCreation($send_date, $offer_info, $email_type, $template_language){
    $template_flag = $this->email_template_model->validateEmailTemplate($offer_info[0]->project_id, $email_type, $template_language);
    if($template_flag->isEmpty()):
      $template_id = NULL;
      $new_content = [];
      $new_content['receiver_email'] = $offer_info[0]->contacts->email;
      // $new_content['sender_email'] = "ashwin.gurung@cloudyfox.com";
      $new_content['sender_email'] = $offer_info[0]->ji_contact->email;
      $new_content['sender_email'] = $offer_info[0]->ji_contact->email;
    else:
      $template_id = $template_flag[0]->id;
      $content = $template_flag[0]->email_contents;
      $new_content['sender_email'] = $content->sender_email;
      $new_content['receiver_email'] = $offer_info[0]->contacts->email;
      $new_content['cc_emails'] = $content->cc_emails;
      $new_content['subject'] = $content->subject;
      $new_content['content'] = $content->content;
      $new_content['attachments'] = $content->attachments;
    endif;
    $insert_content = $this->email_content_model->insertEmailContent($new_content);
    $content_id = $insert_content->id;
    $report_date = $send_date;
    $report_date = \Carbon\Carbon::createFromFormat('Y-m-d', $report_date);
    $report_date = $report_date->subMonth();
    if($email_type == 'microsite2_final' || $email_type == 'microsite2_reminder'){
      $report_flag = $this->offer_report_model->getReportByDateType($report_date, $offer_info[0]->id, 'microsite2');
      // $report_flag = $this->verifyReportFlag($report_date, $offer_info, 'microsite2');
    }else{
      // $report_flag = $this->verifyReportFlag($report_date, $offer_info, 'microsite1');
      $report_flag = $this->offer_report_model->getReportByDate($report_date, $offer_info[0]->id);
    }
    $email_data = ['email_template_id' => $template_id, 'email_content_id' => $content_id, 'offer_id' => $offer_info[0]->id, 'send_date' => $send_date, 'confirm_send' => 1, 'type' => $email_type, 'offer_report_id' => $report_flag[0]->id];
    if($email_type == 'microsite2_reminder'):
      $email_data['confirm_send'] = false;
    endif;
    $this->email_model->insertEmail($email_data);
  }

  /**
   * [verifyReportFlag description]
   * @param  [type] $report_date [description]
   * @param  [type] $offer_info  [description]
   * @param  [type] $type        [description]
   * @return [type]              [description]
   */
  public function verifyReportFlag($report_date, $offer_info, $type){
    $report_flag = $this->offer_report_model->getReportByDateType($report_date, $offer_info[0]->id, $type);
    /*if($report_flag->isEmpty()){
      $report_data = $this->offer_report_model->insertReportByDateType($report_date, $offer_info[0]->id, $type);
      if($type == 'microsite2_final' || $type == 'microsite2_reminder'){
        $this->microsite2_report_model->createNew(['offer_report_id' => $report_data->id]);
      }
      $return_data[0] = $report_data;
      return $return_data;
    }*/
    return $report_flag;
  }


}
