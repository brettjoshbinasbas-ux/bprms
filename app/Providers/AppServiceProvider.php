<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use App\Models\Application;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Share pending application count to all admin views
        View::composer('layouts.admin', function ($view) {
            if (auth('admin')->check()) {
                $pendingCount = Application::where('application_status', 'pending')->count();
                $view->with('pendingCount', $pendingCount);
            }
        });

        // Status badge directive for application status
        Blade::directive('statusBadge', function ($status) {
            return "<?php
                \$cls = match({$status}) {
                    'pending'    => 'badge-pending',
                    'approved'   => 'badge-approved',
                    'rejected'   => 'badge-rejected',
                    'cancelled'  => 'badge-cancelled',
                    'active'     => 'badge-active',
                    'expired'    => 'badge-expired',
                    'terminated' => 'badge-terminated',
                    'available'  => 'badge-available',
                    'occupied'   => 'badge-occupied',
                    'unavailable'=> 'badge-unavailable',
                    'completed'  => 'badge-approved',
                    'failed'     => 'badge-rejected',
                    default      => 'bg-secondary text-white',
                };
                echo '<span class=\"badge ' . \$cls . ' px-2 py-1 rounded\">' . ucfirst({$status}) . '</span>';
            ?>";
});

// Format date directive
Blade::directive('formatDate', function ($date) {
return "<?php echo {$date} ? \Carbon\Carbon::parse({$date})->format('d M Y') : '-'; ?>";
});

// Format currency directive
Blade::directive('ringgit', function ($amount) {
return "<?php echo 'RM ' . number_format({$amount}, 2); ?>";
});
}
}
