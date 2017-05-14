<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNearby extends FormRequest
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
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'range' => 'nullable|numeric|between:1,12'
        ];
    }
}
