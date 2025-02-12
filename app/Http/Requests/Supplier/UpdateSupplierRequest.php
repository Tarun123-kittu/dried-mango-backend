<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSupplierRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255|not_in:admin',
            'company_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:suppliers,email,' . $this->route('id'),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_joining' => 'nullable|date_format:Y-m-d',
            'status' => 'sometimes|in:0,1',
            'gender' => 'nullable|in:male,female',
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

}
