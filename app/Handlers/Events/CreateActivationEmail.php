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
use Carbon\Carabon as Carbon;

class CreateActivationEmail
{
  public $offer_model;
  public $email_template_model;
  public $email_content_model;
  public $email_model;
  public $communication_package_model;
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
    $template_language = "en";
    if($offer_info[0]->contacts->language_id == 2){
      $template_language = "de";
    }
    $send_date = $this->getEmailSendDate($offer_info[0]->placements, $offer_info[0]->project_id);
    $project_id = $offer_info[0]->project_id;
    $template_flag = $this->email_template_model->validateEmailTemplate($project_id, "activation", $template_language);
    if($template_flag->isEmpty()):
      $template_id = NULL;
      $new_content = [];
      $new_content['receiver_email'] = $offer_info[0]->contacts->email;
      // $new_content['sender_email'] = "ashwin.gurung@cloudyfox.com";
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
    $email_data = ['email_template_id' => $template_id, 'email_content_id' => $content_id, 'offer_id' => $event->offer_id, 'send_date' => $send_date, 'confirm_send' => 1, 'type' => 'activation'];
    $this->email_model->insertEmail($email_data);
  }

  /**
   * [getEmailSendDate description]
   * @param  [type] $offer_placements [description]
   * @return [type]                   [description]
   */
  public function getEmailSendDate($offer_placements, $project_id){
    $start_date = \Carbon\Carbon::now()->format('Y-m-d');
    $sn = 1;
    foreach ($offer_placements as $key => $placement) {
      $format_date = \Carbon\Carbon::createFromFormat('d/m/Y', $placement->start_date)->format('Y-m-d');
      if($sn == 1 && $format_date > $start_date):
        $start_date = $format_date;
      endif;
      if($sn != 1 && $format_date < $start_date):
        $start_date = $format_date;
      endif;
      $sn++;
    }
    $commn_package = $this->communication_package_model->getCommnPackageByProject($project_id);
    if($start_date != \Carbon\Carbon::now()->format('Y-m-d')):
      if(!$commn_package->isEmpty()):
        $activation_days = $commn_package[0]->activation;
        $new_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->subDays($activation_days);
      else:
        $new_date = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->subDays(5);
      endif;
    else:
      $new_date = $start_date;
    endif;
    if($new_date < \Carbon\Carbon::now()->format('Y-m-d')):
      $new_date = \Carbon\Carbon::now()->format('Y-m-d');
    endif;
    return $new_date;
  }
}
