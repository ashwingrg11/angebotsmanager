<?php

/**
 * OfferTypeController.php
 *
 * Handles offer type module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\OfferType;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Http\Controllers\Offer\OfferController;
use App\Models\Project as Project;
use App\Models\General_Offer_Type_Masterlist as GeneralOfferType;
use App\Models\Detailed_Offer_Type_Masterlist as DetailedOfferType;
use App\Models\Offer_Type_Category_Masterlist as OfferTypeCategory;
use Validator;
use Exception;
use Auth;
use Illuminate\Support\Collection as Collection;

class OfferTypeController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Offer Type Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to offer types
  | module. It includes project, general offer type, detailed offer type
  | and offer type category models for it's related operations.
  |
  */
  private $project_model;
  private $general_offer_type_model;
  private $detailed_offer_type_model;
  private $offer_type_category_model;

  /**
   * Validation rules for create/update offer types.
   * @var array
   */
  private $new_offer_type_validation_rules = [
    'project' => 'required',
    'type' => 'required',
    'name' => 'required',
  ];

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->project_model = new Project;
    $this->general_offer_type_model = new GeneralOfferType;
    $this->detailed_offer_type_model = new DetailedOfferType;
    $this->offer_type_category_model = new OfferTypeCategory;
    $this->offer_controller = new OfferController;
  }

  /**
   * Return offer types listing page with available projects data.
   * @return \Illuminate\Http\Response
   */
  public function getIndex(){
    try {
      $projects = $this->commonGetProjects();
      return view('offer_types.list_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }//End getIndex function

  /**
   * [getListAll description]
   * @return [type] [description]
   */
  public function getListAll(){
    try {
      $offers = $this->offer_controller->commonListOffers();
      return view('offer_types.offer_types_list', compact('offers'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getListOfferTypes description]
   * @return [type] [description]
   */
  public function getListOfferTypes(){
    try {
      $projects = $this->commonGetProjects();
      if(Auth::user()->user_type == "general"){
        $detailed_offer_types = $this->detailed_offer_type_model->getAllByUser($projects);
        $general_offer_types = $this->general_offer_type_model->getAllByUser($projects);
        $offer_type_categories = $this->offer_type_category_model->getAllByUser($projects);
      }
      else{
        $detailed_offer_types = $this->detailed_offer_type_model->getAll();
        $general_offer_types = $this->general_offer_type_model->getAll();
        $offer_type_categories = $this->offer_type_category_model->getAll();
      }
      $offer_types = [
        'detailed offer type' => $detailed_offer_types,
        'general offer type' => $general_offer_types,
        'offer type category' => $offer_type_categories
      ];
      return view('offer_types.all_list_view', compact('offer_types'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * Validate incoming offer type post create request and save offer type into database.
   * @return \Illuminate\Http\Response
   */
  public function postCreate(Request $request){
    //Validate user inputs and return validation error messages if validator fails
    //else, proceed to save offer type data into database.
    $validator = Validator::make($request->all(), $this->new_offer_type_validation_rules);
    if ($validator->fails()) {
      return redirect('offer_type/index')
                    ->withErrors($validator)
                    ->withInput();
    }
    $data = $this->constructData($request);
    $data['project_id'] = null;
    $data['project_id'] = $data['project'];
    $data['label'] = $data['name'];
    $type = $data['type'];
    unset($data['project'], $data['type'], $data['name']);
    try {
      //Insert offer type data to respective offer types, throw exception if offer type
      //could not insert otherwise redirect to offer type listing page with
      //successfully added success notice.
      switch ($type) {
        case 'general':
          $insert_flag = $this->general_offer_type_model->insertGeneralOfferType($data);
          break;
        case 'detailed':
          $insert_flag = $this->detailed_offer_type_model->insertDetailedOfferType($data);
          break;
        case 'type_category':
          $insert_flag = $this->offer_type_category_model->insertOfferTypeCategory($data);
          break;
        default:
          # code...
          break;
      }//End switch, offer type
      if(empty($insert_flag)):
        throw new Exception('Could not insert offer type this time. Please try again later.');
      endif;
      return redirect('offer_type/index')->with('added_notice', 'New offer type has been added successfully with provided details.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Validate incoming offer type post update request and update offer type data into database.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    //Validate user inputs and return validation error messages if validator fails
    //else, proceed to update offer type data/info into database.
    $validator = Validator::make($request->all(), $this->new_offer_type_validation_rules);
    if ($validator->fails()) {
      $projects = $this->commonGetProjects();
      $offer_type_details = $this->detailsByType($request->input('old_type'), $request->input('offer_type_id'));
      $offer_type_details[1] = ['type' => $request->input('old_type')];
      return view('offer_types.list_view', compact('projects', 'offer_type_details'))->withErrors($validator);
    }
    $data = $this->constructData($request);
    $data['project_id'] = null;
    $data['project_id'] = $data['project'];
    $data['label'] = $data['name'];
    $type = $data['type'];
    $old_type = $data['old_type'];
    $offer_type_id = $data['offer_type_id'];
    unset($data['project'], $data['type'], $data['name'], $data['offer_type_id'], $data['old_type']);
    try {
      if($type == $old_type){
        switch ($type) {
          case 'general':
            $update_flag = $this->general_offer_type_model->updateGeneralOfferType($data, $offer_type_id);
            break;
          case 'detailed':
            $update_flag = $this->detailed_offer_type_model->updateDetailedOfferType($data, $offer_type_id);
            break;
          case 'type_category':
            $update_flag = $this->offer_type_category_model->updateOfferTypeCategory($data, $offer_type_id);
            break;
          default:
            # code...
            break;
        }//End switch, offer type
      }
      else{
        switch ($type) {
          case 'general':
            $update_flag = $this->general_offer_type_model->insertGeneralOfferType($data, $offer_type_id);
            break;
          case 'detailed':
            $update_flag = $this->detailed_offer_type_model->insertDetailedOfferType($data, $offer_type_id);
            break;
          case 'type_category':
            $update_flag = $this->offer_type_category_model->insertOfferTypeCategory($data, $offer_type_id);
            break;
          default:
            # code...
            break;
        }//End switch, offer type
        $this->deleteByType($old_type, $offer_type_id);
      }
      if(empty($update_flag)){
        throw new Exception('Could not update offer type this time. Please try again later.');
      }
      return redirect('offer_type/index')->with('updated_notice', 'Selected offer type has been updated successfully with provided details.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Delete offer type as per provied offer type id and type.
   * @param  string $type [offer type]
   * @param  int $id [offer type id]
   * @return object [deleted offer type info]
   */
  public function deleteByType($type, $id){
    switch ($type) {
      case 'general':
        $delete_flag = $this->general_offer_type_model->deleteGeneralOfferType($id);
        break;
      case 'detailed':
        $delete_flag = $this->detailed_offer_type_model->deleteDetailedOfferType($id);
        break;
      case 'type_category':
        $delete_flag = $this->offer_type_category_model->deleteOfferTypeCategory($id);
        break;
      default:
        # code...
        break;
    }
    return $delete_flag;
  }//End deleteByType function

  /**
   * Return offer type details of provided offer type id and type.
   * @param  string $type [offer type]
   * @param  int $id [offer type id]
   * @return collection object
   */
  public function detailsByType($type, $id){
    switch ($type) {
      case 'general':
        $details = $this->general_offer_type_model->getGeneralOfferTypeById($id);
        break;
      case 'detailed':
        $details = $this->detailed_offer_type_model->getDetailedOfferTypeById($id);
        break;
      case 'type_category':
        $details = $this->offer_type_category_model->getOfferTypeCategoryById($id);
        break;
      default:
        # code...
        break;
    }
    return $details;
  }//End detailsByType function

}//End Class OfferTypeController
