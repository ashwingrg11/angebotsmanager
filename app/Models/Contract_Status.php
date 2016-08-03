<?php

/**
 * Contract_Status.php
 *
 * Handles contract status database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract_Status extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'contract_status';
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
    return $this->hasMany('App\Models\Offer');
  }

  /**
   * [getAllContractStatus description]
   * @return [type] [description]
   */
  public function getAllContractStatus(){
    return $this->get();
  }
}
