<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;

class CheckTenantUsers extends Command
{
    protected $signature = 'tenant:users {id}';
    protected $description = 'Belirtilen Tenant IDsine ait kullanicilari ve admin yetkilerini listeler';

    public function handle()
    {
        $tenant = Tenant::find($this->argument('id'));
        if (!$tenant) {
            $this->error('Tenant bulunamadi!');
            return;
        }

        $tenant->run(function () {
            $users = User::all(['id', 'name', 'email', 'is_admin'])->toArray();
            if (empty($users)) {
                $this->warn('Bu tenantta hic kullanici yok.');
            } else {
                $this->table(['ID', 'İsim', 'E-posta', 'Admin Mi? (1/0)'], $users);
            }
        });
    }
}
