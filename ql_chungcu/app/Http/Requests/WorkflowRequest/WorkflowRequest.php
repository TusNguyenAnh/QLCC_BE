<?php

namespace App\Http\Requests\WorkflowRequest;

use App\Enums\ErrorCode;
use App\Exceptions\AppException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class WorkflowRequest extends FormRequest
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
            'complex_id' => 'required',
            'workflow_name' => 'required',
            'description' => 'required',
            'status' => 'required',
            // workflow_step
            'workflow_step' => 'required|array|min:1',

            // từng object trong workflow_step
            'workflow_step.*.org_level' => 'required',
            'workflow_step.*.step_order' => 'required',
            'workflow_step.*.description' => 'required',
            'workflow_step.*.status' => 'required',

            // position là mảng
            'workflow_step.*.position' => 'required|array',

            // từng phần tử trong position
            'workflow_step.*.position.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'complex_id.required' => 'WORKFLOW_COMPLEX_ID_NOT_EMPTY',
            'workflow_name.required' => 'WORKFLOW_NAME_NOT_EMPTY',
            'description.required' => 'WORKFLOW_DESCRIPTION_NOT_EMPTY',
            'status.required' => 'WORKFLOW_STATUS_NOT_EMPTY',
            'workflow_step.required' => 'WORKFLOW_STEP_NOT_EMPTY',
            'workflow_step.min' => 'WORKFLOW_STEP_MIN',
            'workflow_step.*.org_level.required' => 'WORKFLOW_STEP_ORG_LEVEL_NOT_EMPTY',
            'workflow_step.*.step_order.required' => 'WORKFLOW_STEP_ORDER_NOT_EMPTY',
            'workflow_step.*.description.required' => 'WORKFLOW_STEP_DESCRIPTION_NOT_EMPTY',
            'workflow_step.*.status.required' => 'WORKFLOW_STEP_STATUS_NOT_EMPTY',
            'workflow_step.*.position.required' => 'WORKFLOW_STEP_POSITION_NOT_EMPTY',
            'workflow_step.*.position.array' => 'WORKFLOW_STEP_POSITION_ARRAY',
            'workflow_step.*.position.*.required' => 'WORKFLOW_STEP_POSITION_ITEM_NOT_EMPTY',
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
