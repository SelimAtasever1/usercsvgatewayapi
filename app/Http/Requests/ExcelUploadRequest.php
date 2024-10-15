<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelUploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'excel_file' => 'required|mimes:xlsx,xls',
        ];
    }

    public function messages()
    {
        return [
            'excel_file.mimes' => 'The uploaded file must be in xlsx or xls format.',
        ];
    }

    public function authorize()
    {
        return true;
    }
}