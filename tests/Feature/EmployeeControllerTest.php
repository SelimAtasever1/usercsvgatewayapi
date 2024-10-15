<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected function expectedEmployeeData($employee)
    {
        return [
            'emp_id' => $employee->emp_id,
            'name_prefix' => $employee->name_prefix,
            'first_name' => $employee->first_name,
            'middle_initial' => $employee->middle_initial,
            'last_name' => $employee->last_name,
            'gender' => $employee->gender,
            'email' => $employee->email,
            'date_of_birth' => $employee->date_of_birth,
            'time_of_birth' => $employee->time_of_birth,
            'age_in_yrs' => (string) $employee->age_in_yrs, 
            'date_of_joining' => $employee->date_of_joining,
            'age_in_company_years' => (string) $employee->age_in_company_years, 
            'phone_no' => $employee->phone_no,
            'place_name' => $employee->place_name,
            'county' => $employee->county,
            'city' => $employee->city,
            'zip' => $employee->zip,
            'region' => $employee->region,
            'user_name' => $employee->user_name,
            'created_at' => $employee->created_at->toISOString(), 
            'updated_at' => $employee->updated_at->toISOString(), 
        ];
    }

    public function test_can_retrieve_paginated_employees()
    {
        Employee::factory()->count(10)->create();
        $response = $this->getJson('/api/employee');

        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'data',
            'current_page',
            'total',
            'per_page'
        ]);
        $this->assertCount(10, $response->json('data'));
    }

    public function test_can_retrieve_specific_employee()
    {
        $employee = Employee::factory()->create();

        $employee->refresh();

        $response = $this->getJson('/api/employee/' . $employee->id);

        $response->assertStatus(200);
        
        $response->assertJson($this->expectedEmployeeData($employee));
    }

    public function test_can_update_employee()
    {
        $user = User::factory()->create();
        $this->actingAs($user); //Simulating  authentication

        $employee = Employee::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'emp_id' => 'E12345',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'age_in_yrs' => 34,
            'date_of_joining' => '2020-01-01',
            'age_in_company_years' => 4,
            'phone_no' => '1234567890',
            'city' => 'Example City',
            'zip' => '12345',
            'region' => 'Example Region',
            'user_name' => 'johndoe',
        ]);

        $updatedData = [
            'emp_id' => 'E12345',
            'first_name' => 'Clark',
            'last_name' => 'Kent',
            'gender' => 'F',
            'email' => 'jane.doe@example.com',
            'date_of_birth' => '1990-01-01',
            'age_in_yrs' => 100,
            'date_of_joining' => '2020-01-01',
            'age_in_company_years' => 4,
            'phone_no' => '1234567890',
            'city' => 'Example City',
            'zip' => '12345',
            'region' => 'Example Region',
            'user_name' => 'janedoe',
        ];

        $response = $this->putJson('/api/employee/' . $employee->id, $updatedData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Employee updated successfully',
                    'data' => [
                        'id' => $employee->id,
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                        'email' => 'jane.doe@example.com',
                    ],
                ]);

        $employee->refresh(); // Important ! : Refresh the employee instance to get updated data
        
        $this->assertEquals('Clark', $employee->first_name);
        $this->assertEquals('Kent', $employee->last_name);
        $this->assertEquals(100, $employee->age_in_yrs);
    }


    public function test_can_delete_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson('/api/employee/' . $employee->id);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Employee deleted successfully']);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
