<?php

namespace App\Http\Requests\API\ShopList;

use Illuminate\Foundation\Http\FormRequest;

class BackAgreeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('back_agree', \App\Models\ShopList::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [            
            // id号：在member数据表中
            'id' => ['nullable', 'exists:shop_list,id'],
    
        ];
    }
}
