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

    public function show($perPage)
    {
        return $this->workflowRepository->show($perPage);
    }

    public function add(array $data)
    {
        $createdWorkflow = $this->workflowRepository->store(Arr::except($data, ['workflow_step']));
        $dataWorkflowStep = [];

        foreach ($data["workflow_step"] as $wfs) {
            $dataWorkflowStep[] = [
                'id' => (string) Str::uuid(),
                'org_id' => $wfs["org_id"],
                'step_order' => $wfs["step_order"],
                'description' => $wfs["description"],
                'status' => $wfs["status"],
                'workflow_id' => $createdWorkflow->id
            ];
        }
        $this->workflowStepRepository->store($dataWorkflowStep);
        return $createdWorkflow;
    }
}
