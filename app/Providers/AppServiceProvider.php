<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Auth;
use Hash;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
    Validator::extend('hashmatch', function($attribute, $value, $parameters){
      return Hash::check($value, Auth::user()->$parameters[0]);
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
