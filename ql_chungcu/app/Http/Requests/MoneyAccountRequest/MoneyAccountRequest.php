<?php

namespace App\Http\Requests\MoneyAccountRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MoneyAccountRequest extends FormRequest
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
            'building_id' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'term' => 'required|integer',
            'deposit_date' => 'required|date',
            'maturity_date' => 'required|date',
            'interest_rate' => 'required|numeric',
            'money' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'building_id.required' => 'BUILDING_NOT_EMPTY',
            'bank_name.required' => 'MONEY_ACCOUNT_BANK_NAME_NOT_EMPTY',
            'account_number.required' => 'MONEY_ACCOUNT_NUMBER_NOT_EMPTY',
            'term.required' => 'MONEY_ACCOUNT_TERM_NOT_EMPTY',
            'term.integer' => 'MONEY_ACCOUNT_TERM_NOT_INTEGER',
            'deposit_date.required' => 'MONEY_ACCOUNT_DEPOSIT_DATE_NOT_EMPTY',
            'deposit_date.date' => 'MONEY_ACCOUNT_DEPOSIT_DATE_INVALID',
            'maturity_date.required' => 'MONEY_ACCOUNT_MATURITY_DATE_NOT_EMPTY',
            'maturity_date.date' => 'MONEY_ACCOUNT_MATURITY_DATE_INVALID',
            'interest_rate.required' => 'MONEY_ACCOUNT_INTEREST_RATE_NOT_EMPTY',
            'interest_rate.numeric' => 'MONEY_ACCOUNT_INTEREST_RATE_NOT_NUMERIC',
            'money.required' => 'MONEY_ACCOUNT_MONEY_NOT_EMPTY',
            'money.numeric' => 'MONEY_ACCOUNT_MONEY_NOT_NUMERIC',
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
