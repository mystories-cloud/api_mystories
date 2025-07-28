<?php

namespace App\Imports;

use App\Models\BetaAppointment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BetaAppointmentImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            BetaAppointment::updateOrCreate(['email' => $row['email']], [
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'date' => $row['date'],
                'time' => $row['time'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20|regex:/^[\d\s\-\+\(\)]+$/',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
        ];
    }
}
