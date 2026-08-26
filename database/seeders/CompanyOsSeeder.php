<?php

namespace Database\Seeders;

use App\Models\ApprovalRule;
use App\Models\Employee;
use App\Models\FinancialInvoice;
use App\Models\Project;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use App\Services\CompanyOs\WorkflowEngine;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompanyOsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPermissions();
        $this->seedWorkflows();
        $this->seedApprovalRules();
        $this->backfill();
    }

    private function seedPermissions(): void
    {
        foreach (['transition-lifecycle', 'approve-lifecycle', 'view-control-center'] as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        foreach (['super_admin', 'admin'] as $role) {
            $r = Role::where('name', $role)->first();
            $r?->givePermissionTo(['transition-lifecycle', 'approve-lifecycle', 'view-control-center']);
        }

        Role::where('name', 'project_manager')->first()?->givePermissionTo(['transition-lifecycle', 'approve-lifecycle']);
        Role::where('name', 'hr')->first()?->givePermissionTo(['transition-lifecycle']);
        Role::where('name', 'accountant')->first()?->givePermissionTo(['transition-lifecycle', 'approve-lifecycle']);
    }

    private function seedWorkflows(): void
    {
        foreach (config('company-os.workflows') as $entityType => $config) {
            $definition = WorkflowDefinition::updateOrCreate(
                ['entity_type' => $entityType],
                [
                    'name' => $config['name'],
                    'slug' => $config['slug'],
                    'is_active' => true,
                ]
            );

            $stateIds = [];
            foreach ($config['states'] as $i => $state) {
                $row = WorkflowState::updateOrCreate(
                    ['workflow_definition_id' => $definition->id, 'key' => $state['key']],
                    [
                        'label_ar' => $state['label_ar'],
                        'sort_order' => $i,
                        'is_initial' => $state['is_initial'] ?? false,
                        'is_terminal' => $state['is_terminal'] ?? false,
                        'is_side' => $state['is_side'] ?? false,
                        'sla_hours' => $state['sla_hours'] ?? null,
                        'severity_if_breached' => $state['severity_if_breached'] ?? null,
                        'maps_to_status' => $state['maps_to_status'] ?? null,
                    ]
                );
                $stateIds[$state['key']] = $row->id;
            }

            $pairs = [];
            foreach ($config['transitions'] as $t) {
                $pairs[] = [$t['from'], $t['to'], $t];
            }
            foreach ($config['side_from'] ?? [] as $from) {
                foreach ($config['side_to'] ?? [] as $to) {
                    $pairs[] = [$from, $to, []];
                }
            }

            foreach ($pairs as [$from, $to, $meta]) {
                if (! isset($stateIds[$from], $stateIds[$to])) {
                    continue;
                }
                WorkflowTransition::updateOrCreate(
                    [
                        'from_state_id' => $stateIds[$from],
                        'to_state_id' => $stateIds[$to],
                    ],
                    [
                        'workflow_definition_id' => $definition->id,
                        'required_permission' => $meta['permission'] ?? null,
                        'requires_approval' => $meta['requires_approval'] ?? false,
                        'guard_key' => $meta['guard'] ?? null,
                    ]
                );
            }
        }
    }

    private function seedApprovalRules(): void
    {
        foreach (config('company-os.approval_rules') as $rule) {
            ApprovalRule::updateOrCreate(
                [
                    'entity_type' => $rule['entity_type'],
                    'min_amount' => $rule['min_amount'],
                    'max_amount' => $rule['max_amount'],
                ],
                [
                    'steps' => $rule['steps'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function backfill(): void
    {
        $engine = app(WorkflowEngine::class);

        Project::query()->whereDoesntHave('lifecycle')->each(fn (Project $p) => $engine->bootstrap($p, null, null, false));
        Employee::query()->whereDoesntHave('lifecycle')->each(fn (Employee $e) => $engine->bootstrap($e, null, null, false));
        FinancialInvoice::query()->whereDoesntHave('lifecycle')->each(fn (FinancialInvoice $i) => $engine->bootstrap($i, null, null, false));
    }
}
