<?php

/**
 * Disabled_Offer.php
 *
 * Handles disabled offer database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Disabled_Offer extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'disabled_offers';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['user_id','offer_id'];

  /**
   * Define eloquent one to many relationship with User model.
   * @return [object] User
   */
  public function users(){
    return $this->belongsTo('App\Models\User', 'user_id');
  }

  /**
   * Define eloquent one to many relationship with Offer model.
   * @return [object] Offer
   */
  public function offers(){
    return $this->belongsTo('App\Models\Offer', 'offer_id');
  }

  /**
   * Return all disabled offers by user id.
   * @return [collection object] [disabled offers]
   */
  public function getAllByUser($user_id){
    return $this->where('user_id', $user_id)->get();
  }

  /**
   * Insert new disabled offer item in database table.
   * @param  [int] $offer_id
   * @return [object] DisabledOffer [recently inserted item]
   */
  public function insertNew($offer_id){
    $data['offer_id'] = $offer_id;
    $data['user_id'] = Auth::user()->id;
    return $this->create($data);
  }

}
