<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class EmployeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['employee_id']) || empty($row['employee_id'])) {
            return null;
        }

        return Employee::updateOrCreate(
            ['employee_id' => $row['employee_id']],
            [
                'firstname' => $row['first_name'] ?? $row['firstname'] ?? 'N/A',
                'lastname' => $row['last_name'] ?? $row['lastname'] ?? 'N/A',
                'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? '1990-01-01'),
                'education_qualification' => $row['education_qualification'] ?? $row['education'] ?? 'Other',
                'address' => $row['address'] ?? 'Not provided',
                'email' => $row['email'] ?? Str::random(5) . '@example.com',
                'phone' => $row['phone'] ?? '0000000000',
            ]
        );
    }

    private function parseDate($date)
    {
        if (is_numeric($date)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }
        return date('Y-m-d', strtotime($date));
    }
}
