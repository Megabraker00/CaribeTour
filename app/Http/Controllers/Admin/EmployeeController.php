<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::query()
            ->with(['position', 'statusRecord'])
            ->orderBy('last_name')
            ->orderBy('name')
            ->get();

        return view('admin.employee.index', compact('employees'));
    }

    public function create(): View
    {
        return view('admin.employee.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedEmployee($request);
        $validated['created_user_id'] = auth()->id();

        Employee::create($validated);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    public function edit(Employee $employee): View
    {
        return view('admin.employee.edit', array_merge(
            $this->formOptions(),
            ['employee' => $employee]
        ));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $employee->update($this->validatedEmployee($request, $employee));

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'positions' => Position::query()->orderBy('name')->get(),
            'statuses' => Status::query()
                ->where('statusable', Employee::class)
                ->orderBy('name')
                ->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedEmployee(Request $request, ?Employee $employee = null): array
    {
        $emailPersonal = Rule::unique('employees', 'email_personal');
        $emailCompany = Rule::unique('employees', 'email_company');
        $document = Rule::unique('employees', 'dni_passport');

        if ($employee) {
            $emailPersonal = $emailPersonal->ignore($employee->id);
            $emailCompany = $emailCompany->ignore($employee->id);
            $document = $document->ignore($employee->id);
        }

        return $request->validate([
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email_personal' => ['required', 'email', 'max:100', $emailPersonal],
            'email_company' => ['required', 'email', 'max:100', $emailCompany],
            'dni_passport' => ['required', 'string', 'max:20', $document],
            'phone' => 'nullable|string|max:20',
            'position_id' => 'required|integer|exists:positions,id',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Employee::class),
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'Los apellidos son obligatorios.',
            'email_personal.required' => 'El email personal es obligatorio.',
            'email_company.required' => 'El email de empresa es obligatorio.',
            'dni_passport.required' => 'El DNI o pasaporte es obligatorio.',
            'position_id.required' => 'Selecciona un cargo.',
            'status_id.required' => 'Selecciona un estado.',
        ]);
    }
}
