<?php

namespace App\Services;

use App\Models\Route;
use App\Repositories\RouteRepository;

class RouteService extends BaseService
{
    public function __construct(RouteRepository $repository)
    {
        parent::__construct($repository);
    }

    public function allWithRelations()
    {
        return Route::with('terminalAsal', 'terminalTujuan')->orderBy('terminal_asal_id')->get();
    }
}
