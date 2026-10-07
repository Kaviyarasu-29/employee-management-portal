<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'employee_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
            'education_qualification' => $this->faker->randomElement(['10th', '12th', 'Diploma', 'UG', 'PG', 'Other']),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('##########'),
        ];
    }
}
