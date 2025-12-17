<?php

namespace App\Http\Requests\RevenueRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GenerateRevenueRequest extends FormRequest
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
            'building_id' => 'required|string',
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ];
    }

    public function messages()
    {
        return [
            'building_id.required' => 'BUILDING_NOT_EMPTY',
            'year.required' => 'YEAR_NOT_EMPTY',
            'year.integer' => 'YEAR_NOT_INTEGER',
            'year.min' => 'YEAR_MIN_MAX',
            'year.max' => 'YEAR_MIN_MAX',
            'month.required' => 'MONTH_NOT_EMPTY',
            'month.integer' => 'MONTH_NOT_INTEGER',
            'month.min' => 'MONTH_MIN_MAX',
            'month.max' => 'MONTH_MIN_MAX',
        ];
    }

    /**
     * @throws AppException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new AppException(ErrorCode::UNCATEGORIZED_EXCEPTION);
    }
}
