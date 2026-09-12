<?php

namespace App\Exports;

use App\Exports\Sheets\DashboardResumenSheet;
use App\Exports\Sheets\DashboardUsuariosSheet;
use App\Exports\Sheets\DashboardTramitesSheet;
use App\Exports\Sheets\DashboardMetodosPagoSheet;
use App\Exports\Sheets\DashboardHorariosSheet;
use App\Exports\Sheets\DashboardOficinasSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardExport implements WithMultipleSheets
{
    protected array $analytics;
    protected array $aiReport;

    public function __construct(array $analytics, array $aiReport)
    {
        $this->analytics = $analytics;
        $this->aiReport = $aiReport;
    }

    public function sheets(): array
    {
        return [
            new DashboardResumenSheet($this->analytics, $this->aiReport),
            new DashboardUsuariosSheet($this->analytics),
            new DashboardTramitesSheet($this->analytics),
            new DashboardMetodosPagoSheet($this->analytics),
            new DashboardHorariosSheet($this->analytics),
            new DashboardOficinasSheet($this->analytics),
        ];
    }
}
