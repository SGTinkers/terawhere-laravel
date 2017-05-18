<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReview extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'offer_id'   => 'required_without_all:booking_id|integer',
      'booking_id' => 'required_without_all:offer_id|integer',
      'body'       => 'required',
      'rating'     => 'required|integer|between:1,5',
    ];
  }
}
