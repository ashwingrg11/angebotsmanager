<?php

namespace App\Handlers\Events;

use Mail;
use App\Events\OfferExtension;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Offer_Report as OfferReport;
use App\Models\Offer as Offer;
use App\Models\Email_Content as EmailContent;
use App\Models\Email_Template as EmailTemplate;
use Carbon\Carbon as Carbon;
use Config;


class SendOfferExtensionEmail
{
  public $offer_report_model;
  public $offer_model;
  /**
   * Create the event handler.
   *
   * @return void
   */
  public function __construct()
  {
    $this->offer_report_model = new OfferReport;
    $this->offer_model = new Offer;
    $this->email_content_model = new EmailContent;
    $this->email_template_model = new EmailTemplate;
    $this->url = Config::get('app.url').'/public';
  }

  /**
   * Handle the event.
   *
   * @param  OfferExtension  $event
   * @return void
   */
  public function handle(OfferExtension $event)
  {
    $report_code = $event->data;
    $report_info = $this->offer_report_model->getReportByCode($report_code);
    $offer_info = $this->offer_model->getOfferById($report_info[0]->offer_id);
    $receiver_email = $offer_info[0]->ji_contact->email;
    $project_id = $offer_info[0]->project_id;
    $template_language = "en";
    if($offer_info[0]->contacts->language_id == 2){
      $template_language = "de";
    }
    $email_template = $this->email_template_model->templateByProject($project_id, 'offer_extension', $template_language);
    if($email_template->isEmpty()):
      $email_data['subject'] = 'Offer Extension Request!';
      $email_data['content'] = null;
      $email_data['cc_emails'] = null;
      $email_data['attachments'] = null;
    else:
      $email_data['subject'] = $email_template[0]->email_contents->subject;
      $email_data['content'] = $email_template[0]->email_contents->content;
      $email_data['cc_emails'] = $email_template[0]->email_contents->cc_emails;
      $email_data['content'] = $this->replaceVariables($email_data, $offer_info);
      $email_data['attachments'] = $email_template[0]->email_contents->attachments;
    endif;
    $email_data['receiver_email'] = $receiver_email;
    $email_data['content'] = $this->replaceActivationLink($offer_info, $email_data['content']);
    Mail::send('emails.offers.offer_extension', ['offer_info' => $offer_info, 'email_data' => $email_data], function ($message) use ($offer_info, $email_data) {
      // $message->from($offer_info[0]->contacts->email, $offer_info[0]->contacts->first_name.' '.$offer_info[0]->contacts->last_name);
      $message->to($email_data['receiver_email'])->subject($email_data['subject']);
      if(!empty($email_data['cc_emails'])){
        foreach ($email_data['cc_emails'] as $cc_email) {
          $message->cc($cc_email, '');
        }
      }
      if(!empty($email_data['attachments'])){
        foreach($email_data['attachments'] as $attachment){
          $message->attach(public_path().'/emails/attachments/'.$attachment);
        }
      }
    });
    // $this->updateOfferReport($report_info);
  }//End handle function

  /**
   * [replaceVariables description]
   * @param  array $email_data
   * @param  collection object $offer
   * @return array
   */
  public function replaceVariables($email_data, $offer){
    $first_placement = $this->getFirstOfferPlacement($offer);
    $placement_start_date = $placement_title = null;
    if($first_placement){
      $placement_start_date = isset($first_placement->pivot->placement_start_date)? $first_placement->pivot->placement_start_date : null;
      $placement_title = isset($first_placement->title)? $first_placement->title : null;
    }
    $variables = [
      '{greeting}' => $offer[0]->contacts->greeting,
      '{first_name}' => $offer[0]->contacts->first_name,
      '{last_name}' => $offer[0]->contacts->last_name,
      '{company}' => $offer[0]->contacts->company,
      '{offer_title}' => $offer[0]->title_en,
      '{project}' => $offer[0]->projects->name,
      '{valid_from}' => Carbon::createFromFormat('d/m/Y', $offer[0]->valid_from)->format('Y-m-d'),
      '{valid_until}' => Carbon::createFromFormat('d/m/Y', $offer[0]->valid_until)->format('Y-m-d'),
      '{first_placement_start_date}' => $placement_start_date,
      '{first_placement_name}' => $placement_title,
    ];
    if($offer[0]->contacts->language_id == 2){
      $variables['{offer_title}'] = $offer[0]->title_de;
    }
    foreach ($variables as $variable=>$value):
      $email_data['content'] = str_replace($variable, $value, $email_data['content']);
    endforeach;
    return $email_data['content'];
  }//End replaceVariables function

  /**
   * [updateOfferReport description]
   * @return [type] [description]
   */
  public function updateOfferReport($report_info){
    $data = ['extension_request' => 'yes'];
    $this->offer_report_model->updateOfferReport($report_info[0]->id, $data);
  }

  /**
   * [replaceActicationLink description]
   * @return [type] [description]
   */
  public function replaceActivationLink($offer_info, $data){
    $link = '<a href="'.$this->url.'/offer/activate/'.$offer_info[0]->offer_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/offer/activate/'.$offer_info[0]->offer_code.'</a><br>';
    $data = str_replace("{activation_link}", $link, $data);
    return $data;
  }

  /**
   * [getFirstOfferPlacement description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function getFirstOfferPlacement($offer){
    $offer_placements = $offer[0]->placements;
    $sn = 1;
    $flag_date = $first_placement = false;
    foreach ($offer_placements as $key => $value) {
      if($sn == 1){
        $flag_date = $value->pivot->placement_start_date;
        $first_placement = $value;
      }
      if($value->pivot->placement_start_date < $flag_date ){
        $flag_date = $value->pivot->placement_start_date;
        $first_placement = $value;
      }
      $sn++;
    }
    return $first_placement;
  }

}
