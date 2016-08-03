<?php

/**
 * Placement.php
 *
 * Handles placements database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection as Collection;

class Placement extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'placements';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'channel_id', 'title', 'start_date', 'end_date'];
  public $new_placement_validation_rules = [
    'project' => 'required',
    'channel' => 'required',
    'title' => 'required',
    'start_date' => 'required|date_format:Y-m-d'
  ];
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }
  /**
   * [channels description]
   * @return [type] [description]
   */
  public function channels(){
    return $this->belongsTo('App\Models\Channel', 'channel_id');
  }
  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    // return $this->belongsToMany('App\Models\Offer', 'offer_placement', 'placement_id', 'offer_id')->withPivot(['id', 'placement_start_date', 'placement_end_date']);
    return $this->belongsToMany('App\Models\Offer', 'offer_placement', 'placement_id', 'offer_id')->withPivot(['id', 'placement_start_date', 'placement_end_date']);
  }

  /**
   * [getStartDateAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getStartDateAttribute($value){
    return Carbon::parse($value)->format('d/m/Y');
  }

  /**
   * [getEndDateAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getEndDateAttribute($value){
    if($value == null){
      return null;
    }else{
      return Carbon::parse($value)->format('d/m/Y');
    }
  }

  /**
   * [getAllPlacements description]
   * @return [type] [description]
   */
  public function getAllPlacements(){
    return $this->get();
  }

  /**
   * [newValidationRules description]
   * @param  [type] $end_date [description]
   * @return [type]           [description]
   */
  public function newValidationRules($end_date){
    if($end_date){
      $this->new_placement_validation_rules['end_date'] = 'date_format:Y-m-d|after:start_date';
      return $this->new_placement_validation_rules;
    }else{
      return $this->new_placement_validation_rules;
    }
  }

  /**
   * [insertPlacement description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertPlacement($data){
    return $this->create($data);
  }

  /**
   * [deletePlacement description]
   * @param  [type] $placement_id [description]
   * @return [type]               [description]
   */
  public function deletePlacement($placement_id){
    return $this->where('id', $placement_id)->delete();
  }

  /**
   * [getPlacementById description]
   * @return [type] [description]
   */
  public function getPlacementById($placement_id){
    return $this->where('id', $placement_id)->get();
  }

  /**
   * [updatePlacement description]
   * @param  [type] $data         [description]
   * @param  [type] $placement_id [description]
   * @return [type]               [description]
   */
  public function updatePlacement($data, $placement_id){
    return $this->where('id', $placement_id)->update($data);
  }

  /**
   * [getPlacementByUserProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getPlacementByUserProject($projects){
    if(empty($projects)){
      return new Collection;
    }
    $placements = $this->where(function($query) use ($projects){
              foreach($projects as $project){
                $query->orWhere('project_id', $project->id);
              }
            })->get();
    return $placements;
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('title', 'LIKE', '%'.$search_key.'%')
                ->orWhere('start_date', 'LIKE', '%'.$search_key.'%')
                ->orWhere('end_date', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [searchByUserProject description]
   * @param  [type] $search_key [description]
   * @param  [type] $projects   [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects){
    if(empty($projects)){
      return new Collection;
    }
    $results = $this->where('title', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('start_date', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('end_date', 'LIKE', '%'.$search_key.'%')
                    ->where(function($query) use ($projects){
                      foreach($projects as $project){
                        $query->orWhere('project_id', $project->id);
                      }
                    })->get();
    return $results;
  }//End searchByGeneralUser function

  /**
   * [searchByGeneralViewer description]
   * @return [type] [description]
   */
  public function searchByViewer($search_key, $projects){
    if(empty($projects)){
      return new Collection;
    }
    $placements = $this->where('title', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('start_date', 'LIKE', '%'.$search_key.'%')
                    ->orWhere('end_date', 'LIKE', '%'.$search_key.'%')
                    /*->where(function($query) use ($projects){
                      foreach($projects as $project){
                        $query->orWhere('project_id', $project->id);
                      }
                    })->get();*/
                    ->get();
    $results = $placements->filter(function($item) use($projects){
                $flag = false;
                foreach ($projects as $project) {
                  if($item->project_id == $project->id){
                    $flag = true;
                  }
                }
                return $flag;
              });
    return $results;
  }

  /**
   * [getPlacementByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getPlacementByProject($project_id){
    return $this->where('project_id', $project_id)
                ->get();
  }

  /**
   * [getProjectsPlacements description]
   * @param  [type] $project_ids [description]
   * @return [type]              [description]
   */
  public function getProjectsPlacements($project_ids){
    $placements = $this->where(function($query) use ($project_ids){
              foreach($project_ids as $project_id){
                $query->orWhere('project_id', $project_id);
              }
            })->get();
    return $placements;
  }

  /**
   * [getPlacementNames description]
   * @param  [type] $placement_ids [description]
   * @return [type]                [description]
   */
  public function getPlacementNames($placement_ids){
    if(empty($placement_ids)){
      return array();
    }
    $placements = $this->where(function($query) use ($placement_ids){
              foreach($placement_ids as $placement_id){
                $query->orWhere('id', $placement_id);
              }
            })->get();
    $placement_names = [];
    foreach ($placements as $placement) {
      $placement_names[] = $placement->title;
    }
    if(in_array('n_a', $placement_ids)){
      $placement_names[] = 'N/A';
    }
    return $placement_names;
  }

}//End Class Placement
