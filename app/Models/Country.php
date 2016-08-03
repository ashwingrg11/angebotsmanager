<?php

/**
 * Country.php
 *
 * Handles country database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  /**
   * Database table name for this model.
   * @var string
   */
  protected $table = 'countries';

  /**
   * Mass assignable properties of this model.
   * @var array
   */
  protected $fillable = ['label'];

  /**
   * Validation rules for create/update country.
   * @var array
   */
  public $new_country_validation_rules = ['label' => 'required|unique:countries,label'];

  /**
   * Validation attributes for country's create/update validation.
   * @var array
   */
  public $validation_attributes = ['label' => 'country name'];

  /**
   * Eloquent, many-to-many relationship with project model.
   * @return object App\Models\Project
   */
  public function projects(){
    return $this->belongsToMany('App\Models\Project');
  }

  /**
   * Eloquent, one-to-many relationship with offer model.
   * @return object App\Models\Offer
   */
  public function offers(){
    return $this->hasMany('App\Models\Offer', 'country_id');
  }

  /**
   * Eloquent, one-to-many relationship with offer model.
   * @return object App\Models\Offer
   */
  public function address(){
    return $this->hasMany('App\Models\Address', 'country_id');
  }

  /**
   * Return all available countries.
   * @return collection object
   */
  public function allCountries(){
    return $this->get();
  }

  /**
   * Insert new country into database table.
   * @param  array $data
   * @return object
   */
  public function insertCountry($data){
    return $this->insert($data);
  }

  /**
   * Return country details as per provided country id.
   * @param  int $id
   * @return collection object
   */
  public function getCountry($id){
    return $this->where('id', $id)->get();
  }

  /**
   * Update country data as per provided country id and it's details.
   * @param  int $id [country id]
   * @param  array $data
   * @return bool
   */
  public function updateCountry($id, $data){
    return $this->where('id', $id)->update($data);
  }

  /**
   * Delete country from database.
   * @param  int $id [country id]
   * @return object
   */
  public function deleteCountry($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * Return validation rules for creating/updating country record.
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function getValidationRules($request){
    //First we need to check whether the request is for update or create,
    //set unique value rule for label, if the request is from update
    //return validation rules
    if($request->has('country_id')):
      $this->new_country_validation_rules['label'] = 'required|unique:countries,label,'.$request->country_id;
      return $this->new_country_validation_rules;
    else:
      return $this->new_country_validation_rules;
    endif;
  }//End getValidationRules function

  /**
   * [search description]
   * @param  [type] $search_key [description]
   * @return [type]             [description]
   */
  public function search($search_key){
    return $this->where('label', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

}//End Class, Country
