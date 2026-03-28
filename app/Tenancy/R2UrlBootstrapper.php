<?php

namespace App\Tenancy;

use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class R2UrlBootstrapper implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        $tenantId = $tenant->getTenantKey();

        // Sadece root'u set ediyoruz — url() metodu root prefix'ini otomatik ekler
        // Yani url('2026/03/file.webp') → baseUrl/tenantId/2026/03/file.webp olur
        config([
            'filesystems.disks.r2.root' => $tenantId,
        ]);

        // Disk cache'ini temizle — yoksa eski root ile çalışmaya devam eder
        app('filesystem')->forgetDisk('r2');
    }

    public function revert()
    {
        config([
            'filesystems.disks.r2.root' => '',
        ]);

        app('filesystem')->forgetDisk('r2');
    }
}
