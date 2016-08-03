<?php

/**
 * Offer_Status.php
 *
 * Handles offer status database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer_Status extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'offer_status';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['label'];
  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    return $this->belongsToMany('App\Models\Offer', 'offer_offer_status')->withPivot('type');
  }

  /**
   * [getAllOfferStatus description]
   * @return [type] [description]
   */
  public function getAllOfferStatus(){
    return $this->get();
  }
}
