<?php

namespace App\Http\Requests\FileRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ExcelFileRequest extends FormRequest
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
            'files' => 'required|file|mimes:xlsx,xls|max:51200', // Max 50MB
        ];
    }

    public function messages()
    {
        return [
            'files.required' => 'FILE_REQUIRED',
            'files.file' => 'FILE_INVALID',
            'files.mimes' => 'FILE_EXCEL_INVALID_FORMAT',
            'files.max' => 'FILE_SIZE_EXCEEDED',
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
