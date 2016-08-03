<?php

/**
 * Contact_Project.php
 *
 * Handles contacts-projects database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact_Project extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'contact_project';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'contact_id'];

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsTo('App\Models\Contact', 'contact_id');
  }

  /**
   * [getAll description]
   * @return [type] [description]
   */
  public function getAll(){
    return $this->get();
  }

  /**
   * Insert new record into database.
   * @param  [int] $project_id
   * @param  [array] $contact_ids
   * @return [object] Contact_Project
   */
  public function insertByProject($project_id, $contact_ids){
    $flag = null;
    foreach ($contact_ids as $contact_id) {
      $flag = $this->create(['project_id'=>$project_id, 'contact_id'=>$contact_id]);
    }
    return $flag;
  }

  /**
   * [deleteByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function deleteByProject($project_id){
    return $this->where('project_id', $project_id)
                ->delete();
  }

  /**
   * [getByProjectIds description]
   * @param  [type] $project_ids [description]
   * @return [type]              [description]
   */
  public function getByProjectIds($project_ids){
    $contacts_projects = $this->where(function($query) use ($project_ids){
                          foreach($project_ids as $project_id){
                            $query->orWhere('project_id', $project_id);
                          }
                        })->get();
    return $contacts_projects;
  }

}
