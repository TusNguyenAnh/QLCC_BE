<?php

namespace App\Http\Requests\ApartmentRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
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
            'building_id' => 'required',
            'floor' => 'required|integer',
            'apt_number' => 'required|max:20',
            'apt_area' => 'required|numeric|min:0.01',
            'apt_type' => 'required',
            'description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'building_id.required' => 'BUILDING_NOT_EMPTY',
            'floor.required' => 'FLOOR_REQUIRED',
            'floor.integer' => 'FLOOR_NOT_INTEGER',
            'apt_number.required' => 'APT_NUMBER_REQUIRED',
            'apt_number.max' => 'APT_NUMBER_LENGTH',
            'apt_area.required' => 'APT_AREA_REQUIRED',
            'apt_area.numeric' => 'APT_AREA_NOT_NUMERIC',
            'apt_area.min' => 'APT_AREA_MIN',
            'apt_type.required' => 'APT_TYPE_REQUIRED',
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
