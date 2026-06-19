<?php

namespace App\Console\Commands;

use App\Http\Controllers\EmployeeWorkspaceController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class WorkspaceSmokeTest extends Command
{
    protected $signature = 'test:workspace-smoke';

    protected $description = 'Smoke-test workspace, tasks pages and task workflow against the live database';

    public function handle(): int
    {
        $this->info('=== Solvesta Workspace Smoke Test ===');
        $failed = 0;

        $failed += $this->check('Task workflow helpers', function () {
            assert(Task::normalizeStatus('review') === 'code_review');
            assert(Task::normalizeStatus('completed') === 'done');
            assert(count(Task::workflowStatuses()) === 7);
            assert(Task::statusLabelAr('in_progress') === 'قيد التنفيذ');
        });

        $user = User::permission('view-own-tasks')->first()
            ?? User::role('developer')->first()
            ?? User::first();

        if (! $user) {
            $this->error('No users in database.');
            return 1;
        }

        $this->line("Using user: {$user->email} (id={$user->id})");

        $failed += $this->check('Workspace route registered', function () {
            assert(Route::has('workspace.index'));
            assert(Route::has('workspace.tasks.status'));
        });

        $failed += $this->check('Workspace index renders', function () use ($user) {
            auth()->login($user);
            $controller = app(EmployeeWorkspaceController::class);
            $view = $controller->index(new Request);
            $html = View::make($view->name(), $view->getData())->render();
            assert(str_contains($html, 'مساحة عملي'));
            assert(str_contains($html, 'workspace-column'));
        });

        $task = Task::with(['project', 'milestone', 'assignedTo'])->first();
        $ephemeralTask = null;

        if (! $task) {
            $ephemeralTask = $this->createEphemeralTask($user);
            $task = $ephemeralTask;
            $this->warn('Created ephemeral task for testing (will be deleted).');
        }

        if ($task) {
            $this->line("Using task: {$task->task_key} (status={$task->status})");

            $failed += $this->check('Task show view renders', function () use ($task, $user) {
                auth()->login($user);
                $controller = app(TaskController::class);
                $view = $controller->show($task);
                $html = View::make($view->name(), $view->getData())->render();
                assert(str_contains($html, $task->task_key));
            });

            $assignee = User::find($task->assigned_to) ?? $user;

            $failed += $this->check('Status update API', function () use ($task, $assignee) {
                auth()->login($assignee);
                $original = $task->status;
                $target = $original === 'todo' ? 'in_progress' : 'todo';

                $controller = app(EmployeeWorkspaceController::class);
                $response = $controller->updateStatus(
                    Request::create('/', 'PATCH', ['status' => $target]),
                    $task->fresh()
                );

                assert($response->getStatusCode() === 200, 'Expected 200, got '.$response->getStatusCode());
                $task->refresh();
                assert($task->status === $target, "Status not updated to {$target}");

                $task->update(['status' => $original]);
            });
        } else {
            $this->warn('No tasks in DB — skipping task-specific tests.');
        }

        if ($ephemeralTask) {
            $projectId = $ephemeralTask->project_id;
            $ephemeralTask->delete();
            \App\Models\Project::where('id', $projectId)->where('name', 'Smoke Test Project')->delete();
            \App\Models\Client::where('email', 'smoke-test@solvesta.local')->delete();
            $this->line('Ephemeral test data cleaned up.');
        }

        $failed += $this->check('Tasks index view renders', function () use ($user) {
            auth()->login($user);
            $controller = app(TaskController::class);
            $view = $controller->index();
            View::make($view->name(), $view->getData())->render();
        });

        $failed += $this->check('Tasks create/edit views compile', function () use ($user) {
            auth()->login($user);
            View::share('errors', new \Illuminate\Support\ViewErrorBag);
            if ($user->can('create-tasks')) {
                $view = app(TaskController::class)->create();
                View::make($view->name(), $view->getData())->render();
            }
        });

        if ($user->can('view-dev-workflow')) {
            $failed += $this->check('Dev workflow index renders', function () use ($user) {
                auth()->login($user);
                $request = Request::create('/dev-workflow', 'GET');
                $request->setUserResolver(fn () => $user);
                $controller = app(\App\Http\Controllers\DevWorkflowController::class);
                $view = $controller->index($request);
                View::make($view->name(), $view->getData())->render();
            });
        }

        $failed += $this->check('Workspace blade files exist', function () {
            assert(view()->exists('workspace.index'));
            assert(view()->exists('workspace.partials.task-card'));
        });

        if ($failed > 0) {
            $this->error("FAILED: {$failed} check(s).");
            return 1;
        }

        $this->info('All smoke tests passed.');
        return 0;
    }

    private function check(string $label, callable $fn): int
    {
        try {
            $fn();
            $this->info("✓ {$label}");
            return 0;
        } catch (\Throwable $e) {
            $this->error("✗ {$label}: {$e->getMessage()}");
            if ($this->output->isVerbose()) {
                $this->line($e->getTraceAsString());
            }
            return 1;
        }
    }

    private function createEphemeralTask(User $user): Task
    {
        $client = \App\Models\Client::firstOrCreate(
            ['email' => 'smoke-test@solvesta.local'],
            ['name' => 'Smoke Test Client', 'status' => 'active']
        );

        $project = \App\Models\Project::create([
            'name' => 'Smoke Test Project',
            'client_id' => $client->id,
            'project_manager_id' => $user->id,
            'start_date' => now()->toDateString(),
            'status' => 'in_progress',
        ]);

        return Task::create([
            'title' => 'Smoke test task',
            'description' => 'Auto-created for smoke test',
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'created_by' => $user->id,
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => now()->addWeek()->toDateString(),
        ]);
    }
}
