<?php

namespace App\Repositories;

use App\Contracts\Repositories\ScheduleRepository as ScheduleRepositoryContract;
use App\Models\Schedule;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryContract
{
    public function __construct(Schedule $model)
    {
        $this->model = $model;
    }
}
