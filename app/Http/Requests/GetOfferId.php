<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOfferId extends JsonRequest
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
      'offer_id' => 'required|integer',
    ];
  }

  /**
   * Validate even url parameters
   *
   * @return array
   */
  public function all()
  {
    return array_replace_recursive(
      parent::all(),
      $this->route()->parameters()
    );
  }
}
