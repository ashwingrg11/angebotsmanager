<?php

/**
 * Client.php
 *
 * Handles client database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'clients';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['name','region','notes','address_id'];
  /**
   * [$new_client_validation_rules description]
   * @var array
   */
  public $new_client_validation_rules = [
      'name' => 'required',
  ];
  /**
   * [contacts description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsToMany('App\Models\Contact');
  }
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->hasMany('App\Models\Project');
  }
  /**
   * [address description]
   * @return [type] [description]
   */
  public function address(){
    return $this->belongsTo('App\Models\Address');
  }

  /**
   * [setRegionAttribute description]
   * @param [type] $value [description]
   */
  public function setRegionAttribute($value){
    if($value):
      $this->attributes['region'] = $value;
    else:
      $this->attributes['region'] = null;
    endif;
  }

  /**
   * [setNotesAttribute description]
   * @param [type] $value [description]
   */
  public function setNotesAttribute($value){
    if($value):
      $this->attributes['notes'] = $value;
    else:
      $this->attributes['notes'] = null;
    endif;
  }

  /**
   * [getAllClients description]
   * @return [type] [description]
   */
  public function getAllClients(){
    return $this->get();
  }

  /**
   * [getClientById description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function getClientById($client_id){
    return $this->where('id', $client_id)->with(['address'])->get();
  }

  /**
   * [updateContact description]
   * @return [type] [description]
   */
  public function updateClient($client_id, $data){
    return $this->where('id', $client_id)->update($data);
  }

  /**
   * [deleteClient description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function deleteClient($client_id){
    return $this->where('id', $client_id)->delete();
  }
  /**
   * [insertNewClient description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewClient($data){
    return $this->create($data);
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('notes', 'LIKE', '%'.$search_key.'%')
                ->orWhere('region', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

}//End Class Client
