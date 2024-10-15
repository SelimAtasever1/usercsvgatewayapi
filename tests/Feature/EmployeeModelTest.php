<?php

namespace Tests\Feature;
use App\Models\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeModelTest extends TestCase // factory tests.
{
    use RefreshDatabase; 
    // refdb ile empid duplicate etc etme sorunu gider.
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_employee_coorectly()
    {
        $employee = Employee::factory()->create([
            'emp_id' => 'EMP123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'date_of_birth' => '1990-01-01',
            'date_of_joining' => '2020-01-01',
            'age_in_yrs' => 30,
            'phone_no' => '123456789',
            'city' => 'New York',
            'region' => 'NY',
            'user_name' => 'johndoe',
        ]);

        $this->assertDatabaseHas('employees', [
            'emp_id' => 'EMP123',
            'email' => 'john.doe@example.com',
            'age_in_yrs' => 30,
            'region' => 'NY'
        ]);
    }

    public function test_can_update_employee()
    {
        $employee = Employee::factory()->create();

        $employee->update([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);
    }

    public function test_can_delete_employee()
    {
        $employee = Employee::factory()->create();
        $employee->delete();

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }



}
