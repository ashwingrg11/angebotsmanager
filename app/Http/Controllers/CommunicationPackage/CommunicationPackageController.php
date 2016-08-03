<?php

/**
 * CommunicationPackageController.php
 *
 * Handles communication package/email communication module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\CommunicationPackage;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Communication_Package as CommunicationPackage;
use App\Models\Project as Project;
use Auth;

class CommunicationPackageController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Communication Package Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to communication
  | package module. It includes communication package and project models for
  | it's functionalities.
  |
  */
  public $communication_package_model;
  public $project_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->communication_package_model = new CommunicationPackage;
    $this->project_model = new Project;
  }

  /**
   * Return communication package listing view to show all available communication packages.
   * @return \Illuminate\View\View communication_package.list_view or,
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      $projects = $this->commonGetProjects(); //get projects
      if(Auth::user()->user_type == "general"){
        // $communications = $this->channel_model->getChannelByUserProject($projects);
        $communications = $this->communication_package_model->getCommunicationByUser($projects);
      }//End if user_type general
      else{
        //get all available communication packages
        $communications = $this->communication_package_model->getAllCommunication();
      }//End else user_type general
      return view('communication_package.list_view', compact('communications'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getIndex function

  /**
   * Return view to create new communiation package.
   * @return \Illuminate\View\View communication_package.add_view
   * @return \Illuminate\Http\Response
   */
  public function getCreate()
  {
    try {
      //all available projects
      $projects = $this->project_model->getAllProjects();
      return view('communication_package.add_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getCreate function

  /**
   * Validate incoming commn. package post-create request and insert new commn package details.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //validate user inputs
    $validator = \Validator::make($request->all(), $this->communication_package_model->getCommnValidationRules($request));
    //set validator attributes
    $validator->setAttributeNames($this->communication_package_model->validation_attributes);
    if ($validator->fails()) {
      return redirect('communication-package/create')
        ->withErrors($validator)
        ->withInput();
    }//End validator fails if
    $data = $this->constructData($request);
    try {
      //insert new communication package
      $this->communication_package_model->insertCommnPackage($data);
      //redirect to commn package index page with create success notice
      return redirect('communication-package/index')->with('added_notice', 'New email communication info has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get communicaiton package edit id and return details/view to edit commn package.
   * @param  \Illuminate\Http\Request $request [commn package post edit request]
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    try {
      //communication package edit id
      $communication_id = $request->input('post_id');
      //communication package details by it's id
      $communication_details = $this->communication_package_model->getCommnPackageById($communication_id);
      $projects = $this->commonGetProjects(); //get projects
      return view('communication_package.add_view', compact('projects', 'communication_details'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEdit function

  /**
   * Validate incoming commn. package post update request and update commn package details.
   * @param  \Illuminate\Http\Request $request [commn. package post update request]
   * @return mixed
   */
  public function postUpdate(Request $request){
    //communication package edit id
    $communication_id = $request->input('id');
    //validate user inputs
    $validator = \Validator::make($request->all(), $this->communication_package_model->getCommnValidationRules($request));
    //set validator attributes
    $validator->setAttributeNames($this->communication_package_model->validation_attributes);
    if ($validator->fails()) {
      //communication package details
      $communication_details = $this->communication_package_model->getCommnPackageById($communication_id);
      $projects = $this->commonGetProjects(); //get projects
      return view('communication_package.add_view', compact('projects', 'communication_details'))->withErrors($validator);
    }//End validator fails if
    $data = $this->constructData($request);
    try {
      unset($data['old_project_id']);
      $update_flag = $this->communication_package_model->updateCommnPackage($communication_id, $data);
      if(!$update_flag):
        throw new \Exception('Could not update now.');
      endif;//End commn package updated or not, if
      //Redirect to commn package index page with updated success notice
      return redirect('communication-package/index')->with('updated_notice', 'Selected email communicatioin details has been updated successfully with the provided details.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get commn package delete id and delete commn package record.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function deleteDelete(Request $request){
    //commn package delete id
    $communication_id = $request->input('delete_id');
    try {
      //delete communication package
      $flag = $this->communication_package_model->deleteCommnPackage($communication_id);
      if(!$flag):
        throw new \Exception('Could not delete Commnpackage now.');
      endif;//End commn package deleted or not, if
      $deleted_notice = "Chosen communication package details has been deleted successfully.";
      //all available communication packages
      $communications = $this->communication_package_model->getAllCommunication();
      return view('communication_package.list_view', compact('communications', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this communication package because it is being used by other modules.']);
    }//End try-catch
  }//End commn package delete function

}//End Class, CommunicationPackageController
