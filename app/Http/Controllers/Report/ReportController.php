<?php

/**
 * ReportController.php
 *
 * Handles report module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Offer as Offer;
use App\Models\Offer_Report as OfferReport;
use App\Models\Disabled_Offer as DisabledOffer;
use App\Models\Placement as Placement;
use Illuminate\Support\Collection as Collection;
use Exception;
use Excel;
use Response;
use Validator;
use File;
use View;
use Auth;
use DB;
use DateTime;
use Schema;


class ReportController extends MyController
{
  public $offer_model;
  public $offer_report_model;
  public function __construct()
  {
    parent::__construct();
    $this->offer_model = new Offer;
    $this->offer_report_model = new OfferReport;
    $this->disabled_offer_model = new DisabledOffer;
    $this->placement_model = new Placement;
  }

  /**
   * [getMicrositeFeedback description]
   * @return [type] [description]
   */
  public function getMicrositeFeedback(){
    try {
      $offer_reports = $this->offer_report_model->getAllOfferReports();
      if(\Auth::user()->user_type == "general"){
        $projects_user = $this->commonGetProjects();
        $offer_reports = $this->offer_report_model->getMicrositesData($projects_user);
        return view('reports.microsite_feedback_summary', compact('offer_reports'));
      }
      return view('reports.microsite_feedback_summary', compact('offer_reports'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getMicrosite1Feedback description]
   * @return [type] [description]
   */
  public function getMicrosite1Feedback(){
    try {
      $offer_reports = $this->offer_report_model->getMicrositeReports('microsite1');
      if(\Auth::user()->user_type == "general"){
        $projects_user = $this->commonGetProjects();
        $offer_reports = $this->offer_report_model->micrositeReportsByUser($projects_user, 'microsite1');
        return view('reports.microsite1_feedback_summary', compact('offer_reports'));
      }
      return view('reports.microsite1_feedback_summary', compact('offer_reports'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getMicrosite2Feedback description]
   * @return [type] [description]
   */
  public function getMicrosite2Feedback(){
    try {
      $offer_reports = $this->offer_report_model->getMicrositeReports('microsite2');
      if(\Auth::user()->user_type == "general"){
        $projects_user = $this->commonGetProjects();
        $offer_reports = $this->offer_report_model->micrositeReportsByUser($projects_user, 'microsite2');
        return view('reports.microsite2_feedback_summary', compact('offer_reports'));
      }
      return view('reports.microsite2_feedback_summary', compact('offer_reports'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getOfferActivation description]
   * @return [type] [description]
   */
  public function getOfferActivation(){
    try {
      $offers = $this->offer_model->getAllOffers();
      if(\Auth::user()->user_type == "general"){
        $projects_user = $this->commonGetProjects();
        $offers = $this->offer_report_model->activationReportsByProject($projects_user, $offers);
        return view('reports.offer_activation_report', compact('offers'));
      }
      return view('reports.offer_activation_report', compact('offers'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getShow description]
   * @param  [type] $report_id [description]
   * @return [type]            [description]
   */
  public function getShow($report_id){
    $report_details = $this->offer_report_model->getReportById($report_id);
    return view('reports.show_view', compact('report_details'));
  }

  /**
   * [getCallCenterBriefing description]
   * @return [type] [description]
   */
  public function getExportCallCenter(){
    try {
      $projects = $this->commonGetProjects(); //get projects
      $placements = $this->placement_model->getAllPlacements();
      if(Auth::user()->user_type == "general"):
        $placements = $this->placement_model->getPlacementByUserProject($projects);
      endif;
      return view('reports.exports.call_center_briefing_index_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [postExportCallCenter description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postExportCallCenter(Request $request){
    //validate request input data
    $validator = Validator::make($request->all(), $this->offer_model->getCallCenterValidation($request));
    if ($validator->fails()) {
      return redirect('report/export-call-center')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator if
    $data = $this->constructData($request);
    $content = $this->callCenterBriefingContent($data);
    File::put(public_path().'/reports/call-center-briefing.doc', $content);
    $projects = $this->commonGetProjects(); //get projects
    $placements = $this->placement_model->getAllPlacements();
    if(Auth::user()->user_type == "general"):
      $placements = $this->placement_model->getPlacementByUserProject($projects);
    endif;
    $notice = "The file is being downloaded.";
    $index_view = view('reports.exports.call_center_briefing_index_view', ['projects' => $projects, 'common_notice' => $notice]);
    $view = $index_view->render();
    $link = url('report/call-center-briefing');
    return response()->json(['view' => $view, 'link' => $link]);
  }

  /**
   * [getCallCenterBriefing description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getCallCenterBriefing(){
    $headers = array(
      "Content-type"=>"text/html",
      "Content-Disposition"=>"attachment;Filename=call-center-briefing.doc"
    );
    $file = public_path().'/reports/call-center-briefing.doc';
    return Response::download($file,'call-center-briefing.doc',$headers);
  }

  /**
   * [callCenterExportData description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function callCenterBriefingContent($data){
    $offers = $this->offer_model->callCenterBriefingData($data);
    $data['project_info'] = $this->my_project_model->getProjectById($data['project']);
    return view('reports.exports.call_center_briefing_data', compact('offers', 'data'));
  }

  /**
   * [getVendor description]
   * @return [type] [description]
   */
  public function getVendor(){
    \Artisan::call('vendor:publish');
  }

  /**
   * [getOfferMonthReport description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function getOfferMonthReport(Request $request){
    try {
      $projects = $this->commonGetProjects(); //get projects
      $placements = $this->placement_model->getAllPlacements();
      if(Auth::user()->user_type == "general"):
        $placements = $this->placement_model->getPlacementByUserProject($projects);
      endif;
      return view('reports.exports.offer_month_index_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [postOfferMonthReport description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postOfferMonthReport(Request $request){
    //validate request input data
    $validator = Validator::make($request->all(), $this->offer_model->getOfferReportingValidation($request));
    if ($validator->fails()) {
      return redirect('report/offer-month-report')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator if
    $data = $this->constructData($request);
    // try {
      $offers = $this->offer_model->offerReportingOffers($data);
      $user_data = $this->offerMonthUserData($data);
      $this->prepareOfferReport($offers, $user_data);
      $notice = "The file is being downloaded.";
      $projects = $this->commonGetProjects(); //get projects
      $placements = $this->placement_model->getAllPlacements();
      if(Auth::user()->user_type == "general"):
        $placements = $this->placement_model->getPlacementByUserProject($projects);
      endif;
      $index_view = view('reports.exports.offer_month_index_view', ['projects' => $projects, 'placements' => $placements, 'common_notice' => $notice]);
      $view = $index_view->render();
      $link = url('report/offer-month-report');
      return response()->json(['view' => $view, 'link' => $link]);
    // } catch (\Exception $e) {
      // return response()->json(['exc' => utf8_encode($e->getMessage())]);
    // }
  }

  /**
   * [offerMonthUserData description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function offerMonthUserData($data){
    $user_data['project_info'] = $this->my_project_model->getProjectById($data['project']);
    if (isset($data['placement_ids'])) {
      $user_data['placements'] = $this->placement_model->getPlacementNames($data['placement_ids']);
    }
    if ($data['date_from'] && $data['date_until']) {
      $user_data['date_from'] = $data['date_from'];
      $user_data['date_until'] = $data['date_until'];
    }
    return $user_data;
  }

  /**
   * [prepareOfferReport description]
   * @param  [type] $offers [description]
   * @return [type]         [description]
   */
  public function prepareOfferReport($offers, $data){
    set_time_limit(0);
    $time = \Carbon\Carbon::now()->format('d-M-Y');
    Excel::create('angebotsmanager-reporting', function($excel) use ($offers, $data){
      //first sheet
      $excel->sheet('Offers & Placements', function($sheet) use ($offers, $data){
        $this->offerPlacementReport($sheet, $offers, $data);
        //phpexcel cell text wrap
        $sheet->getStyle('A3:C9999')
              ->getAlignment()->setWrapText(true);
        //excel row height
        $sheet->getDefaultRowDimension()->setRowHeight(15);
      });
      //second sheet
      $excel->sheet('Partner Reporting', function($sheet) use ($offers, $data){
        $this->partnerReporting($sheet, $offers, $data);
        //phpexcel cell text wrap
        $sheet->getStyle('A2:D9999')
              ->getAlignment()->setWrapText(true);
        //excel row height
        $sheet->getDefaultRowDimension()->setRowHeight(15);
      });
    })->save('xls', public_path().'/reports/');
    // return Response::download(public_path().'/reports/angebotsmanager-reporting'.'.xls');
  }

  /**
   * [getDownloadOfferMonthReport description]
   * @return [type] [description]
   */
  public function getDownloadOfferMonthReport(){
    $headers = array(
      "Content-type"=>"text/html",
      "Content-Disposition"=>"attachment;Filename=angebotsmanager-reporting.xls"
    );
    $file = public_path().'/reports/angebotsmanager-reporting.xls';
    return Response::download($file,'angebotsmanager-reporting.xls',$headers);
  }

  /**
   * [reportOffers description]
   * @return [type] [description]
   */
  public function reportOffers(){
    $disabled_offers = $this->disabled_offer_model->getAllByUser(Auth::user()->id);
    if(Auth::user()->user_type == "general"):
      $projects = $this->commonGetProjects();
      $offers = $this->offer_model->getOfferByUserProject($disabled_offers, $projects);
    else:
      $offers = $this->offer_model->getOfferByUser($disabled_offers);
    endif;
    return $offers;
  }

  /**
   * [getOfferMonth description]
   * @return [type] [description]
   */
  public function getOfferMonth(){
    set_time_limit(0);
    $offers = $this->reportOffers();
    if($offers->isEmpty()){
      echo "You have not created any offers yet. Please create some offers first.";
    }
    else{
      try {
        $time = \Carbon\Carbon::now()->format('d-M-Y');
        Excel::create('angebotsmanager-reporting', function($excel){
          //first sheet
          $excel->sheet('Offers & Placements', function($sheet){
            $offers = $this->reportOffers();
            $this->offerPlacementReport($sheet, $offers);
            //phpexcel cell text wrap
            $sheet->getStyle('A3:C9999')
                  ->getAlignment()->setWrapText(true);
            //excel row height
            $sheet->getDefaultRowDimension()->setRowHeight(15);
          });
          //second sheet
          $excel->sheet('Partner Reporting', function($sheet){
            // $offers = Offer::all();
            $offers = $this->reportOffers();
            $this->partnerReporting($sheet, $offers);
            //phpexcel cell text wrap
            $sheet->getStyle('A2:D9999')
                  ->getAlignment()->setWrapText(true);
            //excel row height
            $sheet->getDefaultRowDimension()->setRowHeight(15);
          });
        })->save('xls', public_path().'/reports/');
        return Response::download(public_path().'/reports/angebotsmanager-reporting'.'.xls');
      } catch (\Exception $e) {
        return response()->json(['exc' => utf8_encode($e->getMessage())]);
      }
    }
  }//End getTestExcel function

  /**
   * [offerPlacementReport description]
   * @param  [type] $sheet [description]
   * @return [type]        [description]
   */
  public function offerPlacementReport($sheet, $offers, $user_data){
    $min_month_start = $max_month_date = null;
    if ($offers->isEmpty()) {
      $data = ["min_start" => null, "max_end" => null, 'no_months' => null];
      $sheet->loadView('reports.exports.offer_month_sheet1', ['offers' => $offers, 'data' => $data, 'user_data' => $user_data]);
    }
    else {
      $offer_flag = 1;
      foreach ($offers as $offer):
        $placement_flag = 1;
        foreach ($offer->placements as $placement):
          $placement_start = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_start_date);
          if($placement_flag == 1 && $min_month_start == null):
            $min_month_start = $placement_start;
          else:
            if($placement_start < $min_month_start):
              $min_month_start = $placement_start;
            endif;
          endif;
          $placement_flag++;
        endforeach;
        $offer_valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        if($placement_flag == 1 && $min_month_start == null){
          $min_month_start = $offer_valid_from;
        }
        else {
          if ($offer_valid_from < $min_month_start) {
            $min_month_start = $offer_valid_from;
          }
        }
        $offer_end = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        if($offer_flag == 1):
          $max_month_date = $offer_end;
        else:
          if($offer_end > $max_month_date):
            $max_month_date = $offer_end;
          endif;
        endif;
        $offer_flag++;
      endforeach;
      try {
        $min_month_start = \Carbon\Carbon::createFromFormat('d/m/Y', $min_month_start);
      } catch (\Exception $e) {
        $min_month_start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $min_month_start)->format('d/m/Y');
      }
      $min_month_start = $this->offer_report_model->verifyOfferReportDates($user_data, $min_month_start, 'min');
      $max_month_date = $this->offer_report_model->verifyOfferReportDates($user_data, $max_month_date, 'max');
      $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_month_date));
      $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_month_start));
      $count_months = 0;
      while ($min <= $max) {
        $min->modify('first day of next month');
        $count_months++;
      }
      $data = ["min_start" => $min_month_start, "max_end" => $max_month_date, 'no_months' => $count_months];
      //load offer reporting view
      $sheet->loadView('reports.exports.offer_month_sheet1', ['offers' => $offers, 'data' => $data, 'user_data' => $user_data]);
    }
  }//End offerPlacementReport function


  /**
   * [partnerReportingSheet description]
   * @param  [type] $sheet [description]
   * @return [type]        [description]
   */
  public function partnerReporting($sheet, $offers, $user_data){
    $min_month_start = $max_month_date = null;
    if ($offers->isEmpty()) {
      $data = ["min_start" => null, "max_end" => null, 'no_months' => null];
      $sheet->loadView('reports.exports.offer_month_sheet2', ['offers' => $offers, 'data' => $data, 'user_data' => $user_data]);
    }
    else {
      $offer_flag = 1;
      foreach ($offers as $offer):
        $placement_flag = 1;
        foreach ($offer->placements as $placement):
          $placement_start = \Carbon\Carbon::createFromFormat('Y-m-d', $placement->pivot->placement_start_date);
          if($placement_flag == 1 && $min_month_start == null):
            $min_month_start = $placement_start;
          else:
            if($placement_start < $min_month_start):
              $min_month_start = $placement_start;
            endif;
          endif;
          $placement_flag++;
        endforeach;
        $offer_valid_from = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_from);
        if($placement_flag == 1 && $min_month_start == null){
          $min_month_start = $offer_valid_from;
        }
        else {
          if ($offer_valid_from < $min_month_start) {
            $min_month_start = $offer_valid_from;
          }
        }
        $offer_end = \Carbon\Carbon::createFromFormat('d/m/Y', $offer->valid_until);
        if($offer_flag == 1):
          $max_month_date = $offer_end;
        else:
          if($offer_end > $max_month_date):
            $max_month_date = $offer_end;
          endif;
        endif;
        $offer_flag++;
      endforeach;
      try {
        $min_month_start = \Carbon\Carbon::createFromFormat('d/m/Y', $min_month_start);
      } catch (\Exception $e) {
        $min_month_start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $min_month_start)->format('d/m/Y');
      }
      $min_month_start = $this->offer_report_model->verifyOfferReportDates($user_data, $min_month_start, 'min');
      $max_month_date = $this->offer_report_model->verifyOfferReportDates($user_data, $max_month_date, 'max');
      $max = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $max_month_date));
      $min = new DateTime(\Carbon\Carbon::createFromFormat('d/m/Y', $min_month_start));
      $count_months = 0;
      while ($min <= $max) {
        $min->modify('first day of next month');
        $count_months++;
      }
      $valid_offers = $this->offer_report_model->getValidOffers($offers, $min_month_start, $max_month_date);
      $part_offers = $this->offer_report_model->getValidPartOffers($offers, $min_month_start, $max_month_date);
      $msone_emails = $this->offer_report_model->getValidMsoneEmails($offers, $min_month_start, $max_month_date);
      $mstwo_emails = $this->offer_report_model->getValidMstwoEmails($offers, $min_month_start, $max_month_date);
      //offer-ms data reported
      $reported_requests = $this->offer_report_model->getValidRequests($offers, $min_month_start, $max_month_date);
      $reported_reservations = $this->offer_report_model->getValidReservations($offers, $min_month_start, $max_month_date);
      $reported_participants = $this->offer_report_model->getValidParticipants($offers, $min_month_start, $max_month_date);
      $offers_commn_yes = $this->offer_report_model->validOffersByCommnPackage($offers, $min_month_start, $max_month_date, 'yes');
      $offers_commn_no = $this->offer_report_model->validOffersByCommnPackage($offers, $min_month_start, $max_month_date, 'no');
      $reported_requests_feedbacks = $this->offer_report_model->reportedRequestsFeedbacks($offers, $min_month_start, $max_month_date);
      $reported_reservations_feedbacks = $this->offer_report_model->reportedReservationsFeedbacks($offers, $min_month_start, $max_month_date);
      $reported_participants_feedbacks = $this->offer_report_model->reportedParticipantsFeedbacks($offers, $min_month_start, $max_month_date);
      $data = ["min_start" => $min_month_start, "max_end" => $max_month_date, 'no_months' => $count_months, 'valid_offers' => $valid_offers, 'part_offers' => $part_offers, 'msone_emails' => $msone_emails, 'mstwo_emails' => $mstwo_emails, 'reported_requests' => $reported_requests, 'reported_reservations' => $reported_reservations, 'reported_participants' => $reported_participants, 'offers_commn_yes' => $offers_commn_yes, 'offers_commn_no' => $offers_commn_no, 'reported_requests_feedbacks' => $reported_requests_feedbacks, 'reported_reservations_feedbacks' => $reported_reservations_feedbacks, 'reported_participants_feedbacks' => $reported_participants_feedbacks];
      //load offer reporting view
      $sheet->loadView('reports.exports.offer_month_sheet2', ['offers' => $offers, 'data' => $data, 'user_data' => $user_data]);
    }
  }//End partnerReporting Function

  /**
   * [getRawDataExport description]
   * @return [type] [description]
   */
  public function getRawDataExport(){
    if(Auth::user()->user_type != "admin"){
      return "You are not granted access to this functionality.";
    }
    $tables = DB::select('SHOW TABLES');
    $db_name = DB::connection()->getDatabaseName();
    $db_name1 = 'Tables_in_'.$db_name;
    //loop through each tablenames
    foreach ($tables as $table) {
      $table_name = $table->$db_name1;
      //fetch associative arrays from table
      DB::connection()->setFetchMode(\PDO::FETCH_ASSOC);
      $columns[$table_name] = DB::table($table_name)->select('*')->get();
      $db_summary[$table_name] = Schema::getColumnListing($table_name);
    }
    // echo "<pre>";
    // print_r($tables);
    // print_r($db_summary);
    Excel::create('raw_data_angebotmanager', function($excel) use ($columns, $db_summary){
      $excel->sheet('SUMMARY', function($sheet) use ($db_summary){
        $sheet->loadView('reports.exports.raw_data_summary', ['db_summary' => $db_summary]);
      });
      foreach ($columns as $key=>$column){
        $excel->sheet($key, function($sheet) use ($column){
          if(empty($column)){
            $column[] = "There are no datas in the table";
          }
          $sheet->fromArray($column);
        });
      }
    })->save('xls', public_path().'/reports/');
    // return response for download
    return Response::download(public_path().'/reports/raw_data_angebotmanager.xls');
  }// End getRawDataExport funciton

  /**
   * [getAllContactsExport description]
   * @return [type] [description]
   */
  public function getAllContactsExport(){
    $projects = $this->commonGetProjects();
    return view('reports.exports.all_contacts_export_index_view', compact('projects'));
  }

  /**
   * [postProjectsPlacements description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postProjectsPlacements(Request $request){
    $data = $this->constructData($request);
    $placements = $this->my_placement_model->getProjectsPlacements($data['project_ids']);
    return view('reports.exports.all_contacts_project_placements', compact('placements'));
  }

  /**
   * [postAllContactsExport description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postAllContactsExport(Request $request){
    $validator = Validator::make($request->all(), $this->offer_model->all_contacts_export_validation);
    $validator->setAttributeNames($this->offer_model->all_contacts_export_attributes);
    if ($validator->fails()) {
      return redirect('report/all-contacts-export')
        ->withErrors($validator)
        ->withInput();
    };
    $data = $this->constructData($request);
    //get all valid offers as per projects and placements
    $offers = $this->offer_model->offersByProjectPlacements($data['project_ids'], $data['placement_ids']);
    //user input project names
    $project_names = $this->my_project_model->getProjectNames($data['project_ids']);
    //user input placement names
    $placement_names = $this->my_placement_model->getPlacementNames($data['placement_ids']);
    //get other contacts
    if(\Auth::user()->user_type == "general"){
      $non_assigned_contacts = new Collection;
    }
    else{
      $non_assigned_contacts = new Collection;
      if(in_array('n_a', $data['project_ids'])){
        $non_assigned_contacts = $this->my_contact_model->getNonAssignedContacts();
      }
    }
    //excel file creation
    Excel::create('all_contacts_angebotsmanager', function($excel) use ($offers, $project_names, $placement_names, $non_assigned_contacts){
      $excel->sheet('test', function($sheet) use ($offers, $project_names, $placement_names, $non_assigned_contacts) {
        $sheet->loadView('reports.exports.all_contacts_export_data', ['offers' => $offers, 'project_names' => $project_names, 'placement_names' => $placement_names, 'non_assigned_contacts' => $non_assigned_contacts]);
      });
      /*foreach($excel->getActiveSheet()->getRowDimensions() as $rd) {
        $rd->setRowHeight(-1);
      }*/
    })->save('xls', public_path().'/reports/');
    $projects = $this->commonGetProjects();
    $notice = "The file is being downloaded.";
    $index_view = view('reports.exports.all_contacts_export_index_view', ['projects' => $projects, 'common_notice' => $notice]);
    $view = $index_view->render();
    $link = url('report/download-all-contacts');
    return response()->json(['view' => $view, 'link' => $link]);
  }//End postAllContactsExport function

  /**
   * [getDownloadAllContacts description]
   * @return [type] [description]
   */
  public function getDownloadAllContacts(){
    $headers = array(
      "Content-type"=>"text/html",
      "Content-Disposition"=>"attachment;Filename=all_contacts_angebotsmanager.xls"
    );
    $file = public_path().'/reports/all_contacts_angebotsmanager.xls';
    return Response::download($file,'all_contacts_angebotsmanager.xls',$headers);
  }

}//End Class ReportController
