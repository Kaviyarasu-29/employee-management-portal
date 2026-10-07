<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_employees_list_can_be_viewed()
    {
        Employee::factory()->count(3)->create();

        $response = $this->get('/employees');

        $response->assertStatus(200);
        $response->assertViewHas('employees');
    }

    public function test_employee_can_be_created()
    {
        $employeeData = [
            'employee_id' => 'EMP9999',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'date_of_birth' => '1990-01-01',
            'education_qualification' => 'UG',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ];

        $response = $this->post('/employees', $employeeData);

        $response->assertRedirect('/employees');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('employees', [
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_employee_can_be_updated()
    {
        $employee = Employee::factory()->create();

        $response = $this->put("/employees/{$employee->id}", [
            'employee_id' => $employee->employee_id,
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'date_of_birth' => '1992-05-10',
            'education_qualification' => 'PG',
            'phone' => '0987654321',
            'address' => '456 Side St',
        ]);

        $response->assertRedirect('/employees');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'firstname' => 'Jane',
            'email' => 'jane.smith@example.com',
        ]);
    }

    public function test_employee_can_be_deleted()
    {
        $employee = Employee::factory()->create();

        $response = $this->delete("/employees/{$employee->id}");

        $response->assertRedirect('/employees');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_requires_unique_email()
    {
        $existingEmployee = Employee::factory()->create([
            'email' => 'existing@example.com'
        ]);

        $newEmployeeData = [
            'employee_id' => 'EMP9998',
            'firstname' => 'Mark',
            'lastname' => 'Twain',
            'email' => 'existing@example.com', // Duplicate
            'date_of_birth' => '1980-01-01',
            'education_qualification' => 'UG',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ];

        $response = $this->post('/employees', $newEmployeeData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('employees', [
            'employee_id' => 'EMP9998'
        ]);
    }
}
