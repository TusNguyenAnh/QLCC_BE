<?php

namespace App\Http\Requests\AdjustmentRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdjustmentRequest extends FormRequest
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
            'amount' => 'required|numeric',  // Chỉ là ước tính
            'reason' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'AMOUNT_REQUIRED',
            'amount.numeric' => 'AMOUNT_NOT_NUMERIC',
        ];
    }

    /**
     * @throws AppException
     */
    protected function failedValidation(Validator $validator)
    {
        $errorName = $validator->errors()->first();
        throw new AppException(ErrorCode::getCaseName($errorName));
    }
}
