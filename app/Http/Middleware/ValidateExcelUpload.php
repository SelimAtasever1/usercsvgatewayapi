<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateExcelUpload
{
    public function handle(Request $request, Closure $next)
    {
        $customMessages = [

            'excel_file.mimes' => 'The uploaded file must be in xlsx or xls format.',
        ];

        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls',
            ], $customMessages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        return $next($request);
    }
}
