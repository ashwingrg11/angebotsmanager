<?php

/**
 * Communication_Package.php
 *
 * Handles communication package database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication_Package extends Model
{
  /**
   * [database table name for this Communication_Package model]
   * @var string $table
   */
  protected $table = 'communication_package';
  /**
   * [mass assignable properties of this model]
   * @var [array] $fillable
   */
  protected $fillable = ['project_id', 'activation', 'microsite1_monthly', 'microsite2_final', 'microsite2_reminder'];
  /**
   * [validation rules for creating communication package info]
   * @var [array] $new_commn_validation_rules
   */
  public $new_commn_validation_rules = [
    'activation' => 'required|numeric|regex:/^[1-9]\d*$/',
    'microsite1_monthly' => 'required|numeric|regex:/^[1-9]\d*$/',
    'microsite2_final' => 'required|numeric|regex:/^[1-9]\d*$/',
    'microsite2_reminder' => 'required|numeric|regex:/^[1-9]\d*$/'
  ];
  /**
   * [validator attributes for validations]
   * @var [array] $validation_attributes
   */
  public $validation_attributes = [
    'project_id' => 'project',
    'activation' => 'prior duration of offer activation email',
    'microsite1_monthly' => 'after duration of microsite1 monthly email',
    'microsite2_final' => 'after duration of microsite2 final email',
    'microsite2_reminder' => 'after duration of microsite2 reminder email'
  ];
  /**
   * [reverse, one to one relationship with project model]
   * @return [object] [instance of Project model]
   */
  public function project(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [return validation rules for creating or updating a commn package details]
   * @return [array] $new_commn_validation_rules
   */
  public function getCommnValidationRules($request){
    if($request->input('old_project_id')):
      if($request->input('old_project_id') == $request->input('project_id')):
        $this->new_commn_validation_rules['project_id'] = "required";
      else:
        $this->new_commn_validation_rules['project_id'] = "required|unique:communication_package,project_id";
      endif;
    else:
      $this->new_commn_validation_rules['project_id'] = "required|unique:communication_package,project_id";
    endif;
    return $this->new_commn_validation_rules;
  }
  /**
   * [return all available communication packages]
   * @return [collection object] [communication packages]
   */
  public function getAllCommunication(){
    return $this->get();
  }

  /**
   * [insert new communication package details]
   * @param  [array] $data
   * @return [object] [newly inserted package]
   */
  public function insertCommnPackage($data){
    return $this->create($data);
  }

  /**
   * [return communication package details by it's id]
   * @param  [int] $id [package id]
   * @return [object] [package details]
   */
  public function getCommnPackageById($id){
    return $this->where('id', $id)->get();
  }

  /**
   * [delete communication package by it's id]
   * @param  [int] $id [commn package id which record will be deleted]
   * @return [bool]
   */
  public function deleteCommnPackage($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * [update communication package details]
   * @param  [int] $id [commn package id]
   * @param  [array] $data [data to update]
   * @return [bool]
   */
  public function updateCommnPackage($id, $data){
    return $this->where('id', $id)->update($data);
  }

  /**
   * [return communication details by project id]
   * @param  [int] $project_id
   * @return [object] [communicatioin details]
   */
  public function getCommnPackageByProject($project_id){
    return $this->where('project_id', $project_id)->get();
  }

  /**
   * [getCommunicationByUser description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getCommunicationByUser($projects){
    $communications = $this->getAllCommunication();
    $communications = $communications->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $communications;
  }//End getCommunicationByUser function

}//End Class Communication_Package
