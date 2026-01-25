<?php

namespace App\Http\Requests\TaskRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'tasktype_id' => 'required',
            'building_id' => 'required',
            'task_name' => 'required',
            'description' => 'required',
            //            'files' => 'required|array',
            //            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,pdf,docx|max:51200',
        ];
    }

    public function messages()
    {
        return [
            'tasktype_id.required' => 'TASK_TYPE_ID_NOT_EMPTY',
            'building_id.required' => 'TASK_BUILDING_ID_NOT_EMPTY',
            'task_name.required' => 'TASK_NAME_NOT_EMPTY',
            'description.required' => 'TASK_DESCRIPTION_NOT_EMPTY',
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
