<?php

/**
 * Offer_Type_Category_Masterlist.php
 *
 * Handles offer type categories database management and related operations.
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

class Offer_Type_Category_Masterlist extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'offer_type_category_masterlist';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['project_id', 'label'];
  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    return $this->hasMany('App\Models\Offer', 'offer_type_category_masterlist_id', 'id');
  }
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * [getAll description]
   * @return [type] [description]
   */
  public function getAll(){
    return $this->get();
  }

  /**
   * [insertOfferTypeCategory description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertOfferTypeCategory($data){
    return $this->create($data);
  }
  /**
   * [deleteOfferTypeCategory description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function deleteOfferTypeCategory($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * [getOfferTypeCategoryById description]
   * @param  [type] $id [description]
   * @return [type]     [description]
   */
  public function getOfferTypeCategoryById($id){
    return $this->where('id', $id)->get();
  }

  /**
   * [updateOfferTypeCategory description]
   * @return [type] [description]
   */
  public function updateOfferTypeCategory($data, $id){
    return $this->where('id', $id)->update($data);
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('label', 'LIKE', '%'.$search_key.'%')
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
    $results = $this->where('label', 'LIKE', '%'.$search_key.'%')
                ->where(function($query) use ($projects){
                  foreach($projects as $project){
                    $query->orWhere('project_id', $project->id);
                  }
                })->get();
    return $results;
  }//End searchByGeneralUser function

  /**
   * [getAllByUser description]
   * @param  [type] $projects [description]
   * @return [type]           [description]
   */
  public function getAllByUser($projects){
    $offer_types = $this->getAll();
    $offer_types = $offer_types->filter(function($item) use($projects){
                    $flag = false;
                    foreach ($projects as $project) {
                      if($item->project_id == $project->id){
                        $flag = true;
                      }
                    }
                    return $flag;
                  });
    return $offer_types;
  }

}
