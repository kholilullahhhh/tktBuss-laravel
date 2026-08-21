<?php

namespace App\Repositories;

use App\Contracts\Repositories\OperatorRepository as OperatorRepositoryContract;
use App\Models\Operator;

class OperatorRepository extends BaseRepository implements OperatorRepositoryContract
{
    public function __construct(Operator $model)
    {
        $this->model = $model;
    }
}
