<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmploymentContract;
use App\Services\CompanyOs\WorkflowEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmploymentContractController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        if (! auth()->user()->can('edit-employees')) {
            abort(403);
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'base_salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'notes' => 'nullable|string',
            'contract_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'activate' => 'nullable|boolean',
        ], [
            'end_date.after_or_equal' => 'تاريخ نهاية العقد يجب أن يكون بعد تاريخ البداية.',
        ]);

        $documentPath = null;
        if ($request->hasFile('contract_document')) {
            $documentPath = $request->file('contract_document')->store('employment-contracts', 'public');
        }

        $contract = EmploymentContract::create([
            'employee_id' => $employee->id,
            'contract_number' => EmploymentContract::generateContractNumber(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'base_salary' => $validated['base_salary'],
            'employment_type' => $validated['employment_type'],
            'document_path' => $documentPath,
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if ($request->boolean('activate')) {
            return $this->activateContract($employee, $contract);
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم حفظ عقد التوظيف (مسودة). فعّل العقد لربط الراتب بالمحاسبة.');
    }

    public function activate(Employee $employee, EmploymentContract $contract)
    {
        if (! auth()->user()->can('edit-employees')) {
            abort(403);
        }

        if ($contract->employee_id !== $employee->id) {
            abort(404);
        }

        return $this->activateContract($employee, $contract);
    }

    protected function activateContract(Employee $employee, EmploymentContract $contract)
    {
        EmploymentContract::where('employee_id', $employee->id)
            ->where('id', '!=', $contract->id)
            ->where('status', 'active')
            ->update(['status' => 'terminated']);

        $contract->update([
            'status' => 'active',
            'signed_at' => $contract->signed_at ?? now(),
        ]);

        $employee->update([
            'salary' => $contract->base_salary,
            'employment_type' => $contract->employment_type,
            'hire_date' => $contract->start_date,
            'status' => 'active',
        ]);

        if ($employee->lifecycleStateKey() === 'onboarding') {
            try {
                app(WorkflowEngine::class)->transition($employee, 'active', auth()->user());
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم تفعيل عقد التوظيف وربط الراتب ('.number_format((float) $contract->base_salary, 2).' ج.م) بقسم المحاسبة.');
    }

    public function destroy(Employee $employee, EmploymentContract $contract)
    {
        if (! auth()->user()->can('edit-employees')) {
            abort(403);
        }

        if ($contract->employee_id !== $employee->id || $contract->status === 'active') {
            return redirect()->back()->with('error', 'لا يمكن حذف عقد ساري. أنهِ العقد أولاً.');
        }

        if ($contract->document_path && Storage::disk('public')->exists($contract->document_path)) {
            Storage::disk('public')->delete($contract->document_path);
        }

        $contract->delete();

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم حذف مسودة العقد.');
    }
}
