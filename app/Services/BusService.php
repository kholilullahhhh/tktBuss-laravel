<?php

namespace App\Services;

use App\Models\Bus;
use App\Repositories\BusRepository;

class BusService extends BaseService
{
    public function __construct(BusRepository $repository)
    {
        parent::__construct($repository);
    }

    public function allWithRelations()
    {
        return Bus::with('operator')->orderBy('nama_bus')->get();
    }

    public function activeBuses()
    {
        return Bus::where('status', true)->with('operator')->orderBy('nama_bus')->get();
    }
}
