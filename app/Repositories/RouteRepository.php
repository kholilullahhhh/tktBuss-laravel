<?php

namespace App\Repositories;

use App\Contracts\Repositories\RouteRepository as RouteRepositoryContract;
use App\Models\Route;

class RouteRepository extends BaseRepository implements RouteRepositoryContract
{
    public function __construct(Route $model)
    {
        $this->model = $model;
    }
}
