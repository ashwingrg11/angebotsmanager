<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact_Partner extends Model
{
  /**
   * [$table description]
   * @var string
   */
  protected $table = 'contact_partner';
  /**
   * [$fillable description]
   * @var array
   */
  protected $fillable = ['contact_id', 'partner_id'];
  /**
   * [projects description]
   * @return [type] [description]
   */
  public function contacts(){
    return $this->belongsTo('App\Models\Contact', 'contact_id');
  }

  /**
   * [projects description]
   * @return [type] [description]
   */
  public function partners(){
    return $this->belongsTo('App\Models\Partner', 'partner_id');
  }

  /**
   * [fetchByProject description]
   * @param  [type] $partner_id [description]
   * @return [type]             [description]
   */
  public function fetchByPartner($partner_id){
    return $this->where('partner_id', $partner_id)->get();
  }

  /**
   * [getAllContactsPartners description]
   * @return [type] [description]
   */
  public function getAllContactsPartners(){
    return $this->get();
  }

}
