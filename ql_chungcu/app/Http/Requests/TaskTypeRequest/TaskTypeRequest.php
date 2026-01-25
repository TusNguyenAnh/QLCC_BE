<?php

namespace App\Http\Requests\TaskTypeRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TaskTypeRequest extends FormRequest
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
            'workflow_id' => 'required',
            'priority_id' => 'required',
            'type_name' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'workflow_id.required' => 'TASKTYPE_WORKFLOW_ID_NOT_EMPTY',
            'priority_id.required' => 'TASKTYPE_PRIORITY_ID_NOT_EMPTY',
            'type_name.required' => 'TASKTYPE_NAME_NOT_EMPTY',
            'description.required' => 'TASKTYPE_DESCRIPTION_NOT_EMPTY',
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
