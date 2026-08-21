<?php

namespace App\Repositories;

use App\Contracts\Repositories\BusRepository as BusRepositoryContract;
use App\Models\Bus;

class BusRepository extends BaseRepository implements BusRepositoryContract
{
    public function __construct(Bus $model)
    {
        $this->model = $model;
    }
}
