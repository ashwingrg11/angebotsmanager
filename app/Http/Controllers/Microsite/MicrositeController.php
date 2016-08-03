<?php

/**
 * MicrositeController.php
 *
 * Handles microsite module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Microsite;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Offer as Offer;
use App\Models\Offer_Report as OfferReport;
use App\Models\Microsite2_Report as Microsite2;
use App\Events\OfferExtension;
use Validator;
use Exception;
use Event;

class MicrositeController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Microsite Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the functionalities related to microsite1-2(en & de)
  | module. It includes offer, offer_report and microsite2 models for
  | it's related operations.
  |
  */
  public $offer_model;
  public $offer_report_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    //load offer, offer_report, microsite2 models
    $this->offer_model = new Offer;
    $this->offer_report_model = new OfferReport;
    $this->microsite2_model = new Microsite2;
  }

  /**
   * Return microsite-en view by varifying the requested offer report code.
   * @param  int $microsite
   * @param  string $code [offer report code]
   * @return mixed
   */
  public function getEn($microsite, $code){
    //validate microsite and offer report code
    $verify_offer_details = $this->validateMicrosite($microsite, $code);
    if($verify_offer_details == false){
      abort(404, "commn_no_en");
    }//End invalid request, if
    if($verify_offer_details == "commn_no"){
      abort(404, "commn_no_en");
    }
    return $this->commonMicrositeGet($verify_offer_details, 'en', $microsite);
  }//End getEn function

  /**
   * Validate user inputs and update microsite1/microsite2 data/record and send
   * offer extension email if it is requested.
   * @param  \Illumiinate\Http\Request $request
   * @return mixed
   */
  public function postEn(Request $request){
    //get offer report code, input
    $report_code = $request->input('report_code');
    //get microsite type, input
    $microsite = $request->input('microsite');
    //validate user inputs
    $validator = Validator::make($request->all(), $this->offer_report_model->getValidationRules($request));
    //set validation attributes name
    $validator->setAttributeNames($this->offer_report_model->validation_attribute);
    if ($validator->fails()) { //if validation fails
      //Redirect to microsite page with validator errors & inputs
      return redirect('microsite/en/'.$microsite.'/'.$report_code)
          ->withErrors($validator)
          ->withInput();
    }//End validation fails, if
    try {
      $data = $this->constructData($request);
      //trigger offer extension event if user has requested to extend offer
      if(isset($data['extend_offer'])){
        \Event::fire(new OfferExtension($report_code));
      }//End if offer extension request
      $db_flag = $this->commonMicrositeFunction($data);
      if($db_flag){//if microsite data updated
        //Redirect to microsite page with updated success notice
        return redirect('microsite/en/'.$microsite.'/'.$report_code)->with('updated', 'Your data has been submitted successfully.');
      }else{
        throw new Exception("Error Processing Request");
      }//End update microsite data, else
    } catch (\Exception $e) {
      return redirect('microsite/en/'.$microsite.'/'.$report_code)->with('exc', $e->getMessage());
    }//End try-catch
  }//End postEn function

  /**
   * Return view for microsite-de by varifying the requested offer report code.
   * @param  int $microsite
   * @param  string $code [offer report code]
   * @return mixed
   */
  public function getDe($microsite, $code){
    //validate microsite request
    $verify_offer_details = $this->validateMicrosite($microsite, $code);
    if($verify_offer_details == false){
      abort(404, "commn_no_de");
    }
    if($verify_offer_details == "commn_no"){
      abort(404, "commn_no_de");
    }
    return $this->commonMicrositeGet($verify_offer_details, 'de', $microsite);
  }//End getDe cuntion

  /**
   * Return microsite view with offer and report details.
   * @param  array $report_details
   * @param  string $language
   * @param  int $microsite
   * @return mixed
   */
  public function commonMicrositeGet($report_details, $language, $microsite){
    //get offer details
    $offer_details = $this->offer_model->getOfferById($report_details[0]->offers->id);
    //get microsite reports
    $offer_reports = $this->offer_report_model->getMicrositeReportByOffer($report_details[0]->offers->id);
    $current_offer_report = $report_details;
    if($microsite == 1)://if microsite type is 1
      return view('microsites.'.$language.'.microsite1', compact('offer_details', 'offer_reports', 'current_offer_report'));
    endif;
    //return microsite2 view with all necessary offer details
    return view('microsites.'.$language.'.microsite2', compact('offer_details', 'offer_reports', 'current_offer_report'));
  }//End commonMicrositeGet function

  /**
   * Validate user inputs and update microsite1/microsite2 (de) data/record and send
   * offer extension email if it is requested.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postDe(Request $request){
    //get offer repot code, input
    $report_code = $request->input('report_code');
    //get microsite type, input
    $microsite = $request->input('microsite');
    //validate user inputs
    $validator = Validator::make($request->all(), $this->offer_report_model->getValidationRules($request));
    //set validation attribute names
    $validator->setAttributeNames($this->offer_report_model->validation_attribute);
    if ($validator->fails()) { //if validation fails
      //Redirect to microsite(de) page with validator errors & inputs
      return redirect('microsite/de/'.$microsite.'/'.$report_code)
          ->withErrors($validator)
          ->withInput();
    }//End validator fails, if
    try {
      $data = $this->constructData($request);
      //triggger offer extension event if user requests to extend offer
      if(isset($data['extend_offer'])){
        \Event::fire(new OfferExtension($report_code));
      }//End if extend offer request
      $db_flag = $this->commonMicrositeFunction($data);
      if($db_flag){ //if microsite data updated
        //Redirect to microsite(de) page with updated success notice
        return redirect('microsite/de/'.$microsite.'/'.$report_code)->with('updated', 'Ihre Daten wurden erfolgreich übermittelt.');
      }else{
        throw new Exception("Error Processing Request");
      }//End update microsite data fails, else
    } catch (\Exception $e) {
      return redirect('microsite/de/'.$microsite.'/'.$report_code)->with('exc', $e->getMessage());
    }//End try-catch
  }//End postDe function

  /**
   * Validate microsites and return report details for valid microsites.
   * @param  string $report_code
   * @return mixed
   */
  public function validateMicrosite($type, $report_code){
    if($type != 1 && $type != 2){
      return false;
    }//End if microsite is not 1or2
    $report_details = $this->offer_report_model->getReportByCode($report_code);
    //if report details is empty and offer has communication pakage 'no'
    if($report_details->isEmpty()){
      return false;
    }
    elseif($report_details[0]->offers->has_communication_package == "no"){
      return "commn_no";
    }
    else{ //if report details exists
      return $report_details;
    }//End report details exist, else
  }//End validateMicrosite Function

  /**
   * Get all input data and update/record offer reports of microsite 1&2.
   * @param  array $data [all input data from microsites]
   * @return mixed
   */
  public function commonMicrositeFunction($data){
    $new_data = $data;
    //if microsite page request doesnot have offer report id
    if(!isset($data['offer_reports_id'])):
      throw new Exception('Could not perform your action now.');
    endif;
    $data['comments'] = $data['limitedtextarea'];
    //offer id, input
    $offer_id = $data['offer_id'];
    //offer report id, input
    $offer_report_id = $data['offer_reports_id'];
    $microsite = $data['microsite']; //microsite type, input
    $report_data['extension_request'] = 'no';
    if(isset($data['extend_offer'])){
      $report_data['extension_request'] = 'yes';
    }
    //new carbon date object for report date
    $report_data['report_date'] = \Carbon\Carbon::now()->format('Y-m-d');
    $report_data['no_of_requests'] = $data['no_of_requests'];
    $report_data['no_of_final_bookings'] = $data['no_of_final_bookings'];
    $report_data['no_of_packages_sold'] = $data['no_of_packages_sold'];
    $report_data['comments'] = $data['limitedtextarea'];
    if($microsite == 1):
      //update microsite1 data
      return $this->offer_report_model->updateOfferReport($offer_report_id, $report_data);
    else:
      //update microsite2 record/data
      $this->offer_report_model->updateOfferReport($offer_report_id, $report_data);
      return $this->microsite2_model->saveMicrosite2Data($offer_report_id, $new_data);
    endif;
  }//End commonMicrositeFunction function

}//End Class, MicrositeController
