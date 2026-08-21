<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RevenueExport implements FromView
{
    protected $rows;

    protected $total;

    public function __construct($rows, $total)
    {
        $this->rows = $rows;
        $this->total = $total;
    }

    public function view(): View
    {
        return view('exports.revenue', [
            'rows' => $this->rows,
            'total' => $this->total,
        ]);
    }
}
