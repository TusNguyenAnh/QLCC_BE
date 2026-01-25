<?php

namespace App\Http\Requests\ResidentRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ResidentRequest extends FormRequest
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
            'gender' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'birthday' => 'required',
            'phone_number' => 'required',
            'org_id' => 'nullable',
            'relationship' => 'required',
            'cccd' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'gender.required' => 'RESIDENT_GENDER_NOT_EMPTY',
            'fullname.required' => 'RESIDENT_FULLNAME_NOT_EMPTY',
            'email.required' => 'RESIDENT_EMAIL_NOT_EMPTY',
            'birthday.required' => 'RESIDENT_BIRTHDAY_NOT_EMPTY',
            'phone_number.required' => 'RESIDENT_PHONE_NOT_EMPTY',
            'relationship.required' => 'RESIDENT_RELATIONSHIP_NOT_EMPTY',
            'cccd.required' => 'RESIDENT_CCCD_NOT_EMPTY',
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
