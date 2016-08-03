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

namespace App\Http\Controllers\Country;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Country as Country;
use Validator;

class CountryController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Country Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to country
  | module. It includes country model for it's related operations.
  |
  */

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->country_model = new Country;
  }

  /**
   * Get all available countries from database and return a view to disaplay information.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    try {
      $countries = $this->country_model->allCountries();
      return view('countries.list_view', compact('countries'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getIndex function

  /**
   * Validate incoming country post create request and insert new country.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //First we need to validate incoming post create request and return errors
    //if requested data are invalid.
    $validator = Validator::make($request->all(), $this->country_model->getValidationRules($request));
    $validator->setAttributeNames($this->country_model->validation_attributes);
    if ($validator->fails()) {
      return redirect('country/index')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator, if
    $data = $this->constructData($request);
    try {
      //Save valid data into database and return country index view
      //with added success notice.
      $this->country_model->insertCountry($data);
      return redirect('country/index')->with('added_notice', 'New country has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get country details as per requested country id and return edit view to edit details.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    //First we need to get country id from received request, get country details
    //as per received country id, get all countries and return a view
    //to edit country details.
    $country_id = $request->input('post_id');
    $country_details = $this->country_model->getCountry($country_id);
    $countries = $this->country_model->allCountries();
    return view('countries.list_view', compact('country_details', 'countries'));
  }//End postEdit function

  /**
   * Validate incoming country post update request and update country info.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    //First we need to validate incoming request and return errors
    //if received data are invalid.
    $validator = Validator::make($request->all(), $this->country_model->getValidationRules($request));
    $validator->setAttributeNames($this->country_model->validation_attributes);
    if ($validator->fails()) {
      $country_details = $this->country_model->getCountry($request->country_id);
      $countries = $this->country_model->allCountries();
      return view('countries.list_view', compact('country_details', 'countries'))->withErrors($validator);
    }//End validator, if
    $data = $this->constructData($request);
    $country_id = $data['country_id'];
    unset($data['country_id']);
    try {
      //Update country data, throw exception if country info is not updated
      //and return to channel's listing page with success notice,
      //if country info is updated.
      $update_flag = $this->country_model->updateCountry($country_id, $data);
      if(!$update_flag):
        throw new Exception('Could not update country this time. Please try again later.');
      endif;
      return redirect('country/index')->with('updated_notice', 'Chosen country has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get country's delete id from incoming request and delete the same country.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function deleteDelete(Request $request){
    $delete_id = $request->input('delete_id');
    try {
      //Delete country from database, throw exception if not deleted and
      //return country's list view with success notice if
      //country is deleted.
      $delete_flag = $this->country_model->deleteCountry($delete_id);
      if($delete_flag == false):
        throw new Exception('Could not delete requested country. Please try again.');
      endif;
      $deleted_notice = "Selected country has been deleted successfully.";
      $countries = $this->country_model->allCountries();
      return view('countries.list_view', compact('countries', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this country because it is being used by other modules.']);
    }//End try-catch
  }//End delete function

}//End Class, CountryController
