<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: Check with JWT token
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
          'user_id' => 'required|integer',
          'meetup_time' => 'required|date_format:Y-m-d H:i',
          'start_name' => 'required',
          'start_addr' => 'required',
          'start_lat' => 'required|numeric|between:-90,90',
          'start_lng' => 'required|numeric|between:-180,180',
          'end_name' => 'required',
          'end_addr' => 'required',
          'end_lat' => 'required|numeric|between:-90,90',
          'end_lng' => 'required|numeric|between:-180,180',
          'vacancy' => 'required|integer',
          'pref_gender' => 'integer|between:0,1'
        ];
    }
}
