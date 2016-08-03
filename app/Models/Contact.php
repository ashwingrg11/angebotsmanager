<?php

/**
 * Contact.php
 *
 * Handles contact database management and related operations.
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
use DB;

class Contact extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'contacts';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['address_id','language_id', 'title', 'greeting', 'first_name', 'last_name', 'company', 'department', 'job_title', 'email', 'phone', 'mobile_phone', 'notes'];
  /**
   * [$new_contact_validation_rules description]
   * @var array
   */
  public $new_contact_validation_rules = [
    'language' => 'required',
    'title' => 'required',
    'greeting' => 'required',
    // 'first_name' => 'required',
    'last_name' => 'required',
    'company' => 'required',
    'email' => 'required|email|unique:contacts,email'
  ];
  /**
   * [$update_contact_validation_rules description]
   * @var [type]
   */
  public $update_contact_validation_rules = [
    'language' => 'required',
    'title' => 'required',
    'greeting' => 'required',
    // 'first_name' => 'required',
    'last_name' => 'required',
    'company' => 'required'
  ];
  /**
   * [clients description]
   * @return [type] [description]
   */
  public function clients(){
    return $this->belongsToMany('App\Models\Client');
  }
  /**
   * [partners description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->belongsToMany('App\Models\Partner');
  }
  /**
   * [languages description]
   * @return [type] [description]
   */
  public function languages(){
    return $this->belongsTo('App\Models\Language', 'language_id');
  }
  /**
   * [offers description]
   * @return [type] [description]
   */
  public function offers(){
    return $this->hasMany('App\Models\Offer', 'contact_id');
  }
  /**
   * [address description]
   * @return [type] [description]
   */
  public function address(){
    return $this->belongsTo('App\Models\Address');
  }

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsToMany('App\Models\Project', 'contact_project', 'contact_id', 'project_id');
  }

  /**
   * [setFirstNameAttribute description]
   * @param [type] $value [description]
   */
  public function setFirstNameAttribute($value){
    if($value):
      $this->attributes['first_name'] = $value;
    else:
      $this->attributes['first_name'] = null;
    endif;
  }

  /**
   * [getAllContacts description]
   * @return [type] [description]
   */
  public function getAllContacts(){
    return $this->get();
    // return $this->with(['clients', 'partners'])->get();
  }

  /**
   * [insertNewContact description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function insertNewContact($data){
    $data = $this->handleCommonConstraint($data);
    return $this->create($data);
  }

  /**
   * [deleteContact description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function deleteContact($contact_id){
    return $this->where('id', $contact_id)->delete();
  }

  /**
   * [getContactById description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function getContactById($contact_id){
    return $this->where('id', $contact_id)->with(['address'])->get();
  }

  /**
   * [updateContact description]
   * @return [type] [description]
   */
  public function updateContact($contact_id, $data){
    $data = $this->handleCommonConstraint($data);
    return $this->where('id', $contact_id)->update($data);
  }

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('first_name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('last_name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('email', 'LIKE', '%'.$search_key.'%')
                ->orWhere('company', 'LIKE', '%'.$search_key.'%')
                ->orWhere('job_title', 'LIKE', '%'.$search_key.'%')
                ->orWhere('department', 'LIKE', '%'.$search_key.'%')
                ->orWhere('company', 'LIKE', '%'.$search_key.'%')
                ->orWhere('phone', 'LIKE', '%'.$search_key.'%')
                ->orWhere('mobile_phone', 'LIKE', '%'.$search_key.'%')
                ->orWhere('notes', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

  /**
   * [getEditValidationRules description]
   * @param  [type] $contact_id [description]
   * @return [type]             [description]
   */
  public function getEditValidationRules($contact_id){
    $this->update_contact_validation_rules['email'] = 'required|email|unique:contacts,email,'.$contact_id;
    return $this->update_contact_validation_rules;
  }

  /**
   * [handleCommonConstraint description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function handleCommonConstraint($data){
    $constraints = ['first_name', 'department', 'job_title', 'phone', 'mobile_phone', 'notes'];
    foreach ($constraints as $constraint) {
      if(isset($data[$constraint]) && !$data[$constraint]){
        $data[$constraint] = null;
      }
    }
    return $data;
  }

  /**
   * [filterContactsByOffers description]
   * @param  [type] $offers [description]
   * @return [type]         [description]
   */
  public function filterContactsByOffers($contacts, $offers){
    $offer_contacts = [];
    foreach ($offers as $offer) {
      $offer_contacts[] = $offer->contacts->id;
    }
    array_unique($offer_contacts);
    // $contacts = $this->getAllContacts();
    if($contacts->isEmpty()){
      return new Collection;
    }
    $new_contacts = $contacts->filter(function($item) use($offer_contacts){
                      if(!in_array($item->id, $offer_contacts)){
                        return true;
                      }
                    });
    return $new_contacts;
  }

  /**
   * [filterContactsByOffers description]
   * @param  [type] $offers [description]
   * @return [type]         [description]
   */
  public function filterContactsByProjects($project_ids){
    $contacts = $this->getAllContacts();
    $new_contacts = $contacts->filter(function($item) use($project_ids){
                      $flag = false;
                      if (!$item->projects->isEmpty() && $item->offers->isEmpty()) {
                        $contact_project_ids = [];
                        foreach($item->projects as $project){
                          if(in_array($project->id, $project_ids)){
                            if(!$flag){
                              // $flag = true;
                              return true;
                            }
                          }
                        }
                      }
                      // return $flag;
                    });
    return $new_contacts;
  }

  /**
   * [getNonAssignedContacts description]
   * @return [type] [description]
   */
  public function getNonAssignedContacts(){
    $contacts = $this->getAllContacts();
    $contacts = $contacts->filter(function($item){
                      if($item->partners->isEmpty()){
                        return true;
                      }
                    });
    return $contacts;
  }

}//End Class Contact
