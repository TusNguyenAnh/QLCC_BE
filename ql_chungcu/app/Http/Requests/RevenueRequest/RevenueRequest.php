<?php

namespace App\Http\Requests\RevenueRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RevenueRequest extends FormRequest
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
        // LớP 1 - NGHĨA VỤ PHẢI THU (METADATA)
        // Thông tin thanh toán thực tế nằm trong ledgers (LớP 2)
        return [
            'apartment_id' => 'required|string|exists:apartments,id',
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'original_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:unpaid,partial,paid,overpaid',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'apartment_id.required' => 'APARTMENT_ID_REQUIRED',
            'apartment_id.exists' => 'APARTMENT_ID_NOT_EXISTS',
            'year.required' => 'YEAR_NOT_EMPTY',
            'year.integer' => 'YEAR_NOT_INTEGER',
            'year.min' => 'YEAR_MIN_MAX',
            'year.max' => 'YEAR_MIN_MAX',
            'month.required' => 'MONTH_NOT_EMPTY',
            'month.integer' => 'MONTH_NOT_INTEGER',
            'month.min' => 'MONTH_MIN_MAX',
            'month.max' => 'MONTH_MIN_MAX',
            'original_amount.required' => 'ORIGINAL_AMOUNT_REQUIRED',
            'original_amount.numeric' => 'ORIGINAL_AMOUNT_NOT_NUMERIC',
            'status.in' => 'STATUS_INVALID',
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
