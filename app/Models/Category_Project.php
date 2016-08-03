<?php

/**
 * Category_Project.php
 *
 * Handles categories-projects database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_Project extends Model
{
  /**
   * Table name for this model.
   * @var string $table
   */
  protected $table = 'category_project';

  /**
   * Mass assignable attributes for this model.
   * @var array $fillable
   */
  protected $fillable = ['project_id', 'category_id'];

  /**
   * Insert new record into database.
   * @param  int $project_id
   * @param  array $categories
   * @return object Category_Project
   */
  public function insertByProject($project_id, $categories){
    $flag = null;
    foreach ($categories as $category) {
      $flag = $this->create(['project_id'=>$project_id, 'category_id'=>$category]);
    }
    return $flag;
  }//End insertByProject function

  /**
   * Delete project categories record as per provided project id.
   * @param  int $project_id
   * @return collection object
   */
  public function deleteByProject($project_id){
    return $this->where('project_id', $project_id)->delete();
  }


}//End Class Category_Project
