<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TravelExport implements FromView
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('exports.travel', [
            'rows' => $this->rows,
        ]);
    }
}
