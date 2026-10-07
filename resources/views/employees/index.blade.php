@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Employee Management</h2>
    <div>
        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">+ Add Employee</a>
        <a href="{{ route('employees.export') }}" class="btn btn-success btn-sm ms-1">Export Excel</a>
        <button type="button" class="btn btn-secondary btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#importModal">Import Excel</button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('employees.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search ID, name, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="education" class="form-select form-select-sm">
                    <option value="">-- Education --</option>
                    @foreach(['All', '10th', '12th', 'Diploma', 'UG', 'PG', 'Other'] as $edu)
                        <option value="{{ $edu }}" {{ request('education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select form-select-sm">
                    <option value="employee_id" {{ request('sort') == 'employee_id' ? 'selected' : '' }}>Employee ID</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="direction" class="form-select form-select-sm">
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>ASC</option>
                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>DESC</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Education</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Photo</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->employee_id }}</strong></td>
                            <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                            <td>{{ $employee->date_of_birth }}</td>
                            <td><span class="badge bg-info text-dark">{{ $employee->education_qualification }}</span></td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo" width="40" height="40" class="rounded-circle object-fit-cover">
                                @else
                                    <span class="text-muted small">No photo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info text-white">View</a>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $employees->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Employees Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" id="file" class="form-control" required accept=".xlsx,.xls,.csv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
