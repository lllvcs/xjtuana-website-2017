<?php

namespace App\Http\Requests\API\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNetidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('updateNetid', \App\Models\Member::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // NETID：字符串
            'netid' => ['required', 'string'],
        ];
    }
}
