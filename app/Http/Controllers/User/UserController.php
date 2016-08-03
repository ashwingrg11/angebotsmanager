<?php

/**
 * UserController.php
 *
 * Handles user module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\User as User;
use Input;

class UserController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | User Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration/amendments of new users,
  | as well as it returns all the currently available users.
  |
  */

  /**
   * [__construct description]
   */
  public function __construct(){
    parent::__construct();
    $this->user_model = new User;
  }

  /**
   * Return all available users in user listing page
   * @return [collection object] $users [all users]
   * @return \Illuminate\View\View users.list_view
   */
  public function getIndex(){
    try {
      $users = $this->user_model->allUsers();
      return view('users.list_view', compact('users'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * Return view for incoming user registration request.
   * @return \Illuminate\View\View users.add_view
   */
  public function getCreate(){
    return view('users.add_view');
  }

  /**
   * Validate incoming user post create request and register new user.
   * @param \Illuminate\Http\Request $request
   * @return [type] [description]
   */
  public function postCreate(Request $request){
    $validator = \Validator::make($request->all(), $this->user_model->getValidationRules($request));
    if ($validator->fails()) {
      return redirect('user/create')
          ->withErrors($validator)
          ->withInput();
    };
    $data = $this->constructData($request);
    unset($data['password_confirmation']);
    try {
      $insert_flag = $this->user_model->insertUser($data);
      return redirect('user/index')->with('added_notice', 'New user has been registered successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * Return user details and view to edit user details.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    try {
      $user_details = $this->user_model->getUserById($request->input('post_id'));
      return view('users.add_view', compact('user_details'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * Validate user inputs of incoming user update request and update user details.
   * @param  \Illuminate\Http\Request $request
   * @return [type]           [description]
   */
  public function postUpdate(Request $request){
    $validator = \Validator::make($request->all(), $this->user_model->getValidationRules($request));
    $user_id = $request->input('id');
    if($validator->fails()) {
      $user_details = $this->user_model->getUserById($user_id);
      Input::flash();
      return view('users.add_view', compact('user_details'))->withErrors($validator);
    };
    $data = $this->constructData($request);
    $msg = "Selected user's details has been updated successfully.";
    return $this->commonUserUpdate($data, "user/index", "updated_notice", $msg);
  }

  /**
   * Delete user from database and redirect to list page.
   * @param  \Illuminate\Http\Request $request
   * @return [mixed]
   */
  public function deleteDelete(Request $request){
    try {
      $delete_flag = $this->user_model->deleteUser($request->input('delete_id'));
      if($delete_flag == false){
        throw new Exception('Could not delete user now. Please try again.');
      };
      $deleted_notice = "Chosen user details has been deleted successfully.";
      $users = $this->user_model->allUsers();
      return view('users.list_view', compact('users', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this user now. It seems this user is being used by other modules.']);
    }
  }

  /**
   * [getShow description]
   * @param  [type] $user_id [description]
   * @return [type]          [description]
   */
  public function getShow($user_id){
    $user_info = $this->user_model->getUserById($user_id);
    return view('users.show_view', compact('user_info'));
  }

  /**
   * [getProfile description]
   * @return [type] [description]
   */
  public function getProfile(){
    $user_info = $this->user_model->getUserById(\Auth::user()->id);
    return view('users.show_profile', compact('user_info'));
  }

  /**
   * [postEditProfile description]
   * @return [type] [description]
   */
  public function postEditProfile(Request $request){
    $validator = \Validator::make($request->all(), $this->user_model->getProfileValidationRules($request));
    $user_id = $request->input('id');
    if($validator->fails()) {
      return redirect('user/profile')
        ->withErrors($validator);
    };
    $data = $this->constructData($request);
    $msg = "Your profile details has been updated successfully.";
    if(\Auth::user()->user_type == "admin"){
      if(!$this->validateEditProfile($request->input('user_type'))){
        return $this->commonUserUpdate($data, "user/profile", "updated_notice", $msg)->with('user_type_notice', 'user_type_notice');
      }
    }
    return $this->commonUserUpdate($data, "user/profile", "updated_notice", $msg);
  }//End postEditProfile function

  /**
   * [validateEditProfile description]
   * @param  [type] $new_user_type [description]
   * @return [type]                [description]
   */
  public function validateEditProfile($new_user_type){
    $old_user_type = \Auth::user()->user_type;
    if($old_user_type != $new_user_type){
      return false;
    }
    return true;
  }

  /**
   * [postResetPassword description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postResetPassword(Request $request){
    $validator = \Validator::make($request->all(), $this->user_model->password_reset_validation_rules);
    if($validator->fails()) {
      return redirect('user/profile')
        ->withErrors($validator)
        ->withInput();
    };
    $data = ['password' => $request->input('new_password')];
    $msg = "Your password has been reset successfully.";
    return $this->commonUserUpdate($data, "user/profile", "password_reset_success", $msg);
  }//End postResetPassword function

  /**
   * [commonUserUpdate description]
   * @return [type] [description]
   */
  public function commonUserUpdate($data, $page, $notice, $message){
    $user_id = \Auth::user()->id;
    if(isset($data['id'])){
      $user_id = $data['id'];
      if(empty($data['password'])){
        unset($data['password']);
      }
    }
    unset($data['id'], $data['password_confirmation']);
    try {
      $update_flag = $this->user_model->updateUser($user_id, $data);
      if($update_flag):
        return redirect($page)->with($notice, $message);
      endif;
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }//End commonUserUpdate function

}//End Class UserController
