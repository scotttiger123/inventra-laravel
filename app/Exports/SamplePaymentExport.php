<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;


class SamplePaymentExport implements ToCollection
{
    public function collection()
    {
        return new Collection([
            ['Amount', 'Date', 'Status', 'Payment Type', 'Payment Method'],
            ['1000', '2024-12-19', 'Completed', 'Credit', 'Cash'],
            ['2000', '2024-12-18', 'Pending', 'Debit', 'Online'],
            
        ]);
    }
}
