<?php

/**
 * CategoryController.php
 *
 * Handles project category module's functionalities and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Category as Category;
use Validator;

class CategoryController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Project Category Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the crud functionalities related to project
  | category module. It includes relevant category model for it's
  | related operations.
  |
  */

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->category_model = new Category;
  }

  /**
   * Return all available categories from database with categories listing page.
   * @return \Illuminate\Http\Response
   */
  public function getIndex()
  {
    try {
      $categories = $this->category_model->allCategories();
      return view('categories.list_view', compact('categories'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End getIndex function

  /**
   * Validate incoming category post create request and insert new project category.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postCreate(Request $request){
    //First we need to validate incoming post create request and return errors
    //if requested data are invalid.
    $validator = Validator::make($request->all(), $this->category_model->getValidationRules($request));
    $validator->setAttributeNames($this->category_model->validation_attributes);
    if ($validator->fails()) {
      return redirect('category/index')
                    ->withErrors($validator)
                    ->withInput();
    }//End validator, if
    $data = $this->constructData($request);
    try {
      //Save valid data into database and return project category index page
      //with added success notice.
      $this->category_model->insertCategory($data);
      return redirect('category/index')->with('added_notice', 'New project category has been added successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postCreate function

  /**
   * Get project category details as per requested id and return edit page to edit the same info.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function postEdit(Request $request){
    //First we need to get category id from incoming request, get category details
    //as per received id, get all project categories and return a view
    //to edit project category details.
    $category_id = $request->input('post_id');
    $category_details = $this->category_model->getCategory($category_id);
    $categories = $this->category_model->allCategories();
    return view('categories.list_view', compact('category_details', 'categories'));
  }//End postEdit function

  /**
   * Validate incoming project category post update request and update category info.
   * @param  \Illuminate\Http\Request $request
   * @return mixed
   */
  public function postUpdate(Request $request){
    //First we need to validate incoming request and return errors
    //if received data are invalid.
    $validator = Validator::make($request->all(), $this->category_model->getValidationRules($request));
    $validator->setAttributeNames($this->category_model->validation_attributes);
    if ($validator->fails()) {
      $category_details = $this->category_model->getCategory($request->category_id);
      $categories = $this->category_model->allCategories();
      return view('categories.list_view', compact('category_details', 'categories'))->withErrors($validator);
    }//End validator, if
    $data = $this->constructData($request);
    $category_id = $data['category_id'];
    unset($data['category_id']);
    try {
      //Update project category data, throw exception if category info is not updated
      //and return to project categories listing page with success notice,
      //if project category info is updated successfully.
      $update_flag = $this->category_model->updateCategory($category_id, $data);
      if(!$update_flag):
        throw new Exception('Could not update requested project category this time. Please try again later.');
      endif;
      return redirect('category/index')->with('updated_notice', 'Chosen project category has been updated successfully.');
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage())]);
    }//End try-catch
  }//End postUpdate function

  /**
   * Get category's delete id from incoming request and delete the same project category.
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function deleteDelete(Request $request){
    $delete_id = $request->input('delete_id');
    try {
      //Delete project category from database, throw exception if not deleted and
      //return project category's listing page with success notice if
      //project category is deleted.
      $delete_flag = $this->category_model->deleteCategory($delete_id);
      if($delete_flag == false):
        throw new Exception('Could not delete requested project category. Please try again.');
      endif;
      $deleted_notice = "Selected project category has been deleted successfully.";
      $categories = $this->category_model->allCategories();
      return view('categories.list_view', compact('categories', 'deleted_notice'));
    } catch (\Exception $e) {
      return response()->json(['exc' => utf8_encode($e->getMessage()), 'custom_message' => 'Could not delete this category because it is being used by other modules.']);
    }//End try-catch
  }//End delete function

}//End Class, CategoryController
