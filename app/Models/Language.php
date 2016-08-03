<?php

/**
 * Language.php
 *
 * Handles language database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  /**
   * Database table name for this model.
   * @var string
   */
  protected $table = 'languages';

  /**
   * Mass assiganable properties of this model.
   * @var array
   */
  protected $fillable = ['label'];

  /**
   * Validation rules for create/update language.
   * @var array
   */
  public $validation_rules = ['label' => 'required|unique:languages,label'];

  /**
   * Validation attributes for create/update language validator.
   * @var array
   */
  public $validation_attributes = ['label' => 'language name'];

  /**
   * Eloquent, one to many relationship with contact model.
   * @return object App\Models\Contact
   */
  public function contacts(){
    return $this->hasMany('App\Models\Contact');
  }

  /**
   * Eloquent many to many relationship with project model.
   * @return object App\Models\Project
   */
  public function projects(){
    // return $this->belongsToMany('App\Models\Project', 'language_project', 'language_id', 'project_id');
    return $this->hasMany('App\Models\Project');
  }

  /**
   * Return all available languages.
   * @return collection object
   */
  public function allLanguages(){
    return $this->get();
  }

  /**
   * Return language info as per provided language id.
   * @param  int $id [language id]
   * @return collection object
   */
  public function getLanguage($id){
    return $this->where('id', $id)->get();
  }

  /**
   * Insert new language record into database and returns the inserted language.
   * @param  array $data
   * @return object [inserted language data]
   */
  public function insertLanguage($data){
    return $this->insert($data);
  }

  /**
   * Update language info as per provided language id and data respectively.
   * @param  int $id [language id]
   * @param  array $data
   * @return bool
   */
  public function updateLanguage($id, $data){
    return $this->where('id', $id)->update($data);
  }

  /**
   * Delete language record by provided language id.
   * @param  int $id [language id]
   * @return object
   */
  public function deleteLanguage($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * Return validation rules for creating/updating language.
   * @param  \Illuminate\Http\Request $request
   * @return array $this->validation_rules
   */
  public function getValidationRules($request){
    //First we need to check whether the request is for update or create,
    //set unique value rule for label, if the request is from update
    //return validation rules
    if($request->has('language_id')):
      $this->validation_rules['label'] = 'required|unique:languages,label,'.$request->language_id;
      return $this->validation_rules;
    else:
      return $this->validation_rules;
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

}//End Class, Language
