<?php

/**
 * Address.php
 *
 * Handles address database management and related operations.
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

class Address extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'address';
  /**
   * [$timestamps description]
   * @var boolean
   */
  public $timestamps = false;
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['country_id', 'street1', 'street2', 'street3', 'post_code', 'city', 'county', 'state', 'display_address'];

  /**
   * [$search_keys description]
   * @var [type]
   */
  protected $search_keys = ['street2', 'street3', 'post_code', 'city', 'county', 'state', 'display_address'];

  /**
   * [partners description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->hasOne('App\Models\Partner');
  }
  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->hasOne('App\Models\Contact');
  }
  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function clients(){
    return $this->hasOne('App\Models\Client');
  }

  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function countries(){
    return $this->belongsTo('App\Models\Country', 'country_id');
  }

  /**
   * [insertNewAddress description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewAddress($data){
    $data = $this->handleCommonConstraint($data);
    return $this->insertGetId($data);
    // return $address->
  }
  /**
   * [updateAddress description]
   * @param  [type] $address_id [description]
   * @param  [type] $data       [description]
   * @return [type]             [description]
   */
  public function updateAddress($address_id, $data){
    $data = $this->handleCommonConstraint($data);
    return $this->where('id', $address_id)->update($data);
  }

  /**
   * [deleteAddress description]
   * @param  [type] $address_id [description]
   * @return [type]             [description]
   */
  public function deleteAddress($address_id){
    return $this->where('id', $address_id)->delete();
  }

  /**
   * [handleCommonConstraint description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function handleCommonConstraint($data){
    $constraints = ['country_id', 'street1', 'street2', 'street3', 'post_code', 'city', 'county', 'state', 'display_address'];
    foreach ($constraints as $constraint) {
      if(isset($data[$constraint]) && !$data[$constraint]){
        $data[$constraint] = null;
      }
    }
    return $data;
  }//End handleCommonConstraint function

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    $search_keys = $this->search_keys;
    $query = $this->where('street1', 'LIKE', '%'.$search_key.'%');
    foreach ($this->search_keys as $key) {
      $query = $query->orWhere($key, 'LIKE', '%'.$search_key.'%');
    }
    $results = $query->get();
    return $results;
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function searchByGeneralUser($search_key, $projects){
    if(empty($projects)){
      return new Collection;
    }
    $search_data = $this->search($search_key);
  }


}//End Address Class
