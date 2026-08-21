<?php

namespace App\Repositories;

use App\Contracts\Repositories\TerminalRepository as TerminalRepositoryContract;
use App\Models\Terminal;

class TerminalRepository extends BaseRepository implements TerminalRepositoryContract
{
    public function __construct(Terminal $model)
    {
        $this->model = $model;
    }
}
