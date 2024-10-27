<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SKURequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sku_code' => 'required|string|unique:skus,sku_code,' . $this->sku,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'attributes' => 'required|array',
            'attributes.size' => 'nullable|string',
            'attributes.color' => 'nullable|string',
            'attributes.flavor' => 'nullable|string',
        ];
    }
}
