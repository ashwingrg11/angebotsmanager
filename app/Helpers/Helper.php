<?php
namespace App\Helpers;
use App\Http\Controllers\MyController;
use App\Models\Offer_Report as OfferReport;
use Session;

class Helper
{

  /**
   * [quarterOfMonth description]
   * @param  [type] $days [description]
   * @return [type]       [description]
   */
  public static function quarterOfMonth($days){
    if($days <= 7){
      return "1";
    }
    elseif($days <= 15) {
      return "2";
    }
    elseif($days <= 23) {
      return "3";
    }
    else{
      return "4";
    }
  }//End quarterOfMonth function

  /**
   * [getForMonth description]
   * @param  [type] $for_month [description]
   * @return [type]            [description]
   */
  public static function getForMonth($for_month){
    $months = [
                "Jan" => "January",
                "Feb" => "February",
                "Mar" => "March",
                "Apr" => "April",
                "May" => "May",
                "Jun" => "June",
                "Jul" => "July",
                "Aug" => "August",
                "Sep" => "September",
                "Oct" => "October",
                "Nov" => "November",
                "Dec" => "December"
              ];
    foreach ($months as $key => $value) {
      if($key == $for_month){
        return $value;
      }
    }
  }

  /**
   * [getGermanMonth description]
   * @param  [type] $for_month [description]
   * @return [type]            [description]
   */
  public static function getGermanMonth($for_month){
     $months = [
                "Jan" => "Januar",
                "Feb" => "Februar",
                "Mar" => "März",
                "Apr" => "April",
                "May" => "Mai",
                "Jun" => "Juni",
                "Jul" => "Juli",
                "Aug" => "August",
                "Sep" => "September",
                "Oct" => "Oktober",
                "Nov" => "November",
                "Dec" => "Dezember"
              ];
    foreach ($months as $key => $value) {
      if($key == $for_month){
        return $value;
      }
    }
  }

  /**
   * [sort_arr_of_obj description]
   * @param  [type] $array     [description]
   * @param  [type] $sortby    [description]
   * @param  string $direction [description]
   * @return [type]            [description]
   */
  public static function sort_arr_of_obj($array, $sortby, $direction='asc') {
    $sortedArr = array();
    $tmp_Array = array();
    foreach($array as $k => $v) {
        $tmp_Array[] = strtolower($v->$sortby);
    }
    if($direction=='asc'){
        asort($tmp_Array);
    }else{
        arsort($tmp_Array);
    }
    foreach($tmp_Array as $k=>$tmp){
        $sortedArr[] = $array[$k];
    }
    return $sortedArr;
  }//End sort_arr_of_obj function

  /**
   * [verifyUserProject description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public static function verifyUserProject($project_id){
    $my_obj = new MyController;
    $projects = $my_obj->commonGetProjects();
    $project_ids = array();
    foreach ($projects as $key => $value) {
      $project_ids[] = $value->id;
    }
    if(in_array($project_id, $project_ids)){
      return true;
    }
    return false;
  }//End verifyUserProject function

  /**
   * [verifyUserClient description]
   * @param  [type] $project_id [description]
   * @return [type]             [description]
   */
  public static function verifyUserClient($client_id){
    $my_obj = new MyController;
    $projects = $my_obj->commonGetProjects();
    $client_ids = array();
    foreach ($projects as $key => $value) {
      $client_ids[] = $value->client_id;
    }
    if(in_array($client_id, $client_ids)){
      return true;
    }
    return false;
  }

  /**
   * [getUserProjectIds description]
   * @return [type] [description]
   */
  public static function getUserProjectIds(){
    $my_obj = new MyController;
    $projects = $my_obj->commonGetProjects();
    $project_ids = array();
    foreach ($projects as $key => $value) {
      $project_ids[] = $value->id;
    }
    return $project_ids;
  }

  /**
   * [verifyUserOffer description]
   * @param  [type] $offer_id [description]
   * @return [type]           [description]
   */
  public static function verifyUserOffer($offer_id){
    $my_obj = new MyController;
    $projects = $my_obj->commonGetProjects();
    $offer_ids = array();
    foreach ($projects as $key => $value) {
      if(!$value->offers->isEmpty()){
        foreach ($value->offers as $offer) {
          $offer_ids[] = $offer->id;
        }
      }
    }
    if(in_array($offer_id, $offer_ids)){
      return true;
    }
    return false;
  }

}//End Class, Helper
?>