<?php

/**
 * Project.php
 *
 * Handles projects database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class Project extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'projects';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['client_id', 'language_id', 'name', 'description', 'logo', 'first_issue_launch_date', 'circulation', 'note'];
  /**
   * [$new_project_validation_rules description]
   * @var [type]
   */
  public $new_project_validation_rules = [
    'name' => 'required',
    'client' => 'required',
    'language' => 'required',
    'client_contact' => 'required',
    'ji_contact' => 'required',
    // 'first_issue_launch_date' => 'required|date_format:d/m/Y',
  ];
  /**
   * [$edit_project_validation_rules description]
   * @var [type]
   */
  public $edit_project_validation_rules = [
    'name' => 'required',
    'client' => 'required',
    'language' => 'required',
    'client_contact' => 'required',
    'ji_contact' => 'required',
    // 'first_issue_launch_date' => 'required|date_format:d/m/Y',
  ];
  /**
   * [clients description]
   * @return [type] [description]
   */
  public function clients(){
    return $this->belongsTo('App\Models\Client', 'client_id');
  }
  /**
   * [offer_type_category_masterlist description]
   * @return [type] [description]
   */
  public function offer_type_category_masterlist(){
    return $this->hasMany('App\Models\Offer_Type_Category_Masterlist');
  }
  /**
   * [countries description]
   * @return [type] [description]
   */
  public function countries(){
    return $this->belongsToMany('App\Models\Country');
  }
  /**
   * [languages description]
   * @return [type] [description]
   */
  public function languages(){
    return $this->belongsTo('App\Models\Language', 'language_id');
    // return $this->belongsTo('App\Models\Language', 'language_project', 'project_id', 'language_id');
  }
  /**
   * [detailed_offer_type_masterlist description]
   * @return [type] [description]
   */
  public function detailed_offer_type_masterlist(){
    return $this->hasMany('App\Models\Detailed_Offer_Type_Masterlist');
  }
  /**
   * [general_offer_type_masterlist description]
   * @return [type] [description]
   */
  public function general_offer_type_masterlist(){
    return $this->hasMany('App\Models\General_Offer_Type_Masterlist');
  }
  /**
   * [channels description]
   * @return [type] [description]
   */
  public function channels(){
    return $this->hasMany('App\Models\Channel');
  }

  /**
   * [placements description]
   * @return [type] [description]
   */
  public function placements(){
    return $this->hasMany('App\Models\Placement');
  }

  /**
   * [partners description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->belongsToMany('App\Models\Partner', 'partner_project', 'project_id', 'partner_id');
  }

  /**
   * [users description]
   * @return [type] [description]
   */
  public function users(){
    return $this->belongsToMany('App\Models\User', 'project_user', 'project_id', 'user_id');
  }

  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsToMany('App\Models\Contact', 'contact_project', 'project_id', 'contact_id');
  }

  /**
   * [categories description]
   * @return [type] [description]
   */
  public function categories(){
    return $this->belongsToMany('App\Models\Category', 'category_project', 'project_id', 'category_id');
  }

  /**
   * [communication_package description]
   * @return [type] [description]
   */
  public function communication_package(){
    return $this->hasOne('App\Models\Communication_Package');
  }

  /**
   * [getFirstIssueLaunchDateAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getFirstIssueLaunchDateAttribute($value){
    if($value){
      return Carbon::parse($value)->format('Y-m-d');
      // return Carbon::parse($value)->format('Y-m-d');
    }else{
      return $value;
    }
  }

  /**
   * [setCirculationAttribute description]
   * @param [type] $value [description]
   */
  public function setCirculationAttribute($value){
    if($value):
      $this->attributes['circulation'] = $value;
    else:
      $this->attributes['circulation'] = null;
    endif;
  }

  /**
   * [getAllProjects description]
   * @return [type] [description]
   */
  public function getAllProjects(){
    return $this->get();
  }

  /**
   * [insertNewProject description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewProject($data){
    return $this->create($data);
  }

  /**
   * [deleteProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function deleteProject($project_id){
    return $this->where('id', $project_id)->delete();
  }

  /**
   * [getProjectById description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getProjectById($project_id){
    return $this->where('id', $project_id)->get();
  }

  /**
   * [getProjectByIdWith description]
   * @return [type] [description]
   */
  public function getProjectByIdWith($project_id=0, $with){
    return $this->where('id', $project_id)->with($with)->get();
  }

  /**
   * [editValidationRules description]
   * @param  [type] $logo [description]
   * @return [type]       [description]
   */
  public function editValidationRules($data){
    if(isset($data['logo'])){
      $this->edit_project_validation_rules['logo'] = 'image';
      return $this->edit_project_validation_rules;
    }
    if(!empty($data['first_issue_launch_date'])){
      // $this->edit_project_validation_rules['first_issue_launch_date'] = "required|date_format:d/m/Y";
      $this->edit_project_validation_rules['first_issue_launch_date'] = "date_format:Y-m-d";

    }
    return $this->edit_project_validation_rules;
  }

  /**
   * [getNewValidationRule description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function getNewValidationRule($data){
    if(isset($data['logo'])){
      $this->new_project_validation_rules['logo'] = "required|image";
    }
    if(!empty($data['first_issue_launch_date'])){
      $this->new_project_validation_rules['first_issue_launch_date'] = "date_format:Y-m-d";
    }
    return $this->new_project_validation_rules;
  }

  /**
   * [updateProject description]
   * @param  [type] $project_id [description]
   * @param  [type] $data       [description]
   * @return [type]             [description]
   */
  public function updateProject($project_id, $data){
    return $this->where('id', $project_id)->update($data);
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('first_issue_launch_date', 'LIKE', '%'.$search_key.'%')
                ->orWhere('description', 'LIKE', '%'.$search_key.'%')
                ->orWhere('circulation', 'LIKE', '%'.$search_key.'%')
                ->orWhere('note', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [searchByViewer description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function searchByViewer($search_key){
    $projects = $this->where('name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('first_issue_launch_date', 'LIKE', '%'.$search_key.'%')
                ->orWhere('description', 'LIKE', '%'.$search_key.'%')
                ->orWhere('circulation', 'LIKE', '%'.$search_key.'%')
                ->orWhere('note', 'LIKE', '%'.$search_key.'%')
                ->get();
    $results = $projects->filter(function($item){
                $flag = false;
                $project_users = $item->users;
                foreach ($project_users as $user){
                  if($user->id == \Auth::user()->id){
                    return true;
                    break;
                  }
                }
              });
    return $results;
  }

  /**
   * [getProjectNames description]
   * @param  [type] $project_ids [description]
   * @return [type]              [description]
   */
  public function getProjectNames($project_ids){
    $projects = $this->where(function($query) use ($project_ids){
              foreach($project_ids as $project_id){
                $query->orWhere('id', $project_id);
              }
            })->get();
    $project_names = [];
    foreach ($projects as $project) {
      $project_names[] = $project->name;
    }
    if(in_array('n_a', $project_ids)){
      $project_names[] = 'N/A';
    }
    return $project_names;
  }

}//End Class Project
