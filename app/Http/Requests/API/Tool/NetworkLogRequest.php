<?php

namespace App\Http\Requests\API\Tool;

use Illuminate\Foundation\Http\FormRequest;

class NetworkLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isMember();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string'],
            'type' => ['required', 'string']
        ];
    }
}
