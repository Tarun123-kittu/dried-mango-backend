<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateSupplierRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|not_in:admin',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_joining' => 'required|date_format:Y-m-d',
            'status' => 'required|in:0,1',
            'gender' => 'required|in:male,female',
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
