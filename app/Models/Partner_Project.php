<?php

/**
 * Partner_Project.php
 *
 * Handles partners-projects database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner_Project extends Model
{
  //
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'partner_project';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['partner_id', 'project_id'];

  /**
   * [partners description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->belongsTo('App\Models\Partner', 'partner_id');
  }

  /**
   * [partners description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [insertParnterProject description]
   * @param  [type] $partner_id  [description]
   * @param  [type] $project_ids [description]
   * @return [type]              [description]
   */
  public function insertParnterProject($partner_id, $project_ids){
    foreach ($project_ids as $project_id):
      $flag = $this->create(['partner_id' => $partner_id, 'project_id' => $project_id]);
    endforeach;
    return $flag;
  }

  /**
   * [deleteByPartner description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function deleteByPartner($partner_id){
    return $this->where('partner_id', $partner_id)->delete();
  }

  /**
   * [fetchByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function fetchByProject($project_id){
    return $this->where('project_id', $project_id)->get();
  }

   /**
   * [getNonAssignedContacts description]
   * @return [type] [description]
   */
  public function getNonOfferPartnerContacts(){
    $partners_projects = $this->get();
    $partners_projects = $partners_projects->filter(function($item){
                          $flag = false;
                          if(!$item->partners->contacts->isEmpty()){
                            foreach($item->partners->contacts as $contact){
                              echo $contact->id;
                              if($contact->offers->isEmpty()){
                                if(!$flag){
                                  $flag = true;
                                }
                              }else{
                                $flag = false;
                              }
                            }
                          }
                          return $flag;
                        });
    return $partners_projects;
  }

}
