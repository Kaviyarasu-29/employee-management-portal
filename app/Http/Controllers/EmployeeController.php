<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('education') && $request->education !== 'All') {
            $query->where('education_qualification', $request->education);
        }

        $sort = $request->input('sort', 'employee_id');
        $direction = $request->input('direction', 'asc');
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        if ($sort === 'name') {
            $query->orderBy('firstname', $direction)->orderBy('lastname', $direction);
        } elseif ($sort === 'email') {
            $query->orderBy('email', $direction);
        } elseif ($sort === 'created_at') {
            $query->orderBy('created_at', $direction);
        } else {
            $query->orderBy('employee_id', $direction);
        }

        $employees = $query->paginate(10)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees',
            'date_of_birth' => 'required|date',
            'education_qualification' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('employees/resumes', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . $employee->id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
            'date_of_birth' => 'required|date',
            'education_qualification' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = null;
        }

        if ($request->hasFile('resume')) {
            if ($employee->resume && Storage::disk('public')->exists($employee->resume)) {
                Storage::disk('public')->delete($employee->resume);
            }
            $data['resume'] = $request->file('resume')->store('employees/resumes', 'public');
        } elseif ($request->boolean('remove_resume')) {
            if ($employee->resume && Storage::disk('public')->exists($employee->resume)) {
                Storage::disk('public')->delete($employee->resume);
            }
            $data['resume'] = null;
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }

            if ($employee->resume && Storage::disk('public')->exists($employee->resume)) {
                Storage::disk('public')->delete($employee->resume);
            }

            $employee->delete();

            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('employees.index')->with('error', 'Cannot delete this employee because they are linked to other records.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'An error occurred while trying to delete the employee.');
        }
    }

    /**
     * Export employees to Excel
     */
    public function export()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    /**
     * Import employees from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
            return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for MySQL duplicate entry error code (1062)
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('employees.index')->with('error', 'Import failed: One of the employees in your file has an email address or ID that is already in use by someone else.');
            }
            return redirect()->route('employees.index')->with('error', 'Import failed: A database error occurred while saving the records.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Import failed: Please ensure the file format is correct.');
        }
    }
}
