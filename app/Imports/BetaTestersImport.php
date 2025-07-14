<?php

namespace App\Imports;

use App\Models\BetaTester;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BetaTestersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            BetaTester::updateOrCreate(['email' => $row['email']], [
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
