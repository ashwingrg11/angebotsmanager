<?php

/**
 * Offer_Placement.php
 *
 * Handles offers-placements database management and related operations.
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

class Offer_Placement extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'offer_placement';
  /**
   * [$fillable description]
   * @var [type]
   */
  protected $fillable = ['offer_id', 'placement_id', 'placement_start_date', 'placement_end_date'];
  /**
   * [$update_offer_placement description]
   * @var [type]
   */
  public $update_offer_placement = [
    'offer_id' => 'required|integer',
    'placement_id' => 'required|integer',
    'placement_start_date' => 'required|date_format:Y-m-d|before:placement_end_date',
    'placement_end_date' => 'required|date_format:Y-m-d|after:placement_start_date'
  ];
  /**
   * [$validation_attribute description]
   * @var [type]
   */
  public $validation_attribute = [
    'placement_id' => 'placement',
    'placement_start_date' => 'start date',
    'placement_end_date' => 'end date'
  ];
  /**
   * [getPlacementStartDateAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getPlacementStartDateAttribute($value){
    // return Carbon::parse($value)->format('d/m/Y');
    return Carbon::parse($value)->format('Y-m-d');
  }
  /**
   * [getPlacementStartDateAttribute description]
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function getPlacementEndDateAttribute($value){
    // return Carbon::parse($value)->format('d/m/Y');
    return Carbon::parse($value)->format('Y-m-d');
  }
  /**
   * [insertOfferPlacement description]
   * @param  [type] $offer_id      [description]
   * @param  [type] $placement_ids [description]
   * @return [type]                [description]
   */
  public function insertOfferPlacement($data){
    $flag = array();
    foreach ($data as $key => $row) {
      $flag[] = $this->create($row);
    }
    return $flag;
  }

  /**
   * [deleteOfferPlacementByOffer description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function deleteOfferPlacementByOffer($offer_id){
    return $this->where('offer_id', $offer_id)->delete();
  }

  /**
   * [getOfferPlacement description]
   * @param  [type] $offer_id     [description]
   * @param  [type] $placement_id [description]
   * @return [type]               [description]
   */
  public function getOfferPlacement($offer_id, $placement_id){
    return $this->where('offer_id', $offer_id)
                ->where('placement_id', $placement_id)
                ->get();
  }

  /**
   * [updateOfferPlacementById description]
   * @param  [type] $id   [description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function updateOfferPlacementById($id, $data){
    return $this->where('id', $id)->update($data);
  }

}
