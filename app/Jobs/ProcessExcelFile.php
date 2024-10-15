<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class ProcessExcelFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $spreadsheet = IOFactory::load(storage_path('app/' . $this->filePath));
        $worksheet = $spreadsheet->getActiveSheet();

        $startRow = 2;
        $endRow = $worksheet->getHighestRow();
        $batchSize = 100;
        $batchData = [];  

        for ($row = $startRow; $row <= $endRow; $row++) {
            $empId = $worksheet->getCell('A' . $row)->getValue();
            if (empty($empId)) {
                Log::warning("empId is missing on row {$row}. Skipping this row.");
                continue;  
            }

            $employeeData = [
                'emp_id' => $worksheet->getCell('A' . $row)->getValue(),
                'name_prefix' => $worksheet->getCell('B' . $row)->getValue(),
                'first_name' => $worksheet->getCell('C' . $row)->getValue(),
                'middle_initial' => $worksheet->getCell('D' . $row)->getValue(),
                'last_name' => $worksheet->getCell('E' . $row)->getValue(),
                'gender' => $worksheet->getCell('F' . $row)->getValue(),
                'email' => $worksheet->getCell('G' . $row)->getValue(),
                'date_of_birth' => $worksheet->getCell('H' . $row)->getValue(),
                'time_of_birth' => $worksheet->getCell('I' . $row)->getValue(),
                'age_in_yrs' => $worksheet->getCell('J' . $row)->getValue(),
                'date_of_joining' => $worksheet->getCell('K' . $row)->getValue(),
                'age_in_company_years' => $worksheet->getCell('L' . $row)->getValue(),
                'phone_no' => $worksheet->getCell('M' . $row)->getValue(),
                'place_name' => $worksheet->getCell('N' . $row)->getValue(),
                'county' => $worksheet->getCell('O' . $row)->getValue(),
                'city' => $worksheet->getCell('P' . $row)->getValue(),
                'zip' => $worksheet->getCell('Q' . $row)->getValue(),
                'region' => $worksheet->getCell('R' . $row)->getValue(),
                'user_name' => $worksheet->getCell('S' . $row)->getValue(),
            ];

            $batchData[] = $employeeData;

            if (count($batchData) === $batchSize || $row === $endRow) {
                Employee::insert($batchData);
                Log::info("Processed batch from row " . ($row - $batchSize + 1) . " to $row.");
                $batchData = [];  
            }
        }
    }
}
