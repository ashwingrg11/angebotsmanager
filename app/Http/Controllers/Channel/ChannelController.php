<?php

/**
 * ChannelController.php
 *
 * Handles channel module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Channel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Channel as Channel;
use App\Models\Project as Project;
use Validator;
use Exception;
use Auth;

class ChannelController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Channel Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to channel
  | module. It includes channel and project models for
  | it's related operations.
  |
  */
  private $channel_model;
  private $project_model;

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    //channel model
    $this->channel_model = new Channel;
    //project model
    $this->project_model = new Project;
  }

  /**
   * Return a view to list all channels.
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      $projects = $this->commonGetProjects(); //get projects
      if(Auth::user()->user_type == "general"){
        $channels = $this->channel_model->getChannelByUserProject($projects);
      }//End if user_type general
      else{
        //all available channels
        $channels = $this->channel_model->getAllChannels();
      }//End else user_type general
      return view('channels.list_view', compact('channels', 'projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End index function

  /**
   * Validate incoming channel post create request data and insert new channel.
   * @param  \Illuminate\Http\Request $request
   * @return [mixed]
   */
  public function postCreate(Request $request)
  {
    //validate request input data
    $validator = Validator::make($request->all(), $this->channel_model->new_channel_validation_rules);
    if ($validator->fails()) {
      return redirect('channel/index')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator if
    $data = $this->constructData($request);
    $data['project_id'] = null;
    $data['project_id'] = $data['project'];
    unset($data['project']);
    try {
      //insert new channel
      $insert_flag = $this->channel_model->insertNewChannel($data);
      if(empty($insert_flag)){
        throw new Exception('Could not insert channel this time. Please try again later.');
      }
      //redirect with success notice message
      return redirect('channel/index')->with('added_notice', 'New channel has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get channel id and query to delete channel.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function deleteDelete(Request $request){
    try {
      //delete id from incoming request
      $delete_id = $request->input('delete_id');
      //delete channel by received channel id
      $delete_flag = $this->channel_model->deleteChannel($delete_id);
      if($delete_flag == false){
        throw new Exception('Invalid delete id.');
      }
      //all available projects
      $projects = $this->project_model->getAllProjects();
      $deleted_notice = "Chosen channel has been deleted successfully.";
      if(Auth::user()->user_type == "general"){
        //get available channels as per logged in general user
        $channels = $this->channel_model->getChannelByUserProject($projects);
      }else{
        //get all channels if user type is not general
        $channels = $this->channel_model->getAllChannels();
      }//End if-else user_type is general
      return view('channels.list_view', compact('channels', 'deleted_notice', 'projects'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this channel because it is being used by other modules.']);
    }
  }//End delete function

  /**
   * Get channel id from incoming edit request and return view to edit details.
   * @param  \Illuminate\Http\Request $request [channel-post edit request]
   * @return [mixed]
   */
  public function postEdit(Request $request){
    //get channel id
    $channel_id = $request->input('post_id');
    //channel details by it's id
    $channel_details = $this->channel_model->getChannelById($channel_id);
    $projects = $this->commonGetProjects(); //projects
    //channel edit view with channel and project details
    return view('channels.channel_form_partial', compact('data','channel_details', 'projects'));
  }//End postEdit function

  /**
   * Validate incoming channel post update request and update channel info.
   * @param  \Illuminate\Http\Request $request [channel post update request]
   * @return [mixed]
   */
  public function postUpdate(Request $request){
    //validates user inputs
    $validator = Validator::make($request->all(), $this->channel_model->edit_channel_validation_rules);
    $channel_id = $request->input('channel_id'); //channel edit id
    if ($validator->fails()) {
      $channel_details = $this->channel_model->getChannelById($channel_id);
      //all available projects
      $projects = $this->project_model->getAllProjects();
      $channels = $this->channel_model->getAllChannels(); //all channels
      return view('channels.list_view', compact('channels', 'projects', 'channel_details'))->withErrors($validator);
    }//End validator fails if
    $data = $this->constructData($request);
    $data['project_id'] = null;
    $data['project_id'] = $data['project'];
    unset($data['project'], $data['channel_id']);
    try {
      //update channel info
      $update_flag = $this->channel_model->updateChannel($data, $channel_id);
      if(!$update_flag){
        throw new Exception('Could not update channel this time. Please try again later.');
      }
      //redirect to channel index page with update-success notice
      return redirect('channel/index')->with('updated_notice', 'Chosen channel has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdae function

}//End Class, ChannelController
