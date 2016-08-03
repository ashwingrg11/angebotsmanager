<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\PostLoginRequest;
use Auth;


class AuthController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Registration & Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users, as well as the
  | authentication of existing users. By default, this controller uses
  | a simple trait to add these behaviors. Why don't you explore it?
  |
  */
  use AuthenticatesAndRegistersUsers, ThrottlesLogins;

  /**
   * Redirect location /login, if user is not successfully authenticated.
   * @var string $loginPath
   */
  protected $loginPath = '/login';

  /**
   * Redirect location /dashboard, if user is successfully authenticated.
   * @var string $redirectPath
   */
  protected $redirectPath = '/dashboard';

  /**
   * Redirect location after use is logged out.
   * @var string $redirectAfterLogout
   */
  protected $redirectAfterLogout = '/login';

  /**
   * Define maximum number of login attempts.
   * @var integer $maxLoginAttempts
   */
  protected $maxLoginAttempts = 10;

  /**
   * Create a new authentication controller instance.
   * @return void
   */
  public function __construct(){
    $this->middleware('guest', ['except' => 'getLogout']);
  }

  /**
   * Get a validator for an incoming registration request.
   * @param array $data [user inputs]
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data){
    return Validator::make($data, [
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|confirmed|min:6',
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   * @param array $data
   * @return [object] User
   */
  protected function create(array $data){
    return User::create([
      'username' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
    ]);
  }

  /**
   * Validate incoming post login request and authenticate user.
   * @param  PostLoginRequest $request [validate user inputs]
   * @return \Illuminate\Http\RedirectResponse
   */
  public function postLogin(PostLoginRequest $request){
    $remember = ($request->has('remember-me')) ? true : false;
    $data = $this->constructData($request);
    if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $remember)) {
      return redirect('dashboard');
      // return redirect()->intended('dashboard');
    }
    return redirect('login')->with('loginError', 'Invalid Credentials.')->withInput();
  }
}
