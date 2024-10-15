<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateEmployeeData
{
    public function handle(Request $request, Closure $next)
    {
        $isUpdate = $request->isMethod('put');
    
        $rules = [
            'emp_id' => 'required|unique:employees,emp_id' . ($isUpdate ? ',' . $request->id : ''),
            'name_prefix' => 'nullable|string|max:10',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:2',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'email' => 'required|email|max:255|unique:employees,email' . ($isUpdate ? ',' . $request->id : ''),
            'date_of_birth' => 'required|date',
            'time_of_birth' => 'nullable|date_format:H:i:s',
            'age_in_yrs' => 'required|numeric|min:0|max:150',
            'date_of_joining' => 'required|date',
            'age_in_company_years' => 'required|numeric|min:0',
            'phone_no' => 'required|string|max:20',
            'place_name' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'region' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:employees,user_name' . ($isUpdate ? ',' . $request->id : ''),
        ];
    
        if ($isUpdate) {
            foreach ($rules as $field => &$rule) {
                if (strpos($rule, 'required') !== false && !$request->has($field)) {
                    $rule = str_replace('required|', '', $rule);
                }
            }
        }
    
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        \Illuminate\Support\Facades\Log::info($validator->errors()); //sil

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }
    
        return $next($request);
    }
    
}
