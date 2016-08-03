<?php

/**
 * ContactController.php
 *
 * Handles contact module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Contact;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Contact as Contact;
use App\Models\Address as Address;
use App\Models\Language as Language;
use App\Models\Country as Country;
use App\Models\Contact_Partner as ContactPartner;
use Validator;
use DB;

class ContactController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Contact Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to contact
  | module. It includes contact and adress models for
  | it's operations.
  |
  */
  private $contact_model;
  private $address_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->contact_model = new Contact;
    $this->address_model = new Address;
    $this->language_model = new Language;
    $this->country_model = new Country;
    $this->contact_partner_model = new ContactPartner;
  }

  /**
   * Return contacts listing view to list all available contacts.
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      //all available contacts
      $contacts = $this->contact_model->getAllContacts();
      $projects = $this->commonGetProjects();
      $project_ids = $this->commonContactProjects();
      return view('contacts.list_view', compact('contacts', 'project_ids'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getIndex function

  /**
   * Return view to create new contact.
   * @return \Illuminate\View\View contacts.add_view
   */
  public function getCreate(){
    try {
      //First we need to retrieve all available languages from database and
      //return a view to register new contact info.
      $languages = $this->language_model->allLanguages();
      $countries = $this->country_model->allCountries();
      return view('contacts.add_view', compact('languages', 'countries'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }

  /**
   * [commonContactProjects description]
   * @return [type] [description]
   */
  public function commonContactProjects(){
    $projects = $this->commonGetProjects();
    $project_ids = [];
    foreach ($projects as $project) {
      $project_ids[] = $project->id;
    }
    return $project_ids;
  }

  /**
   * Validate the incoming contact post create request and insert new contact if all data are valid.
   * @param \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //validate user inputs
    $validator = Validator::make($request->all(), $this->contact_model->new_contact_validation_rules);
    if ($validator->fails()) {
      return redirect('contact/create')
            ->withErrors($validator)
            ->withInput();
    }//End validation fails, if
    $data = $this->constructData($request);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      //contact data
      if($key != 'street1' && $arr_flag == 0){
        $contact_data[$key] = $value;
      }else{ //address data
        $address_data[$key] = $value;
      }//End if-else, contact/address data
    }//End $data foreach
    try {
      //insert address data
      $address_flag = $this->address_model->insertNewAddress($address_data);
      if(!$address_flag){
        throw new Exception('Could not insert address now. Please try again.');
      }//End address inserted, if
      $contact_data['address_id'] = $address_flag; //address id
      //insert contact details
      $contact_data['language_id'] = $contact_data['language'];
      unset($contact_data['language']);
      $contact_flag = $this->contact_model->insertNewContact($contact_data);
      //Redirect to contact index page with created success notice
      return redirect('contact/index')->with('added_notice', 'New contact has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate funciton

  /**
   * Get contact delete-id from incoming request and delete contact record.
   * @param  \Illuminate\Http\Request $request [contact delete request]
   * @return mixed
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      if(!$this->verifyContactDelete($delete_id)){
        throw new \Exception('Could not delete contact now. Please try again.');
      }
      $contact_info = $this->contact_model->getContactById($delete_id);
      //delete contact record
      $delete_flag = $this->contact_model->deleteContact($delete_id);
      if($delete_flag == false){
        throw new \Exception('Could not delete contact now. Please try again.');
      }//End contact not deleted, if
      //all available contacts
      $this->address_model->deleteAddress($contact_info[0]->address_id);
      $contacts = $this->contact_model->getAllContacts();
      $deleted_notice = "Chosen contact has been deleted successfully.";
      $project_ids = $this->commonContactProjects();
      //contacts listing view with contact records and contact deleted success notice
      return view('contacts.list_view', compact('contacts', 'deleted_notice', 'project_ids'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => "Could not delete contact now. It seems this contact is being used by other modules."]);
    }//End try-catch
  }//End contact delete function

  /**
   * Return view and contact details to edit contact.
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    //First we need to get contact id from incoming request and get the same contact's info
    //and return a view to edit contact's info.
    $contact_id = $request->input('post_id');
    $languages = $this->language_model->allLanguages();
    $countries = $this->country_model->allCountries();
    $contact_details = $this->contact_model->getContactById($contact_id);
    return view('contacts.add_view', compact('contact_details', 'languages', 'countries'));
  }//End postEdit function

  /**
   * Validate incoming contact post update request and update contact details if all data are valid.
   * @param  \Illumiinate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    $data = $this->constructData($request);
    //contact edit id
    $contact_id = $data['id'];
    //validate user inputs
    $validator = Validator::make($request->all(), $this->contact_model->getEditValidationRules($contact_id));
    if ($validator->fails()) {
      $languages = $this->language_model->allLanguages();
      $contact_details = $this->contact_model->getContactById($contact_id);
      $countries = $this->country_model->allCountries();
      $project_ids = $this->commonContactProjects();
      return view('contacts.add_view', compact('languages', 'countries', 'project_ids'))->withErrors($validator)->with('contact_details', $contact_details);
    }//End validation fails, if
    $address_id = $data['address_id']; //address edit-id
    unset($data['id'], $data['address_id']);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      //if, contact data
      if($key != 'street1' && $arr_flag == 0){
        $contact_data[$key] = $value;
      }else{
        $address_data[$key] = $value;
      }//End else, address data
    }//End $data foreach
    try {
      //update address data
      $address_update = $this->address_model->updateAddress($address_id, $address_data);
      //update contact data
      $contact_data['language_id'] = $contact_data['language'];
      unset($contact_data['language']);
      $cotact_update = $this->contact_model->updateContact($contact_id, $contact_data);
      if($cotact_update == false){
        throw new Exception('Could not update contact now. Please try again.');
      }//End contact updated, if
      //Redirect to contact index page with updated success notice
      return redirect('contact/index')->with('updated_notice', 'Chosen contact has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get contact id from incoming post assign contact request and return view to assign contacts.
   * @param  \Illuminate\Http\Request $request [post assign contact request]
   * @return \Illuminate\View\View contacts.assign_contact
   */
  public function postAssignContact(Request $request){
    $contact_id = $request->input('post_id');
    return view('contacts.assign_contact');
  }//End postAssignContact function

  /**
   * Get selected contacts and assign to partner/client accordingly.
   * @param \Illuminate\Http\Request $request [post assign contactto request]
   * @return mixed
   */
  public function postAssignContactTo(Request $request){
    $from = $request->input('from');
    //selected contact ids
    $contact_ids = $request->input('assign_contact_id');
    switch ($from){
      case 'client': //case contacts are assigning from client
        //client id, from
        $client_id = $request->input('primary_id');
        //delete existing assigned contacts records
        DB::table('client_contact')->where('client_id','=',$client_id)->delete();
        //Insert/assign contacts if contact_ids is not empty
        if(!empty($contact_ids)){
          foreach($contact_ids as $contact_id){
            DB::table('client_contact')->insert(['client_id'=>$client_id , 'contact_id'=>$contact_id]);
          }
        }//End if, contact_ids is not empty
        //Redirect to client index page with assigned/updated success notice
        return redirect('client/index')->with('updated_notice', 'Client contacts updated successfully.');
        break;
      case 'partner': //case contacts are assigning from partner
        //partner id, from
        $partner_id = $request->input('primary_id');
        //delete existing assigned contacts records
        DB::table('contact_partner')->where('partner_id','=',$partner_id)->delete();
        //Insert/assign contacts if contact_ids is not empty
        if(!empty($contact_ids)){
          foreach($contact_ids as $contact_id){
            DB::table('contact_partner')->insert(['partner_id'=>$partner_id , 'contact_id'=>$contact_id]);
          }
        }
        if($request->has('is_modal')){
          return redirect('partner/list-by-offer/'.$request->input('contact_modal_project_id'))->with('updated_notice', 'Partner contacts updated successfully.');
        }
        //Redirect to partner index page with updated success notice
        return redirect('partner/index')->with('updated_notice', 'Partner contacts updated successfully.');
        break;
      default: //default
        break;
    }//End $from, switch
    return true;
  }//End postAssignContactTo function

  /**
   * [getShow description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function getShow($contact_id){
    $contact_info = $this->contact_model->getContactById($contact_id);
    return view('contacts.show_view', compact('contact_info'));
  }

  /**
   * [getByPartner description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function getByPartner($partner_id){
    $partner_contact = $this->contact_partner_model->fetchByPartner($partner_id);
    if($partner_contact->isEmpty()){
      return response()->json(['empty' => 'no data.']);
    }
    $contacts = array();
    foreach ($partner_contact as $key => $value) {
      $contacts[] = $value->contacts;
    }
    return response()->json(['contacts' => $contacts]);
  }

  /**
   * [verifyContactDelete description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function verifyContactDelete($contact_id){
    $contact_info = $this->contact_model->getContactById($contact_id);
    if($contact_info[0]->clients->isEmpty() && $contact_info[0]->partners->isEmpty() && $contact_info[0]->projects->isEmpty()){
      return true;
    }
    return false;
  }

}//End Class, ContactController
