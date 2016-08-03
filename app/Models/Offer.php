<?php

/**
 * Offer.php
 *
 * Handles general offers database management and related operations.
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
use Carbon\Carbon as Carbon;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;

class Offer extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'offers';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'partner_id', 'contact_id', 'ji_contact_id', 'offer_type_category_masterlist_id', 'general_offer_type_masterlist_id', 'detailed_offer_type_masterlist_id', 'contract_status_id', 'country_id', 'title_en', 'title_de', 'location', 'valid_from', 'valid_until', 'street1', 'street2', 'street3', 'postal_code', 'city', 'county', 'state', 'market', 'price_en', 'price_de', 'offer_en', 'offer_de', 'benefit_en', 'benefit_de', 'further_information_en', 'further_information_de', 'reservation_telephone', 'reservation_fax', 'reservation_email', 'reservation_url', 'how_to_book_en', 'how_to_book_de', 'exclusive_advantage', 'note', 'editorial_note', 'microsite1', 'microsite2', 'has_communication_package', 'offer_code', 'activation_flag', 'activation_email'];
  /**
   * [$new_offer_validation_rules description]
   * @var [type]
   */
  public $new_offer_validation_rules = [
    'project_id' => 'required',
    'placement_ids' => 'required',
    'partner_id' => 'required',
    'contact_id' => 'required',
    'ji_contact_id' => 'required',
    'title_en' => 'required',
    'title_de' => 'required',
    'valid_from' => 'required|date_format:Y-m-d',
    'valid_until' => 'required|date_format:Y-m-d|after:valid_from',
    'has_communication_package' => 'required'
  ];
  /**
   * [$attributeNames description]
   * @var [type]
   */
  public $validation_attribute = [
    'project_id' => 'project',
    'placement_ids' => 'placement',
    'partner_id' => 'offer partner',
    'contact_id' => 'partner contact',
    'contract_status_id' => 'contract status',
    'ji_user_ids' => 'JI user',
    'ji_contact_id' => 'JI contact',
    'title_en' => 'title(en)',
    'title_de' => 'title(de)',
    'title' => 'offer title',
    'offer_en' => 'offer(en)',
    'offer_de' => 'offer(de)',
    'benefit_en' => 'privilege/benefit(en)',
    'benefit_de' => 'privilege/benefit(de)',
    'has_communication_package' => 'communication package',
  ];

  /**
   * [$export_call_center_validation description]
   * @var [type]
   */
  public $export_call_center_validation = [
    'project' => 'required',
    'language' => 'required',
    'date_from' => 'required|date_format:Y-m-d',
    'date_until' => 'required|date_format:Y-m-d|after:date_from'
  ];

  /**
   * [$export_call_center_validation description]
   * @var [type]
   */
  public $export_offer_reporting_validation = [
    'project' => 'required',
    'date_from' => 'required|date_format:m-Y',
    'date_until' => 'required|date_format:m-Y|after:date_from'
  ];

  /**
   * [$all_contacts_export_validation description]
   * @var [type]
   */
  public $all_contacts_export_validation = [
    'project_ids' => 'required',
    'placement_ids' => 'required'
  ];

  /**
   * [$all_contacts_export_attributes description]
   * @var [type]
   */
  public $all_contacts_export_attributes = [
    'project_ids' => 'project(s)',
    'placement_ids' => 'placement(s)'
  ];

  /**
   * [$search_keys description]
   * @var [type]
   */
  public $search_keys = ['title_de', 'offer_en', 'offer_de', 'benefit_en', 'benefit_de', 'price_en', 'price_de', 'further_information_de', 'further_information_en', 'valid_from', 'valid_until', 'exclusive_advantage', 'reservation_telephone', 'reservation_fax', 'reservation_email', 'reservation_url', 'how_to_book_de', 'how_to_book_en', 'editorial_note', 'postal_code', 'street1', 'street2', 'street3', 'market', 'city', 'state', 'note'];

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [placements description]
   * @return [type] [description]
   */
  public function placements(){
    return $this->belongsToMany('App\Models\Placement', 'offer_placement', 'offer_id', 'placement_id')->withPivot(['id', 'placement_start_date', 'placement_end_date']);
  }
  /**
   * [partners description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->belongsTo('App\Models\Partner', 'partner_id');
  }
  /**
   * [contract_status description]
   * @return [type] [description]
   */
  public function contract_status(){
    return $this->belongsTo('App\Models\Contract_Status', 'contract_status_id');
  }
  /**
   * [countries description]
   * @return [type] [description]
   */
  public function countries(){
    return $this->belongsTo('App\Models\Country', 'country_id');
  }
  /**
   * [offer_type_category_masterlist description]
   * @return [type] [description]
   */
  public function offer_type_category_masterlist(){
    return $this->belongsTo('App\Models\Offer_Type_Category_Masterlist', 'offer_type_category_masterlist_id');
  }
  /**
   * [detailed_offer_type_masterlist description]
   * @return [type] [description]
   */
  public function detailed_offer_type_masterlist(){
    return $this->belongsTo('App\Models\Detailed_Offer_Type_Masterlist', 'detailed_offer_type_masterlist_id');
  }
  /**
   * [general_offer_type_masterlist description]
   * @return [type] [description]
   */
  public function general_offer_type_masterlist(){
    return $this->belongsTo('App\Models\General_Offer_Type_Masterlist', 'general_offer_type_masterlist_id');
  }
  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsTo('App\Models\Contact', 'contact_id');
  }
  /**
   * [offer_status description]
   * @return [type] [description]
   */
  public function offer_status(){
    return $this->belongsToMany('App\Models\Offer_Status', 'offer_offer_status', 'offer_id', 'offer_status_id')->withPivot('type');
  }

  /**
   * [users description]
   * @return [type] [description]
   */
  public function users(){
    return $this->belongsToMany('App\Models\User', 'offer_user', 'offer_id', 'user_id');
  }

  /**
   * [ji_contact description]
   * @return [type] [description]
   */
  public function ji_contact(){
    return $this->belongsTo('App\Models\User', 'ji_contact_id');
  }

  /**
   * [offer_reports description]
   * @return [type] [description]
   */
  public function offer_reports(){
    return $this->hasMany('App\Models\Offer_Report');
  }

  /**
   * [emails description]
   * @return [type] [description]
   */
  public function emails(){
    return $this->hasMany('App\Models\Email', 'offer_id');
  }

  /**
   * [getValidFromAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getValidFromAttribute($value){
    return Carbon::parse($value)->format('d/m/Y');
  }
  /**
   * [getValidUntilAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getValidUntilAttribute($value){
    return Carbon::parse($value)->format('d/m/Y');
  }

  /**
   * [setCountryIdAttribute description]
   * @param [type] $value [description]
   */
  public function setCountryIdAttribute($value){
    if(!$value):
      $value = null;
    endif;
    $this->attributes['country_id'] = $value;
  }

  /**
   * [getAllOffers description]
   * @return [type] [description]
   */
  public function getAllOffers(){
    return $this->get();
  }

  /**
   * [insertOffer description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertOffer($data){
    $data = $this->handleCommonConstraint($data);
    return $this->create($data);
  }

  /**
   * [deleteOffer description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function deleteOffer($offer_id){
    return $this->where('id', $offer_id)->delete();
  }

  /**
   * [getOfferById description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function getOfferById($offer_id){
    return $this->where('id', $offer_id)->get();
  }

  /**
   * [getOfferByIdWith description]
   * @param  integer $offer_id [description]
   * @param  [type]  $with     [description]
   * @return [type]            [description]
   */
  public function getOfferByIdWith($offer_id=0, $with){
    return $this->where('id', $offer_id)->with($with)->get();
  }

  /**
   * [updateOffer description]
   * @param  [type] $offer_id [description]
   * @param  [type] $data     [description]
   * @return [type]           [description]
   */
  public function updateOffer($offer_id, $data){
    $data = $this->handleCommonConstraint($data);
    return $this->where('id', $offer_id)->update($data);
  }

  /**
   * [activateOffer description]
   * @param  [type] $offer_code [description]
   * @return [type]             [description]
   */
  public function getOfferByCode($offer_code){
    return $this->where('offer_code', $offer_code)->get();
  }

  /**
   * [getOfferByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getOfferByProject($project_id){
    return $this->where('project_id', $project_id)
                ->get();
  }

  /**
   * [getMicrositeOffers description]
   * @return [type] [description]
   */
  public function getMicrositeOffers(){
    return $this->where('has_communication_package', 'yes')->get();
  }

  /**
   * [getNewValidationRules description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function getNewValidationRules($title){
    $validation_rules = $this->new_offer_validation_rules;
    if($title == "title_en"){
      unset($validation_rules['title_de']);
      return $validation_rules;
    }
    unset($validation_rules['title_en']);
    return $validation_rules;
  }

  /**
   * [handleCommonConstraint description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function handleCommonConstraint($data){
    $constraints = ['offer_type_category_masterlist_id', 'general_offer_type_masterlist_id', 'detailed_offer_type_masterlist_id', 'contract_status_id', 'country_id', 'title_en', 'title_de', 'location', 'county', 'price_en', 'price_de', 'further_information_en', 'further_information_de', 'reservation_telephone', 'reservation_fax', 'reservation_email', 'reservation_url', 'how_to_book_en', 'how_to_book_de', 'exclusive_advantage', 'note', 'editorial_note', 'street1', 'street2', 'street3', 'postal_code', 'city', 'state', 'market', 'offer_en', 'offer_de', 'benefit_en', 'benefit_de', 'activation_email'];
    foreach ($constraints as $constraint) {
      if(isset($data[$constraint]) && !$data[$constraint]){
        $data[$constraint] = null;
      }
    }
    return $data;
  }

  /**
   * [getFirstMicrosite1Date description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function getFirstMicrosite1Date($offer_id){
    //get offer details
    $offer_info = $this->getOfferById($offer_id);
    //First we need to get offer validity dates
    $original_valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_from);
    $original_valid_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_until);
    //blank array for microsite/reports dates array
    $data = array();
    //First, we need to verify whether the validity dates are in same year-month, if so then
    //we need to generate ms1-ms2 reports for the same month, else, we need to
    //go through the each montsh that are valid within the given
    //range of the offer validity dates.
    if($original_valid_from->format('Y') == $original_valid_until->format('Y') && $original_valid_from->format('m') == $original_valid_until->format('m')){
      //dates data for report type of microsite1
      $data[] = ['year' => $original_valid_from->format('Y'), 'month' => $original_valid_from->format('M'), 'report_type' => 'microsite2'];
      //dates data for report type of microsite2
      // $data[] = ['year' => $original_valid_from->format('Y'), 'month' => $original_valid_from->format('M'), 'report_type' => 'microsite2'];
    } else { //if offer validity isnot in the same year-month, going through the each months that are valid
      $start = new DateTime($original_valid_from);
      $end = new DateTime($original_valid_until);
      while ($start <= $end) {
        $data[] = array('year' => $start->format('Y'), 'month' => $start->format('M'), 'report_type' => 'microsite1');
        $start->modify('first day of next month');
      }
      //getting last month as a microsite2 report
      $microsite2_data = end($data);
      $microsite2_data['report_type'] = 'microsite2';
      array_pop($data);
      $data[] = $microsite2_data;
    }
    return $data;
  }//End getFirstMicrosite1Date function

  /**
   * Function to verify whether the given date lies on the previously defined dates array or not.
   * @param  [type] $data [description]
   * @param  Date $date
   * @return bool
   */
  public function verifyOfferValidityMonths($data, $date){
    /*$valid_from = new DateTime($original_valid_from);
    $valid_until = new DateTime($original_valid_until);
    $interval = DateInterval::createFromDateString('1 day');
    $periods = new DatePeriod($valid_from, $interval, $valid_until);
    $data = array();
    foreach($periods as $dt) :
      if(!empty($data)){
        if($this->verifyOfferValidityMonths($data, $dt)):
          $data[] = ['year' => $dt->format("Y"), 'month' => $dt->format("M") , 'report_type' => 'microsite1'];
        endif;
      } else {
        $data[] = ['year' => $dt->format("Y"), 'month' => $dt->format("M") , 'report_type' => 'microsite1'];
      }
    endforeach;*/
    $flag = true;
    foreach ($data as $key => $value) :
      if($value['year'] == $date->format('Y') && $value['month'] == $date->format('M')):
        $flag = false;
      endif;
    endforeach;
    return $flag;
  }//End verifyOfferValidityMonths function

  /**
   * [getOfferByUser description]
   * @param  [type] $disabled_offers [description]
   * @return [type]                  [description]
   */
  public function getOfferByUser($disabled_offers){
    $offers = $this->where(function($query) use ($disabled_offers){
              foreach($disabled_offers as $disabled_offer){
                $query->where('id', '!=', $disabled_offer->offer_id);
              }
            })->get();
    return $offers;
  }

  /**
   * Return offer as per user's projects.
   * @param  [array] $disabled_offers
   * @param  [array] $projects
   * @return [collection object]
   */
  public function getOfferByUserProject($disabled_offers, $projects){
    $offers = $this->where(function($query) use ($disabled_offers){
              foreach($disabled_offers as $disabled_offer){
                return $query->where('id', '!=', $disabled_offer->offer_id);
              }
            })->get();
    $offers = $offers->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $offers;
  }

  /**
   * [callCenterBriefing description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function callCenterBriefingData($data){
    $now = \Carbon\Carbon::now()->format('Y-m-d');
    $now_date = \Carbon\Carbon::now();
    $offers = $this->where('project_id', $data['project'])->get();
    if(isset($data['placement_ids'])){
      return $this->callCenterDataByPlacements($data);
    }
    $date_until = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_until']);
    $date_from = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_from']);
    //We need to filter offers data as per offer's placements start date, return offers if offer's
    //placements are valid in provided date-range
    $offers_data = $offers->filter(function($item) use($now_date, $date_from, $date_until, $data){
      $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $item->valid_from);
      $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $item->valid_until);
      if($date_from >= $offer_start && $date_until <= $offer_until){
        return true;
      }
      $placements = $item->placements;
      foreach ($placements as $placement):
        $placement_start = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_start_date);
        $placement_end = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_end_date);
        if($placement_start >= $date_from && $placement_start <= $date_until):
          return true;
        endif;
        // }
      endforeach;
    });
    return $offers_data;
  }//End callCenterBriefingData function

  /**
   * [callCenterDataByPlacements description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function callCenterDataByPlacements($data){
    $now_date = \Carbon\Carbon::now();
    $offers = $this->where('project_id', $data['project'])->where('valid_until', '>=', $now_date)->get();
    $offers_data = $offers->filter(function($item) use($data){
      $placements = $item->placements;
      foreach ($placements as $placement):
        if(in_array($placement->id, $data['placement_ids'])) return true;
      endforeach;
    });
    return $offers_data;
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    $search_keys = $this->search_keys;
    $query = $this->where('title_en', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $results = $query->get();
    return $results;
  }

  /**
   * [searchByUserProject description]
   * @param  [type] $search_key [description]
   * @param  [type] $projects   [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects, $disabled_offers){
    if(empty($projects)){
      return new Collection;
    }
    $search_keys = $this->search_keys;
    $query = $this->where('title_en', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $query = $query->where(function($query) use ($disabled_offers){
                    foreach($disabled_offers as $disabled_offer){
                      return $query->where('id', '!=', $disabled_offer->offer_id);
                    }});
    $offers = $query->get();
    $results = $offers->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $results;
  }//End searchByGeneralUser function

  /**
   * Search offer records by viewer-general user.
   * @param  String $search_key [description]
   * @param  Array $projects user projects
   * @return mixed
   */
  public function searchByViewer($search_key, $projects){
    if(empty($projects)){
      return new Collection;
    }
    $search_keys = $this->search_keys;
    $query = $this->where('title_en', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $offers = $query->get();
    $results = $offers->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $results;
  }//End searchByGeneralViewer function

  /**
   * [verifyPartnerLanguage description]
   * @param  [type] $offer_details [description]
   * @param  [type] $type          [description]
   * @return [type]                [description]
   */
  public function verifyPartnerLanguage($offer_details, $type){
    $notice = null;
    if($offer_details[0]->contacts->language_id == 2){
      $notice = $this->dePartnerMSNotice($type);
      return view('offers.offer_activation.offer_activation_de_view', compact('offer_details', 'notice'));
    }
    $notice = $this->enPartnerMSNotice($type);
    return view('offers.offer_activation.offer_activation_en_view', compact('offer_details', 'notice'));
  }

  /**
   * [enPartnerMSNotice description]
   * @return [type] [description]
   */
  public function enPartnerMSNotice($activation_status){
    $notice = null;
    switch ($activation_status) {
      case 'success':
        $notice = 'The offer tracking manager for your offer has been successfully activated. Thank you!';
        break;
      case 'already_activated':
        $notice = 'The offer tracking manager for your offer had been successfully activated. Thank you!';
        break;
      default:
        # code...
        break;
    }
    return $notice;
  }

  /**
   * [dePartnerMSNotice description]
   * @return [type] [description]
   */
  public function dePartnerMSNotice($activation_status){
    $notice = null;
    switch ($activation_status) {
      case 'success':
        $notice = 'Der Angebotsmanager für Ihr Angebot wurde hiermit erfolgreich aktiviert. Vielen Dank!';
        break;
      case 'already_activated':
        $notice = 'Der Angebotsmanager für Ihr Angebot wurde bereits erfolgreich aktiviert. Vielen Dank!';
        break;
      default:
        # code...
        break;
    }
    return $notice;
  }//End dePartnerMSNotice function

  /**
   * Return call center valiation rules as per received request.
   * @param  object $request
   * @return array call center validation rules
   */
  public function getCallCenterValidation($request){
    if(null !== $request->input('placement_ids')){
      unset($this->export_call_center_validation['date_from'], $this->export_call_center_validation['date_until']);
      return $this->export_call_center_validation;
    }
    return $this->export_call_center_validation;
  }

  /**
   * [getOfferReportingValidation description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function getOfferReportingValidation($request){
    if(null !== $request->input('placement_ids')){
      unset($this->export_offer_reporting_validation['date_from'], $this->export_offer_reporting_validation['date_until']);
      return $this->export_offer_reporting_validation;
    }
    return $this->export_offer_reporting_validation;
  }

  /**
   * [offersByProjectPlacements description]
   * @param  [type] $project_ids   [description]
   * @param  [type] $placement_ids [description]
   * @return [type]                [description]
   */
  public function offersByProjectPlacements($project_ids, $placement_ids){
    $offers = $this->where(function($query) use ($project_ids){
              foreach($project_ids as $project_id){
                $query->orWhere('project_id', $project_id);
              }
            })->get();
    $offers = $this->filterOffersByPlacements($offers, $placement_ids);
    return $offers;
  }

  /**
   * [filterOffersByPlacements description]
   * @param  [type] $offers        [description]
   * @param  [type] $placement_ids [description]
   * @return [type]                [description]
   */
  public function filterOffersByPlacements($offers, $placement_ids){
    $offers = $offers->filter(function($item) use($placement_ids){
                $flag = false;
                $offer_placements = $item->placements;
                $offer_placement_ids = [];
                foreach ($item->placements as $offer_placement) {
                  $offer_placement_ids[] = $offer_placement->id;
                }
                foreach ($placement_ids as $placement_id) {
                  if(in_array($placement_id, $offer_placement_ids)){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $offers;
  }//End filterOffersByPlacements function

  /**
   * [filterOffersByValidity description]
   * @param  [type] $date_from  [description]
   * @param  [type] $date_until [description]
   * @return [type]             [description]
   */
  public function filterOffersByValidity($date_from, $date_until, $offers){
    $offer_from = \Carbon\Carbon::createFromFormat('m-Y', $date_from);
    $offer_until = \Carbon\Carbon::createFromFormat('m-Y', $date_until);
    $offer_from->day = 01;
    $offer_until->day = 01;
    $offers = $offers->filter(function($item) use($offer_from, $offer_until){
                $valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $item->valid_from);
                $valid_from->day = 01;
                $valid_until = \Carbon\Carbon::createFromFormat('d/m/Y', $item->valid_until);
                $valid_until->day = 01;
                if($valid_from >= $offer_from && $offer_until >= $valid_until){
                  return true;
                }
              });
    return $offers;
  }//End filterOffersByValidity function

  /**
   * [offerReportingOffers description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function offerReportingOffers($data){
    $offers = $this->getOfferByProject($data['project']);
    if ($offers->isEmpty()) {
      return new Collection;
    }
    if ($data['date_from'] && $data['date_until'] && !empty($data['placement_ids'])) {
      $final_offers = $this->filterOffersByValidity($data['date_from'], $data['date_until'], $offers);
      return $this->filterOffersByPlacements($final_offers, $data['placement_ids']);
    }
    elseif ($data['date_from'] && $data['date_until']) {
      return $this->filterOffersByValidity($data['date_from'], $data['date_until'], $offers);
    }
    else {
      return $this->filterOffersByPlacements($offers, $data['placement_ids']);
    }
  }//End offerReportingOffers function

  /**
   * [getOfferActivationData description]
   * @return [type] [description]
   */
  public function getOfferActivationData($offer_info){
    foreach ($offer_info as $key => $value) {
      if (isset($value['id'])) {
        return $offer_info[$key];
      }
    }
  }

}//End Class, Offer
