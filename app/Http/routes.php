<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// use Artisan;

//Default route
Route::get('/', function () {
  // \Artisan::call('vendor:publish');
  return redirect('/login');
});

//Authentication routes
Route::get('login', 'Auth\AuthController@getLogin'); //get login
Route::post('login', 'Auth\AuthController@postLogin'); //post login
Route::get('logout', 'Auth\AuthController@getLogout'); //get logout
Route::controllers([
  'password' => 'Auth\PasswordController',
]);

//grouped routes -middleware=>auth
Route::group(['middleware' => 'auth'], function () {
  //UserController routes
  Route::controller('user', 'User\UserController');

  //DashboardController routes
  Route::controller('dashboard', 'Dashboard\DashboardController');

  //ContactController routes
  Route::controller('contact', 'Contact\ContactController');

  //ClientController routes
  Route::controller('client', 'Client\ClientController');

  //PartnerController routes
  Route::controller('partner', 'Partner\PartnerController');

  //ProjectController routes
  Route::controller('project', 'Project\ProjectController');

  //ChannelController routes
  Route::controller('channel', 'Channel\ChannelController');

  //OfferTypeController routes
  Route::controller('offer_type', 'OfferType\OfferTypeController');

  //GeneralOfferTypeController routes
  Route::controller('general_offer_type', 'OfferType\GeneralOfferTypeController');

  //DetailedOfferTypeController routes
  Route::controller('detailed_offer_type', 'OfferType\DetailedOfferTypeController');

  //OfferTypeCategoryController routes
  Route::controller('offer_type_category', 'OfferType\OfferTypeCategoryController');

  //PlacementController routes
  Route::controller('placement', 'Placement\PlacementController');

  //ReportController routes
  Route::controller('report', 'Report\ReportController');

  //EmailTemplateController routes
  Route::controller('email-template', 'Email\EmailTemplateController');

  //EmailController routes
  Route::controller('email', 'Email\EmailController');

  //CommunicationPackageController routes
  Route::controller('communication-package', 'CommunicationPackage\CommunicationPackageController');

  //CountryController routes
  Route::controller('country', 'Country\CountryController');

  //LanguageController routes
  Route::controller('language', 'Language\LanguageController');

  //ProjectCategory routes
  Route::controller('category', 'Category\CategoryController');
});//End grouped routes

//OfferController routes
Route::controller('offer', 'Offer\OfferController');

//MicrositeController routes
Route::controller('microsite', 'Microsite\MicrositeController');

//
Route::filter('auth', function()
{
  if (Auth::guest()) {
    if (Request::ajax()) {
      return Response::json(false, 401);
    } else {
      return Redirect::guest('login');
    }
  }
});