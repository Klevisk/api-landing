<?php

namespace App\Console\Commands;

use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCompanyStatus extends Command
{
    protected $signature = 'company:update-status';
    protected $description = 'Update company status based on renewal date';

    public function handle()
{
    Log::info('Inicio de la tarea de UpdateCompanyStatus ...');

$now = Carbon::now();
$allCompanies = Business::all();

foreach ($allCompanies as $company) {
    Log::info('Empresa: ' . $company->name . ', Fecha de renovación: ' . $company->renewal_date);

    if ($company->renewal_date <= $now) {
        $company->update(['status' => false]);
        Log::info('Estado de la empresa actualizado: ' . $company->name);
    }
}

Log::info('Fin de la tarea de UpdateCompanyStatus ...');
}
}
