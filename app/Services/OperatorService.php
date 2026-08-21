<?php

namespace App\Services;

use App\Repositories\OperatorRepository;

class OperatorService extends BaseService
{
    public function __construct(OperatorRepository $repository)
    {
        parent::__construct($repository);
    }

    public function activeOperators()
    {
        return $this->repository->model->where('status', true)->orderBy('nama_operator')->get();
    }
}
