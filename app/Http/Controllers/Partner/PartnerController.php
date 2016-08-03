<?php

/**
 * CountryController.php
 *
 * Handles country module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Partner;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Partner as Partner;
use App\Models\Contact as Contact;
use App\Models\Address as Address;
use App\Models\Project as Project;
use App\Models\Partner_Project as PartnerProject;
use App\Models\Country as Country;
use App\Http\Controllers\MyController;
use Validator;
use DB;
use Helper;

class PartnerController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Partner Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities and related operations
  | of partner module. It includes partner, contact, address and project
  | models for it's related operations.
  |
  */
  private $partner_model;
  private $contact_model;
  private $address_model;
  private $project_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->partner_model = new Partner;
    $this->contact_model = new Contact;
    $this->address_model = new Address;
    $this->project_model = new Project;
    $this->partner_project_model = new PartnerProject;
    $this->country_model = new Country;
  }

  /**
   * Display a listing of all the available partners.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    return $this->commonListView('partners.list_view');
  }//End getIndex function

  /**
   * [getListByOffer description]
   * @return [type] [description]
   */
  public function getListByOffer($project_id){
    // return $this->commonListView('offers.partners_list_modal');
    try {
      //First we need to get all available partners from database and filter those
      //partners as per owned projects if the logged in user type is general
      //Finally, return partners listing page with necessary data.
      // $partners = $this->partner_model->getAllPartners();
      $partners_list = $this->partner_model->partnersByProject($project_id);
      return view('offers.partners_list_modal', compact('partners_list'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }

  /**
   * Show the form for creating a new partner.
   * @return \Illuminate\Http\Response
   */
  public function getCreate(){
    return $this->commonCreateView('partners.add_view');
  }//End getCreate function

  /**
   * [getCreateByOffer description]
   * @return [type] [description]
   */
  public function getCreateByOffer(){
    return $this->commonCreateView('offers.add_partner_modal');
  }

  /**
   * [commonCreateView description]
   * @param  [type] $view [description]
   * @return [type]       [description]
   */
  public function commonCreateView($view){
    try {
      $projects = $this->commonGetProjects();
      $countries = $this->country_model->allCountries();
      return view($view, compact('projects', 'countries'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }

  /**
   * [commonListView description]
   * @param  [type] $view [description]
   * @return [type]       [description]
   */
  public function commonListView($view){
    try {
      //First we need to get all available partners from database and filter those
      //partners as per owned projects if the logged in user type is general
      //Finally, return partners listing page with necessary data.
      $partners = $this->partner_model->getAllPartners();
      if(\Auth::user()->user_type == "general"){
        $projects_user = $this->commonGetProjects();
        // $partners = $this->partner_model->getPartnerByUserProject($projects_user);
        $partners = $this->partner_model->getAllPartners();
        return view($view, compact('partners'));
      }
      return view($view, compact('partners'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }

  /**
   * Validate incoming partner post create request and save partner&address data into database.
   * @param \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //Validate user inputs from received request and return validation error messages if validator fails.
    $validator = Validator::make($request->all(), $this->partner_model->new_partner_validation_rules);
    if ($validator->fails()) {
      if($request->has('is_modal')){
        return redirect('partner/create-by-offer')
                    ->withErrors($validator)
                    ->withInput();
      }
      return redirect('partner/create')
          ->withErrors($validator)
          ->withInput();
    }
    $data = $this->constructData($request);
    unset($data['is_modal']);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      if($key != 'street1' && $arr_flag == 0){
        $partner_data[$key] = $value;
      }else{
        $address_data[$key] = $value;
      }
    }
    try {
      //First prepare data for address and partner table and insert data into database table respectively.
      $address_flag = $this->address_model->insertNewAddress($address_data);
      if(!$address_flag){
        throw new Exception('Could not insert in address table now. Please try again.');
      }
      $partner_data['address_id'] = $address_flag;
      $project_ids = $partner_data['project'];
      unset($partner_data['project']);
      //Insert new partner details into database and
      $partner_flag = $this->partner_model->insertNewPartner($partner_data);
      $this->partner_project_model->insertParnterProject($partner_flag->id, $project_ids);
      if($request->has('is_modal')){
        return redirect('partner/create-by-offer')->with('added_notice', 'New partner has been added successfully.');
      }
      return redirect('partner/index')->with('added_notice', 'New Partner has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Return partner edit page with partner edit details.
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    $partner_id = $request->input('post_id');
    $partners_details = $this->partner_model->getPartnerById($partner_id);
    $projects = $this->commonGetProjects();
    $countries = $this->country_model->allCountries();
    return view('partners.add_view', compact('partners_details', 'projects', 'countries'));
  }//End postEdit function

  /**
   * Validate incoming partner post udpate request and update partner data into database.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    $data = $this->constructData($request);
    $partner_id = $data['id'];
    unset($data['id']);
    // $validator = Validator::make($request->all(), $this->partner_model->new_partner_validation_rules);
    $validator = Validator::make($request->all(), $this->partner_model->getEditValidationRules());
    if ($validator->fails()) {
      $partners_details = $this->partner_model->getPartnerById($partner_id);
      $projects = $this->commonGetProjects();
      $countries = $this->country_model->allCountries();
      return view('partners.add_view', compact('projects', 'countries'))->withErrors($validator)->with('partners_details', $partners_details);
    }
    $address_id = $data['address_id'];
    if(\Auth::user()->user_type == "general"){
      $project_ids = $this->partner_model->getPartnerProjectIds($partner_id);
    } else {
      $project_ids = $data['project'];
    }
    unset($data['id'], $data['address_id'], $data['project']);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      if($key != 'street1' && $arr_flag == 0){
        $partner_data[$key] = $value;
      }else{
        $address_data[$key] = $value;
      }
    }
    try {
      $address_update = $this->address_model->updateAddress($address_id, $address_data);
      if(!$partner_data['region']):
        $partner_data['region'] = null;
      endif;
      $partner_update = $this->partner_model->updatePartner($partner_id, $partner_data);
      $this->partner_project_model->deleteByPartner($partner_id);
      $this->partner_project_model->insertParnterProject($partner_id, $project_ids);
      if($partner_update == false){
        throw new Exception('Could not update partner details now. Please try again.');
      }
      return redirect('partner/index')->with('updated_notice', 'Chosen Partner has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * [postDelete description]
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      $partner_info = $this->partner_model->getPartnerById($delete_id);
      $delete_flag = $this->partner_model->deletePartner($delete_id);
      if($delete_flag == false){
        throw new Exception('Could not delete partner now. Please try again.');
      }
      $this->address_model->deleteAddress($partner_info[0]->address_id);
      $partners = $this->partner_model->getAllPartners();
      $deleted_notice = "Chosen Partners has been deleted successfully.";
      return view('partners.list_view', compact('partners', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this partner now. It seems this partner is being used by other modules.']);
    }//End try-catch
  }//End delete function

  /**
   * [postAssignContact description]
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postAssignContact(Request $request){
    return $this->commonAssignContact('contacts.assign_contact_to', $request);
  }//End postAssignContact function

  /**
   * [postAssignContactByOffer description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postAssignContactByOffer(Request $request){
    return $this->commonAssignContact('offers.assign_contact_to_modal', $request);
  }

  /**
   * [commonAssignContact description]
   * @param  [type] $view [description]
   * @return [type]       [description]
   */
  public function commonAssignContact($view, $request){
    $contacts = $this->contact_model->getAllContacts();
    $from = 'partner';
    $id = $request->input('post_id');
    $existing_records = DB::table('contact_partner')->select('contact_id')->where('partner_id','=',$id)->get();
    return view($view, compact('from','id','contacts', 'existing_records'));
  }

  /**
   * [postAssignProject description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postAssignProject(Request $request){
    $partner_id = $request->input('post_id');
    $projects = $this->partner_model->filterParterAvailableProjects($partner_id);
    return view('partners.assign_partner_project', compact('partner_id', 'projects'));
  }

  /**
   * [postAssignPartnerProjectTo description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postAssignPartnerProjectTo(Request $request){
    $project_ids = $request->input('porject_id');
    $partner_id = $request->input('partner_id');
    try {
      $this->partner_project_model->insertParnterProject($partner_id, $project_ids);
      return redirect('partner/index')->with('updated_notice', "Chosen Partner's Projects info has been updated successfully.");
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getShow description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function getShow($partner_id){
    $partner_info = $this->partner_model->getPartnerById($partner_id);
    return view('partners.show_view', compact('partner_info'));
  }

  /**
   * [getByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getByProject($project_id){
    $partner_project = $this->partner_project_model->fetchByProject($project_id);
    if($partner_project->isEmpty()){
      return response()->json(['empty' => 'no data.']);
    }
    $partners = array();
    foreach ($partner_project as $key => $value) {
      $partners[] = $value->partners;
    }
    $partners = Helper::sort_arr_of_obj($partners, 'partner_name', 'asc');
    return response()->json(['partners' => $partners]);
  }

}//End Class PartnerController
