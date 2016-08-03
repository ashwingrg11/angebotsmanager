<?php

/**
 * Project_User.php
 *
 * Handles project-users database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project_User extends Model
{
  /**
   * Table name for this model.
   * @var string $table
   */
  protected $table = 'project_user';
  /**
   * Mass assignable attributes for this model.
   * @var array $fillable
   */
  protected $fillable = ['project_id', 'user_id'];

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsTo('App\Models\Project', 'project_id');
  }

  /**
   * Insert new record into database.
   * @param  int $project_id
   * @param  array $user_ids
   * @return object Project_User
   */
  public function insertByProject($project_id, $user_ids){
    $flag = null;
    foreach ($user_ids as $user_id) {
      $flag = $this->create(['project_id'=>$project_id, 'user_id'=>$user_id]);
    }
    return $flag;
  }

  /**
   * Return Project_User records by user id.
   * @param  int $user_id
   * @return collection object
   */
  public function getByUserId($user_id){
    return $this->where('user_id', $user_id)->get();
  }

  /**
   * [deleteByProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public function deleteByProject($project_id){
    return $this->where('project_id', $project_id)
                ->delete();
  }

}//End Class Project_User
