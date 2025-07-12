<?php

namespace App\Http\Requests\OrganizationRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'org_code' => 'required',
            'org_name' => 'required|min:5|max:30',
            'parent_org_id' => 'nullable|exists:organization,id',
            'description' => 'nullable|max:100',
        ];
    }
    public function messages()
    {
        return [
            'org_name.required' => "ORG_NAME_NOT_EMPTY",
            'org_name.min' => "ORG_NAME_LENGTH",
            'org_name.max' => "ORG_NAME_LENGTH",
            'parent_org_id.exists' => "ORG_NAME_NOT_FOUND",
            'description.max' => "ORG_DESCRIPTION_LENGTH",
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
