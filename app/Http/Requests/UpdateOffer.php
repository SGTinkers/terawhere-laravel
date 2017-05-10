<?php

namespace App\Http\Requests;

class UpdateOffer extends JsonRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    // TODO: Check for Auth::user and that the authenticated user owns the offer
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
      'meetup_time'     => 'required|date_format:Y-m-d H:i',
      'start_name'      => 'required',
      'start_addr'      => 'required',
      'start_lat'       => 'required|numeric|between:-90,90',
      'start_lng'       => 'required|numeric|between:-180,180',
      'end_name'        => 'required',
      'end_addr'        => 'required',
      'end_lat'         => 'required|numeric|between:-90,90',
      'end_lng'         => 'required|numeric|between:-180,180',
      'vacancy'         => 'required|integer',
      'remarks'         => 'nullable',
      'status'          => 'nullable|integer',
      'pref_gender'     => 'nullable|in:male,female',
      'vehicle_number'  => 'required|alpha_num',
      'vehicle_desc'    => 'nullable',
      'vehicle_model'   => 'required',
    ];
  }
}
