<?php

namespace App\Http\Requests\API\FaqCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', \App\Models\FaqCategory::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 名称：字符串 varchar(20)
            'name' => ['nullable', 'string', 'max:20'],
        ];
    }
}
