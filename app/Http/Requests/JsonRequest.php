<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class JsonRequest extends FormRequest
{
  /**
   * The data to be validated should be processed as JSON.
   * @return mixed
   */
  protected function validationData()
  {
    if ($this->isJson()) {
      return $this->json()->all();
    } else {
      return parent::validationData();
    }
  }
}
