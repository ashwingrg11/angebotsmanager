<?php

/**
 * Category.php
 *
 * Handles project category database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  /**
   * Database table name for this model.
   * @var string
   */
  protected $table = 'categories';

  /**
   * Mass assignable properties of this model.
   * @var array
   */
  protected $fillable = ['label'];

  /**
   * Validation rules for create/update category.
   * @var array
   */
  public $validation_rules = ['label' => 'required'];

  /**
   * Validation attributes for category's create/update validation.
   * @var array
   */
  public $validation_attributes = ['label' => 'category name'];

  /**
   * Many to many relationship with project table having the bridge
   * entity of category_project table.
   * @param none
   * @return object/instance Project
   */
  public function projects(){
    return $this->belongsToMany('App\Models\Project', 'category_project', 'category_id', 'project_id');
  }

  /**
   * Return all available project categories.
   * @return collection object
   */
  public function allCategories(){
    return $this->get();
  }

  /**
   * Insert new project category into database table.
   * @param  array $data
   * @return object inserted category data
   */
  public function insertCategory($data){
    return $this->insert($data);
  }

  /**
   * Return category details as per provided category id.
   * @param  int $id
   * @return collection object
   */
  public function getCategory($id){
    return $this->where('id', $id)->get();
  }

  /**
   * Update project category data as per provided id and it's details.
   * @param  int $id [category id]
   * @param  array $data
   * @return bool
   */
  public function updateCategory($id, $data){
    return $this->where('id', $id)->update($data);
  }

  /**
   * Delete project category from database.
   * @param  int $id [category id]
   * @return object
   */
  public function deleteCategory($id){
    return $this->where('id', $id)->delete();
  }

  /**
   * Return validation rules for creating/updating project category record.
   * @param  \Illuminate\Http\Request $request
   * @return array $this->validation_rules
   */
  public function getValidationRules($request){
    //First we need to check whether the request is for update or create,
    //set unique value rule for label, if the request is from update
    //return validation rules
    if($request->has('category_id')):
      $this->validation_rules['label'] = 'required|unique:categories,label,'.$request->category_id;
      return $this->validation_rules;
    else:
      return $this->validation_rules;
    endif;
  }//End getValidationRules function

  /**
   * Get search key/label and return the similar records.
   * @param String $search_key
   * @return Eloquent Collection
   */
  public function search($search_key){
    return $this->where('label', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

}//End Class, Category
