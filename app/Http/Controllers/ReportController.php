<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class ReportController extends Controller
{

    public function __construct(protected readonly ReportService $service)
    {
    }

    public function getDashBoard()
    {
        return $this->respond($this->service->getTransactionDashBoard());
    }
}
