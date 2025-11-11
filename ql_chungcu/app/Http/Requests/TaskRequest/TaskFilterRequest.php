<?php

namespace App\Http\Requests\TaskRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
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
            'priority_id' => 'nullable|array',
            'taskType_id' => 'nullable|array',
            'time_approved_start' => 'nullable|date',
            'time_approved_end' => 'nullable|date',
            'time_request_start' => 'nullable|date',
            'time_request_end' => 'nullable|date',
            'order' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
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
