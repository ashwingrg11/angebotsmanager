<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Email as Email;
use App\Models\Offer as Offer;
use App\Models\Email_Content as EmailContent;
use Carbon\Carbon as Carbon;
use Mail;
use URL;
use Illuminate\Routing\UrlGenerator;
use Config;

class SendEmails extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'emails:send';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send queue emails.';
  private $email_model;
  private $email_content_model;
  private $url;
  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(UrlGenerator $url)
  {
    parent::__construct();
    $this->email_model = new Email;
    $this->email_content_model = new EmailContent;
    $this->offer_model = new Offer;
    $this->url = Config::get('app.url').'/public';
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $emails = $this->email_model->tobeSentEmails();
    if(!$emails->isEmpty()):
      foreach ($emails as $key => $email):
        $send_date = Carbon::createFromFormat('d/m/Y', $email->send_date)->format('Y-m-d');
        $today_date = Carbon::now()->format('Y-m-d');
        if($send_date == $today_date):
          $data['sender_email'] = $email->email_contents->sender_email;
          $data['receiver_email'] = $email->email_contents->receiver_email;
          $data['subject'] = $email->email_contents->subject;
          $data['content'] = $email->email_contents->content;
          $data['attachments'] = $email->email_contents->attachments;
          $data['cc_emails'] = $email->email_contents->cc_emails;
          $data['offer_code'] = $email->offers->offer_code;
          $data['type'] = $email->type;
          switch ($data['type']) {
            case 'activation':
              $data['view'] = "emails.offers.offer_activation";
              $link = '<a href="'.$this->url.'/offer/activate/'.$data['offer_code'].'" target="_blank" style="text-decoration: underline;">'.$this->url.'/offer/activate/'.$data['offer_code'].'</a><br>';
              $data['content'] = str_replace("{activation_link}", $link, $data['content']);
              $data['content'] = $this->replaceCommonVariables($data, $email);
              break;
            case 'microsite1':
              $data['view'] = "emails.offers.offer_microsite1";
              $data['content'] = $this->replaceCommonVariables($data, $email);
              $data['content'] = $this->replaceMicrositeLinks($data, $email);
              break;
            case 'microsite2_final':
              $data['view'] = "emails.offers.offer_microsite2";
              $data['content'] = $this->replaceCommonVariables($data, $email);
              $data['content'] = $this->replaceMicrositeLinks($data, $email);
              break;
            case 'microsite2_reminder':
              $data['view'] = "emails.offers.offer_microsite2";
              $data['content'] = $this->replaceCommonVariables($data, $email);
              $data['content'] = $this->replaceMicrositeLinks($data, $email);
              break;
            default:
              # code...
              break;
          }
          $send_flag = true;
          if($data['type'] == 'microsite2_reminder') {
            if ($email->offer_reports->filled_in == "yes") {
              $send_flag = false;
            }
          }
          if ($send_flag) {
            // echo "send me";
            $data['content'] = $this->replaceActivationLink($email, $data['content']);
            $this->sendMail($data, $email);
          }
        endif;
      endforeach;
    endif;
  }

  /**
   * [send an email and update email status from pending to sent]
   * @param  [array] $data [all necessary data for an email]
   * @param  [object] $email
   * @return void
   */
  public function sendMail($data, $email){
    Mail::send($data['view'], ['data' => $data], function ($message) use ($data) {
      // $message->from($data['sender_email']);
      $message->to($data['receiver_email'])->subject($data['subject']);
      if(!empty($data['cc_emails'])){
        foreach ($data['cc_emails'] as $cc_email) {
          $message->cc($cc_email, '');
        }
      }
      if(!empty($data['attachments'])){
        foreach($data['attachments'] as $attachment){
          $message->attach(public_path().'/emails/attachments/'.$attachment);
        }
      }
    });
    $this->email_model->updateEmail($email->id, ['status' => 'sent']);
    $this->email_content_model->updateEmailContent(['content' => $data['content']], $email->email_contents->id);
    if($email->type == "activation"){
      $this->offer_model->updateOffer($email->offers->id, ['activation_email' => 'sent']);
    }
  }

  /**
   * [replaceCommonVariables description]
   * @param  [type] $data  [description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function replaceCommonVariables($data, $email){
    $first_placement = $this->getFirstOfferPlacement($email);
    $placement_start_date = $placement_title = null;
    if($first_placement){
      $placement_start_date = isset($first_placement->pivot->placement_start_date)? $first_placement->pivot->placement_start_date : null;
      $placement_title = isset($first_placement->title)? $first_placement->title : null;
    }
    $common_variables = [
      '{greeting}' => $email->offers->contacts->greeting,
      '{first_name}' => $email->offers->contacts->first_name,
      '{last_name}' => $email->offers->contacts->last_name,
      '{company}' => $email->offers->contacts->company,
      '{offer_title}' => $email->offers->title_en,
      '{project}' => $email->offers->projects->name,
      '{valid_from}' => Carbon::createFromFormat('d/m/Y', $email->offers->valid_from)->format('Y-m-d'),
      '{valid_until}' => Carbon::createFromFormat('d/m/Y', $email->offers->valid_until)->format('Y-m-d'),
      '{first_placement_start_date}' => $placement_start_date,
      '{first_placement_name}' => $placement_title,
    ];
    if($email->offers->contacts->language_id == 2){
      $common_variables['{offer_title}'] = $email->offers->title_de;
    }
    foreach ($common_variables as $variable=>$value):
      $data['content'] = str_replace($variable, $value, $data['content']);
    endforeach;
    return $data['content'];
  }

  /**
   * [replaceMicrositeLinks description]
   * @param  [type] $data  [description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function replaceMicrositeLinks($data, $email){
    $common_links = ['{microsite1_link_en}', '{microsite1_link_de}', '{microsite2_link_en}', '{microsite2_link_de}'];
    $email_report_code = $email->offer_reports->report_code;
    $microsite1_en = $microsite1_de = $microsite2_en = $microsite2_de = null;
    foreach ($common_links as $value) {
      switch ($value) {
        case '{microsite1_link_en}':
          $microsite1_en = '<a href="'.$this->url.'/microsite/en/1/'.$email_report_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/microsite/en/1/'.$email_report_code.'</a><br>';
          break;
        case '{microsite1_link_de}':
          $microsite1_de = '<a href="'.$this->url.'/microsite/de/1/'.$email_report_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/microsite/de/1/'.$email_report_code.'</a><br>';
          break;
        case '{microsite2_link_en}':
          $microsite2_en = '<a href="'.$this->url.'/microsite/en/2/'.$email_report_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/microsite/en/2/'.$email_report_code.'</a><br>';
          break;
        case '{microsite2_link_de}':
          $microsite2_de = '<a href="'.$this->url.'/microsite/de/2/'.$email_report_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/microsite/de/2/'.$email_report_code.'</a><br>';
          break;
        default:
          break;
      }
    }
    $microsite_links = [
      '{microsite1_link_en}' => $microsite1_en,
      '{microsite1_link_de}' => $microsite1_de,
      '{microsite2_link_en}' => $microsite2_en,
      '{microsite2_link_de}' => $microsite2_de
    ];
    foreach ($microsite_links as $variable=>$value):
      $data['content'] = str_replace($variable, $value, $data['content']);
    endforeach;
    return $data['content'];
  }

  /**
   * [getFirstOfferPlacement description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function getFirstOfferPlacement($email){
    $offer_placements = $email->offers->placements;
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

  /**
   * [replaceActicationLink description]
   * @return [type] [description]
   */
  public function replaceActivationLink($email, $data){
    $link = '<a href="'.$this->url.'/offer/activate/'.$email->offers->offer_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/offer/activate/'.$email->offers->offer_code.'</a><br>';
    $data = str_replace("{activation_link}", $link, $data);
    return $data;
  }

}//End Class, SendEmails
