<?php

/**
 * MyController.php
 *
 * Handles common/essentials functionalities and related operations for this project.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Project as Project;
use App\Models\Project_User as ProjectUser;
use App\Models\Contact as Contact;
use App\Models\Partner as Partner;
use App\Models\User as User;
use App\Models\Offer as Offer;
use App\Models\Placement as Placement;
use App\Models\Client as Client;
use App\Models\Channel as Channel;
use App\Models\Country as Country;
use App\Models\Language as Language;
use App\Models\General_Offer_Type_Masterlist as GeneralOTM;
use App\Models\Detailed_Offer_Type_Masterlist as DetailedOT;
use App\Models\Offer_Type_Category_Masterlist as OfferTypeCategory;
use App\Models\Email_Template as EmailTemplate;
use App\Models\Category as Category;
use App\Models\Address as Address;
use App\Models\Email as Email;
use App\Models\Email_Content as EmailContent;
use App\Models\Offer_Report as OfferReport;
use App\Models\Contact_Project as ContactProject;
use App\Models\Contact_Partner as ContactPartner;
use App\Models\Partner_Project as PartnerProject;
use DateTime;
use Exception;
use Validator;
use File;
use Auth;
use Carbon\Carbon as Carbon;
use Config;

class MyController extends Controller
{
  public function __construct()
  {
    $this->my_project_model = new Project;
    $this->my_project_user_model = new ProjectUser;
    $this->my_contact_model = new Contact;
    $this->my_partner_model = new Partner;
    $this->my_user_model = new User;
    $this->my_offer_model = new Offer;
    $this->my_placement_model = new Placement;
    $this->my_client_model = new Client;
    $this->my_channel_model = new Channel;
    $this->my_country_model = new Country;
    $this->my_language_model = new Language;
    $this->my_general_otmodel = new GeneralOTM;
    $this->my_offertypecategory_model = new OfferTypeCategory;
    $this->my_detailed_ot_model = new DetailedOT;
    $this->my_email_template_model = new EmailTemplate;
    $this->my_project_category_model = new Category;
    $this->my_address_model = new Address;
    $this->my_email_model = new Email;
    $this->my_email_content_model = new EmailContent;
    $this->my_offer_report_model = new OfferReport;
    $this->my_contact_project_model = new ContactProject;
    $this->my_contact_partner_model = new ContactPartner;
    $this->my_partner_project_model = new PartnerProject;
    $this->url = Config::get('app.url').'/public';
  }

  /**
   * [constructData description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function constructData($request)
  {
    $post_data = array();
    foreach ($request->input() as $key => $value) {
        $post_data[$key] = $value;
    }
    return $post_data;
  }

  /**
   * [getDateFormat description]
   * @param  [type] $input_date [description]
   * @return [type]             [description]
   */
  public function getDateFormat($input_date){
    return DateTime::createFromFormat('Y-m-d', $input_date)->format('Y-m-d');
  }

  /**
   * [getDateInstance description]
   * @param  [type] $date [description]
   * @return [type]       [description]
   */
  public function getDateInstance($date){
    return DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
  }

  /**
   * [generateRandomCode description]
   * @param  integer $length [description]
   * @return [type]          [description]
   */
  public function generateRandomCode($length = 8) {
    $randstring = '';
    $string_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Zå'));
    for ($i = 0; $i < $length; $i++) {
      $randstring .= $string_array[array_rand($string_array)];
    }
    return $randstring;
  }

  /**
   * Return true if file exists otherwise return false.
   * @param  [string] $file_path
   * @return [bool]
   */
  public function checkIfFileExists($file_path)
  {
    if (File::exists($file_path)):
      return true;
    else:
      return false;
    endif;
  }

  /**
   * Return logged in user's info.
   * @return [object] User
   */
  public function loggedInInfo(){
    return Auth::user();
  }

  /**
   * [getGeneralProjects description]
   * @return [type] [description]
   */
  public function getGeneralProjects(){
    $user = $this->loggedInInfo();
    $project_user = $this->my_project_user_model->getByUserId($user->id);
    // $db_general = $this->getGeneralProjects();
    $project_array = array();
    foreach ($project_user as $value) {
      $project_array[] = $value->projects;
    };
    $projects = $project_array;
    return $projects;
  }

  /**
   * Check whether the logged in user is general or not, return simalar projects.
   * @return [collection object] [projects]
   */
  public function commonGetProjects(){
    // if(Auth::user()->user_type == 'general'):
    if(Auth::user()->user_type == "general" || Auth::user()->user_type =="viewer"):
      return $projects = $this->getGeneralProjects();
    else:
      return $projects = $this->my_project_model->getAllProjects();
    endif;
  }

  /**
   * [replaceEmailBody description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function replaceEmailBody($email){
    $email_body = $email->email_contents->content;
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
      $email_body = str_replace($variable, $value, $email_body);
    endforeach;
    if($email->type != 'activation'){
      $email_body = $this->replaceMicrositeLinks($email, $email_body);
    }
    $email_body = $this->replaceActivationLink($email, $email_body);
    return $email_body;
  }//End replaceEmailBody function

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
   * [replaceMicrositeLinks description]
   * @param  [type] $data  [description]
   * @param  [type] $email [description]
   * @return [type]        [description]
   */
  public function replaceMicrositeLinks($email, $data){
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
      $data = str_replace($variable, $value, $data);
    endforeach;
    return $data;
  }//End replaceMicrositeLinks function

  /**
   * [replaceActivationLink description]
   * @param  [type] $email [description]
   * @param  [type] $data  [description]
   * @return [type]        [description]
   */
  public function replaceActivationLink($email, $data){
    $link = '<a href="'.$this->url.'/offer/activate/'.$email->offers->offer_code.'" target="_blank" style="text-decoration: underline;">'.$this->url.'/offer/activate/'.$email->offers->offer_code.'</a><br>';
    $data = str_replace("{activation_link}", $link, $data);
    return $data;
  }

}//End Class MyController
