<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['max:30', 'unique:products'],
            'name' => ['max:100'],
            'description' => ['nullable', 'max:255'],
            'price' => ['numeric', 'min:0'],
            'stock' => ['integer', 'min:0'],
            'weight' => ['integer', 'min:0'],
            'supplier_id' => ['exists:suppliers,id'],
            'category_id' => ['exists:categories,id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
