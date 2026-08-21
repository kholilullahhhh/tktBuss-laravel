<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAndMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Customer', 'slug' => 'customer'],
        ];

        $roleIds = [];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $roleIds[$role['slug']] = DB::table('roles')->where('slug', $role['slug'])->first()->id;
        }

        // 2. Menus (slug harus sama dengan route name agar menu aktif)
        $menus = [
            // Admin
            ['name' => 'Dashboard', 'slug' => 'admin.dashboard', 'path' => '/admin/dashboard', 'icon' => 'ri-home-smile-line', 'order_no' => 1],
            ['name' => 'Master Data', 'slug' => 'master-data', 'path' => null, 'icon' => 'ri-database-2-line', 'order_no' => 2],
            ['parent' => 'Master Data', 'name' => 'Operator Bus', 'slug' => 'admin.operators.index', 'path' => '/admin/operators', 'icon' => 'ri-bus-2-line', 'order_no' => 1],
            ['parent' => 'Master Data', 'name' => 'Bus', 'slug' => 'admin.buses.index', 'path' => '/admin/buses', 'icon' => 'ri-bus-line', 'order_no' => 2],
            ['parent' => 'Master Data', 'name' => 'Kursi', 'slug' => 'admin.seats.index', 'path' => '/admin/seats', 'icon' => 'ri-seat-line', 'order_no' => 3],
            ['parent' => 'Master Data', 'name' => 'Terminal', 'slug' => 'admin.terminals.index', 'path' => '/admin/terminals', 'icon' => 'ri-map-pin-line', 'order_no' => 4],
            ['parent' => 'Master Data', 'name' => 'Rute', 'slug' => 'admin.routes.index', 'path' => '/admin/routes', 'icon' => 'ri-road-map-line', 'order_no' => 5],
            ['parent' => 'Master Data', 'name' => 'Jadwal', 'slug' => 'admin.schedules.index', 'path' => '/admin/schedules', 'icon' => 'ri-calendar-check-line', 'order_no' => 6],
            ['name' => 'Transaksi', 'slug' => 'transaksi', 'path' => null, 'icon' => 'ri-exchange-funds-line', 'order_no' => 3],
            ['parent' => 'Transaksi', 'name' => 'Booking', 'slug' => 'admin.bookings.index', 'path' => '/admin/bookings', 'icon' => 'ri-shopping-basket-line', 'order_no' => 1],
            ['parent' => 'Transaksi', 'name' => 'Pembayaran', 'slug' => 'admin.payments.index', 'path' => '/admin/payments', 'icon' => 'ri-bank-card-line', 'order_no' => 2],
            ['parent' => 'Transaksi', 'name' => 'Customer', 'slug' => 'admin.customers.index', 'path' => '/admin/customers', 'icon' => 'ri-user-star-line', 'order_no' => 3],
            ['name' => 'Laporan', 'slug' => 'laporan', 'path' => null, 'icon' => 'ri-file-chart-line', 'order_no' => 4],
            ['parent' => 'Laporan', 'name' => 'Laporan Booking', 'slug' => 'admin.reports.booking', 'path' => '/admin/reports/booking', 'icon' => 'ri-file-list-3-line', 'order_no' => 1],
            ['parent' => 'Laporan', 'name' => 'Laporan Pendapatan', 'slug' => 'admin.reports.revenue', 'path' => '/admin/reports/revenue', 'icon' => 'ri-money-dollar-circle-line', 'order_no' => 2],
            ['parent' => 'Laporan', 'name' => 'Laporan Perjalanan', 'slug' => 'admin.reports.travel', 'path' => '/admin/reports/travel', 'icon' => 'ri-route-line', 'order_no' => 3],
            ['name' => 'Manajemen Akses', 'slug' => 'manajemen-akses', 'path' => null, 'icon' => 'ri-admin-line', 'order_no' => 5],
            ['parent' => 'Manajemen Akses', 'name' => 'Users', 'slug' => 'user.index', 'path' => '/admin/user', 'icon' => 'ri-user-settings-line', 'order_no' => 1],
            ['parent' => 'Manajemen Akses', 'name' => 'Roles', 'slug' => 'role.index', 'path' => '/admin/role', 'icon' => 'ri-shield-user-line', 'order_no' => 2],
            ['parent' => 'Manajemen Akses', 'name' => 'Menus', 'slug' => 'menu.index', 'path' => '/admin/menu', 'icon' => 'ri-menu-search-line', 'order_no' => 3],
            ['parent' => 'Manajemen Akses', 'name' => 'Permissions', 'slug' => 'permission.index', 'path' => '/admin/permission', 'icon' => 'ri-lock-password-line', 'order_no' => 4],
            ['name' => 'Activity Log', 'slug' => 'activity-log.index', 'path' => '/admin/activity-log', 'icon' => 'ri-history-line', 'order_no' => 6],
            ['name' => 'Pengaturan', 'slug' => 'pengaturan', 'path' => null, 'icon' => 'ri-settings-3-line', 'order_no' => 7],
            ['parent' => 'Pengaturan', 'name' => 'Pengaturan Umum', 'slug' => 'settings.index', 'path' => '/admin/settings', 'icon' => 'ri-settings-line', 'order_no' => 1],
            ['parent' => 'Pengaturan', 'name' => 'Profil', 'slug' => 'profile.index', 'path' => '/profile', 'icon' => 'ri-user-3-line', 'order_no' => 2],
            ['parent' => 'Pengaturan', 'name' => 'System Health', 'slug' => 'system.health', 'path' => '/admin/system/health', 'icon' => 'ri-heart-pulse-line', 'order_no' => 3],
            ['name' => 'API Docs', 'slug' => 'api-docs', 'path' => '/api/documentation', 'icon' => 'ri-book-open-line', 'order_no' => 8],

            // Customer
            ['name' => 'Dashboard', 'slug' => 'customer.dashboard', 'path' => '/customer/dashboard', 'icon' => 'ri-home-smile-line', 'order_no' => 1],
            ['name' => 'Cari Tiket', 'slug' => 'tickets.index', 'path' => '/tiket', 'icon' => 'ri-search-line', 'order_no' => 2],
            ['name' => 'Booking Saya', 'slug' => 'customer.bookings', 'path' => '/customer/bookings', 'icon' => 'ri-shopping-basket-line', 'order_no' => 3],
            ['name' => 'Tiket Saya', 'slug' => 'customer.tickets', 'path' => '/customer/tickets', 'icon' => 'ri-ticket-2-line', 'order_no' => 4],
            ['name' => 'Profil', 'slug' => 'profile.index', 'path' => '/profile', 'icon' => 'ri-user-3-line', 'order_no' => 5],
        ];

        $adminSlugs = [
            'admin.dashboard', 'admin.operators.index', 'admin.buses.index', 'admin.seats.index',
            'admin.terminals.index', 'admin.routes.index', 'admin.schedules.index',
            'admin.bookings.index', 'admin.payments.index', 'admin.customers.index',
            'admin.reports.booking', 'admin.reports.revenue', 'admin.reports.travel',
            'user.index', 'role.index', 'menu.index', 'permission.index',
            'activity-log.index', 'settings.index', 'system.health',
        ];

        $menuIdMap = [];
        foreach ($menus as $m) {
            $parentId = isset($m['parent']) ? ($menuIdMap[$m['parent']] ?? null) : null;

            DB::table('menus')->updateOrInsert(
                ['slug' => $m['slug']],
                [
                    'parent_id' => $parentId,
                    'name' => $m['name'],
                    'path' => $m['path'],
                    'icon' => $m['icon'],
                    'order_no' => $m['order_no'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $dbMenu = DB::table('menus')->where('slug', $m['slug'])->first();
            $menuIdMap[$m['name']] = $dbMenu->id;

            $isParent = isset($m['parent']) || $m['path'] === null;

            // Super Admin: akses penuh semua menu
            DB::table('role_menu')->updateOrInsert(
                ['role_id' => $roleIds['super-admin'], 'menu_id' => $dbMenu->id],
                [
                    'can_create' => true,
                    'can_read' => true,
                    'can_update' => true,
                    'can_delete' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Admin: akses menu admin
            if (in_array($m['slug'], $adminSlugs) || $isParent && in_array($m['slug'], ['master-data', 'transaksi', 'laporan', 'manajemen-akses', 'pengaturan'])) {
                DB::table('role_menu')->updateOrInsert(
                    ['role_id' => $roleIds['admin'], 'menu_id' => $dbMenu->id],
                    [
                        'can_create' => true,
                        'can_read' => true,
                        'can_update' => true,
                        'can_delete' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Customer: menu customer
            if (in_array($m['slug'], ['customer.dashboard', 'tickets.index', 'customer.bookings', 'customer.tickets', 'profile.index'])) {
                DB::table('role_menu')->updateOrInsert(
                    ['role_id' => $roleIds['customer'], 'menu_id' => $dbMenu->id],
                    [
                        'can_create' => false,
                        'can_read' => true,
                        'can_update' => false,
                        'can_delete' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
