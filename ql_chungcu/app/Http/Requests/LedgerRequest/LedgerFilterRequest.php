<?php

namespace App\Http\Requests\LedgerRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LedgerFilterRequest extends FormRequest
{
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
            'building_id' => 'nullable|exists:buildings,id',
            'fund_type' => 'nullable|string|in:operating_fund,maintenance_fund,reserve_fund,other_fund',
            'payment_method' => 'nullable|string|in:cash,bank_transfer',
            'trans_from' => 'nullable|string',
            'trans_to' => 'nullable|string|after_or_equal:trans_from',
            'type' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'building_id.exists' => 'BUILDING_NON_EXISTED',
            'fund_type.in' => 'FUND_TYPE_INVALID',
            'payment_method.in' => 'PAYMENT_METHOD_INVALID',
            'trans_from.date' => 'DATE_INVALID',
            'trans_to.date' => 'DATE_INVALID',
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
