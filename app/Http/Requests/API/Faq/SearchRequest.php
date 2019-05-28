<?php

namespace App\Http\Requests\API\Faq;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('search', \App\Models\Faq::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 类别：在faq_categories数据表中
            'category_id' => ['nullable', 'exists:faq_categories,id'],
            // 问题：字符串 varchar(100)
            'question' => ['nullable', 'string', 'max:100'],
            // 回答：字符串
            'answer' => ['nullable', 'string'],
        ];
    }
}
