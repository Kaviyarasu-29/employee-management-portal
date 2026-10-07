<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::all();
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'First Name',
            'Last Name',
            'Date of Birth',
            'Education Qualification',
            'Address',
            'Email',
            'Phone',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->firstname,
            $employee->lastname,
            $employee->date_of_birth,
            $employee->education_qualification,
            $employee->address,
            $employee->email,
            $employee->phone,
        ];
    }
}
