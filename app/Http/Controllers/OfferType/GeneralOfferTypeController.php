<?php

/**
 * GeneralOfferTypeController.php
 *
 * Handles general offer type module's functionalities and related operations.
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
use App\Models\Project as Project;
use App\Models\General_Offer_Type_Masterlist as GeneralOfferType;

class GeneralOfferTypeController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | General Offer Type Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to general
  | offer type module. It includes project and general offer type
  | models for it's related operations.
  |
  */
  private $general_offer_type_model;
  private $project_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->general_offer_type_model = new GeneralOfferType;
    $this->project_model = new Project;
  }

  /**
   * Return offer type edit page along with requested offer type details.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postEdit(Request $request){
    try {
      //First we need to get offer type details from requested id along with project
      //details and return offer type edit page to edit the same info.
      $offer_type_details = $this->general_offer_type_model->getGeneralOfferTypeById($request->input('post_id'));
      $offer_type_details[1] = ['type' => 'general'];
      $projects = $this->commonGetProjects();
      return view('offer_types.offer_type_form_partial', compact('offer_type_details', 'projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEdit function

  /**
   * Get general offer type id from incoming request and delete the same record.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      //Delete requested general offer type from database, throw exception if requested
      //offer type couldnot delete else return offer types listing page
      //with success notice.
      $delete_flag = $this->general_offer_type_model->deleteGeneralOfferType($delete_id);
      if($delete_flag == false){
        throw new Exception('Invalid delete id.');
      }
      $projects = $this->project_model->getAllProjects();
      $deleted_notice = "Chosen offer type has been deleted successfully.";
      return view('offer_types.list_view', compact('projects', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this offer type now. It seems this offer type is being used by other modules.']);
    }//End try-catch
  }//End delete function

}//End Class GeneralOfferTypeController
