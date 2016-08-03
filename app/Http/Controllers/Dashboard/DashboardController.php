<?php

/**
 * DashboardController.php
 *
 * Handles functionalities of dashboard page and related operations.
 *
 * @access    public
 * @package   angebotmanager
 * @author    Ashwin Gurung <ashwin.gurung@cloudyfox.com>
 * @copyright 2015-2016 Ashwin
 * @since     0.0.0
 **/

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;
use App\Models\Disabled_Offer as DisabledOffer;
use App\Events\OfferWasCreated;
use Config;
use Event;
use Artisan;
use Excel;
use Auth;
use Mail;

class DashboardController extends MyController
{
  /*
  |--------------------------------------------------------------------------
  | Dashboad Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles all the functionalities related to dashboard page/module.
  |
  */

  /**
   * [__construct description]
   */
  public function __construct()
  {
    parent::__construct();
    $this->disabled_offer_model = new DisabledOffer;
  }

  /**
   * Return dashboard's index view page.
   * @return \Illuminate\View\View dashboard.index_view
   */
  public function getIndex()
  {
    // \Event::fire(new OfferWasCreated(1, [], []));
    // dd(Config::get('session'));
    // print_r(app()->environment());
    return view('dashboard.index_view');
    // echo __DIR__;
    // Artisan::call('emails:send');
    // Artisan::call('config:cache');
  }

  /**
   * [getTestEmail description]
   * @return [type] [description]
   */
  public function getTestEmail(){
    Mail::send('emails.welcome', ['key' => 'value'], function($message)
    {
      // $message->to('thapasanjeev@outlook.com', 'John Smith')->subject('Welcome!');
      // $message->from('ashwin.gurung@cloudyfox.com', 'Ashwin Smith');
      $message->to('gloomy.gurung@gmail.com', 'Ashwin Smith')->subject('Welcome!');
      echo "string";
    });
    if(count(Mail::failures()) > 0){
      //$errors = 'Failed to send password reset email, please try again.';
      echo $message = "Email not send";
      echo "<pre>";
      print_r(Mail::failures());
    }
  }

  /**
   * [postSearch description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function postSearch(Request $request){
    $search_by = $request->input('entire_search');
    $search_tables = $this->searchTables();
    $search_results = array();
    $data['records_flag'] = false;
    $general_controllers = ['offer', 'placement', 'partner', 'channel', 'offer_type', 'address', 'email', 'email_content', 'offer_report'];
    if(\Auth::user()->user_type == "general" || \Auth::user()->user_type == "viewer"){
      $projects = $this->commonGetProjects();
    }
    foreach ($search_tables as $key => $value):
      $columns = $value['columns'];
      $model = $value['model'];
      $controller = $value['controller'];
      $table = $key;
      if(\Auth::user()->user_type == "general" && in_array($controller, $general_controllers)){
        if($controller == "offer"):
          $disabled_offers = $this->disabled_offer_model->getAllByUser(Auth::user()->id);
          $records = $model->searchByGeneralUser($search_by, $projects, $disabled_offers);
        else:
          $records = $model->searchByGeneralUser($search_by, $projects);
        endif;
      }
      elseif (\Auth::user()->user_type == "viewer") {
        $records = $model->searchByViewer($search_by, $projects);
      }
      else{
        $records = $model->search($search_by);
      }
      $records_array = (array) $records;
      if(!empty($records)):
        $data['records_flag'] = true;
        foreach ($records_array as $key => $value):
          $value = (array) $value;
          if($controller == "email_content"){
            $search_results[] = $this->getColumnsData($columns, $value, $table, $controller, 1);
            $search_results[] = $this->getColumnsData($columns, $value, $table, $controller, 2);
          }
          else{
            $search_results[] = $this->getColumnsData($columns, $value, $table, $controller, 1);
          }
        endforeach;
      endif;
    endforeach;
    $data['search_by'] = $search_by;
    $search_results = $this->verifySearchData($search_results);
    return view('dashboard.search_results_view', compact('data', 'search_results'));
  }//End postSearch function

  /**
   * [getColumnsData description]
   * @param  [type] $columns [description]
   * @param  [type] $data    [description]
   * @param  [type] $table   [description]
   * @return [type]          [description]
   */
  public function getColumnsData($columns, $data, $table, $controller, $sn){
    $result = array();
    $sn_flag = 0;
    $verify_flag = $this->verifyColumnsData($controller, $data, $sn);
    $search_controller = $controller;
    if($verify_flag){
      return $verify_flag;
    }
    foreach ($data as $key => $value):
      foreach ($columns as $column):
        if(!isset($result[$table][$sn_flag]['search_content'])):
          if($controller == "offer_report"){
            $content = $value->offers->title_en;
            if(!$content){
              $content = $value->offers->title_de;
            }
            $result[$table][$sn_flag]['mode'] = 'microsite-feedback';
            $search_controller = 'report';
          }else{
            $content = $value[$column];
          }

          if($controller == "contact"){
            $content = $value->first_name.' '.$value->last_name;
          }

          $result[$table][$sn_flag]['search_content'] = $content;
        endif;
        $result[$table][$sn_flag]['controller'] = $search_controller;
      endforeach;
      $sn_flag++;
    endforeach;
    return $result;
  }

  /**
   * [verifyColumnsData description]
   * @param  [type] $data [description]
   * @param  [type] $sn   [description]
   * @return [type]       [description]
   */
  public function verifyColumnsData($controller, $data, $sn){
    if($controller == "address"){
      return $this->getAddressData($data);
    }
    if($controller == "email"){
      return $this->my_email_model->serachEmailsData($data);
    }
    if($controller == "email_content"){
      return $this->my_email_content_model->serachEmailContentsData($data, $sn);
    }
    if($controller == "email_content" && $sn == 2){
      return $this->my_email_content_model->serachEmailContentsData($data, $sn);
    }
    return false;
  }//End verifyColumnsData function


  /**
   * [getAddressData description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function getAddressData($data){
    $result = array();
    $address_data = ['clients' => ['client', 'name'], 'contacts' => ['contact', 'first_name', 'last_name'], 'partners' => ['partner', 'partner_name']];
    foreach ($address_data as $key => $value) {
      $sn_flag = 0;
      $filter_search = [];
      foreach ($data as $data_key => $data_value) {
        $content_value = null;
        $data_content = $data_value->$key;
        if($data_content){
          if(!isset($result[$value[0]][$sn_flag]['search_content']) && !in_array($data_content->$value[1], $filter_search)):
            $result[$value[0]][$sn_flag]['search_content'] = $data_content->$value[1];
            if($key == "contacts"){
              $result[$value[0]][$sn_flag]['search_content'] = $data_content->$value[1].' '.$data_content->$value[2];
            }
            $filter_search[] = $data_content->$value[1];
          endif;
          $result[$value[0]][$sn_flag]['controller'] = $value[0];
          $sn_flag++;
        }
      }
    }
    return $result;
  }//End getAddressData function

  /**
   * Return all the table names and controllers, for search functionality
   * @return array
   */
  public function searchTables(){
    $viewer_tables = ['offer' => ['columns'=> ['title_en', 'title_de'], 'model' => $this->my_offer_model, 'controller' => 'offer']];
    $admin_tables = [
      'contact' => ['columns'=> ['first_name', 'last_name'], 'model' => $this->my_contact_model, 'controller' => 'contact'],
      'partner' => ['columns'=> ['partner_name'], 'model' => $this->my_partner_model, 'controller' => 'partner'],
      'placement' => ['columns'=> ['title'], 'model' => $this->my_placement_model, 'controller' => 'placement'],
      'offer' => ['columns'=> ['title_en', 'title_de'], 'model' => $this->my_offer_model, 'controller' => 'offer'],
      'client' => ['columns'=> ['name'], 'model' => $this->my_client_model, 'controller' => 'client'],
      'project' => ['columns'=> ['name'], 'model' => $this->my_project_model, 'controller' => 'project'],
      'user' => ['columns'=> ['first_name', 'last_name'], 'model' => $this->my_user_model, 'controller' => 'user'],
      'channel' => ['columns'=> ['name'], 'model' => $this->my_channel_model, 'controller' => 'channel'],
      'country' => ['columns'=> ['label'], 'model' => $this->my_country_model, 'controller' => 'country'],
      'language' => ['columns'=> ['label'], 'model' => $this->my_language_model, 'controller' => 'language'],
      'general offer type' => ['columns'=> ['label'], 'model' => $this->my_general_otmodel, 'controller' => 'offer_type'],
      'detailed offer type' => ['columns'=> ['label'], 'model' => $this->my_detailed_ot_model, 'controller' => 'offer_type'],
      'offer type category' => ['columns'=> ['label'], 'model' => $this->my_offertypecategory_model, 'controller' => 'offer_type'],
      'email template' => ['columns'=> ['type'], 'model' => $this->my_email_template_model, 'controller' => 'email-template'],
      'project category' => ['columns'=> ['label'], 'model' => $this->my_project_category_model, 'controller' => 'category'],
      'address' => ['columns' => ['column'],  'model' => $this->my_address_model, 'controller' => 'address'],
      'email' => ['columns' => ['column'],  'model' => $this->my_email_model, 'controller' => 'email'],
      'email_content' => ['columns' => ['column'],  'model' => $this->my_email_content_model, 'controller' => 'email_content', 'is_email_content' => 'yes'],
      'microsite feedback report' => ['columns'=> ['column'], 'model' => $this->my_offer_report_model, 'controller' => 'offer_report'],
    ];
    switch (Auth::user()->user_type) {
      case 'viewer':
        return [
          'offer' => ['columns'=> ['title_en', 'title_de'], 'model' => $this->my_offer_model, 'controller' => 'offer'],
          'placement' => ['columns'=> ['title'], 'model' => $this->my_placement_model, 'controller' => 'placement'],
          'project' => ['columns'=> ['name'], 'model' => $this->my_project_model, 'controller' => 'project']
        ];
        break;
      case 'admin':
        return $admin_tables;
        break;
      case 'general':
        $general_tables = $admin_tables;
        unset($general_tables['client'], $general_tables['project'], $general_tables['user'], $general_tables['country'], $general_tables['language'], $general_tables['project category'], $general_tables['email template']);
        return $general_tables;
        break;
      default:
        # code...
        break;
    }
  }//End searchTables function

  /**
   * [verifySearchData description]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  public function verifySearchData($data){
    $verify_data = [];
    foreach ($data as $key => $value):
      foreach ($value as $data_key => $data_value):
        foreach ($data_value as $search_key => $search_value){
          $check_content = $search_value['search_content'];
          $check_type = (isset($search_value['custom_type']) ? $search_value['custom_type'] : $search_value['controller']);
          $flag = true;
          foreach ($verify_data as $verify_value) {
            if(($verify_value['content'] == $check_content && $verify_value['type'] == $check_type) || !$check_content){
              $flag = false;
            }
          }
          if(!$flag){
            unset($data[$key][$data_key][$search_key]);
          }
          $verify_data[] = ['content' => $search_value['search_content'], 'type' => (isset($search_value['custom_type']) ? $search_value['custom_type'] : $search_value['controller'])];
        }
      endforeach;
    endforeach;
    return $data;
  }//End verifySearchData function

}//End Class, DashboardController