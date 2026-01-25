<?php

namespace App\Http\Requests\RoleRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'role_name' => 'required',
            'complex_id' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'ROLE_NAME_NOT_EMPTY',
            'complex_id.required' => 'ROLE_COMPLEX_ID_NOT_EMPTY',
            'description.required' => 'ROLE_DESCRIPTION_NOT_EMPTY',
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
