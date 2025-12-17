<?php

namespace App\Http\Requests\LedgerRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
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
            'building_id' => 'required|exists:buildings,id',

            'fund_type' => 'required|string|in:operating_fund,maintenance_fund,reserve_fund,other_fund',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,bank_transfer',
            'description' => 'nullable|string|max:1000',
            'transaction_date' => 'required|date',

            // Bank info (required when payment_method = bank_transfer)
            'bank_transaction_id' => 'nullable|string|max:100',
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:100',
            'bank_account' => 'required_if:payment_method,bank_transfer|string|max:50',

            // Person info
            'payer_name' => 'nullable|string|max:200',
            'receiver_name' => 'nullable|string|max:200',
            'contact_info' => 'nullable|string|max:255',

            // Attachments
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:51200',
        ];
    }

    public function messages()
    {
        return [
            'building_id.required' => 'BUILDING_ID_REQUIRED',
            'building_id.exists' => 'BUILDING_NON_EXISTED',
            'fund_type.required' => 'FUND_TYPE_REQUIRED',
            'fund_type.in' => 'FUND_TYPE_INVALID',
            'amount.required' => 'AMOUNT_REQUIRED',
            'amount.numeric' => 'AMOUNT_NOT_NUMERIC',
            'amount.min' => 'AMOUNT_MIN',
            'payment_method.required' => 'PAYMENT_METHOD_REQUIRED',
            'payment_method.in' => 'PAYMENT_METHOD_INVALID',
            'transaction_date.required' => 'TRANSACTION_DATE_REQUIRED',
            'transaction_date.date' => 'DATE_INVALID',
            'bank_name.required_if' => 'PAYMENT_METHOD_REQUIRED',
            'bank_account.required_if' => 'PAYMENT_METHOD_REQUIRED',
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
