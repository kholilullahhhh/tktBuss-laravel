<?php

namespace App\Services;

use App\Models\Terminal;
use App\Repositories\TerminalRepository;

class TerminalService extends BaseService
{
    public function __construct(TerminalRepository $repository)
    {
        parent::__construct($repository);
    }

    public function activeTerminals()
    {
        return Terminal::where('status', true)->orderBy('nama_terminal')->get();
    }
}
