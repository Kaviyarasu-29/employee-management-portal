@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Employee Details</h5>
                <div>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm ms-1">Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-3 text-center">
                        @if($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo" class="img-thumbnail rounded-circle object-fit-cover" style="width: 120px; height: 120px;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; font-size: 2.5rem;">
                                {{ substr($employee->firstname, 0, 1) }}{{ substr($employee->lastname, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h3 class="h4 mb-1">{{ $employee->firstname }} {{ $employee->lastname }}</h3>
                        <p class="text-muted mb-2">Employee ID: <strong>{{ $employee->employee_id }}</strong></p>
                        <span class="badge bg-info text-dark fs-6">{{ $employee->education_qualification }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Employee ID</th>
                                <td>{{ $employee->employee_id }}</td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td>{{ $employee->firstname }}</td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td>{{ $employee->lastname }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $employee->date_of_birth }}</td>
                            </tr>
                            <tr>
                                <th>Education Qualification</th>
                                <td>{{ $employee->education_qualification }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $employee->address }}</td>
                            </tr>
                            <tr>
                                <th>Resume</th>
                                <td>
                                    @if($employee->resume)
                                        <a href="{{ asset('storage/' . $employee->resume) }}" target="_blank" class="btn btn-outline-primary btn-sm">View / Download Resume</a>
                                    @else
                                        <span class="text-muted">No resume uploaded</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit Employee</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-2">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
