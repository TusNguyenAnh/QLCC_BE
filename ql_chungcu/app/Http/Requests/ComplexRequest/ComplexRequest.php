<?php

namespace App\Http\Requests\ComplexRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ComplexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'complex_name' => 'required',
            'address' => 'required|min:5|max:50',
            'total_building' => 'required:max:20',
            'total_apartment' => 'required',
            'name_contact' => 'required',
            'phone_contact' => 'required',
            'description' => 'nullable|max:100',
            'email_contact' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'complex_name.required' => "COMPLEX_NAME_NOT_EMPTY",
            'address.required' => "ADDRESS_NOT_EMPTY",
            'address.min' => "ADDRESS_LENGTH",
            'address.max' => "ADDRESS_LENGTH",
            'total_building.required' => "TOTAL_BUILDING_NOT_EMPTY",
            'total_building.max' => "TOTAL_BUILDING_LENGTH",
            'total_apartment.required' => "TOTAL_APARTMENT_NOT_EMPTY",
            'name_contact.required' => "NAME_CONTACT_NOT_EMPTY",
            'phone_contact.required' => "PHONE_CONTACT_NOT_EMPTY",
            'description.max' => "DESCRIPTION_LENGTH",
            'email_contact.required' => "EMAIL_CONTACT_NOT_EMPTY",
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
