<?php

namespace App\Http\Requests;

class StoreBooking extends JsonRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // TODO: Check for Auth::user and that offer does not belong to authenticated user
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
      'offer_id'       => 'required|integer',
      'pax'            => 'nullable|integer'
    ];
  }
}
