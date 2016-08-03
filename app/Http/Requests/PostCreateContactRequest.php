<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostCreateContactRequest extends Request
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // return false;
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $rules = [
      'greeting' => 'required'
      // 'offer_type_id' => 'required|numeric',
      // 'image' => 'required|image|max:1024|mimes:jpeg,png,jpg',
      // 'start_date' => 'required|date|after:yesterday',
      // 'end_date' => 'required|date|after:start_date',
      // 'qty' => 'required|numeric|regex:/^[0-9]+$/',
      // 'customer_criteria' => 'required',
      // 'earning_type' => 'required',
      // 'offer_claiming_type' => 'required',
      // 'activity_type_id' => 'required',
    ];
    return $rules;
  }
}

