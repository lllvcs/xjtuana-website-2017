<?php

namespace App\Http\Requests\API\ShopList;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('sell_delete', \App\Models\ShopList::find($this->id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['nullable', 'exists:shop_list,id'],
        ];
    }
}
