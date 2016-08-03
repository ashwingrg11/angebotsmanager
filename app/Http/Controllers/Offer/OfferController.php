<?php

/**
 * OfferController.php
 *
 * Handles offer module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Offer;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Project as Project;
use App\Models\Partner as Partner;
use App\Models\Offer as Offer;
use App\Models\Contact as Contact;
use App\Models\Contract_Status as ContractStatus;
use App\Models\Offer_Status as OfferStatus;
use App\Models\Offer_Offer_Status as OfferOfferStatus;
use App\Models\Offer_Placement as OfferPlacement;
use App\Models\Placement as Placement;
use App\Models\Offer_Report as OfferReport;
use App\Models\Disabled_Offer as DisabledOffer;
use App\Models\Microsite2_Report as Microsite2Report;
use App\Models\Email as Email;
use App\Models\User as User;
use App\Models\Offer_User as OfferUser;
use App\Models\Country as Country;
use App\Models\Email_Content as EmailContent;
use App\Events\OfferWasCreated;
use Exception;
use Validator;
use Event;
use Auth;

class OfferController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Offer Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities and related operations
  | of offer module. It includes all the essential models for its operations.
  | It also requires laravel facades for its operations.
  |
  */

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    //Include default auth middleware for all the functions inside this
    //controller except offer activation function.
    $this->middleware('auth', ['except' => ['getActivate']]);
    //loading all the essential model classes
    $this->project_model = new Project;
    $this->contract_status_model = new ContractStatus;
    $this->partner_model = new Partner;
    $this->offer_status_model = new OfferStatus;
    $this->offer_model = new Offer;
    $this->offer_offer_status_model = new OfferOfferStatus;
    $this->offer_placement_model = new OfferPlacement;
    $this->placement_model = new Placement;
    $this->contact_model = new Contact;
    $this->offer_report_model = new OfferReport;
    $this->disabled_offer_model = new DisabledOffer;
    $this->microsite2_report_model = new Microsite2Report;
    $this->email_model = new Email;
    $this->user_model = new User;
    $this->offer_user_model = new OfferUser;
    $this->country_model = new Country;
    $this->email_content_model = new EmailContent;
  }

  /**
   * Get available offers and return offer listing page with the same data/info.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    try {
      $offers = $this->commonListOffers();
      return view('offers.list_view', compact('offers'));
    } catch (\Exception $e) {
      return response()->json(['exc' => $e->getMessage()]);
    }//End try-catch
  }//End getIndex funtion

  /**
   * [commonListOffer description]
   * @return [type] [description]
   */
  public function commonListOffers(){
    //First we need to get offers those are disabled by logged in user, and filter
    //those offers by project if the type of logged in user is general
    $disabled_offers = $this->disabled_offer_model->getAllByUser(Auth::user()->id);
    // if(Auth::user()->user_type == "general"):
    if(Auth::user()->user_type == "general" || Auth::user()->user_type == "viewer"):
      $projects = $this->commonGetProjects();
      $offers = $this->offer_model->getOfferByUserProject($disabled_offers, $projects);
    else:
      $offers = $this->offer_model->getOfferByUser($disabled_offers);
    endif;
    return $offers;
  }

  /**
   * Return offer registration page with all the necessary data/info.
   * @return \Illuminate\Http\Response
   */
  public function getCreate(){
    try {
      //First we need to retrieve some necessary data/info for creating an offer i.e.
      //offfer-contract status, projects, ji users and return offer create
      //page with the same retrieved info.
      $contract_statuses = $this->contract_status_model->getAllContractStatus();
      $offer_statuses = $this->offer_status_model->getAllOfferStatus();
      $projects = $this->commonGetProjects();
      $ji_users = $this->user_model->allUsers();
      $countries = $this->country_model->allCountries();
      $partners = $this->partner_model->getAllPartners();
      if(\Auth::user()->user_type == "general"){
        $partners = $this->partner_model->getPartnerByUserProject($projects);
      }
      return view('offers.add_view', compact('projects', 'contract_statuses', 'offer_statuses', 'ji_users', 'countries', 'partners'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getCreate function

  /**
   * Validate incoming offer post create request and insert offer data into database.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //Set validation rules for user inputs, set validation attributes and return offer
    //create page with validation errors if validator fails.
    $validator = Validator::make($request->all(), $this->offer_model->new_offer_validation_rules);
    $validator->setAttributeNames($this->offer_model->validation_attribute);
    if ($validator->fails()) {
      return redirect('offer/create')
          ->withErrors($validator)
          ->withInput();
    };
    $data = $this->constructData($request);
    //placement ids, input
    $placement_ids = $data['placement_ids'];
    $placement_flag = $this->validateOfferPlacements($data['valid_until'], $placement_ids);
    if($placement_flag){
      return redirect('offer/create')
          ->withErrors($validator)
          ->withInput()
          ->with('placement_notice', $placement_flag);
    }
    if(isset($data['offer_status'])):
      $offer_statuses = $data['offer_status'];
    endif;
    //ji user ids, input
    $ji_user_ids = null;
    if(isset($data['ji_user_ids'])):
      $ji_user_ids = $data['ji_user_ids'];
    endif;
    unset($data['offer_status'], $data['ji_user_ids'], $data['placement_ids']);
    try {
      $data['valid_from'] = $this->getDateFormat($data['valid_from']);
      $data['valid_until'] = $this->getDateFormat($data['valid_until']);
      $data['offer_code'] = $this->verifyOfferCode();
      //Insert all the offer's info/data into database and redirect to offer listing
      //page with success notice if all info/data is inserted successfully.
      $insert_flag = $this->offer_model->insertOffer($data);
      if(empty($insert_flag)):
        throw new Exception('Could not insert offer now. Please try again.');
      endif;
      $insert_offer_id = $insert_flag->id;
      if($ji_user_ids){
        $ji_user_flag = $this->offer_user_model->insertByOffer($insert_offer_id, $ji_user_ids);
      }
      $offer_placement_data = $this->offerPlacementData($insert_offer_id, $data['valid_until'], $placement_ids);
      $offer_placement_flag = $this->offer_placement_model->insertOfferPlacement($offer_placement_data);
      if(empty($offer_placement_flag)):
        $this->offer_model->deleteOffer($insert_offer_id);
        throw new Exception('Could not insert in offer_placement table. Please try again.');
      endif;
      if(isset($offer_statuses)):
        $offer_status_flag = $this->offer_offer_status_model->insertOfferStatus($insert_offer_id, $offer_statuses);
        if(empty($offer_status_flag)):
          $this->offer_model->deleteOffer($insert_offer_id);
          throw new Exception('Could not insert in offer_offer_status table. Please try again.');
        endif;
      endif;//End if isset, offer status
      $this->updateCommunicationPackage($data['has_communication_package'], $insert_offer_id, 'new', '');
      //Redirect to offer listing page with success notice
      return redirect('offer/index')->with('added_notice', 'New offer has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * [validateOfferPlacements description]
   * @param  [type] $offer_end_date [description]
   * @param  [type] $placement_ids  [description]
   * @return [type]                 [description]
   */
  public function validateOfferPlacements($offer_end_date, $placement_ids){
    $placement_end = false;
    $placement_start = null;
    foreach ($placement_ids as $placement_id) {
      $placement_details = $this->placement_model->getPlacementById($placement_id);
      // $start_date = $placement_details[0]->start_date;
      $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $placement_details[0]->start_date);
      if($placement_start < $start_date){
        $placement_start = clone $start_date;
      }
    }
    $offer_end = \Carbon\Carbon::createFromFormat('Y-m-d', $offer_end_date);
    if(!is_null($placement_start)){
      $placement_start->addDay();
    }
    if($offer_end < $placement_start){
      return $placement_start->format('Y-m-d');
    }else{
      return false;
    }
  }

  /**
   * [offerPlacementData description]
   * @param  int $offer_id
   * @param  string $valid_until
   * @param  array $placement_ids
   * @return array
   */
  public function offerPlacementData($offer_id, $valid_until, $placement_ids){
    $data = array();
    foreach ($placement_ids as $key => $placement_id) {
      $new_placement_end = null;
      $offer_placement_info = $this->offer_placement_model->getOfferPlacement($offer_id, $placement_id);
      if(!$offer_placement_info->isEmpty()){
        // $placement_end_date = $offer_placement_info[0]->placement_end_date;
        $placement_start = $this->getDateFormat($offer_placement_info[0]->placement_start_date);
        $placement_end_date = $this->getDateFormat($offer_placement_info[0]->placement_end_date);
      }
      else {
        $placement_details = $this->placement_model->getPlacementById($placement_id);
        // $placement_end_date = $placement_details[0]->end_date;
        $placement_start = $this->getDateInstance($placement_details[0]->start_date);
        $placement_end_date = null;
        if($placement_details[0]->end_date){
          $placement_end_date = $this->getDateInstance($placement_details[0]->end_date);
        }
      }
      if($placement_end_date)://if placement end date is available
        // $placement_end = $this->getDateInstance($placement_end_date);
        if($placement_end_date > $valid_until):
          $new_placement_end = $valid_until;
        else:
          $new_placement_end = $placement_end_date;
        endif;
      else:
        $new_placement_end = $valid_until;
      endif;//End placement end date, else
      $data[] = ['offer_id' => $offer_id, 'placement_id' => $placement_id, 'placement_start_date' => $placement_start, 'placement_end_date' => $new_placement_end];
    }//End placement ids, foreach
    return $data;
  }//End offerPlacementData function

  /**
   * [getFormDataByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getFormDataByProject($project_id){
    $project_info = $this->project_model->getProjectByIdWith($project_id, ['placements', 'partners', 'offer_type_category_masterlist', 'general_offer_type_masterlist', 'detailed_offer_type_masterlist']);
    return view('offers.select_project_selects_partial', compact('project_info'));
  }

  /**
   * [getPartnerContactsByPartner description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function getPartnerContactsByPartner($partner_id){
    $partner_info = $this->partner_model->getPartnerByIdWith($partner_id, ['contacts']);
    return view('offers.select_primary_contact_partial', compact('partner_info'));
  }

  /**
   * [getSecondaryUsersData description]
   * @return [type] [description]
   */
  public function getSecondaryUsersData($ji_contact_id, $project_id){
    try {
      // $ji_users = $this->user_model->allUsers();
      $project_info = $this->project_model->getProjectById($project_id);
      $ji_users = $project_info[0]->users;
      if($ji_contact_id != 0 && !$ji_users->isEmpty()):
        $ji_users = $ji_users->filter(function($item) use($ji_contact_id){
                    if($item->id != $ji_contact_id):
                      return true;
                    endif;
                  });
      endif;
      return view('offers.select_secondary_users_partial', compact('ji_users'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }//End getSecondaryUsersData function

  /**
   * [postEdit description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postEdit(Request $request){
    try {
      $offer_id = $request->input('post_id');
      $offer_details = $this->offer_model->getOfferByIdWith($offer_id, ['offer_status']);
      $contract_statuses = $this->contract_status_model->getAllContractStatus();
      $offer_statuses = $this->offer_status_model->getAllOfferStatus();
      $countries = $this->country_model->allCountries();
      $projects = $this->commonGetProjects();
      $ji_users = $this->user_model->allUsers();
      $partners = $this->partner_model->getAllPartners();
      if(\Auth::user()->user_type == "general"){
        $partners = $this->partner_model->getPartnerByUserProject($projects);
      }
      return view('offers.add_view', compact('projects', 'contract_statuses', 'offer_statuses', 'offer_details', 'ji_users', 'countries', 'partners'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEdit function

  /**
   * [postUpdate description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postUpdate(Request $request){
    $validator = Validator::make($request->all(), $this->offer_model->new_offer_validation_rules);
    // if($request->has('project_id')){
    //   $project_info = $this->project_model->getProjectById($request->input('project_id'));
    //   $validator = Validator::make($request->all(), $this->offer_model->getNewValidationRules('title_de'));
    //   if($project_info[0]->language_id != 2){
    //     $validator = Validator::make($request->all(), $this->offer_model->getNewValidationRules('title_en'));
    //   }
    // }
    $validator->setAttributeNames($this->offer_model->validation_attribute);
    $offer_id = $request->input('offer_id');
    if ($validator->fails()) {
      $offer_details = $this->offer_model->getOfferByIdWith($offer_id, ['offer_status']);
      $contract_statuses = $this->contract_status_model->getAllContractStatus();
      $offer_statuses = $this->offer_status_model->getAllOfferStatus();
      $countries = $this->country_model->allCountries();
      $projects = $this->commonGetProjects();
      $ji_users = $this->user_model->allUsers();
      $partners = $this->partner_model->getAllPartners();
      if(\Auth::user()->user_type == "general"){
        $partners = $this->partner_model->getPartnerByUserProject($projects);
      }
      return view('offers.add_view', compact('projects', 'contract_statuses', 'offer_statuses', 'offer_details', 'ji_users', 'countries', 'partners'))->withErrors($validator);
    };
    $data = $this->constructData($request);
    $placement_ids = $data['placement_ids'];
    $placement_notice = $this->validateOfferPlacements($data['valid_until'], $placement_ids);
    if($placement_notice){
      $offer_details = $this->offer_model->getOfferByIdWith($offer_id, ['offer_status']);
      $contract_statuses = $this->contract_status_model->getAllContractStatus();
      $offer_statuses = $this->offer_status_model->getAllOfferStatus();
      $countries = $this->country_model->allCountries();
      $projects = $this->commonGetProjects();
      $ji_users = $this->user_model->allUsers();
      $partners = $this->partner_model->getAllPartners();
      if(\Auth::user()->user_type == "general"){
        $partners = $this->partner_model->getPartnerByUserProject($projects);
      }
      return view('offers.add_view', compact('projects', 'contract_statuses', 'offer_statuses', 'offer_details', 'ji_users', 'countries', 'placement_notice', 'partners'))->withErrors($validator);
    }
    if(isset($data['offer_status'])):
      $offer_statuses = $data['offer_status'];
    endif;
    $ji_user_ids = null;
    if(isset($data['ji_user_ids'])):
      $ji_user_ids = $data['ji_user_ids'];
    endif;
    $old_communication = $data['old_has_communication_package'];
    unset($data['ji_user_ids'], $data['offer_status'], $data['placement_ids'], $data['offer_id'], $data['old_has_communication_package']);
    $data['valid_from'] = $this->getDateFormat($data['valid_from']);
    $data['valid_until'] = $this->getDateFormat($data['valid_until']);
    if(!$data['contract_status_id']){
      $data['contract_status_id'] = null;
    }
    try {
      $update_flag = $this->offer_model->updateOffer($offer_id, $data);
      // $this->offer_placement_model->deleteOfferPlacementByOffer($offer_id);
      $this->offer_offer_status_model->deleteOfferStatusPivotByOffer($offer_id);
      //offer secondary ji users
      $this->offer_user_model->deleteByOffer($offer_id);
      if($ji_user_ids){
        $ji_user_flag = $this->offer_user_model->insertByOffer($offer_id, $ji_user_ids);
      }
      $offer_placement_data = $this->offerPlacementData($offer_id, $data['valid_until'], $placement_ids);
      $this->offer_placement_model->deleteOfferPlacementByOffer($offer_id);
      $offer_placement_flag = $this->offer_placement_model->insertOfferPlacement($offer_placement_data);
      if(isset($offer_statuses)){
        $offer_status_flag = $this->offer_offer_status_model->insertOfferStatus($offer_id, $offer_statuses);
      }
      $this->updateCommunicationPackage($data['has_communication_package'], $offer_id, 'edit', $old_communication);
      $this->updateOfferContactEmails($offer_id);
      return redirect('offer/index')->with('updated_notice', 'Selected offer has been updated successfully with the provided details.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * [delete offer]
   * @param  Request $request
   * @return [collection object] $offers
   * @return \Illuminate\Http\Response
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      $email_content_ids = $this->email_model->getEmailContentsByOffer($delete_id, 'all');
      $delete_flag = $this->offer_model->deleteOffer($delete_id);
      if($delete_flag == false){
        throw new Exception('Could not delete offer now. Please try again.');
      };
      $this->email_content_model->deleteEmailContents($email_content_ids);
      $deleted_notice = "Chosen offer has been deleted successfully.";
      $disabled_offers = $this->disabled_offer_model->getAllByUser(Auth::user()->id);
      $offers = $this->offer_model->getOfferByUser($disabled_offers);
      return view('offers.list_view', compact('offers', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this offer now. It seems this offer is being used by other modules.']);
    }//End try-catch
  }//End delete function

  /**
   * [return offer details]
   * @param  [int] $offer_id
   * @return [collection objet] $offer_info
   * @return \Illuminate\Http\Response
   */
  public function getShow($offer_id){
    $offer_info = $this->offer_model->getOfferByIdWith($offer_id, ['projects', 'partners', 'placements', 'offer_status']);
    return view('offers.show_view', compact('offer_info'));
  }

  /**
   * [updateOfferContactEmails description]
   * @return [type] [description]
   */
  public function updateOfferContactEmails($offer_id){
    $offer_info = $this->offer_model->getOfferById($offer_id);
    $contact_email = $offer_info[0]->contacts->email;
    $pending_emails = $this->email_model->pendingEmailsByOffer($offer_id);
    if(!$pending_emails->isEmpty()){
      foreach ($pending_emails as $email) {
        $this->email_content_model->updateEmailContent(['receiver_email' => $contact_email], $email->email_contents->id);
      }
    }
  }

  /**
   * [offerActivation description]
   * @param  [type] $offer_id [description]
   * @param  [type] $data     [description]
   * @return [type]           [description]
   */
  public function offerActivation($offer_id, $data){
    $contact_details = $this->contact_model->getContactById($data['contact_id']);
    $to_contact_email = $contact_details[0]->email;
    \Event::fire(new OfferWasCreated($offer_id, $to_contact_email, $data));
  }

  /**
   * Return new offer code by verifying the same code.
   * @return [string] $offer_code
   */
  public function verifyOfferCode(){
    $offer_code = $this->generateRandomCode();
    $offers = $this->offer_model->getAllOffers();
    $old_offer_codes = array();
    if(!$offers->isEmpty()){
      foreach($offers as $key=>$offer){
        $old_offer_codes[] = $offer->offer_code;
      }
    }
    if(in_array($offer_code, $old_offer_codes)){
      while(!in_array($offer_code, $old_offer_codes)){
        $offer_code = $this->generateRandomCode();
      }
    }
    return $offer_code;
  }//End verifyOfferCode function

  /**
   * Return data to edit placement details of specific offer.
   * @param  \Illuminate\Http\Request $request
   * @return [collection object] $offer_details
   * @return \Illuminate\View\View offers.edit_offer_placement_view
   */
  public function postEditOfferPlacement(Request $request){
    $offer_details = $this->offer_model->getOfferById($request->input('post_id'));
    return view('offers.edit_offer_placement_view', compact('offer_details'));
  }

  /**
   * Get offer details and return a view to edit the placements of same offer.
   * @param  [int] $offer_id
   * @return \Illumiinate\View\View offers.edit_offer_placement_view
   */
  public function getEditOfferPlacement($offer_id){
    $offer_details = $this->offer_model->getOfferById($offer_id);
    return view('offers.edit_offer_placement_view', compact('offer_details'));
  }

  /**
   * [postUpdateOfferPlacement description]
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postUpdateOfferPlacement(Request $request){
    $validator = Validator::make($request->all(), $this->offer_placement_model->update_offer_placement);
    $validator->setAttributeNames($this->offer_placement_model->validation_attribute);
    $offer_id = $request->input('offer_id');
    if ($validator->fails()) {
      $offer_details = $this->offer_model->getOfferById($offer_id);
      return redirect('offer/edit-offer-placement/'.$offer_id)
          ->withErrors($validator)
          ->withInput();
    };
    $data = $this->constructData($request);
    $data['placement_start_date'] = $this->getDateFormat($data['placement_start_date']);
    $data['placement_end_date'] = $this->getDateFormat($data['placement_end_date']);
    $offer_placement_id = $data['offer_placement_id'];
    unset($data['offer_placement_id']);
    try {
      $placement_offer_info = $this->offer_model->getOfferById($data['offer_id']);
      $offer_end_date = $placement_offer_info[0]->valid_until;
      $offer_end_date = $this->getDateInstance($offer_end_date);
      if($data['placement_end_date'] > $offer_end_date){
        $data['placement_end_date'] = $offer_end_date;
      };
      $update_flag = $this->offer_placement_model->updateOfferPlacementById($offer_placement_id, $data);
      if(!$update_flag){
        throw new Exception('Could not update offer placement now. Please try again later.');
      };
      return redirect('offer/edit-offer-placement/'.$offer_id)->with('updated_notice', 'Selected offer placement details has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc'=>utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdateOfferPlacement function

  /**
   * Return placement details within a requested offer.
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postPlacementByOffer(Request $request){
    $offer_placement_details = $this->offer_placement_model->getOfferPlacement($request->input('offer_id'), $request->input('placement_id'));
    return response()->json($offer_placement_details);
  }//End postPlacementByOffer function

  /**
   * Activate offer by its code, usually performed by partner contact.
   * @param  [string] $offer_code
   * @return [mixed]
   */
  public function getActivate($offer_code){
    try {
      $offer_details = $this->offer_model->getOfferByCode($offer_code);
      if(!$offer_details->isEmpty()){
        $activation_flag = $offer_details[0]->activation_flag;
        $offer_id = $offer_details[0]->id;
        if($activation_flag){
          return $this->offer_model->verifyPartnerLanguage($offer_details, 'already_activated');
        }
        else{
          $update_flag = $this->offer_model->updateOffer($offer_details[0]->id, ['activation_flag' => 1]);
          if($update_flag){
            return $this->offer_model->verifyPartnerLanguage($offer_details, 'success');
          }
        }
      }else{
        $notice = "Sorry! The requested offer code is invalid.";
        return view('offers.offer_activation.activation_invalid_view', compact('notice'));
        // return $this->offer_model->verifyPartnerLanguage($offer_details, 'invalid');
      };
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getActivate function


  /**
   * Insert data in offer report table if communication package is enabled.
   * @param  [string] $communication_package [yes/no]
   * @param  [int] $offer_id
   * @return [void]
   */
  public function updateCommunicationPackage($communication_package, $offer_id, $type, $old_communication){
    //get existing offer report of provided offer id
    $offer_details = $this->offer_report_model->getMicrositeReportByOffer($offer_id);
    //get offer details
    $offer_info = $this->offer_model->getOfferById($offer_id);
    $offer_d = (array) $offer_info[0];
    $new_offer_info = array();
    foreach ($offer_d as $key => $value) {
      $new_offer_info[] = $value;
    };
    $activation_data = $this->offer_model->getOfferActivationData($new_offer_info);
    // $activation_data = $new_offer_info[13];
    $email_content_ids = $this->email_model->getEmailContentsByOffer($offer_id, 'activation');
    $all_email_content_ids = $this->email_model->getEmailContentsByOffer($offer_id, 'all');
    //verify whether offer report exists
    if(($communication_package == "yes" && $type == 'new') || ($communication_package == "yes" && $old_communication == "no")):
      $this->email_model->deleteEmailsByOffer($offer_id);
      $this->offer_report_model->deleteOfferReportByOffer($offer_id);
      $this->email_content_model->deleteEmailContents($all_email_content_ids);
      $dates = $this->offer_model->getFirstMicrosite1Date($offer_id);
      $report_data = array();
      foreach ($dates as $date) {
        $report_data[] = [
          'report_date' => null,
          'offer_id' => $offer_id,
          'for_year' => $date['year'],
          'for_month' => $date['month'],
          'report_type' => $date['report_type'],
          'report_code' => $this->offer_report_model->newReportCode()
        ];
      };
      foreach ($report_data as $insert_data) {
        $insert_flag = $this->offer_report_model->insertOfferReport($insert_data);
        if($insert_data['report_type'] == 'microsite2'):
          $this->microsite2_report_model->createNew(['offer_report_id' => $insert_flag->id]);
        endif;
      }//End report data, foreach
      $this->offerActivation($offer_id, $activation_data);
      $this->updateOfferActivation($offer_id);
    else:
      //delete offer report if communication package is disabled
      if($communication_package == "no" && $type == 'edit'){
        // $this->email_model->deleteEmailByTypeOffer($offer_id, 'activation');
        // $this->offer_report_model->deleteOfferReportByOffer($offer_id);
        // $this->email_content_model->deleteEmailContents($email_content_ids);
      }
      elseif($communication_package == "no" && $type == 'new'){
        $this->offer_report_model->deleteOfferReportByOffer($offer_id);
      }
    endif;
  }//End updateCommunicationPackage function

  /**
   * Get offer id and disable the same offer for logged in user.
   * @param  [int] $offer_id
   * @return \Illuminate\Http\Response
   */
  public function getDisable($offer_id){
    try {
      $insert_flag = $this->disabled_offer_model->insertNew($offer_id);
      return redirect('offer/index')->with('updated_notice', 'You have successfully disabled an offer.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getDisable function

  /**
   * [updateOfferActivation description]
   * @return [type] [description]
   */
  public function updateOfferActivation($offer_id){
    $this->offer_model->updateOffer($offer_id, ['activation_email' => 'pending']);
  }

  /**
   * [getProjectJiUsers description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getProjectJiUsers($project_id){
    $project_info = $this->project_model->getProjectById($project_id);
    return $project_info[0]->users->toJson();

  }



}//End Class, OfferController
