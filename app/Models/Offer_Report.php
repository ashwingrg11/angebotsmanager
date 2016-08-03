<?php

/**
 * Offer_Report.php
 *
 * Handles offer reports database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Collection as Collection;
use DateTime;

class Offer_Report extends Model
{
  /**
   * [database table name for this Offer_Report model]
   * @var string $table
   */
  protected $table = 'offer_reports';
  /**
   * [mass assignable properties description]
   * @var [type]
   */
  protected $fillable = ['offer_id', 'no_of_requests', 'no_of_final_bookings', 'no_of_bookings_another_offering', 'no_of_packages_sold', 'comments', 'for_year', 'for_month', 'report_date', 'filled_in', 'report_type', 'report_code', 'extension_request'];

  /**
   * [$search_keys description]
   * @var [type]
   */
  public $search_keys = ['for_month', 'no_of_requests', 'no_of_final_bookings', 'no_of_bookings_another_offering', 'no_of_packages_sold', 'report_type', 'report_date', 'comments'];

  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    return $this->belongsTo('App\Models\Offer', 'offer_id');
  }

  /**
   * [microsite2_reports description]
   * @return [type] [description]
   */
  public function microsite2_reports(){
    return $this->hasOne('App\Models\Microsite2_Report', 'offer_report_id');
  }

  /**
   * [emails description]
   * @return [type] [description]
   */
  public function emails(){
    return $this->hasOne('App\Models\Email', 'offer_report_id');
  }

  /**
   * [$new_data_validation_rules description]
   * @var [type]
   */
  public $new_data_validation_rules = [
    'no_of_requests' => 'numeric|regex:/^[0-9]\d*$/',
  ];

  /**
   * [$validation_attribute description]
   * @var [type]
   */
  public $validation_attribute = [
    'no_of_bookings_another_offering' => 'number of bookings of another offering',
    'no_of_requests' => 'number of requests',
    'no_of_packages_sold' => 'number of packages sold'
  ];

  /**
   * [insertOfferReport description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertOfferReport($data){
    return $this->create($data);
  }

  /**
   * [updateOfferReport description]
   * @param  [type] $id   [description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function updateOfferReport($id, $data){
    $data['filled_in'] = 'yes';
    $data = $this->handleCommonConstraint($data);
    return $this->where('id', $id)->update($data);
  }

  /**
   * [getAllOfferReports description]
   * @return [type] [description]
   */
  public function getAllOfferReports(){
    return $this->get();
  }

  /**
   * [getMicrositeReports description]
   * @param  [type] $type [description]
   * @return [type]       [description]
   */
  public function getMicrositeReports($type){
    return $this->where('report_type', $type)->get();
  }

  /**
   * [getMicrositesData description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getMicrositesData($projects){
    $offer_reports = $this->getAllOfferReports();
    return $this->filterByUserProjects($offer_reports, $projects);
  }

  /**
   * Return report details by report id.
   * @param  [int] $report_id
   * @return [object] Offer Report
   */
  public function getReportById($report_id){
    return $this->where('id', $report_id)->get();
  }

  /**
   * [getCurrentReport description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function getCurrentReport($offer_id){
    return $this->where('offer_id', $offer_id)
                ->where('for_year', \Carbon\Carbon::now()->format('Y'))
                ->where('for_month', \Carbon\Carbon::now()->format('M'))
                ->get();
  }

  /**
   * [getMicrositeReportByOffer description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function getMicrositeReportByOffer($offer_id){
    return $this->where('offer_id', $offer_id)->get();
  }

  /**
   * [insertCurrentOfferReport description]
   * @param  [type] $offer_id [description]
   * @param  [type] $data     [description]
   * @return [type]           [description]
   */
  public function insertCurrentOfferReport($offer_id, $data){
    return $this->where('offer_id', $offer_id)
                ->where('for_year', \Carbon\Carbon::now()->format('Y'))
                ->where('for_month', \Carbon\Carbon::now()->format('M'))
                ->create($data);
  }

  /**
   * [deleteOfferReportByOffer description]
   * @return [type] [description]
   */
  public function deleteOfferReportByOffer($offer_id){
    return $this->where('offer_id', $offer_id)->delete();
  }

  /**
   * [generate random string]
   * @return [string] $random_string
   */
  public function randomCode(){
    $random_string = '';
    $string_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Zå'));
    for ($i = 0; $i <= 10; $i++) {
      $random_string .= $string_array[array_rand($string_array)];
    }
    return $random_string;
  }

  /**
   * [handleCommonConstraint description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function handleCommonConstraint($data){
    $constraints_first = ['no_of_requests', 'no_of_final_bookings', 'no_of_bookings_another_offering', 'no_of_packages_sold', 'comments'];
    // $constraints_second = ['comments'];
    foreach ($constraints_first as $constraint) {
      if(isset($data[$constraint]) && $data[$constraint] == null){
        $data[$constraint] = NULL;
      }
    }
    return $data;
  }

  /**
   * [return random string report code by verifying whether it already exists or not]
   * @return [string] $report_code
   */
  public function newReportCode(){
    $report_code = $this->randomCode();
    $reports = $this->getAllOfferReports();
    $old_report_codes = array();
    if(!$reports->isEmpty()){
      foreach($reports as $key=>$report){
        $old_report_codes[] = $report->report_code;
      }
    }
    if(in_array($report_code, $old_report_codes)){
      while(!in_array($report_code, $old_report_codes)){
        $report_code = $this->generateRandomCode();
      }
    }
    return $report_code;
  }

  /**
   * [return offer reports by report code]
   * @param  [string] $code
   * @return [collection object] [offer reports]
   */
  public function getReportByCode($code){
    return $this->where('report_code', $code)->get();
  }

  /**
   * [return validation rules]
   * @param  \Illuminate\Http\Request $request
   * @return [array] $new_data_validation_rules
   */
  public function getValidationRules($request){
    if($request->has('no_of_final_bookings')):
      $this->new_data_validation_rules['no_of_final_bookings'] = 'numeric|regex:/^[0-9]\d*$/';
    endif;
    if($request->has('no_of_packages_sold')):
      $this->new_data_validation_rules['no_of_packages_sold'] = 'numeric|regex:/^[0-9]\d*$/';
    endif;
    return $this->new_data_validation_rules;
  }

  /**
   * [return first offer report as per provided offer id]
   * @param  [int] $offer_id
   * @return [array] [offer report details]
   */
  public function firstReportByOffer($offer_id){
    return $this->where('offer_id', $offer_id)
                ->orderBy('created_at', 'desc')
                ->take(1)
                ->get();
  }

  /**
   * Return offer report by report year & month.
   * @param  [date] $date
   * @return [object] Offer_Report
   */
  public function getReportByDate($date, $offer_id){
    $month = $date->format('M');
    $year = $date->format('Y');
    return $this->where('offer_id', $offer_id)
                ->where('for_year', $year)
                ->where('for_month', $month)
                ->get();

  }

  /**
   * [insertReportByDate description]
   * @param  [type] $date     [description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function insertReportByDateType($date, $offer_id, $type){
    $month = $date->format('M');
    $year = $date->format('Y');
    $data = [
              'offer_id' => $offer_id,
              'for_year' => $year,
              'for_month' => $month,
              'report_code' => $this->newReportCode(),
              'report_type' => $type
            ];
    return $this->create($data);
  }

  /**
   * [getReportByDateType description]
   * @param  [type] $date        [description]
   * @param  [type] $offer_id    [description]
   * @param  [type] $report_type [description]
   * @return [type]              [description]
   */
  public function getReportByDateType($date, $offer_id, $report_type){
    $month = $date->format('M');
    $year = $date->format('Y');
    return $this->where('offer_id', $offer_id)
                ->where('for_year', $year)
                ->where('for_month', $month)
                ->where('report_type', $report_type)
                ->get();
  }

  /**
   * [getReportByMonthYear description]
   * @param  [type] $offer_id  [description]
   * @param  [type] $for_year  [description]
   * @param  [type] $for_month [description]
   * @return [type]            [description]
   */
  public function getReportByMonthYear ($offer_id, $for_year, $for_month) {
    return $this->where('offer_id', $offer_id)
                ->where('for_year', $for_year)
                ->where('for_month', $for_month)
                ->get();
  }

  /**
   * [offerReportsByUserProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function offerReportsByUserProject($projects){
    $offer_reports = $this->getAllOfferReports();
    return $this->filterByUserProjects($offer_reports, $projects);
  }

  /**
   * [micrositeReportsByUser description]
   * @param  [type] $projects    [description]
   * @param  [type] $report_type [description]
   * @return [type]              [description]
   */
  public function micrositeReportsByUser($projects, $report_type){
    $offer_reports = $this->getMicrositeReports($report_type);
    return $this->filterByUserProjects($offer_reports, $projects);
  }

  /**
   * [offerReportByDetails description]
   * @param  [type] $offer_id  [description]
   * @param  [type] $for_year  [description]
   * @param  [type] $for_month [description]
   * @return [type]            [description]
   */
  public function offerReportByDetails($offer_id, $for_year, $for_month){
    return $this->where('offer_id', $offer_id)
                ->where('for_year', $for_year)
                ->where('for_month', $for_month)
                ->get();
  }

  /**
   * [filterByUserProjects description]
   * @param  [type] $offer_reports [description]
   * @param  [type] $projects      [description]
   * @return [type]                [description]
   */
  public function filterByUserProjects($offer_reports, $projects){
    $offer_reports = $offer_reports->filter(function($item) use($projects){
                  $flag = false;
                    foreach ($projects as $project_user) {
                      if($item->offers->project_id == $project_user->id){
                        $flag = true;
                      }
                    }
                  return $flag;
                });
    return $offer_reports;
  }

  /**
   * [activationReportsByProject description]
   * @param  [type] $projects [description]
   * @param  [type] $offers   [description]
   * @return [type]           [description]
   */
  public function activationReportsByProject($projects, $offers){
    $offers = $offers->filter(function($item) use($projects){
                  $flag = false;
                    foreach ($projects as $project_user) {
                      if($item->project_id == $project_user->id){
                        $flag = true;
                      }
                    }
                  return $flag;
                });
    return $offers;
  }

  /**
   * [feedbackEmails description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @param  [type] $no_months [description]
   * @return [type]            [description]
   */
  public function feedbackEmails($offers, $min_start, $max_end, $no_months){
    $feedback_emails = array();
    $test_flag = 1;
    while($test_flag <= $no_months){
      foreach ($offers as $offer):
        $min_date = clone $min_start;
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        $original_offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $new_min_date = clone $min_date;
        $diff_month = $new_min_date->diffInMonths($original_offer_start);
        if($test_flag > 1):
          $min_date->addMonths($test_flag-1);
        endif;
        if(!isset($feedback_emails[$test_flag])):
          $feedback_emails[$test_flag] = 0;
        endif;
        $offer_emails = $offer->emails;
        foreach ($offer_emails as $email) {
          if($email->type == "microsite1"){
            $email_report = $email->offer_reports;
            if($email_report->for_year == $min_date->format('Y') && $email_report->for_month == $min_date->format('M')){
              $feedback_emails[$test_flag] = $feedback_emails[$test_flag] + 1;
            }
          }
        }
      endforeach;//Endforeach offers
      $test_flag++;
    }//End while, nos. months
    array_unshift($feedback_emails, 0);
    return $feedback_emails;
  }

  /**
   * [getValidOffers description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidOffers($offers, $min_start, $max_end){
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $valid_offers = [];
    $sn_flag = 0;
    while ($min <= $max){
      foreach ($offers as $offer) {
        if(!isset($valid_offers[$sn_flag])){
          $valid_offers[$sn_flag] = 0;
        }
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        $offer_min = new DateTime($offer_start);
        $offer_max = new DateTime($offer_until);
        while ($offer_min <= $offer_max){
          if($offer_min->format('Y') == $min->format('Y') && $offer_min->format('m') == $min->format('m')){
            $valid_offers[$sn_flag] = $valid_offers[$sn_flag]+1;
            break;
          }
          $offer_min->modify('first day of next month');
        }
      }
      $sn_flag++;
      $min->modify('first day of next month');
    }
    return $valid_offers;
  }

  /**
   * [verifyValidOffer description]
   * @param  [type] $offer_info [description]
   * @param  [type] $min_start  [description]
   * @param  [type] $max_end    [description]
   * @return [type]             [description]
   */
  public function verifyValidOffer($offer_info, $verify_date){
    $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info->valid_from);
    $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer_info->valid_until);
    // $flag_date = new DateTime($verify_date);
    if($offer_start->format('Y') == $verify_date->format('Y') && $offer_start->format('m') == $verify_date->format('m')) {
      return true;
    } else {
      if($offer_until->format('Y') == $verify_date->format('Y') && $offer_until->format('m') == $verify_date->format('m')){
        return true;
      }else {
        if($offer_start < $verify_date && $offer_until > $verify_date) {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * [getValidOffers description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidPartOffers($offers, $min_start, $max_end){
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $part_offers = [];
    $sn_flag = 0;
    while ($min <= $max){
      foreach ($offers as $offer) {
        if(!isset($part_offers[$sn_flag])){
          $part_offers[$sn_flag] = 0;
        }
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        $offer_min = new DateTime($offer_start);
        $offer_max = new DateTime($offer_until);
        if($offer_min->format('Y') == $min->format('Y') && $offer_min->format('m') == $min->format('m')) {
          if($offer_min->format('j') >= 7){
            $part_offers[$sn_flag] = $part_offers[$sn_flag]+1;
          }
        } else {
          if($offer_max->format('Y') == $min->format('Y') && $offer_max->format('m') == $min->format('m')){
            if($offer_max->format('j') <= 23){
              $part_offers[$sn_flag] = $part_offers[$sn_flag]+1;
            }
          }
        }
      }
      $sn_flag++;
      $min->modify('first day of next month');
    }
    return $part_offers;
  }

  /**
   * [getValidMsoneEmails description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidMsoneEmails($offers, $min_start, $max_end){
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $msone_emails = [];
    $sn_flag = 0;
    while ($min <= $max){
      foreach ($offers as $offer) {
        if(!isset($msone_emails[$sn_flag])){
          $msone_emails[$sn_flag] = 0;
        }
        if($offer->has_communication_package == "yes"){
          $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
          $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
          $offer_min = new DateTime($offer_start);
          $offer_max = new DateTime($offer_until);
          while ($offer_min <= $offer_max){
            if($offer_min->format('Y') == $min->format('Y') && $offer_min->format('m') == $min->format('m')){
              $msone_emails[$sn_flag] = $msone_emails[$sn_flag]+1;
              break;
            }
            $offer_min->modify('first day of next month');
          }
          if($offer_max->format('Y') == $min->format('Y') && $offer_max->format('m') == $min->format('m')){
            $msone_emails[$sn_flag] = $msone_emails[$sn_flag]-1;
          }
        }
      }
      $sn_flag++;
      $min->modify('first day of next month');
    }
    return $msone_emails;
  }

  /**
   * [getValidMstwoEmails description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidMstwoEmails($offers, $min_start, $max_end){
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $mstwo_emails = [];
    $sn_flag = 0;
    while ($min <= $max){
      foreach ($offers as $offer) {
        if(!isset($mstwo_emails[$sn_flag])){
          $mstwo_emails[$sn_flag] = 0;
        }
        if ($offer->has_communication_package == "yes") {
          $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
          $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
          $offer_min = new DateTime($offer_start);
          $offer_max = new DateTime($offer_until);
          if($offer_max->format('Y') == $min->format('Y') && $offer_max->format('m') == $min->format('m')){
            $mstwo_emails[$sn_flag] = $mstwo_emails[$sn_flag]+1;
          }
        }
      }
      $sn_flag++;
      $min->modify('first day of next month');
    }
    return $mstwo_emails;
  }

  /**
   * [getValidRequests description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidRequests($offers, $min_start, $max_end){
    $reported_requests = [];
    $offer_flag = 0;
    foreach ($offers as $offer) {
      $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
      $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
      $report_flag = 0;
      while ($min <= $max){
        if(!isset($reported_requests[$offer_flag][$report_flag]['data'])){
          $reported_requests[$offer_flag][$report_flag]['data'] = 'n/a';
        }
        if(!isset($reported_requests[$offer_flag][$report_flag]['flag'])){
          $reported_requests[$offer_flag][$report_flag]['flag'] = 'default';
        }
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if ($report_data->isEmpty()) {
          $reported_requests[$offer_flag][$report_flag]['data'] = 'n/a';
          $reported_requests[$offer_flag][$report_flag]['flag'] = 'default';
        }
        else {
          if($report_data[0]->filled_in == "yes") {
            if($report_data[0]->no_of_requests == '0') {
              $reported_requests[$offer_flag][$report_flag]['data'] = '0';
              $reported_requests[$offer_flag][$report_flag]['flag'] = 'value_zero';
            }
            elseif($report_data[0]->no_of_requests == null){
              $reported_requests[$offer_flag][$report_flag]['data'] = 'blank';
              $reported_requests[$offer_flag][$report_flag]['flag'] = 'value_null';
            }
            else {
              $reported_requests[$offer_flag][$report_flag]['data'] = $report_data[0]->no_of_requests;
              $reported_requests[$offer_flag][$report_flag]['flag'] ='data_valid';
            }
          }
          else {
            $reported_requests[$offer_flag][$report_flag]['data'] = 'pending';
            $reported_requests[$offer_flag][$report_flag]['flag'] = 'value_pending';
          }
        }
        if ($offer->has_communication_package == "no") {
          if($this->verifyValidOffer($offer, $min)){
            $reported_requests[$offer_flag][$report_flag]['flag'] = 'commn_no';
          } else {
            $reported_requests[$offer_flag][$report_flag]['flag'] = 'default';
          }
        }
        $min->modify('first day of next month');
        $report_flag++;
      }
      $offer_flag++;
    }
    // echo "<pre>";
    // print_r($reported_requests);die;
    return $reported_requests;
  }

  /**
   * [getValidReservations description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidReservations($offers, $min_start, $max_end) {
    $reported_reservations = [];
    $offer_flag = 0;
    foreach ($offers as $offer) {
      $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
      $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
      $report_flag = 0;
      while ($min <= $max){
        if(!isset($reported_reservations[$offer_flag][$report_flag]['data'])){
          $reported_reservations[$offer_flag][$report_flag]['data'] = 'n/a';
        }
        if(!isset($reported_reservations[$offer_flag][$report_flag]['flag'])){
          $reported_reservations[$offer_flag][$report_flag]['flag'] = 'default';
        }
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if ($report_data->isEmpty()) {
          $reported_reservations[$offer_flag][$report_flag]['data'] = 'n/a';
          $reported_reservations[$offer_flag][$report_flag]['flag'] = 'default';
        }
        else {
          if($report_data[0]->filled_in == "yes") {
            if($report_data[0]->no_of_final_bookings == '0') {
              $reported_reservations[$offer_flag][$report_flag]['data'] = '0';
              $reported_reservations[$offer_flag][$report_flag]['flag'] = 'value_zero';
            }
            elseif($report_data[0]->no_of_final_bookings == null){
              $reported_reservations[$offer_flag][$report_flag]['data'] = 'blank';
              $reported_reservations[$offer_flag][$report_flag]['flag'] = 'value_null';
            }
            else {
              $reported_reservations[$offer_flag][$report_flag]['data'] = $report_data[0]->no_of_final_bookings;
              $reported_reservations[$offer_flag][$report_flag]['flag'] ='data_valid';
            }
          }
          else {
            $reported_reservations[$offer_flag][$report_flag]['data'] = 'pending';
            $reported_reservations[$offer_flag][$report_flag]['flag'] = 'value_pending';
          }
        }
        if ($offer->has_communication_package == "no") {
          if($this->verifyValidOffer($offer, $min)){
            $reported_reservations[$offer_flag][$report_flag]['flag'] = 'commn_no';
          } else {
            $reported_reservations[$offer_flag][$report_flag]['flag'] = 'default';
          }
        }
        $min->modify('first day of next month');
        $report_flag++;
      }
      $offer_flag++;
    }
    return $reported_reservations;
  }

  /**
   * [getValidReservations description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function getValidParticipants($offers, $min_start, $max_end) {
    $reported_paticipants = [];
    $offer_flag = 0;
    foreach ($offers as $offer) {
      $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
      $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
      $report_flag = 0;
      while ($min <= $max){
        if(!isset($reported_paticipants[$offer_flag][$report_flag]['data'])){
          $reported_paticipants[$offer_flag][$report_flag]['data'] = 'n/a';
        }
        if(!isset($reported_paticipants[$offer_flag][$report_flag]['flag'])){
          $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'default';
        }
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if ($report_data->isEmpty()) {
          $reported_paticipants[$offer_flag][$report_flag]['data'] = 'n/a';
          $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'default';
        }
        else {
          if($report_data[0]->filled_in == "yes") {
            if($report_data[0]->no_of_packages_sold == '0') {
              $reported_paticipants[$offer_flag][$report_flag]['data'] = '0';
              $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'value_zero';
            }
            elseif($report_data[0]->no_of_packages_sold == null){
              $reported_paticipants[$offer_flag][$report_flag]['data'] = 'blank';
              $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'value_null';
            }
            else {
              $reported_paticipants[$offer_flag][$report_flag]['data'] = $report_data[0]->no_of_packages_sold;
              $reported_paticipants[$offer_flag][$report_flag]['flag'] ='data_valid';
            }
          }
          else {
            $reported_paticipants[$offer_flag][$report_flag]['data'] = 'pending';
            $reported_paticipants[$offer_flag][$report_flag]['flag'] ='value_pending';
          }
        }
        if ($offer->has_communication_package == "no") {
          if($this->verifyValidOffer($offer, $min)){
            $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'commn_no';
          } else {
            $reported_paticipants[$offer_flag][$report_flag]['flag'] = 'default';
          }
        }
        $min->modify('first day of next month');
        $report_flag++;
      }
      $offer_flag++;
    }
    return $reported_paticipants;
  }

  /**
   * [getValidOffers description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function validOffersByCommnPackage($offers, $min_start, $max_end, $commn_package_type){
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $valid_offers_commn = [];
    $sn_flag = 0;
    while ($min <= $max){
      foreach ($offers as $offer) {
        if(!isset($valid_offers_commn[$sn_flag])){
          $valid_offers_commn[$sn_flag] = 0;
        }
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        $offer_min = new DateTime($offer_start);
        $offer_max = new DateTime($offer_until);
        while ($offer_min <= $offer_max){
          if($offer_min->format('Y') == $min->format('Y') && $offer_min->format('m') == $min->format('m')){
            if($offer->has_communication_package == $commn_package_type) {
              $valid_offers_commn[$sn_flag] = $valid_offers_commn[$sn_flag]+1;
              break;
            }
          }
          $offer_min->modify('first day of next month');
        }
      }
      $sn_flag++;
      $min->modify('first day of next month');
    }
    return $valid_offers_commn;
  }

  /**
   * [getValidRequests description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function reportedRequestsFeedbacks($offers, $min_start, $max_end){
    $reported_requests_feedbacks = [];
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $report_flag = 0;
    while ($min <= $max){
      if(!isset($reported_requests_feedbacks[$report_flag]['with_feedback'])){
        $reported_requests_feedbacks[$report_flag]['with_feedback'] = 0;
      }
      if(!isset($reported_requests_feedbacks[$report_flag]['without_feedback'])){
        $reported_requests_feedbacks[$report_flag]['without_feedback'] = 0;
      }
      $na_flag = false;
      foreach ($offers as $offer) {
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if (!$report_data->isEmpty()) {
          $na_flag = true;
          if($report_data[0]->offers->has_communication_package == 'yes') {
            if($report_data[0]->filled_in == "yes") {
              if(!isset($report_data[0]->no_of_requests)) {
              // if($report_data[0]->no_of_requests == null) {
                $reported_requests_feedbacks[$report_flag]['without_feedback'] = $reported_requests_feedbacks[$report_flag]['without_feedback'] + 1;
              }
              else {
                $reported_requests_feedbacks[$report_flag]['with_feedback'] = $reported_requests_feedbacks[$report_flag]['with_feedback'] + 1;
              }
            }
            else {
              $reported_requests_feedbacks[$report_flag]['without_feedback'] = $reported_requests_feedbacks[$report_flag]['without_feedback'] + 1;
            }
          }
        }
      }
      if(!$na_flag){
        $reported_requests_feedbacks[$report_flag]['without_feedback'] = 'n/a';
        $reported_requests_feedbacks[$report_flag]['with_feedback'] = 'n/a';
      }
      $report_flag++;
      $min->modify('first day of next month');
    }
    return $reported_requests_feedbacks;
  }

  /**
   * [reportedReservationsFeedbacks description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function reportedReservationsFeedbacks($offers, $min_start, $max_end){
    $reported_reservations_feedbacks = [];
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $report_flag = 0;
    while ($min <= $max){
      if(!isset($reported_reservations_feedbacks[$report_flag]['with_feedback'])){
        $reported_reservations_feedbacks[$report_flag]['with_feedback'] = 0;
      }
      if(!isset($reported_reservations_feedbacks[$report_flag]['without_feedback'])){
        $reported_reservations_feedbacks[$report_flag]['without_feedback'] = 0;
      }
      $na_flag = false;
      foreach ($offers as $offer) {
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if (!$report_data->isEmpty()) {
          $na_flag = true;
          if($report_data[0]->offers->has_communication_package == 'yes') {
            if($report_data[0]->filled_in == "yes") {
              if(!isset($report_data[0]->no_of_final_bookings)) {
                $reported_reservations_feedbacks[$report_flag]['without_feedback'] = $reported_reservations_feedbacks[$report_flag]['without_feedback'] + 1;
              }
              else {
                $reported_reservations_feedbacks[$report_flag]['with_feedback'] = $reported_reservations_feedbacks[$report_flag]['with_feedback'] + 1;
              }
            }
            else {
              $reported_reservations_feedbacks[$report_flag]['without_feedback'] = $reported_reservations_feedbacks[$report_flag]['without_feedback'] + 1;
            }
          }
        }
      }
      if(!$na_flag){
        $reported_reservations_feedbacks[$report_flag]['without_feedback'] = 'n/a';
        $reported_reservations_feedbacks[$report_flag]['with_feedback'] = 'n/a';
      }
      $report_flag++;
      $min->modify('first day of next month');
    }
    return $reported_reservations_feedbacks;
  }

  /**
   * [reportedParticipantsFeedbacks description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @return [type]            [description]
   */
  public function reportedParticipantsFeedbacks($offers, $min_start, $max_end){
    $reported_participants_feedbacks = [];
    $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_end));
    $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_start));
    $report_flag = 0;
    while ($min <= $max){
      if(!isset($reported_participants_feedbacks[$report_flag]['with_feedback'])){
        $reported_participants_feedbacks[$report_flag]['with_feedback'] = 0;
      }
      if(!isset($reported_participants_feedbacks[$report_flag]['without_feedback'])){
        $reported_participants_feedbacks[$report_flag]['without_feedback'] = 0;
      }
      $na_flag = false;
      foreach ($offers as $offer) {
        $report_data = $this->getReportByMonthYear($offer->id, $min->format('Y'), $min->format('M'));
        if (!$report_data->isEmpty()) {
          $na_flag = true;
          if($report_data[0]->offers->has_communication_package == 'yes') {
            if($report_data[0]->filled_in == "yes") {
              if(!isset($report_data[0]->no_of_packages_sold)) {
              // if($report_data[0]->no_of_packages_sold == null) {
                $reported_participants_feedbacks[$report_flag]['without_feedback'] = $reported_participants_feedbacks[$report_flag]['without_feedback'] + 1;
              }
              else {
                $reported_participants_feedbacks[$report_flag]['with_feedback'] = $reported_participants_feedbacks[$report_flag]['with_feedback'] + 1;
              }
            }
            else {
              $reported_participants_feedbacks[$report_flag]['without_feedback'] = $reported_participants_feedbacks[$report_flag]['without_feedback'] + 1;
            }
          }
        }
      }
      if(!$na_flag){
        $reported_participants_feedbacks[$report_flag]['without_feedback'] = 'n/a';
        $reported_participants_feedbacks[$report_flag]['with_feedback'] = 'n/a';
      }
      $report_flag++;
      $min->modify('first day of next month');
    }
    return $reported_participants_feedbacks;
  }

  /**
   * [offersValid description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @param  [type] $no_months [description]
   * @return [type]            [description]
   */
  public function offersValid($offers, $min_start, $max_end, $no_months){
    $valid_offers = array();
    $test_flag = 1;
    while($test_flag <= $no_months){
      foreach ($offers as $offer):
        $min_date = clone $min_start;
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        $original_offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until_condition = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until)->addMonth();
        $new_min_date = clone $min_date;
        $diff_month = $original_offer_start->diffInMonths($new_min_date);
        $flag_offer_start = clone $offer_start;
        $flag_offer_start->addMonth();
        $month_flag = $test_flag-1-$diff_month;
        if($test_flag > 1):
          $min_date->addMonth($test_flag-1);
          if($min_date->format('Y') == $flag_offer_start->format('Y') && $min_date->format('m') == $flag_offer_start->format('m')){
            if($diff_month>=2){
              $offer_start->addMonths($month_flag-$diff_month+1);
            }else{
              $offer_start->addMonths($month_flag-$diff_month);
            }
            if($offer_start->format('m') != $min_date->format('m')){
              $offer_start->month = $min_date->month;
            }
          }else{
            if(($min_date->format('Y') >= $flag_offer_start->format('Y') && $min_date->format('m') >= $flag_offer_start->format('m')) || ($min_date->format('Y') > $flag_offer_start->format('Y') && $min_date->format('m') <= $flag_offer_start->format('m'))){
              $offer_start->addMonths($month_flag-$diff_month);
              if($offer_start->format('m') != $min_date->format('m')){
                $offer_start->month = $min_date->month;
              }
            }else{
              $new_month_diff = $flag_offer_start->diffInMonths($min_date);
              $new_diff_flag = $new_month_diff+1;
            }
          }
        endif;//End test_flag
        if(!isset($valid_offers[$test_flag])):
          $valid_offers[$test_flag] = 0;
        endif;
        if($offer_start->format('Y') == $min_date->format('Y') && $offer_start->format('m') == $min_date->format('m')):
          if($offer_start->format('Y') == $offer_until->format('Y') && $offer_start->format('m') == $offer_until->format('m')):
            $valid_offers[$test_flag] = $valid_offers[$test_flag] + 1;
          else:
            if($offer_start < $offer_until):
              $valid_offers[$test_flag] = $valid_offers[$test_flag] + 1;
            endif;
          endif;
        else:
          $valid_offers[$test_flag] = $valid_offers[$test_flag] + 0;
        endif;
      endforeach;//Endforeach
      $test_flag++;
    }
    return $valid_offers;
  }

  /**
   * [verifyOfferReportDates description]
   * @param  [type] $user_data [description]
   * @param  [type] $date      [description]
   * @param  [type] $type      [description]
   * @return [type]            [description]
   */
  public function verifyOfferReportDates($user_data, $date, $type){
    if ($type == 'min') {
      if (isset($user_data['date_from'])) {
        $dates = explode("-", $user_data['date_from']);
        $string_date = $dates[1].'-'.$dates[0].'-01';
        $date_from = \Carbon\Carbon::createFromFormat('Y-m-d', $string_date);
        $date_from->day = 01;
        if ($date > $date_from) {
          return $date_from->format('d/m/Y');
        }
      }
      try {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
      } catch (\Exception $e) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('d/m/Y');
      }
    }
    elseif ($type == 'max') {
      if (isset($user_data['date_until'])) {
        $dates = explode("-", $user_data['date_until']);
        $string_date = $dates[1].'-'.$dates[0].'-01';
        $date_until = \Carbon\Carbon::createFromFormat('Y-m-d', $string_date);
        $date_until->day = 01;
        if ($date_until > $date) {
          return $date_until->format('d/m/Y');
        }
      }
      try {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
      } catch (\Exception $e) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('d/m/Y');
      }
    }
  }//End verifyOfferReportDates function

  /**
   * [offersExpired description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @param  [type] $no_months [description]
   * @return [type]            [description]
   */
  public function offersExpired($offers, $min_start, $max_end, $no_months){
    $expired_offers = array();
    $test_flag = 1;
    while($test_flag <= $no_months){
      foreach ($offers as $offer):
        $min_date = clone $min_start;
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        if($test_flag != 1):
          $min_date->addMonths($test_flag-1);
        endif;
        if(!isset($expired_offers[$test_flag])):
          $expired_offers[$test_flag] = 0;
        endif;
        if($min_date->format('Y') == $offer_until->format('Y') && $min_date->format('m') == $offer_until->format('m')):
          $expired_offers[$test_flag] = $expired_offers[$test_flag] + 1;
        endif;
      endforeach;//Endforeach
      $test_flag++;
    }
    array_unshift($expired_offers, 0);
    return $expired_offers;
  }

  /**
   * [monthlyPartOffers description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @param  [type] $no_months [description]
   * @return [type]            [description]
   */
  public function monthlyPartOffers($offers, $min_start, $max_end, $no_months){
    $part_offers = array();
    $test_flag = 1;
    while($test_flag <= $no_months){
      foreach ($offers as $offer):
        $min_date = clone $min_start;
        $offer_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        $offer_until = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        if($test_flag != 1):
          $min_date->addMonths($test_flag-1);
        endif;
        if(!isset($part_offers[$test_flag])):
          $part_offers[$test_flag] = 0;
        endif;

        if($min_date->format('Y') == $offer_until->format('Y') && $min_date->format('m') == $offer_until->format('m')){
          if($offer_until->format('d') < 21){
            $part_offers[$test_flag] = $part_offers[$test_flag] + 1;
          }
        }
        if($min_date->format('Y') == $offer_from->format('Y') && $min_date->format('m') == $offer_from->format('m')){
          if($offer_from->format('d') >= 7){
            $part_offers[$test_flag] = $part_offers[$test_flag] + 1;
          }
        }
      endforeach;//Endforeach
      $test_flag++;
    }
    $part_offers[] = 0;
    return $part_offers;
  }

  /**
   * [bookingsOfOffers description]
   * @param  [type] $offers    [description]
   * @param  [type] $min_start [description]
   * @param  [type] $max_end   [description]
   * @param  [type] $no_months [description]
   * @return [type]            [description]
   */
  public function bookingsOfOffers($offers, $min_start, $max_end, $no_months){
    $booking_offers = array();
    $offer_flag = 0;
    $no_bookings = array();
    foreach ($offers as $offer):
      $test_flag = 1;
      while($test_flag <= $no_months){
        $min_date = clone $min_start;
        $offer_start = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        if($test_flag > 1):
          $min_date->addMonths($test_flag-1);
        endif;
        if(!isset($booking_offers[$offer_flag][$test_flag])):
          $booking_offers[$offer_flag][$test_flag] = "n/a";
        endif;
        if(!isset($no_bookings[$test_flag])):
          $no_bookings[$test_flag] = 0;
        endif;
        $offer_emails = $offer->emails;
        foreach ($offer_emails as $email) {
          if($email->type == "microsite1" || $email->type == "microsite2_final"){
            $email_report = $email->offer_reports;
            if($email_report->for_year == $min_date->format('Y') && $email_report->for_month == $min_date->format('M')){
              if($email->status == "pending"):
                if($email_report->no_of_final_bookings == null):
                  $booking_offers[$offer_flag][$test_flag] = "n/a";
                else:
                  $booking_offers[$offer_flag][$test_flag] = $email_report->no_of_final_bookings;
                  $no_bookings[$test_flag] = $no_bookings[$test_flag]+$email_report->no_of_final_bookings;
                endif;
              else:
                if($email_report->no_of_final_bookings == null):
                  $booking_offers[$offer_flag][$test_flag] = "no answer";
                else:
                  $booking_offers[$offer_flag][$test_flag] = $email_report->no_of_final_bookings;
                  $no_bookings[$test_flag] = $no_bookings[$test_flag]+$email_report->no_of_final_bookings;
                endif;
              endif;
            }//End email report year-month compare, if
          }//End, if email type is reporting
        }//End offer emails, foreach
        $test_flag++;
      }//End while, nos. months
      $offer_flag++;
    endforeach;//Endforeach offers
    $booking_offers['total_bookings'] = $no_bookings;
    return $booking_offers;
  }

  /**
   * [aggregateFunction description]
   * @param  [type] $offers         [description]
   * @param  [type] $min_start      [description]
   * @param  [type] $max_end        [description]
   * @param  [type] $booking_offers [description]
   * @return [type]                 [description]
   */
  public function aggregateReporting($offers, $min_start, $max_end, $no_months){
    $booking_offers = $this->bookingsOfOffers($offers, $min_start, $max_end, $no_months);
    $aggregate_report = array();
    $key_flag = 0;
    unset($booking_offers['total_bookings']);
    foreach ($booking_offers as $booking_key => $booking_info):
      foreach ($booking_info as $key => $value):
        if(!isset($aggregate_report['n/a'][$key])):
          $aggregate_report['n/a'][$key] = 0;
        endif;
        if(!isset($aggregate_report['no_answer'][$key])):
          $aggregate_report['no_answer'][$key] = 0;
        endif;
        if(!isset($aggregate_report['report_back'][$key])):
          $aggregate_report['report_back'][$key] = 0;
        endif;
        if($value == "n/a"){
          $aggregate_report['n/a'][$key] = $aggregate_report['n/a'][$key]+1;
        }
        elseif ($value == "no answer") {
          $aggregate_report['no_answer'][$key] = $aggregate_report['no_answer'][$key]+1;
        }
        else {
          $aggregate_report['report_back'][$key] = $aggregate_report['report_back'][$key]+1;
        }
      endforeach;//End booking info, foreach
      $key_flag++;
    endforeach;//End booking offes, foreach
    return $aggregate_report;
  }//End aggregateReporting function

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    $search_keys = $this->search_keys;
    $query = $this->where('for_year', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $results = $query->get();
    return $results;
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
    $search_keys = $this->search_keys;
    $query = $this->where('for_year', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $results = $query->get();
    $results = $results->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project_user) {
                  // if($item->offers){
                    if($item->offers->project_id == $project_user->id){
                      $flag = true;
                    }
                  // }
                }
                return $flag;
              });
    return $results;
  }


}//End Class Offer_Report