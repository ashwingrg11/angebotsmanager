<?php

/**
 * Partner.php
 *
 * Handles partners database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as Collection;
use Auth;

class Partner extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'partners';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'address_id','partner_name','notes','region'];

  /**
   * [$new_partner_validation_rules description]
   * @var array
   */
  public $new_partner_validation_rules = [
    'project' => 'required',
    'partner_name' => 'required',
    // 'region' => 'required'
    // 'notes' => 'required'
  ];

  /**
   * [address description]
   * @return [type] [description]
   */
  public function address(){
    return $this->belongsTo('App\Models\Address');
  }
  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    return $this->hasMany('App\Models\Offer');
  }
  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsToMany('App\Models\Contact');
  }
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsToMany('App\Models\Project', 'partner_project', 'partner_id', 'project_id');
  }

  /**
   * [setRegionAttribute description]
   * @param [type] $value [description]
   */
  public function setRegionAttribute($value){
    if($value):
      $this->attributes['region'] = $value;
    else:
      $this->attributes['region'] = null;
    endif;
  }

  /**
   * [setNotesAttribute description]
   * @param [type] $value [description]
   */
  public function setNotesAttribute($value){
    if($value):
      $this->attributes['notes'] = $value;
    else:
      $this->attributes['notes'] = null;
    endif;
  }

  /**
   * [getAllClients description]
   * @return [type] [description]
   */
  public function getAllPartners(){
    return $this->get();
  }

  /**
   * [getClientById description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function getPartnerById($partner_id){
    return $this->where('id', $partner_id)->get();
  }

  /**
   * [updatePartner description]
   * @return [type] [description]
   */
  public function updatePartner($partner_id, $data){
    return $this->where('id', $partner_id)->update($data);
  }


  /**
   * [deleteClient description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function deletePartner($partner_id){
    return $this->where('id', $partner_id)->delete();
  }
  /**
   * [insertNewClient description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewPartner($data){
    return $this->create($data);
  }

  /**
   * [getPartnerByIdWith description]
   * @param  [type] $partner_id [description]
   * @param  [type] $with       [description]
   * @return [type]             [description]
   */
  public function getPartnerByIdWith($partner_id, $with){
    return $this->where('id', $partner_id)->with($with)->get();
  }

  /**
   * [getPartnersSortBy description]
   * @return [type] [description]
   */
  public function allPartnersByOrder(){
    return $this->orderBy('partner_name')->get();
  }

  /**
   * [getEditValidationRules description]
   * @return [type] [description]
   */
  public function getEditValidationRules(){
    $validation_rules = $this->new_partner_validation_rules;
    if(Auth::user()->user_type != "admin"){
      unset($validation_rules['project']);
      return $validation_rules;
    }
    return $validation_rules;
  }

  /**
   * [getPartnerProjectIds description]
   * @return [type] [description]
   */
  public function getPartnerProjectIds($partner_id){
    $partner = $this->getPartnerById($partner_id);
    $project_ids = [];
    foreach ($partner[0]->projects as $project) {
      $project_ids[] = $project->id;
    }
    return $project_ids;
  }

  /**
   * [filterParterProjects description]
   * @return [type] [description]
   */
  public function filterParterAvailableProjects($partner_id){
    $projects = Auth::user()->projects;
    if($projects->isEmpty()){
      return new Collection;
    }
    $partner_project_ids = $this->getPartnerProjectIds($partner_id);
    $projects = $projects->filter(function($item) use($partner_project_ids){
                  if(!in_array($item->id, $partner_project_ids)){
                    return true;
                  }
                });
    return $projects;
  }

  /**
   * [getPartnerByUserProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getPartnerByUserProject($projects){
    if(empty($projects)):
      return new Collection;
    endif;
    $partners = $this->getAllPartners();
    $partners = $partners->filter(function($item) use($projects){
                  foreach ($item->projects as $project) {
                    $flag = false;
                    foreach ($projects as $project_user) {
                      if($project_user->id == $project->id):
                        $flag = true;
                      endif;
                    }
                  }
                  return $flag;
                });
    return $partners;
  }//End getPartnerByUserProject function

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('partner_name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('notes', 'LIKE', '%'.$search_key.'%')
                ->orWhere('region', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [partnersByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function partnersByProject($project_id){
    $partners = $this->getAllPartners();
    if($partners->isEmpty()){
      return new Collection;
    }
    $partners = $partners->filter(function($item) use($project_id){
                  if(!$item->projects->isEmpty()) {
                    foreach ($item->projects as $project) {
                      if($project->id == $project_id){
                        return true;
                        break;
                      }
                    }
                  }
                });
    return $partners;
  }

  /**
   * [searchByUserProject description]
   * @param  [type] $search_key [description]
   * @param  [type] $projects   [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects){
    return $this->search($search_key);
    /*if(empty($projects)){
      return new Collection;
    }
    $results = $this->where('partner_name', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('notes', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('region', 'LIKE', '%'.$search_key.'%')
                    ->get();
    $results = $results->filter(function($item) use($projects){
                  foreach ($item->projects as $project) {
                    $flag = false;
                    foreach ($projects as $project_user) {
                      if($project_user->id == $project->id):
                        $flag = true;
                      endif;
                    }
                    return $flag;
                  }
                });*/
    return $results;
  }//End searchByGeneralUser function

}//End Class Partner
