<?php

/**
 * Offer_Offer_Status.php
 *
 * Handles offers-offer status database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer_Offer_Status extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'offer_offer_status';
  /**
   * [$fillable description]
   * @var [type]
   */
  protected $fillable = ['offer_id', 'offer_status_id', 'type'];

  /**
   * [insertOfferStatus description]
   * @param  [type] $offer_id       [description]
   * @param  [type] $offer_statuses [description]
   * @return [type]                 [description]
   */
  public function insertOfferStatus($offer_id, $offer_statuses){
    $flag = array();
    if(isset($offer_statuses['ids'])){
      foreach ($offer_statuses['ids'] as $key => $offer_status_id) {
        $flag[] = $this->create(['offer_id'=>$offer_id, 'offer_status_id'=>$offer_status_id, 'type'=>'yes']);
      }
    }
    if(isset($offer_statuses['na'])){
      foreach ($offer_statuses['na'] as $key => $offer_status_id) {
        $flag[] = $this->create(['offer_id'=>$offer_id, 'offer_status_id'=>$offer_status_id, 'type'=>'na']);
      }
    }
    return $flag;
  }

  /**
   * [deleteOfferStatusPivotByOffer description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public function deleteOfferStatusPivotByOffer($offer_id){
    return $this->where('offer_id', $offer_id)->delete();
  }


}
