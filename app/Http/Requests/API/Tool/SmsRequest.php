<?php

namespace App\Http\Requests\API\Tool;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'targets' => ['required', 'regex:/^(1[3-9]\d{9})(,1[3-9]\d{9})*$/'],
            'content' => ['required', 'string'],
        ];
    }
}
