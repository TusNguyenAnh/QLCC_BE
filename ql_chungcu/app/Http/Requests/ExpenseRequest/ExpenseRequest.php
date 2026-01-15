<?php

namespace App\Http\Requests\ExpenseRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
        // Expense chỉ lưu METADATA (thông tin đề xuất, ước tính)
        // Thông tin thanh toán thực tế (paid_at) nằm trong ledgers
        return [
            'task_id' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:purchase,service,utilities,repair,salary,other',
            'original_amount' => 'required|numeric|min:0',  // Chỉ là ước tính
            'status' => 'nullable|string',
            'vendor' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'building_id' => 'nullable|array',
            'expense_type' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'TITLE_REQUIRED',
            'category.required' => 'CATEGORY_REQUIRED',
            'category.in' => 'CATEGORY_INVALID',
            'amount.required' => 'AMOUNT_REQUIRED',
            'amount.numeric' => 'AMOUNT_NOT_NUMERIC',
            'amount.min' => 'AMOUNT_MIN',
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
