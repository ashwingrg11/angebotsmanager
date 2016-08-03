<?php

/**
 * Offer_User.php
 *
 * Handles offers-users database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer_User extends Model
{
  /**
   * Define database table name for this Offer_User model.
   * @var string $table
   */
  protected $table = 'offer_user';
  /**
   * Define mass assignable attributes.
   * @var [type]
   */
  protected $fillable = ['offer_id', 'user_id'];

  /**
   * [insertByOffer description]
   * @param  [int] $offer_id
   * @param  [array] $user_ids
   * @return [object] Offer_User
   */
  public function insertByOffer($offer_id, $user_ids){
    $flag = null;
    foreach ($user_ids as $user_id) {
      $flag = $this->create(['offer_id'=>$offer_id, 'user_id'=>$user_id]);
    }
    return $flag;
  }

  /**
   * Delete Offer_User records by offer id.
   * @param  [int] $offer_id
   * @return [bool]
   */
  public function deleteByOffer($offer_id){
    return $this->where('offer_id', $offer_id)
                ->delete();
  }
}
