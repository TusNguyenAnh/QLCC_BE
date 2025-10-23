<?php

namespace App\Services\WorkflowService;

use App\Models\Workflow;
use App\Repositories\WorkflowRepository\IWorkflowRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WorkflowService implements IWorkflowService
{
    private IWorkflowRepository $workflowRepository;
    private IWorkflowStepRepository $workflowStepRepository;


    public function __construct(IWorkflowRepository $workflowRepository,IWorkflowStepRepository $workflowStepRepository)
    {
        $this->workflowRepository = $workflowRepository;
        $this->workflowStepRepository = $workflowStepRepository;
    }

    public function show($perPage, $complexId)
    {
        return $this->workflowRepository->show($perPage, $complexId);
    }

    public function add(array $data)
    {
        $createdWorkflow = $this->workflowRepository->store(Arr::except($data, ['workflow_step']));
        $dataWorkflowStep = [];

        foreach ($data["workflow_step"] as $wfs) {
            $dataWorkflowStep[] = [
                'id' => (string) Str::uuid(),
                'org_level' => $wfs["org_level"],
                'step_order' => $wfs["step_order"],
                'description' => $wfs["description"],
                'status' => $wfs["status"],
                'workflow_id' => $createdWorkflow->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $this->workflowStepRepository->store($dataWorkflowStep);
        return $createdWorkflow;
    }
}
