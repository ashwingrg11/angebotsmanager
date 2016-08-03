<?php

/**
 * ClientController.php
 *
 * Handles client module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Client;

use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Client as Client;
use App\Models\Contact as Contact;
use App\Models\Address as Address;
use App\Models\Country as Country;
use App\Http\Controllers\MyController;
use DB;

class ClientController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Client Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to client module
  | It includes client, contact & address model for it's functionalities.
  |
  */
  private $client_model;
  private $contact_model;
  private $address_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->client_model = new Client;
    $this->contact_model = new Contact;
    $this->address_model = new Address;
    $this->country_model = new Country;
  }

  /**
   * Return a view to list all available clients.
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      //all available clients
      $clients = $this->client_model->getAllClients();
      return view('clients.list_view',compact('clients'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End index function

  /**
   * Return view to create new client.
   * @return \Illuminate\View\View clients.add_view
   */
  public function getCreate()
  {
    $countries = $this->country_model->allCountries();
    return view('clients.add_view', compact('countries'));
  }

  /**
   * Validate incoming channel post create request, insert client and address info.
   * @param  \Illuminate\Http\Request $request [client post create request]
   * @return \Illuminate\Http\Response
   */
  public function postCreate(Request $request){
    //validate user inputs
    $validator = Validator::make($request->all(), $this->client_model->new_client_validation_rules);
    if ($validator->fails()) {
      return redirect('client/create')
        ->withErrors($validator)
        ->withInput();
    }//End validator fails if
    $data = $this->constructData($request);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      if($key != 'street1' && $arr_flag == 0){
        $client_data[$key] = $value;
      }//End client data-if
      else{
        $address_data[$key] = $value;
      }//End else
    }//End $data foreach
    try {
      //insert address info
      $address_flag = $this->address_model->insertNewAddress($address_data);
      if(!$address_flag){
        throw new Exception('Could not insert address now. Please try again later.');
      }
      $client_data['address_id'] = $address_flag;//address id
      //insert client info
      $client_flag = $this->client_model->insertNewClient($client_data);
      //Redirect to client index page with create success notice
      return redirect('client/index')->with('added_notice', 'New Client has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get edit id-client and return view to edit client info.
   * @param  \Illuminate\Http\Request $request [client post edit request]
   * @return \Illuminate\View\View clients.add_view
   */
  public function postEdit(Request $request){
    //client edit id
    $client_id = $request->input('post_id');
    //client info by client id
    $countries = $this->country_model->allCountries();
    $clients_details = $this->client_model->getClientById($client_id);
    return view('clients.add_view', compact('clients_details', 'countries'));
  }//End postEdit function

  /**
   * Validate incoming client post update request and update client details.
   * @param  \Illuminate\Http\Request $request [client post update request]
   * @return \Illuminate\Http\Response
   */
  public function postUpdate(Request $request){
    $data = $this->constructData($request);
    $client_id = $data['id'];//client edit id
    unset($data['id']);
    //Validate user inputs
    $validator = Validator::make($request->all(), $this->client_model->new_client_validation_rules);
    if ($validator->fails()) {
      //client details by client id
      $clients_details = $this->client_model->getClientById($client_id);
      $countries = $this->country_model->allCountries();
      return view('clients.add_view', compact('countries'))->withErrors($validator)->with('clients_details', $clients_details);
    }//End validator fails if
    $address_id = $data['address_id'];
    unset($data['id'], $data['address_id']);
    $arr_flag = 0;
    foreach ($data as $key => $value) {
      if($key == 'street1'){
        $arr_flag = 1;
      }
      //client data
      if($key != 'street1' && $arr_flag == 0){
        $client_data[$key] = $value;
      }else{ //address data
        $address_data[$key] = $value;
      }//End if-else client/address data
    }//End $data foreach
    try {
      //update address data
      $address_update = $this->address_model->updateAddress($address_id, $address_data);
      //update client data
      if(!$client_data['region']):
        $client_data['region'] = null;
      endif;
      if(!$client_data['notes']):
        $client_data['notes'] = null;
      endif;
      $client_update = $this->client_model->updateClient($client_id, $client_data);
      if($client_update == false){
        throw new Exception('Could not update client now. Please try again.');
      }
      //Redirect to client index page with edit success notice
      return redirect('client/index')->with('updated_notice', 'Chosen Client has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get client delete-id from request and delete client record.
   * @param  \Illuminate\Http\Request $request [client delete request]
   * @return mixed
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      $client_info = $this->client_model->getClientById($delete_id);
      //delete client record by requested client delete-id
      $delete_flag = $this->client_model->deleteClient($delete_id);
      if($delete_flag == false){
        throw new Exception('Could not delete client now. Please try again.');
      }//End if, deleted or not
      $this->address_model->deleteAddress($client_info[0]->address_id);
      //all available clients
      $clients = $this->client_model->getAllClients();
      $deleted_notice = "Chosen Client has been deleted successfully.";
      //Client listing view with client records and deleted success notice
      return view('clients.list_view', compact('clients', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this client because it is being used by other modules.']);
    }//End try-catch
  }//End delte function

  /**
   * Get client id from request and return view&records to assign contacts.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\View\View contacts.assign_contact_to
   */
  public function postAssignContact(Request $request){
    //all available contacts
    $contacts = $this->contact_model->getAllContacts();
    //client id
    $id = $request->input('post_id');
    $from = 'client';
    $existing_records = DB::table('client_contact')->select('contact_id')->where('client_id','=',$id)->get();
    return view('contacts.assign_contact_to', compact('from','id','contacts','existing_records'));
  }//End postAssignContact function

  /**
   * Return view to list client contacts by client id.
   * @param  int $client_id
   * @return \Illuminate\Http\Response
   */
  public function getClientContact($client_id){
    try {
      $client_contacts_by_client = $this->client_model->getClientById($client_id);
      return view('projects.select_client_contact_partial', compact('client_contacts_by_client'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getClientContact function

  /**
   * [getShow description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function getShow($client_id){
    $client_info = $this->client_model->getClientById($client_id);
    return view('clients.show_view', compact('client_info'));
  }

}//End ClientController
