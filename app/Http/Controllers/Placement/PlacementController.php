<?php

/**
 * PlacementController.php
 *
 * Handles placement module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Placement;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Placement as Placement;
use App\Models\Project as Project;
use App\Models\Channel as Channel;
use Validator;
use Helper;

class PlacementController extends MyController
{
  private $placement_model;
  private $project_model;
  private $channel_model;
  public function __construct()
  {
    parent::__construct();
    $this->placement_model = new Placement;
    $this->project_model = new Project;
    $this->channel_model = new Channel;
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      if(\Auth::user()->user_type == "general" || \Auth::user()->user_type =="viewer"){
        $projects = $this->commonGetProjects();
        $placements = $this->placement_model->getPlacementByUserProject($projects);
      }else{
        $placements = $this->placement_model->getAllPlacements();
      }
      return view('placements.list_view', compact('placements'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getCreate()
  {
    try {
      $projects = $this->commonGetProjects();
      return view('placements.add_view', compact('projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getCreateByOffer description]
   * @return [type] [description]
   */
  public function getCreateByOffer(){
    $projects = $this->commonGetProjects();
    return view('offers.add_placement_modal', compact('projects'));
  }

  /**
   * [postCreate description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postCreate(Request $request){
    $validator = Validator::make($request->all(), $this->placement_model->newValidationRules($request->input('end_date')));
    if ($validator->fails()) {
      if($request->has('is_modal')){
        return redirect('placement/create-by-offer')
                    ->withErrors($validator)
                    ->withInput();
      }
      return redirect('placement/create')
                    ->withErrors($validator)
                    ->withInput();
    }
    $data = $this->constructData($request);
    $data['start_date'] = $this->getDateFormat($data['start_date']);
    if($data['end_date']){
      $data['end_date'] = $this->getDateFormat($data['end_date']);
    }else{
      $data['end_date'] = NULL;
    }
    $data['project_id'] = $data['project'];
    $data['channel_id'] = $data['channel'];
    unset($data['project'], $data['channel'], $data['is_modal']);
    try {
      $insert_flag = $this->placement_model->insertPlacement($data);
      if($request->has('is_modal')){
        return redirect('placement/create-by-offer')->with('added_notice', 'New placement has been added successfully.');
      }
      return redirect('placement/index')->with('added_notice', 'New placement has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [postEdit description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postEdit(Request $request){
    try {
      $placement_id = $request->input('post_id');
      $placement_details = $this->placement_model->getPlacementById($placement_id);
      $projects = $this->commonGetProjects();
      return view('placements.add_view', compact('placement_details', 'projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [postUpdate description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postUpdate(Request $request){
    $validator = Validator::make($request->all(), $this->placement_model->newValidationRules($request->input('end_date')));
    $placement_id = $request->input('placement_id');
    if ($validator->fails()) {
      try {
        $placement_details = $this->placement_model->getPlacementById($placement_id);
        $projects = $this->project_model->getAllProjects();
        return view('placements.add_view', compact('placement_details', 'projects'))->withErrors($validator);
      } catch (\Exception $e) {
        return response()->json(['exc' => utf8_encode($e->getMessage())]);
      }
    }
    $data = $this->constructData($request);
    $data['start_date'] = $this->getDateFormat($data['start_date']);
    if(isset($data['end_date'])){
      if($data['end_date']){
        $data['end_date'] = $this->getDateFormat($data['end_date']);
      }else{
        $data['end_date'] = null;
      }
    }
    $data['project_id'] = $data['project'];
    $data['channel_id'] = $data['channel'];
    unset($data['project'], $data['channel'], $data['placement_id']);
    try {
      $update_flag = $this->placement_model->updatePlacement($data, $placement_id);
      return redirect('placement/index')->with('updated_notice', 'Selected placement has been updated successfully with provided details.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getChannelByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getChannelByProject($project_id){
    try {
      $channels_by_project = $this->channel_model->getChannelByProject($project_id);
      return view('placements.select_channel_partial', compact('channels_by_project'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [getChannelByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getOldChannel($project_id, $old_channel_id=0){
    try {
      $channels_by_project = $this->channel_model->getChannelByProject($project_id);
      return view('placements.select_channel_partial', compact('channels_by_project', 'old_channel_id'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [deleteDelete description]
   * @return [type] [description]
   */
  public function deleteDelete(Request $request){
    try {
      $delete_id = $request->input('delete_id');
      if(!$this->verifyPlacementDelete($delete_id)){
        throw new \Exception('Could not delete placement now. Please try again.');
      }
      $delete_flag = $this->placement_model->deletePlacement($delete_id);
      if(!$delete_flag){
        throw new \Exception('Could not delete placement now. Please try again.');
      }
      $placements = $this->placement_model->getAllPlacements();
      $deleted_notice = "Chosen placement has been deleted successfully.";
      $placements = $this->commonPlacements();
      return view('placements.list_view', compact('placements', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this placement now. It seems this placement is being used by other modules.']);
    }
  }

  /**
   * [commonPlacements description]
   * @return [type] [description]
   */
  public function commonPlacements(){
    if(\Auth::user()->user_type == "general"){
      $projects = $this->commonGetProjects();
      return $this->placement_model->getPlacementByUserProject($projects);
    }else{
      return $this->placement_model->getAllPlacements();
    }
  }

  /**
   * [verifyPlacementDelete description]
   * @return [type] [description]
   */
  public function verifyPlacementDelete($placement_id){
    $placement = $this->placement_model->getPlacementById($placement_id);
    if($placement[0]->offers->isEmpty()){
      return true;
    }
    return false;
  }

  /**
   * [getShow description]
   * @param  [type] $placement_id [description]
   * @return [type]               [description]
   */
  public function getShow($placement_id){
    $placement_info = $this->placement_model->getPlacementById($placement_id);
    return view('placements.show_view', compact('placement_info'));
  }

  /**
   * [getByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getByProject($project_id){
    $placements = $this->placement_model->getPlacementByProject($project_id);
    if($placements->isEmpty()){
      return response()->json(['empty' => 'no data']);
    }
    $placements = Helper::sort_arr_of_obj($placements, 'title', 'asc');
    return response()->json(['placements' => $placements]);
  }

  /**
   * [getPlacementsCallCenter description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getPlacementsCallCenter($project_id){
    try {
      $placements = $this->placement_model->getPlacementByProject($project_id);
      return view('reports.exports.call_center_project_placements', compact('placements'));
    } catch (\Exception $e) {
      return reponse()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }


}//End PlacementController Class
