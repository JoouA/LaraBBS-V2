<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest as BaseFromRequest;

class FormRequest extends BaseFromRequest
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
}
