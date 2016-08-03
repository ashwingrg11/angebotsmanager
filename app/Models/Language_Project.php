<?php

/**
 * Language_Project.php
 *
 * Handles languages-projects database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language_Project extends Model
{
  /**
   * Table name for this model.
   * @var string $table
   */
  protected $table = 'language_project';

  /**
   * Mass assignable attributes for this model.
   * @var array $fillable
   */
  protected $fillable = ['language_id', 'project_id'];

  /**
   * Insert new record language-project into database.
   * @param  int $project_id
   * @param  array $languages
   * @return object Language_Project
   */
  public function insertByProject($project_id, $languages){
    $flag = null;
    foreach ($languages as $language) {
      $flag = $this->create(['project_id'=>$project_id, 'language_id'=>$language]);
    }
    return $flag;
  }//End insertByProject function

  /**
   * Delete project-languages records as per provided project id.
   * @param  int $project_id
   * @return object Language_Project
   */
  public function deleteByProject($project_id){
    return $this->where('project_id', $project_id)->delete();
  }

}//End Class Language_Project
