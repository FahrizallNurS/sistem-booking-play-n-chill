// app/Providers/AuthServiceProvider.php (file ini SUDAH ADA)
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Biarkan kosong atau isi jika ada policy
    ];

    public function boot()
    {
        $this->registerPolicies();

        // TAMBAHKAN ini:
        Gate::define('superadmin', function ($user) {
            return $user->role === 'superadmin';
        });

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}