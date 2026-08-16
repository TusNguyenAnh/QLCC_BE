<?php

namespace App\Http\Requests\BuildingRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_name' => 'required',
            'complex_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'building_name.required' => 'BUILDING_NAME_NOT_EMPTY',
            'complex_id.required' => 'COMPLEX_ID_NOT_EMPTY',
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
