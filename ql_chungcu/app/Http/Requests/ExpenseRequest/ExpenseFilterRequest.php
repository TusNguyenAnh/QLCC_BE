<?php

namespace App\Http\Requests\ExpenseRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseFilterRequest extends FormRequest
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
            'category' => 'nullable',
            'status' => 'nullable',
            'vendor' => 'nullable|string|max:255',
            'approved' => 'nullable|integer',
            'proposed_from' => 'nullable|date',
            'proposed_to' => 'nullable|date|after_or_equal:proposed_from',
            'building_id' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'category.in' => 'CATEGORY_INVALID',
            'status.in' => 'STATUS_INVALID',
            'proposed_from.date' => 'DATE_INVALID',
            'proposed_to.date' => 'DATE_INVALID',
            'proposed_to.after_or_equal' => 'DATE_TO_AFTER_FROM',
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