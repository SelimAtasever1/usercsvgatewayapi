<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\ValidateEmployeeData;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\Employee;

class ValidateEmployeeDataTest extends TestCase
{
    use RefreshDatabase; // to prevent conflict with unique fields.
    public function test_valid_data_passes_through_middleware()
    {
        $middleware = new ValidateEmployeeData();

        $request = Request::create('/api/employee', 'POST', [
            'emp_id' => 'EMP123',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'gender' => 'M',
            'date_of_birth' => '1990-01-01',
            'age_in_yrs' => 30,
            'date_of_joining' => '2020-01-01',
            'age_in_company_years' => 4,
            'phone_no' => '123456789',
            'city' => 'New York',
            'zip' => '10001',
            'region' => 'NY',
            'user_name' => 'johndoe',
        ]);

        $next = function ($request) {
            return response('values passed', 200); // string is sample output body,indicates middleware check has been passed.
        };

        $response = $middleware->handle($request, $next);

        $this->assertEquals('values passed', $response->content());
    }

    public function test_invalid_data_returns_error()
    {
        $middleware = new ValidateEmployeeData();
    
        $request = Request::create('/api/employee', 'POST', [
            'emp_id' => '', // emp_id is required
            'first_name' => '',
            'last_name' => '',
            'email' => 'iam_invalid_email', 
        ]);
    
        $next = function ($request) {
            return response()->json(['errors' => []], 422); 
        };
    
        $response = $middleware->handle($request, $next);

        $this->assertEquals(422, $response->status());
    
        $data = $response->getData(true); // Get the response data as an array
    
        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey('emp_id', $data['errors']); // Make sure this matches your middleware's validation errors
        $this->assertArrayHasKey('first_name', $data['errors']);
        $this->assertArrayHasKey('email', $data['errors']);

        $this->assertEquals('The emp id field is required.', $data['errors']['emp_id'][0]);
    }

    public function test_update_allows_some_missing_fields()
    {
        $middleware = new ValidateEmployeeData();

        $request = Request::create('/api/employee/1', 'PUT', [
            'emp_id' => 'EMP123',
            // Some of the required fields are missing for thhe update req
        ]);

        $next = function ($request) {
            return response('passed', 200);
        };

        $response = $middleware->handle($request, $next);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('passed', $response->content()); //??
    }

    public function test_unique_fields_cause_validation_error_on_conflict()
    {
        $middleware = new ValidateEmployeeData();
        
        $existingEmployee = Employee::factory()->create([
            'emp_id' => 'EMP' . now()->timestamp, // Use timestamp to ensure uniqueness?
            'email' => 'john.doe@example.com',
        ]);
    
        $request = Request::create('/api/employee', 'POST', [
            'emp_id' => $existingEmployee->emp_id, // Use existing emp_id amd email
            'email' => $existingEmployee->email, // 
        ]);
    
        $next = function ($request) {
            return response('should not be called', 200);
        };
    
        $response = $middleware->handle($request, $next);
    
        $this->assertEquals(422, $response->status());
        
        $data = json_decode($response->getContent(), true);
    
        $this->assertArrayHasKey('errors', $data);
        $this->assertArrayHasKey('emp_id', $data['errors']);
        $this->assertArrayHasKey('email', $data['errors']);
    
        $this->assertEquals('The emp id has already been taken.', $data['errors']['emp_id'][0]);
        $this->assertEquals('The email has already been taken.', $data['errors']['email'][0]);
    }
    
    
}
