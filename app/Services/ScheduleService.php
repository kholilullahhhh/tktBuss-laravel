<?php

namespace App\Services;

use App\Models\Schedule;
use App\Repositories\ScheduleRepository;

class ScheduleService extends BaseService
{
    public function __construct(ScheduleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function allWithRelations()
    {
        return Schedule::with('bus.operator', 'route.terminalAsal', 'route.terminalTujuan')
            ->orderBy('tanggal_keberangkatan', 'desc')
            ->orderBy('jam_keberangkatan')
            ->get();
    }

    public function activeSchedules()
    {
        return Schedule::where('status', 'aktif')->with('bus.operator', 'route.terminalAsal', 'route.terminalTujuan')
            ->orderBy('tanggal_keberangkatan')->orderBy('jam_keberangkatan')->get();
    }
}
