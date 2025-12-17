<?php

namespace App\Http\Requests\ServiceUnitPriceRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ServiceUnitPriceRequest extends FormRequest
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
        $rules = [
            'year' => 'required|integer|min:1900|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'price_per_m2' => 'required|numeric|min:0',
        ];

        // Nếu là PUT (update) thì year và month có thể không cập nhật
        if ($this->isMethod('put')) {
            $rules['year'] = 'sometimes|integer|min:1900|max:2100';
            $rules['month'] = 'sometimes|integer|min:1|max:12';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'year.required' => 'YEAR_NOT_EMPTY',
            'year.integer' => 'YEAR_NOT_INTEGER',
            'year.min' => 'YEAR_MIN_MAX',
            'year.max' => 'YEAR_MIN_MAX',
            'month.required' => 'MONTH_NOT_EMPTY',
            'month.integer' => 'MONTH_NOT_INTEGER',
            'month.min' => 'MONTH_MIN_MAX',
            'month.max' => 'MONTH_MIN_MAX',
            'price_per_m2.required' => 'PRICE_PER_M2_NOT_EMPTY',
            'price_per_m2.numeric' => 'PRICE_PER_M2_NOT_NUMERIC',
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
