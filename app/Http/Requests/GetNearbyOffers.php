<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNearbyOffers extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // JWT middleware handles authentication
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
      'lat'   => 'required|numeric|between:-90,90',
      'lng'   => 'required|numeric|between:-180,180',
      'range' => 'required|numeric',
    ];
  }
}
