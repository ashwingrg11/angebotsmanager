<?php

/**
 * ProjectController.php
 *
 * Handles project module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Project as Project;
use App\Models\Client as Client;
use App\Models\User as User;
use App\Models\Project_User as ProjectUser;
use App\Models\Contact_Project as ContactProject;
use App\Models\Category as Category;
use App\Models\Category_Project as CategoryProject;
use App\Models\Language as Language;
use App\Models\Language_Project as LanguageProject;
use Validator;
use Input;
use Exception;
use File;
use Response;

class ProjectController extends MyController
{
  private $project_model;
  private $client_model;
  public function __construct()
  {
    parent::__construct();
    $this->project_model = new Project;
    $this->client_model = new Client;
    $this->user_model = new User;
    $this->project_user_model = new ProjectUser;
    $this->contact_project_model = new ContactProject;
    $this->category_model = new Category;
    $this->category_project_model = new CategoryProject;
    $this->language_model = new Language;
    $this->language_project_model = new LanguageProject;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      // $projects = $this->project_model->getAllProjects();
      $projects = $this->commonGetProjects();
      return view('projects.list_view', compact('projects'));
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
      $data['clients'] = $this->client_model->getAllClients();
      $ji_users = $this->user_model->allUsers();
      $categories = $this->category_model->allCategories();
      $languages = $this->language_model->allLanguages();
      return view('projects.add_view', compact('data', 'ji_users', 'categories', 'languages'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

  /**
   * [postCreate description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postCreate(Request $request){
    $validator = Validator::make($request->all(), $this->project_model->getNewValidationRule($request->all()));
    if ($validator->fails()) {
      return redirect('project/create')
                    ->withErrors($validator)
                    ->withInput();
    }
    $data = $this->constructData($request);
    if($request->file('logo')){
      $data['logo'] = $this->getRandomLogoName($request->file('logo'));
      $request->file('logo')->move(
        base_path() . '/public/images/projects/', $data['logo']
      );
    };
    $data['client_id'] = $data['client'];
    if($data['first_issue_launch_date']):
      $data['first_issue_launch_date'] = $this->getDateFormat($data['first_issue_launch_date']);
    else:
      $data['first_issue_launch_date'] = null;
    endif;
    $contact_ids = $data['client_contact'];
    if(isset($data['category'])):
      $categories = $data['category'];
    endif;
    if(!$data['note']):
      $data['note'] = null;
    endif;
    if(!$data['description']):
      $data['description'] = null;
    endif;
    $data['language_id'] = $data['language'];
    /*if(isset($data['language'])):
      $languages = $data['language'];
    endif;*/
    unset($data['client_contact'], $data['category'], $data['language'], $data['client'], $data['language']);
    try {
      $project_flag = $this->project_model->insertNewProject($data);
      $this->project_user_model->insertByProject($project_flag->id, $data['ji_contact']);
      $this->contact_project_model->insertByProject($project_flag->id, $contact_ids);
      if(!empty($categories)):
        $this->category_project_model->insertByProject($project_flag->id, $categories);
      endif;
      /*if(!empty($languages)):
        $this->language_project_model->insertByProject($project_flag->id, $languages);
      endif;*/
      return redirect('project/index')->with('added_notice', 'New project has been added successfully.');
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
      $project_id = $request->input('post_id');
      $project_details = $this->project_model->getProjectById($project_id);
      $ji_users = $this->user_model->allUsers();
      $categories = $this->category_model->allCategories();
      $languages = $this->language_model->allLanguages();
      $data['clients'] = $this->client_model->getAllClients();
      return view('projects.add_view', compact('data', 'project_details', 'ji_users', 'categories', 'languages'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postEdit function

  /**
   * [postUpdate description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postUpdate(Request $request){
    $project_id = $request->input('project_id');
    $project_logo = $request->file('logo');
    $project_details = $this->project_model->getProjectById($project_id);
    $validator = Validator::make($request->all(), $this->project_model->editValidationRules($request->all()));
    if ($validator->fails()) {
      // $project_details = $this->project_model->getProjectById($project_id);
      $data['clients'] = $this->client_model->getAllClients();
      $categories = $this->category_model->allCategories();
      $languages = $this->language_model->allLanguages();
      $ji_users = $this->user_model->allUsers();
      return view('projects.add_view', compact('data', 'project_details', 'ji_users', 'categories', 'languages'))->withErrors($validator);
    }
    $data = $this->constructData($request);
    $data['client_id'] = $data['client'];
    try {
      if($project_logo){
        $this->deleteProjectLogo($project_details);
        $data['logo'] = $this->getRandomLogoName($project_logo);
        $request->file('logo')->move(
          base_path() . '/public/images/projects/', $data['logo']
        );
      }
      if($data['first_issue_launch_date']):
        $data['first_issue_launch_date'] = $this->getDateFormat($data['first_issue_launch_date']);
      else:
        $data['first_issue_launch_date'] = null;
      endif;
      if(!$data['note']):
        $data['note'] = null;
      endif;
      if(!$data['description']):
        $data['description'] = null;
      endif;
      $contact_ids = $data['client_contact'];
      $jiuser_ids = $data['ji_contact'];
      if(isset($data['category'])):
        $categories = $data['category'];
      endif;
      $data['language_id'] = $data['language'];
      /*if(isset($data['language'])):
        $languages = $data['language'];
      endif;*/
      unset($data['client_contact'], $data['ji_contact'], $data['category'], $data['language'], $data['project_id'], $data['client'], $data['language']);
      if(!$data['circulation']):
        $data['circulation'] = null;
      endif;
      $update_flag = $this->project_model->updateProject($project_id, $data);
      $this->project_user_model->deleteByProject($project_id);
      $this->project_user_model->insertByProject($project_id, $jiuser_ids);
      $this->contact_project_model->deleteByProject($project_id);
      $this->contact_project_model->insertByProject($project_id, $contact_ids);
      $this->category_project_model->deleteByProject($project_id);
      if(!empty($categories)):
        $this->category_project_model->insertByProject($project_id, $categories);
      endif;
      /*$this->language_project_model->deleteByProject($project_id);
      if(!empty($languages)):
        $this->language_project_model->insertByProject($project_id, $languages);
      endif;*/
      return redirect('project/index')->with('updated_notice', 'Selected project has been updated successfully with provided details.');
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
      $project_details = $this->project_model->getProjectById($delete_id);
      $delete_flag = $this->project_model->deleteProject($delete_id);
      $this->deleteProjectLogo($project_details);
      $projects = $this->project_model->getAllProjects();
      $deleted_notice = "Chosen project has been deleted successfully.";
      return view('projects.list_view', compact('projects', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this project because it is being used by other modules.']);
    }
  }

  /**
   * [deleteProjectLogo description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function deleteProjectLogo($project_details){
    $image_url = public_path().'/images/projects/'.$project_details[0]->logo;
    File::Delete($image_url);
  }

  /**
   * [getShow description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getShow($project_id){
    $project_info = $this->project_model->getProjectByIdWith($project_id, ['clients']);
    return view('projects.show_view', compact('project_info'));
  }

  /**
   * [getRandomLogoName description]
   * @param  [type] $logo [description]
   * @return [type]       [description]
   */
  public function getRandomLogoName($logo){
    $image_rand_name = str_random(20);
    return $image_rand_name.time().'.'.$logo->getClientOriginalExtension();
  }

  /**
   * [getDeleteProjectLogo description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getDeleteProjectLogo($project_id){
    try {
      $project_details = $this->project_model->getProjectById($project_id);
      $this->deleteProjectLogo($project_details);
      $this->project_model->updateProject($project_id, ['logo'=>null]);
      $project_info = $this->project_model->getProjectByIdWith($project_id, ['clients']);
      $common_notice = "Selected project logo has been deleted successfully.";
      return view('projects.show_view', compact('project_info', 'common_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }
  }

}//End Class ProjectController
