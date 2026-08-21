<?php

namespace App\Repositories;

use App\Contracts\Repositories\SeatRepository as SeatRepositoryContract;
use App\Models\Seat;

class SeatRepository extends BaseRepository implements SeatRepositoryContract
{
    public function __construct(Seat $model)
    {
        $this->model = $model;
    }
}
