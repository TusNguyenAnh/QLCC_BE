<?php

namespace App\Http\Requests\RevenueRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RevenueFilterRequest extends FormRequest
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
            'status' => 'nullable|string',
            'approved' => 'nullable|integer',
            'proposed_from' => 'nullable|date',
            'proposed_to' => 'nullable|date|after_or_equal:proposed_from',
            'building_id' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'apartment_id.uuid' => 'APARTMENT_ID_NOT_UUID',
            'year.integer' => 'YEAR_NOT_INTEGER',
            'year.min' => 'YEAR_MIN_MAX',
            'year.max' => 'YEAR_MIN_MAX',
            'month.integer' => 'MONTH_NOT_INTEGER',
            'month.min' => 'MONTH_MIN_MAX',
            'month.max' => 'MONTH_MIN_MAX',
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
