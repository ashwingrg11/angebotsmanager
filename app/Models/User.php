<?php

/**
 * User.php
 *
 * Handles users database management and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
  use Authenticatable, Authorizable, CanResetPassword;

  /**
   * The database table used by the model.
   * @var string $table
   */
  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   * @var [array] $fillable
   */
  protected $fillable = ['first_name', 'last_name', 'username', 'telephone', 'user_type', 'email', 'password'];

  /**
   * The attributes excluded from the model's JSON form
   * @var [array] $hidden
   */
  protected $hidden = ['password', 'remember_token'];

  /**
   * [offers description]
   * @return [type] [description]
   */
  public function user_offers(){
    return $this->belongsToMany('App\Models\Offer', 'offer_user');
  }

  /**
   * [offer description]
   * @return [type] [description]
   */
  public function ji_contact_offers(){
    return $this->hasMany('App\Models\Offer');
  }

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function projects(){
    return $this->belongsToMany('App\Models\Project', 'project_user');
  }

  /**
   * Validation rules for creating new user.
   * @var [array] $new_user_validation_rules
   */
  public $new_user_validation_rules = [
    'first_name' => 'required',
    'last_name' => 'required',
    'user_type' => 'required',
    'username' => 'required|min:6',
    'password' => 'required|confirmed|min:6',
    'password_confirmation' => 'required',
  ];

  /**
   * [$password_reset_validation_rules description]
   * @var array
   */
  public $password_reset_validation_rules = [
    'current_password' => 'required|hashmatch:password',
    'new_password' => 'required|confirmed|min:6|different:current_password',
    'new_password_confirmation' => 'required'
  ];

  /**
   * [setFirstNameAttribute description]
   * @param [type] $value [description]
   */
  public function setPasswordAttribute($value){
    $this->attributes['password'] = bcrypt($value);
  }

  /**
   * Return all the currently available users.
   * @return [collection object]
   */
  public function allUsers(){
    return $this->get();
  }

  /**
   * Insert new user data in database table.
   * @param  [array] $data [new user data]
   * @return [object] User
   */
  public function insertUser($data){
    $data = $this->handleCommonConstraints($data);
    return $this->create($data);
  }

  /**
   * [return user details by user id]
   * @param  [int] $user_id
   * @return [object] User
   */
  public function getUserById($user_id){
    return $this->where('id', $user_id)->get();
  }

  /**
   * [handleCommonConstraint description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function handleCommonConstraints($data){
    $constraints = ['telephone'];
    foreach ($constraints as $constraint) {
      if(isset($data[$constraint]) && !$data[$constraint]){
        $data[$constraint] = null;
      }
    }
    return $data;
  }//End handleCommonConstraints function

  /**
   * [deleteUser description]
   * @param  [type] $user_id [description]
   * @return [type]          [description]
   */
  public function deleteUser($user_id){
    return $this->where('id', $user_id)->delete();
  }


  /**
   * Return validation rules for register/update user details.
   * @param  \Illuminate\Http\Request $request
   * @return [array] $this->new_user_validation_rules
   */
  public function getValidationRules($request){
    if($request->input('id')):
      if($request->input('password') || $request->input('password_confirmation')):
      else:
        unset($this->new_user_validation_rules['password'], $this->new_user_validation_rules['password_confirmation']);
      endif;
      $this->new_user_validation_rules['email'] = 'required|email|unique:users,email,'.$request->input('id');
    else:
      $this->new_user_validation_rules['email'] = 'required|email|unique:users,email';
    endif;
    return $this->new_user_validation_rules;
  }

  /**
   * [getProfileValidationRules description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function getProfileValidationRules($request){
    if($request->input('id')):
      unset($this->new_user_validation_rules['password'], $this->new_user_validation_rules['password_confirmation']);
      $this->new_user_validation_rules['email'] = 'required|email|unique:users,email,'.$request->input('id');
    endif;
    if(\Auth::user()->user_type != "admin"):
      unset($this->new_user_validation_rules['user_type']);
    endif;
    return $this->new_user_validation_rules;
  }

  /**
   * Execute update query to update user details in database.
   * @param  [int] $user_id
   * @param  [array] $data
   * @return [bool]
   */
  public function updateUser($user_id, $data){
    $data = $this->handleCommonConstraints($data);
    if(isset($data['password'])):
      $data['password'] = bcrypt($data['password']);
    endif;
    return $this->where('id', $user_id)->update($data);
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
                ->orWhere('telephone', 'LIKE', '%'.$search_key.'%')
                ->orWhere('username', 'LIKE', '%'.$search_key.'%')
                ->get();
  }

}//End Class User
