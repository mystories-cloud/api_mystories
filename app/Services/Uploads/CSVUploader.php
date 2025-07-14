<?php
namespace App\Services\Uploads;

use Maatwebsite\Excel\Facades\Excel;

class CSVUploader
{
    public function import($import)
    {
        $file = request()->file('csv');
        Excel::import($import, $file);
    }
}