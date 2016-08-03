<?php

/**
 * Channel.php
 *
 * Handles channel database management and related operations.
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

class Channel extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'channels';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'name'];
  /**
   * [$new_channel_validation_rules description]
   * @var [type]
   */
  public $new_channel_validation_rules = [
    'project' => 'required',
    'name' => 'required',
  ];
  /**
   * [$edit_channel_validation_rules description]
   * @var [type]
   */
  public $edit_channel_validation_rules = [
    'project' => 'required',
    'name' => 'required',
  ];
  /**
   * [placements description]
   * @return [type] [description]
   */
  public function placements(){
    return $this->hasMany('App\Models\Placement');
  }
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [getAllChannels description]
   * @return [type] [description]
   */
  public function getAllChannels(){
    return $this->get();
  }

  /**
   * [insertNewChannel description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewChannel($data){
    return $this->create($data);
  }

  /**
   * [deleteChannel description]
   * @param  [type] $channel_id [description]
   * @return [type]             [description]
   */
  public function deleteChannel($channel_id){
    return $this->where('id', $channel_id)->delete();
  }

  /**
   * [getChannelById description]
   * @param  [type] $channel_id [description]
   * @return [type]             [description]
   */
  public function getChannelById($channel_id){
    return $this->where('id', $channel_id)->get();
  }

  /**
   * [updateChannel description]
   * @param  [type] $data       [description]
   * @param  [type] $channel_id [description]
   * @return [type]             [description]
   */
  public function updateChannel($data, $channel_id){
    return $this->where('id', $channel_id)->update($data);
  }

  /**
   * [getChannelByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function getChannelByProject($project_id){
    return $this->where('project_id', $project_id)->get();
  }

  /**
   * [getChannelByUserProject description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getChannelByUserProject($projects){
    if(empty($projects)){
      return new Collection;
    }
    $channels = $this->where(function($query) use ($projects){
              foreach($projects as $project){
                $query->orWhere('project_id','=', $project->id);
              }
            })->get();
    return $channels;
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('name', 'LIKE', '%'.$search_key.'%')
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
    $results = $this->where('name', 'LIKE', '%'.$search_key.'%')
                ->where(function($query) use ($projects){
                  foreach($projects as $project){
                    $query->orWhere('project_id', $project->id);
                  }
                })->get();
    return $results;
  }

}//End Class Channel
