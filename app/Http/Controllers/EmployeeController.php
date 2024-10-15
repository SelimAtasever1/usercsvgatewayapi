<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessExcelFile;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use App\Http\Requests\ExcelUploadRequest;
use App\Http\Requests\EmployeeRequest;

use App\Models\Employee;

/**
 * @OA\Info(title="Employee API", version="0.1")
 *
* @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *     @OA\Property(property="emp_id", type="string", description="Employee ID"),
 *     @OA\Property(property="name_prefix", type="string", description="Name Prefix"),
 *     @OA\Property(property="first_name", type="string", description="First Name of the Employee"),
 *     @OA\Property(property="middle_initial", type="string", description="Middle Initial of the Employee"),
 *     @OA\Property(property="last_name", type="string", description="Last Name of the Employee"),
 *     @OA\Property(property="gender", type="string", description="Gender of the Employee"),
 *     @OA\Property(property="email", type="string", description="Email of the Employee"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", description="Date of Birth of the Employee"),
 *     @OA\Property(property="time_of_birth", type="string", format="time", description="Time of Birth of the Employee"),
 *     @OA\Property(property="age_in_yrs", type="number", format="float", description="Age of the Employee in Years"),
 *     @OA\Property(property="date_of_joining", type="string", format="date", description="Date of Joining"),
 *     @OA\Property(property="age_in_company_years", type="number", format="float", description="Age in the Company in Years"),
 *     @OA\Property(property="phone_no", type="string", description="Phone Number of the Employee"),
 *     @OA\Property(property="place_name", type="string", description="Place Name associated with the Employee"),
 *     @OA\Property(property="county", type="string", description="County"),
 *     @OA\Property(property="city", type="string", description="City"),
 *     @OA\Property(property="zip", type="string", description="ZIP Code"),
 *     @OA\Property(property="region", type="string", description="Region"),
 *     @OA\Property(property="user_name", type="string", description="Username of the Employee")
 * )
 */
class EmployeeController extends Controller
{
    protected $excelService;

    public function __construct(ExcelService $excelService) {
        $this->excelService = $excelService;
    }

    /**
     * @OA\Get(
     *     path="/api/employee",
     *     tags={"Employee"},
     *     summary="Get paginated listing of employees",
     *     description="Returns list of employees",
     *     @OA\Response(
     *         response=200, 
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404, 
     *         description="No employees found"
     *     )
     * )
     */

    public function index(Request $request)
    {
        $employees = Employee::paginate(50);
        
        if ($employees->isEmpty()) {
            return response()->json(['message' => 'No employees found'], 404);
        }

        return response()->json($employees, 200);
    }

    /**
     * @OA\Get(
     *      path="/api/employee/{id}",
     *      tags={"Employee"},
     *      summary="Get details of specific employee",
     *      description="Returns details of specific employee",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the employee to fetch",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=404, description="Employee not found")
     * )
     */
    public function show($id)
    {
        $employee = Employee::find($id);
    
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    
        return response()->json($employee, 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/employee/{id}",
     *      tags={"Employee"},
     *      summary="Delete specific employee",
     *      description="Delete specific employee by ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the employee to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=404, description="Employee not found")
     * )
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully'], 200);
    }

    /**
     * @OA\Post(
     *      path="api/employee",
     *      tags={"Employee"},
     *      summary="Upload Excel file for employees",
     *      description="Upload Excel file to import employees to the database",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"excel_file"},
     *                  @OA\Property(
     *                      property="excel_file",
     *                      type="string",
     *                      format="binary",
     *                      description="Excel file to upload"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=400, description="Bad request")
     * )
     */
    public function upload(ExcelUploadRequest $request) 
    {
        $filePath = $request->file('excel_file')->store('public');
        ProcessExcelFile::dispatch($filePath);

        return response()->json(['message' => 'Data processing completed.'], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/employee/{id}",
     *      tags={"Employee"},
     *      summary="Update specific employee",
     *      description="Update specific employee by ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the employee to update",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Employee")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Successful operation"),
     *      @OA\Response(response=404, description="Employee not found")
     * )
     */
    public function update(EmployeeRequest  $request, $id) 
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->update($request->all());

        return response()->json(['message' => 'Employee updated successfully', 'data' => $employee], 200);
    }
}
