<?php

/**
 * LanguageController.php
 *
 * Handles language module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Language;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Language as Language;
use Validator;

class LanguageController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Language Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to languages
  | module. It includes language model for it's related operations.
  |
  */
  public function __construct()
  {
    parent::__construct();
    $this->language_model = new Language;
  }

  /**
   * Get all available languages from database and return language listing page.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    try {
      $languages = $this->language_model->allLanguages();
      return view('languages.list_view', compact('languages'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }

  /**
   * Validate incoming country post create request and insert new language.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //First we need to validate incoming post create request and return errors
    //if requested data are invalid.
    $validator = Validator::make($request->all(), $this->language_model->getValidationRules($request));
    $validator->setAttributeNames($this->language_model->validation_attributes);
    if ($validator->fails()) {
      return redirect('language/index')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator, if
    $data = $this->constructData($request);
    try {
      //Save valid data into database and return language index page
      //with added success notice.
      $this->language_model->insertLanguage($data);
      return redirect('language/index')->with('added_notice', 'New language has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get language details as per requested language id and return edit page to edit details.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    //First we need to get language id from received request, get language details
    //as per received language id, get all languages and return a view
    //to edit language details.
    $language_id = $request->input('post_id');
    $language_details = $this->language_model->getLanguage($language_id);
    $languages = $this->language_model->allLanguages();
    return view('languages.list_view', compact('language_details', 'languages'));
  }//End postEdit function

  /**
   * Validate incoming language post update request and update language info.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postUpdate(Request $request){
    //First we need to validate incoming request and return errors
    //if received data are invalid.
    $validator = Validator::make($request->all(), $this->language_model->getValidationRules($request));
    $validator->setAttributeNames($this->language_model->validation_attributes);
    if ($validator->fails()) {
      $language_details = $this->language_model->getLanguage($request->language_id);
      $languages = $this->language_model->allLanguages();
      return view('languages.list_view', compact('languages', 'language_details'))->withErrors($validator);
    }//End validator, if
    $data = $this->constructData($request);
    $language_id = $data['language_id'];
    unset($data['language_id']);
    try {
      //Update language data, throw exception if language info is not updated
      //and return to channel's listing page with success notice,
      //if language info is updated.
      $update_flag = $this->language_model->updateLanguage($language_id, $data);
      if(!$update_flag):
        throw new Exception('Could not update language this time. Please try again later.');
      endif;
      return redirect('language/index')->with('updated_notice', 'Chosen language has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get language's delete id from incoming request and delete the same language.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function deleteDelete(Request $request){
    $delete_id = $request->input('delete_id');
    try {
      //Delete language from database, throw exception if not deleted and
      //return language's list view with success notice if
      //language is deleted.
      $delete_flag = $this->language_model->deleteLanguage($delete_id);
      if($delete_flag == false):
        throw new Exception('Could not delete requested language. Please try again.');
      endif;
      $deleted_notice = "Selected language has been deleted successfully.";
      $languages = $this->language_model->allLanguages();
      return view('languages.list_view', compact('languages', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this language because it is being used by other modules.']);
    }//End try-catch
  }//End delete function

}//End Class, LanguageController
