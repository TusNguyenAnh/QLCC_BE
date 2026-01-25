<?php

namespace App\Http\Requests\StaffRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
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
            'fullname' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'org_id' => 'required',
            'role_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'STAFF_FULLNAME_NOT_EMPTY',
            'email.required' => 'STAFF_EMAIL_NOT_EMPTY',
            'phone_number.required' => 'STAFF_PHONE_NOT_EMPTY',
            'org_id.required' => 'STAFF_ORG_ID_NOT_EMPTY',
            'role_id.required' => 'STAFF_ROLE_ID_NOT_EMPTY',
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
