<?php

namespace App\Services\WorkflowService;

use App\Models\Workflow;
use App\Repositories\WorkflowRepository\IWorkflowRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepApproverRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkflowService implements IWorkflowService
{
    private IWorkflowRepository $workflowRepository;
    private IWorkflowStepRepository $workflowStepRepository;
    private IWorkflowStepApproverRepository $workflowStepApproverRepository;

    public function __construct(IWorkflowRepository             $workflowRepository, IWorkflowStepRepository $workflowStepRepository,
                                IWorkflowStepApproverRepository $workflowStepApproverRepository)
    {
        $this->workflowRepository = $workflowRepository;
        $this->workflowStepRepository = $workflowStepRepository;
        $this->workflowStepApproverRepository = $workflowStepApproverRepository;
    }

    public function show($perPage, $complexId)
    {
        return $this->workflowRepository->show($perPage, $complexId);
    }

    public function add(array $data)
    {
        DB::beginTransaction();
        try {

            $createdWorkflow = $this->workflowRepository->store(Arr::except($data, ['workflow_step']));
            $dataWorkflowStep = [];
            $dataApprovers = [];

            if (isset($data["workflow_step"]) && is_array($data["workflow_step"])) {
                foreach ($data["workflow_step"] as $wfs) {
                    $stepId = (string)Str::uuid();
                    $dataWorkflowStep[] = [
                        'id' => $stepId,
                        'org_level' => $wfs["org_level"],
                        'step_order' => $wfs["step_order"],
                        'description' => $wfs["description"],
                        'status' => $wfs["status"],
                        'workflow_id' => $createdWorkflow->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Thêm approvers cho workflow step
                    if (isset($wfs['position']) && is_array($wfs['position'])) {
                        foreach ($wfs['position'] as $approver) {
                            $dataApprovers[] = [
                                'id' => (string)Str::uuid(),
                                'workflow_step_id' => $stepId,
                                'position' => $approver,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                }
            }

            $this->workflowStepRepository->store($dataWorkflowStep);
            $this->workflowStepApproverRepository->store($dataApprovers);

            DB::commit();
            return $createdWorkflow;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
