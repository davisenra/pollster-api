<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePollRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3',
            'options' => 'required|array|min:2',
            'options.*' => 'required|distinct|min:2|max:255',
            'email' => 'nullable|email',
            'expires_at' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'expires_at' => 'Must follow [Y-m-d H:m:s] datetime format'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     */
    protected function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400));
    }
}
